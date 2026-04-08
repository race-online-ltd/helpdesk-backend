<?php

namespace App\Http\Controllers;

// use App\Models\Company;

use App\Models\CloseTicket;
use App\Models\OpenTicket;
use App\Models\SmsTemplate;
use App\Models\SubCategory;
use App\Models\Ticket;
use App\Models\TicketTrackingToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class SendSmsController extends Controller
{


    public function checkAndSendSMS(Request $request)
    {
        $sid = $request->input('sid');
        $subCat = $request->input('nature');
        $providedTicketNumber = $request->input('ticket_no');

        if (!$sid) {
            return response()->json(['error' => 'SID is required'], 400);
        }


        if (!empty($subCat)) {
            if (is_numeric($subCat)) {
                $subCategory = SubCategory::find($subCat, ['sub_category_in_english']);
                $subCategoryName = $subCategory ? $subCategory->sub_category_in_english : 'UNKNOWN';
            } else {
                $subCategoryName = $subCat;
            }
        }

        if ($providedTicketNumber) {
            $lastTicketNumber = $providedTicketNumber;
            // $subCategoryName = $subCat;
        } else {
            $lastTicket = Ticket::orderBy('id', 'desc')->first();
            $lastTicketNumber = $lastTicket ? $lastTicket->ticket_number : 'UNKNOWN';

            // $subCategory = SubCategory::where('id', $subCat)->first(['sub_category_in_english']);
            // $subCategoryName = $subCategory ? $subCategory->sub_category_in_english : 'UNKNOWN';
        }


        $response = Http::get("https://webapp.race.net.bd/api/fetch-entity-list-by-sid/{$sid}");

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data[0])) {
                $entity = $data[0];


                if (in_array($entity['entity_type'], ['Area Office', 'Zonal Office', 'RESELLER', 'SUBRESELER', 'Unknown'])) {


                    $mobileFromEntity = $entity['mobile_phone'] ?? null;

                    // Validate if mobile is available
                    if (!$mobileFromEntity) {
                        return response()->json(['error' => 'Mobile number not found in entity data'], 422);
                    }

                    // Step 3: Call sendSMS
                    return $this->sendSMS($entity['customer_nbr'], $lastTicketNumber, $subCategoryName, $mobileFromEntity);
                } else {
                    return response()->json(['message' => 'No SMS sent. Entity type is not Area or Zonal Office.']);
                }
            }

            return response()->json(['message' => 'No entity found with the provided SID'], 404);
        }

        return response()->json(['error' => 'Failed to fetch data from remote API'], 500);
    }




    // public function sendSMS($customerNbr, $lastTicketNumber, $subCategoryName, $mobileFromEntity)
    // {
    //     Log::info('SMS Debug Info', [
    //         'Customer Number'   => $customerNbr,
    //         'Ticket Number'     => $lastTicketNumber,
    //         'Subcategory Name'  => $subCategoryName,
    //         'Mobile'            => $mobileFromEntity,
    //     ]);

    //     $ticket = Ticket::where('ticket_number', $lastTicketNumber)->first(['status_id']);
    //     $statusId = $ticket->status_id ?? null;

    //     $messageType = ($statusId == 6) ? 'resolved' : 'raised';

    //     $message = "Dear ORBIT user {$customerNbr}, {$subCategoryName} related issue has been {$messageType}. Ticket No. {$lastTicketNumber}.\nHelpline 16590 / 09643000222";

    //     // New SMS Gateway credentials
    //     $apiUrl     = "http://isms.digitalsquare.ltd:5683/sendtext";
    //     $apiKey     = "782af7878b6c9794";
    //     $secretKey  = "c8d3b42c";
    //     $callerID   = "8809643016590";

    //     // Build query parameters
    //     $payload = [
    //         'apikey'        => $apiKey,
    //         'secretkey'     => $secretKey,
    //         'callerID'      => $callerID,
    //         'toUser'        => $mobileFromEntity,
    //         'messageContent' => $message,
    //     ];

    //     // Send GET request with query params
    //     $response = Http::get($apiUrl, $payload);

    //     Log::info('DigitalSquare SMS API Response', [
    //         'status' => $response->status(),
    //         'body'   => $response->body(),
    //     ]);

    //     return response()->json([
    //         'sent_to'          => $mobileFromEntity,
    //         'customerNbr'      => $customerNbr,
    //         'ticketNumber'     => $lastTicketNumber,
    //         'subCategoryName'  => $subCategoryName,
    //         'ticket_status_id' => $statusId,
    //         'sms_message'      => $message,
    //         'api_status'       => $response->status(),
    //         'api_response'     => $response->body(),
    //     ]);
    // }

    public function sendSMS($customerNbr, $lastTicketNumber, $subCategoryName, $mobileFromEntity)
    {
        Log::info('SMS Debug Info', [
            'Customer Number'  => $customerNbr,
            'Ticket Number'    => $lastTicketNumber,
            'Subcategory Name' => $subCategoryName,
            'Mobile'           => $mobileFromEntity,
        ]);

        // ── Step 1: Get ticket status ─────────────────────────────────
        $ticket = OpenTicket::where('ticket_number', $lastTicketNumber)->first(['status_id']);

        if (!$ticket) {
            $ticket = CloseTicket::where('ticket_number', $lastTicketNumber)->first(['status_id']);
            Log::info('Ticket found in CloseTicket table', ['ticket_number' => $lastTicketNumber]);
        } else {
            Log::info('Ticket found in OpenTicket table', ['ticket_number' => $lastTicketNumber]);
        }

        if (!$ticket) {
            Log::error('Ticket not found in any table', ['ticket_number' => $lastTicketNumber]);
            return response()->json(['error' => 'Ticket not found: ' . $lastTicketNumber], 404);
        }

        $statusId    = $ticket->status_id ?? null;
        $isResolved  = ($statusId == 6);
        $messageType = $isResolved ? 'সমাধান' : 'রিপোর্ট';

        // ── Step 2: Select template key based on status ───────────────
        // status_id == 6 → resolved  → sid_sms_resolved
        // anything else → reported  → sid_sms_reported
        $templateKey = $isResolved ? 'sid_sms_resolved' : 'sid_sms_reported';

        Log::info('SMS Template Selection', [
            'status_id'    => $statusId,
            'is_resolved'  => $isResolved,
            'template_key' => $templateKey,
        ]);

        // ── Step 3: Fetch template by key ─────────────────────────────
        $smsTemplate = SmsTemplate::getByKey($templateKey);

        if (!$smsTemplate) {
            Log::error('SMS Template not found', ['key' => $templateKey]);
            return response()->json(['error' => 'SMS template not found for key: ' . $templateKey], 500);
        }

        // ── Step 4: Render message with placeholders ──────────────────
        $message = $smsTemplate->render([
            'subCategoryName'    => $subCategoryName,
            'messageType'        => $messageType,
            'lastTicketNumber'   => $lastTicketNumber,
            'businessEntityName' => $customerNbr,
            'clientName'         => $customerNbr,
            'agentName'          => '',
        ]);

        Log::info('Rendered SMS Message', ['message' => $message]);

        // ── Step 5: Send via DigitalSquare API ────────────────────────
        $apiUrl    = "http://isms.digitalsquare.ltd:5683/sendtext";
        $apiKey    = "782af7878b6c9794";
        $secretKey = "c8d3b42c";
        $callerID  = "8809643016590";

        $payload = [
            'apikey'         => $apiKey,
            'secretkey'      => $secretKey,
            'callerID'       => $callerID,
            'toUser'         => $mobileFromEntity,   // no '88' prefix here — kept as original
            'messageContent' => $message,
        ];

        $response = Http::get($apiUrl, $payload);

        Log::info('DigitalSquare SMS API Response', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        return response()->json([
            'sent_to'           => $mobileFromEntity,
            'customerNbr'       => $customerNbr,
            'ticketNumber'      => $lastTicketNumber,
            'subCategoryName'   => $subCategoryName,
            'ticket_status_id'  => $statusId,
            'template_key_used' => $templateKey,
            'sms_message'       => $message,
            'api_status'        => $response->status(),
            'api_response'      => $response->body(),
        ]);
    }





    public function checkAndSendSMSForPartner(Request $request)
    {
        $entity = $request->input('businessEntity');
        $subCat = $request->input('nature');
        $providedTicketNumber = $request->input('ticket_number');
        $mobileFromEntity = $request->input('phone');

        if (!$mobileFromEntity) {
            return response()->json(['error' => 'Mobile number is required'], 400);
        }

        if (!empty($subCat)) {
            if (is_numeric($subCat)) {
                $subCategory = SubCategory::find($subCat, ['sub_category_in_bangla']);
                $subCategoryName = $subCategory ? $subCategory->sub_category_in_bangla : 'UNKNOWN';
            } else {
                $subCategoryName = $subCat;
            }
        } else {
            $subCategoryName = 'UNKNOWN';
        }

        if (!empty($entity)) {
            if (!($entity)) {
                $businessEntityName = $entity;
            } else {
                $businessEntityName = $entity;
            }
        } else {
            $businessEntityName = null;
        }

        if ($providedTicketNumber) {
            $lastTicketNumber = $providedTicketNumber;
        } else {
            $lastTicket = Ticket::orderBy('id', 'desc')->first();
            $lastTicketNumber = $lastTicket ? $lastTicket->ticket_number : 'UNKNOWN';
        }

        if ($businessEntityName) {
            return $this->sendSMSForPartner($businessEntityName, $lastTicketNumber, $subCategoryName, $mobileFromEntity);
        } else {
            return response()->json(['message' => 'No entity found'], 404);
        }
    }


    // public function sendSMSForPartner($businessEntityName, $lastTicketNumber, $subCategoryName, $mobileFromEntity)
    // {
    //     Log::info('Partner SMS Debug Info', [
    //         'Entity Name'       => $businessEntityName,
    //         'Ticket Number'     => $lastTicketNumber,
    //         'Subcategory Name'  => $subCategoryName,
    //         'Mobile'            => $mobileFromEntity,
    //     ]);

    //     $ticket = Ticket::where('ticket_number', $lastTicketNumber)->first(['status_id']);
    //     $statusId = $ticket->status_id ?? null;

    //     $messageType = ($statusId == 6) ? 'সমাধান' : 'রিপোর্ট';

    //     if ($messageType == 'সমাধান') {
    //         $message = "{$subCategoryName} সম্পর্কিত অনুসন্ধানটি {$messageType} করা হয়েছে। টিকেট নং: {$lastTicketNumber} । । অনুসন্ধানকৃত বিষয়টি সমাধান না হয়ে থাকলে, তাৎক্ষণিতভাবে জানাতে অনুরোধ করা যাচ্ছে- (২৪/৭),০৯৬৪৩০০০৪৪৪ । প্যানেল নাম :{$businessEntityName} । অনুগ্রহপূর্বক,পরবর্তী যেকোনো সেবা পেতে আপনার মোবাইলের টিকেটিং এপপ্স থেকে,নতুন টিকেট ওপেন করতে অনুরোধ করা যাচ্ছে।";
    //     } else {
    //         $message = "{$subCategoryName} সম্পর্কিত অনুসন্ধানটি {$messageType} করা হয়েছে। খুবশিগ্রই আপনার সাথে যোগাযোগ করা হবে।  টিকেট নং: {$lastTicketNumber} । যেকোনো প্রয়োজনে কল করুন- (২৪/৭), ০৯৬৪৩০০০৪৪৪,কাস্টমার হেল্পলাইন:১৬৫৯০ । প্যানেল নাম :{$businessEntityName} । মোবাইলের টিকেটিং এপ্লিকেশন ইনস্টল করুন: https://care.orbitbd.net/apps/tickets.apk";
    //     }

    //     // DigitalSquare SMS API credentials
    //     $apiUrl     = "http://isms.digitalsquare.ltd:5683/sendtext";
    //     $apiKey     = "782af7878b6c9794";
    //     $secretKey  = "c8d3b42c";
    //     $callerID   = "8809643016590";

    //     // Build query params
    //     $payload = [
    //         'apikey'         => $apiKey,
    //         'secretkey'      => $secretKey,
    //         'callerID'       => $callerID,
    //         'toUser'         => '88' . $mobileFromEntity,
    //         'messageContent' => $message,
    //     ];

    //     // Send request
    //     $response = Http::get($apiUrl, $payload);

    //     Log::info('DigitalSquare Partner SMS API Response', [
    //         'status' => $response->status(),
    //         'body'   => $response->body(),
    //     ]);

    //     return response()->json([
    //         'sent_to'            => $mobileFromEntity,
    //         'businessEntityName' => $businessEntityName,
    //         'ticketNumber'       => $lastTicketNumber,
    //         'subCategoryName'    => $subCategoryName,
    //         'ticket_status_id'   => $statusId,
    //         'sms_message'        => $message,
    //         'api_status'         => $response->status(),
    //         'api_response'       => $response->body(),
    //     ]);
    // }


    public function sendSMSForPartner($businessEntityName, $lastTicketNumber, $subCategoryName, $mobileFromEntity)
    {
        Log::info('Partner SMS Debug Info', [
            'Entity Name'      => $businessEntityName,
            'Ticket Number'    => $lastTicketNumber,
            'Subcategory Name' => $subCategoryName,
            'Mobile'           => $mobileFromEntity,
        ]);

        // ── Check OpenTicket first, then fall back to CloseTicket ──
        $ticket = OpenTicket::where('ticket_number', $lastTicketNumber)->first(['status_id']);

        if (!$ticket) {
            $ticket = CloseTicket::where('ticket_number', $lastTicketNumber)->first(['status_id']);
            Log::info('Ticket found in CloseTicket table', ['ticket_number' => $lastTicketNumber]);
        } else {
            Log::info('Ticket found in OpenTicket table', ['ticket_number' => $lastTicketNumber]);
        }

        if (!$ticket) {
            Log::error('Ticket not found in any table', ['ticket_number' => $lastTicketNumber]);
            return response()->json(['error' => 'Ticket not found: ' . $lastTicketNumber], 404);
        }

        $statusId    = $ticket->status_id ?? null;
        $isResolved  = ($statusId == 6);
        $messageType = $isResolved ? 'সমাধান' : 'রিপোর্ট';
        $templateKey = $isResolved ? 'partner_sms_resolved' : 'partner_sms_reported';

        Log::info('SMS Template Selection', [
            'status_id'    => $statusId,
            'is_resolved'  => $isResolved,
            'template_key' => $templateKey,
        ]);

        $smsTemplate = SmsTemplate::getByKey($templateKey);

        if (!$smsTemplate) {
            Log::error('SMS Template not found', ['key' => $templateKey]);
            return response()->json(['error' => 'SMS template not found for key: ' . $templateKey], 500);
        }

        $message = $smsTemplate->render([
            'subCategoryName'    => $subCategoryName,
            'messageType'        => $messageType,
            'lastTicketNumber'   => $lastTicketNumber,
            'businessEntityName' => $businessEntityName,
        ]);

        // ── DigitalSquare SMS API ──────────────────────────────────
        $apiUrl    = "http://isms.digitalsquare.ltd:5683/sendtext";
        $apiKey    = "782af7878b6c9794";
        $secretKey = "c8d3b42c";
        $callerID  = "8809643016590";

        $payload = [
            'apikey'         => $apiKey,
            'secretkey'      => $secretKey,
            'callerID'       => $callerID,
            'toUser'         => '88' . $mobileFromEntity,
            'messageContent' => $message,
        ];

        $response = Http::get($apiUrl, $payload);

        Log::info('DigitalSquare Partner SMS API Response', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        return response()->json([
            'sent_to'            => $mobileFromEntity,
            'businessEntityName' => $businessEntityName,
            'ticketNumber'       => $lastTicketNumber,
            'subCategoryName'    => $subCategoryName,
            'ticket_status_id'   => $statusId,
            'template_key_used'  => $templateKey,   // ← helpful for debugging
            'sms_message'        => $message,
            'api_status'         => $response->status(),
            'api_response'       => $response->body(),
        ]);
    }



    public function sendSMSForClient(Request $request) 
    {
            // Log::info('Client SMS Debug Info', [
            //     'Entity Name'      => $businessEntityName,
            //     'Entity ID'        => $businessEntityId,
            //     'Client ID'        => $clientId,
            //     'Ticket Number'    => $lastTicketNumber,
            //     'Subcategory Name' => $subCategoryName,
            //     'Mobile'           => $mobileFromEntity,
            // ]);


             $validated = $request->validate([
                'businessEntityName' => 'required|string',
                'lastTicketNumber'   => 'required',
                'subCategoryName'    => 'required',
                'mobileFromEntity'   => 'required',
                'business_entity_id' => 'required',
                'client_id'          => 'required',
            ]);

        $businessEntityName = $validated['businessEntityName'];
        $lastTicketNumber   = $validated['lastTicketNumber'];
        $subCategoryName    = $validated['subCategoryName'];
        $mobileFromEntity   = $validated['mobileFromEntity'];
        $businessEntityId   = $validated['business_entity_id'];
        $clientId           = $validated['client_id'];


        $clientName = DB::table('user_client_mappings as ucm')
            ->where('ucm.user_id', $clientId)
            ->value('client_name');


        $subCategoryName = DB::table('sub_categories as sc')
            ->where('sc.id', $subCategoryName)
            ->value('sub_category_in_bangla');    

        $trackUrl = TicketTrackingToken::where('ticket_number', $lastTicketNumber)
        ->value('tracking_url');

        // ── Step 1: Get ticket status ─────────────────────────────────
        $ticket = OpenTicket::where('ticket_number', $lastTicketNumber)->first(['status_id']);

        if (!$ticket) {
            $ticket = CloseTicket::where('ticket_number', $lastTicketNumber)->first(['status_id']);
            Log::info('Ticket found in CloseTicket table', ['ticket_number' => $lastTicketNumber]);
        } else {
            Log::info('Ticket found in OpenTicket table', ['ticket_number' => $lastTicketNumber]);
        }

        if (!$ticket) {
            Log::error('Ticket not found in any table', ['ticket_number' => $lastTicketNumber]);
            return response()->json(['error' => 'Ticket not found: ' . $lastTicketNumber], 404);
        }

        $statusId = $ticket->status_id ?? null;
        Log::info('Ticket Status ID', ['status_id' => $statusId]);

        // ── Step 2: Check if clientId is in exclude_notify ───────────
        $isExcluded = DB::table('sms_templates as st')
            ->where('st.business_entity_id', $businessEntityId)
            ->whereRaw("JSON_CONTAINS(st.exclude_notify, ?, '$')", ['"' . $clientId . '"'])
            ->exists();

        Log::info('Exclude Notify Check', [
            'client_id'   => $clientId,
            'is_excluded' => $isExcluded,
        ]);

        if ($isExcluded) {
            Log::info('Client is excluded from SMS notifications. Skipping SMS.', [
                'client_id'          => $clientId,
                'business_entity_id' => $businessEntityId,
            ]);
            return response()->json([
                'message'   => 'Client is excluded from SMS notifications.',
                'client_id' => $clientId,
            ], 200);
        }

        // ── Step 3: Determine event_id based on status_id ────────────
        // status_id == 1 → event_id = 1 (Open/Reported)
        // status_id == 6 → event_id = 2 (Resolved)
        $eventId     = ($statusId == 6) ? 2 : 1;
        $isResolved  = ($statusId == 6);
        $messageType = $isResolved ? 'সমাধান' : 'রিপোর্ট';

        Log::info('Event & Message Type', [
            'event_id'     => $eventId,
            'message_type' => $messageType,
        ]);

        // ── Step 4: Find template — with client_id first, then fallback ──

        // Priority 1: Match business_entity_id + client_id + event_id
        $smsTemplate = DB::table('sms_templates as st')
            ->select('st.id', 'st.key', 'st.template', 'st.template_name')
            ->where('st.business_entity_id', $businessEntityId)
            ->where('st.client_id', $clientId)
            ->where('st.event_id', $eventId)
            ->where('st.status', 'Active')
            ->first();

        Log::info('Template Search - Priority 1 (with client_id)', [
            'business_entity_id' => $businessEntityId,
            'client_id'          => $clientId,
            'event_id'           => $eventId,
            'found'              => $smsTemplate ? true : false,
            ]);

        // Priority 2: Fallback — Match business_entity_id + event_id only (no client_id)
        if (!$smsTemplate) {
            $smsTemplate = DB::table('sms_templates as st')
                ->select('st.id', 'st.key', 'st.template', 'st.template_name')
                ->where('st.business_entity_id', $businessEntityId)
                ->whereNull('st.client_id')
                ->where('st.event_id', $eventId)
                ->where('st.status', 'Active')
                ->first();

            Log::info('Template Search - Priority 2 (without client_id fallback)', [
                'business_entity_id' => $businessEntityId,
                'event_id'           => $eventId,
                'found'              => $smsTemplate ? true : false,
            ]);
        }

        if (!$smsTemplate) {
            Log::error('No active SMS template found', [
                'business_entity_id' => $businessEntityId,
                'client_id'          => $clientId,
                'event_id'           => $eventId,
            ]);
            return response()->json([
                'error' => 'No active SMS template found for business_entity_id: '
                            . $businessEntityId . ', event_id: ' . $eventId,
            ], 500);
        }

        Log::info('SMS Template Found', [
            'template_id'   => $smsTemplate->id,
            'template_key'  => $smsTemplate->key,
            'template_name' => $smsTemplate->template_name,
        ]);

        // ── Step 5: Render template (replace placeholders) ───────────
        $message = $smsTemplate->template;
        $message = str_replace('{{subCategoryName}}',    $subCategoryName,    $message);
        $message = str_replace('{{messageType}}',        $messageType,        $message);
        $message = str_replace('{{lastTicketNumber}}',   $lastTicketNumber,   $message);
        $message = str_replace('{{businessEntityName}}', $businessEntityName, $message);
        $message = str_replace('{{ticketTrack}}', $trackUrl, $message);
        $message = str_replace('{{clientName}}', $clientName, $message);

        Log::info('Rendered SMS Message', ['message' => $message]);

        // ── Step 6: Send SMS via DigitalSquare API ────────────────────
        $apiUrl    = "http://isms.digitalsquare.ltd:5683/sendtext";
        $apiKey    = "782af7878b6c9794";
        $secretKey = "c8d3b42c";
        $callerID  = "8809643016590";

        $payload = [
            'apikey'         => $apiKey,
            'secretkey'      => $secretKey,
            'callerID'       => $callerID,
            'toUser'         => '88' . $mobileFromEntity,
            'messageContent' => $message,
        ];

        $response = Http::get($apiUrl, $payload);

        Log::info('DigitalSquare Client SMS API Response', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        return response()->json([
            'sent_to'            => $mobileFromEntity,
             'track_url' => $trackUrl,
             'clientName'         => $clientName,
            'businessEntityName' => $businessEntityName,
            'businessEntityId'   => $businessEntityId,
            'clientId'           => $clientId,
            'ticketNumber'       => $lastTicketNumber,
            'subCategoryName'    => $subCategoryName,
            'ticket_status_id'   => $statusId,
            'event_id_used'      => $eventId,
            'template_id_used'   => $smsTemplate->id,
            'template_key_used'  => $smsTemplate->key,
            'sms_message'        => $message,
            'api_status'         => $response->status(),
            'api_response'       => $response->body(),
        ]);
    }



    public function sendSMSStatic()
    {
        $payload = [
            "username"    => "RaceOTP",
            "password"    => "IsZiutMb",
            "source"      => "8809617618898",
            "destination" => "8801844543266",
            "message"     => "Test SMS from Laravel API",
        ];

        $response = Http::get('http://apibd.rmlconnect.net/bulksms/personalizedbulksms', $payload);

        Log::info('MIM SMS API Response', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        return response()->json([
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);
    }





    // public function sendSMSStatic()
    // {
    //     $payload = [
    //         "username"        => "RaceOTP",
    //         "password"          => "IsZiutMb",
    //         // "CampaignId"      => "null",
    //         "source"      => "8809617618898",
    //         "destination"    => "8801844543266",
    //         // "TransactionType" => "T",
    //         "message"         => "Test SMS from Laravel API",
    //     ];

    //     $response = Http::withHeaders([
    //         'Content-Type' => 'application/json',
    //         'Accept'       => 'application/json',
    //     ])
    //         ->post('http://apibd.rmlconnect.net/bulksms/personalizedbulksms?', $payload);

    //     Log::info('MIM SMS API Response', [
    //         'status' => $response->status(),
    //         'body'   => $response->body(),
    //     ]);

    //     return response()->json([
    //         'status' => $response->status(),
    //         'body'   => $response->body(),
    //         'json'   => $response->json(),
    //     ]);
    // }
}
