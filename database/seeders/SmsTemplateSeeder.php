<?php

namespace Database\Seeders;

use App\Models\SmsTemplate;
use Illuminate\Database\Seeder;

class SmsTemplateSeeder extends Seeder
{
    public function run(): void
    {
        SmsTemplate::upsert(
            [
                // ── Partner SMS ───────────────────────────────────────────
                // [
                //     'key'                => 'partner_sms_resolved',
                //     'template_name'      => 'Partner SMS - Resolved',
                //     'template'           => '{{subCategoryName}} সম্পর্কিত অনুসন্ধানটি {{messageType}} করা হয়েছে। টিকেট নং: {{lastTicketNumber}} । অনুসন্ধানকৃত বিষয়টি সমাধান না হয়ে থাকলে, তাৎক্ষণিতভাবে জানাতে অনুরোধ করা যাচ্ছে- (২৪/৭),০৯৬৪৩০০০৪৪৪ । প্যানেল নাম :{{businessEntityName}} । অনুগ্রহপূর্বক,পরবর্তী যেকোনো সেবা পেতে আপনার মোবাইলের টিকেটিং এপপ্স থেকে,নতুন টিকেট ওপেন করতে অনুরোধ করা যাচ্ছে।',
                //     'status'             => 'Active',
                //     'business_entity_id' => null,
                //     'client_id'          => null,
                //     'event_id'           => null,
                //     'exclude_notify'     => null,
                // ],
                // [
                //     'key'                => 'partner_sms_reported',
                //     'template_name'      => 'Partner SMS - Reported',
                //     'template'           => '{{subCategoryName}} সম্পর্কিত অনুসন্ধানটি {{messageType}} করা হয়েছে। খুবশিগ্রই আপনার সাথে যোগাযোগ করা হবে। টিকেট নং: {{lastTicketNumber}} । যেকোনো প্রয়োজনে কল করুন- (২৪/৭), ০৯৬৪৩০০০৪৪৪,কাস্টমার হেল্পলাইন:১৬৫৯০ । প্যানেল নাম :{{businessEntityName}} । মোবাইলের টিকেটিং এপ্লিকেশন ইনস্টল করুন: https://care.orbitbd.net/apps/tickets.apk',
                //     'status'             => 'Active',
                //     'business_entity_id' => null,
                //     'client_id'          => null,
                //     'event_id'           => null,
                //     'exclude_notify'     => null,
                // ],

                // ── Client SMS ────────────────────────────────────────────
                [
                    'key'                => 'client_sms_resolved',
                    'template_name'      => 'Client SMS - Resolved',
                    'template'           => 'Dear ORBIT user {{clientName}}, {{subCategoryName}} related issue has been {{messageType}}. Ticket No. {{lastTicketNumber}}. If not resolved, contact us (24/7): 09643000444 | Helpline: 16590.',
                    'status'             => 'Active',
                    'business_entity_id' => null,
                    'client_id'          => null,
                    'event_id'           => null,
                    'exclude_notify'     => null,
                ],
                [
                    'key'                => 'client_sms_reported',
                    'template_name'      => 'Client SMS - Reported',
                    'template'           => 'Dear ORBIT user {{clientName}}, {{subCategoryName}} related issue has been {{messageType}}. Ticket No. {{lastTicketNumber}}. We will contact you shortly. Helpline: 16590 / 09643000222.',
                    'status'             => 'Active',
                    'business_entity_id' => null,
                    'client_id'          => null,
                    'event_id'           => null,
                    'exclude_notify'     => null,
                ],
            ],
            ['key'],                                      // unique key to match on
            ['template_name', 'template', 'status']      // columns to update if key exists
        );
    }
}