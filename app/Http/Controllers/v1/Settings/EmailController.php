<?php

namespace App\Http\Controllers\v1\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EmailAttribute;
use App\Models\EmailTemplate;
use App\Helpers\ApiResponse;
use App\Logic\EmailTemplateFormatting;
use App\Mail\EmailNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * @OA\Info(
 *     title="HelpDesk API (Documention)",
 *     version="v1.0.0",
 *     description="API documentation for Helpdesk",
 *     @OA\Contact(
 *         name="API Support",
 *         url="http://webapp.net.bd/support",
 *         email="support@race.net.bd"
 *     ),
 *     @OA\License(
 *         name="Nginx",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 */


class EmailController extends Controller
{
    

    public function getAttributes()
    {
        try {
            $resources = EmailAttribute::all();
            return ApiResponse::success($resources, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

   
    public function index()
    {
        try {
            $resources = EmailTemplate::all();
            return ApiResponse::success($resources, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

  
    // public function store(Request $request)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $exist = EmailTemplate::where('template_name', $request->name)->first();

    //         if ($exist) {
    //             return ApiResponse::success($exist, "Email template already exists!", 409);
    //         }

    //         $resources = EmailTemplate::create([
    //             'template_name' => $request->name,
    //             'subject' => $request->subject,
    //             'content' => $request->content,
    //             'status' => $request->status,
    //         ]);

    //         DB::commit();
    //         return ApiResponse::success($resources, "Successfully Inserted", 201);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $exist = EmailTemplate::where('template_name', $request->name)
                ->where('event_id', $request->eventId)
                ->first();

            if ($exist) {
                return ApiResponse::success($exist, "Email template already exists!", 409);
            }

            $resources = EmailTemplate::create([
                'template_name'      => $request->name,
                'subject'            => $request->subject,
                'content'            => $request->content,
                'status'             => $request->status,
                'event_id'           => $request->eventId,           // ✅ new
                'business_entity_id' => $request->businessEntity,    // ✅ new
                'client_id'          => $request->client,            // ✅ new
                'notify_client'      => $request->notifyClient,      // ✅ new
            ]);

            DB::commit();
            return ApiResponse::success($resources, "Successfully Inserted", 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

  
    public function show($id)
    {
        try {
            $resources = EmailTemplate::findOrFail($id);
            return ApiResponse::success($resources, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

  
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $resources = EmailTemplate::findOrFail($id);
            $resources->update([
                'template_name' => $request->name,
                'subject' => $request->subject,
                'content' => $request->content,
                'status' => $request->status,
            ]);
            DB::commit();
            return ApiResponse::success($resources, "Successfully Updated", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

  
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $resources = EmailTemplate::findOrFail($id);
            $resources->delete();
            DB::commit();
            return ApiResponse::success(null, "Successfully Deleted", 204);
        } catch (\Throwable $th) {
            DB::rollBack();
            return ApiResponse::error($th->getMessage(), "Error", 500);
        }
    }


    public function sendEmailNotification($ticketNoForEmail, $teamId, $emailTemplate, $recipient, $oldTeamId = null)
    {

        if (!$teamId || !is_numeric($teamId)) {
            return response()->json(['error' => 'Invalid Team ID'], 400);
        }

        $oldTeamName = DB::table('teams')->where('id', $oldTeamId)->value('team_name');

        $teamName = DB::table('teams')->where('id', $teamId)->value('team_name');

        $ticketNo = $ticketNoForEmail['ticket_number'];
        $ticketRaised = $ticketNoForEmail['user_id'];
        $ticketClosed = $ticketNoForEmail['status_update_by'] ?? 'default_value';
        $ticketCatID = $ticketNoForEmail['cat_id'];
        // $ticketTeamtID = $ticketNoForEmail['team_id'];
        $ticketSubCatID = $ticketNoForEmail['subcat_id'];
        $ticketClientID = $ticketNoForEmail['client_id_helpdesk'];
        $ticketCreated_At = $ticketNoForEmail['created_at'];
        $ticketTotalAge = $ticketNoForEmail['ticketAge'];
        $ticketEntityID = $ticketNoForEmail['business_entity_id'];

        // $ticketEntityID = (int) $ticketNoForEmail['business_entity_id'];

        $ticketRaisedBy = DB::table('user_profiles')->where('user_id', $ticketRaised)->value('fullname');
        $ticketClosedBy = DB::table('user_profiles')->where('user_id', $ticketClosed)->value('fullname');

        // $subCatSlaFr = DB::table('sla_subcategories')->where('subcat_id', $ticketSubCatID)->value('fr_res_time_str');
        // $subCatSlaSrv = DB::table('sla_subcategories')->where('subcat_id', $ticketSubCatID)->value('srv_time_str');

        $subCatSlaFr = DB::table('first_res_configs')->where('team_id', $teamId)->value('duration_min');
        $subCatSlaSrv = DB::table('sla_subcat_configs')->where('subcategory_id', $ticketSubCatID)->where('team_id', $teamId)->value('resolution_min');

        $emailCatName = DB::table('categories')
            ->where('id', $ticketCatID)
            ->first(['category_in_english', 'category_in_bangla']);

        $emailSubCatName = DB::table('sub_categories')
            ->where('id', $ticketSubCatID)
            ->first(['sub_category_in_english', 'sub_category_in_bangla']);

        $emailClientName = DB::table('user_client_mappings')
            ->where('user_id', $ticketClientID)
            ->first(['client_name']);


        if ($emailTemplate) {

            // return 'i am email.';

            $subject = $emailTemplate->subject . ' ' . '[' .  $ticketNo . ']' . ' ' . 'Against' . ' ' . $emailSubCatName->sub_category_in_english . ' ' . 'for' . ' ' . $emailClientName->client_name;


            // return $subject;



            $placeholderData = [
                "Team Name" => $teamName,
                "Ticket Number" => $ticketNo,
                "Category" => $emailCatName->category_in_english,
                "Sub-Category" => $emailSubCatName->sub_category_in_english,
                "First Response Time" => $subCatSlaFr,
                "Service Time" => $subCatSlaSrv,
                "Created By" => $ticketRaisedBy,
                "Closed By" => $ticketClosedBy,
                "Created Time" => $ticketCreated_At,
                "Ticket Age" => $ticketTotalAge,
                "Forwarded By" => $oldTeamName,
            ];

            $name =  EmailTemplateFormatting::replacePlaceholders($emailTemplate->content, $placeholderData);

            // return $name;
        } else {
            // Handle the case when no template is found
            $subject = "Default Subject";
            $name = "Default Content";
        }


        $recipientEmail = !empty($recipient) ? $recipient : null;

        // return $recipientEmail;


        if ($recipientEmail) {
            // Split the recipient emails into an array
            $recipientEmails = explode(',', $recipientEmail);

            // return $recipientEmails;
        
            // Filter valid email addresses
            $validEmails = array_filter($recipientEmails, function ($email) {
                return filter_var(trim($email), FILTER_VALIDATE_EMAIL);
            });

            // return $validEmails;
        
            if (!empty($validEmails)) {
                foreach ($validEmails as $email) {
                    try {

                        $ticketEntityID = (string) $ticketEntityID;

                        if ($ticketEntityID === "4") {
                            $mailer = 'smtp_1';
                            $fromName = env('APP_NAME_1');
                            $fromAddress = env('MAIL_FROM_ADDRESS_1');
                            Log::info("Using mailer: smtp_1 for ticketEntityID: 4");
                        } elseif ($ticketEntityID === "8" || $ticketEntityID === "9") {
                            $mailer = 'smtp_2';
                            $fromName = env('APP_NAME_2');
                            $fromAddress = env('MAIL_FROM_ADDRESS_2');
                            Log::info("Using mailer: smtp_2 for ticketEntityID: 8 or 9");
                        } elseif ($ticketEntityID === "5") {
                            $mailer = 'smtp_3';
                            $fromName = env('APP_NAME_3');
                            $fromAddress = env('MAIL_FROM_ADDRESS_3');
                            Log::info("Using mailer: smtp_3 for ticketEntityID: 5");
                        } elseif ($ticketEntityID === "6" || $ticketEntityID === "7") {
                            $mailer = 'smtp_4';
                            $fromName = env('APP_NAME_4');
                            $fromAddress = env('MAIL_FROM_ADDRESS_4');
                            Log::info("Using mailer: smtp_4 for ticketEntityID: 6 or 7");
                        } else {
                            Log::warning("No valid mailer found for ticketEntityID: " . $ticketEntityID);
                        }
                        
                        // return [$ticketEntityID,$mailer,$fromName,$fromAddress];
        
                        // Send the email using the chosen mailer
                        Log::info("Sending email using mailer: " . $mailer);
                        Mail::mailer($mailer)
                            ->to(trim($email))
                            ->queue(new EmailNotification($subject, $name, $fromAddress, $fromName));
                    } catch (\Exception $e) {
                        // Log the error for debugging
                        Log::error("Error sending email to $email: " . $e->getMessage());
                    }
                }
        
                return response()->json(['message' => 'Emails sent successfully to recipients'], 200);
            } else {
                return response()->json(['error' => 'No valid email addresses found'], 404);
            }
        } else {
            return response()->json(['error' => 'Recipient email is invalid or not found'], 404);
        }
        
        
    }
}