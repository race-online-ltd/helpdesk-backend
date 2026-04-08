<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\OpenTicket;
use App\Models\MergeTicket;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class OpenTicketController extends Controller
{
    // public function getOpenTicketsByTeam($teamId)
    // {
    //     try {
    //         $tickets = OpenTicket::where('team_id', $teamId)
    //             ->select('ticket_number')
    //             ->orderBy('ticket_number', 'desc')
    //             ->get();

    //         return ApiResponse::success($tickets, 'Success', 200);

    //     } catch (\Throwable $e) {
    //         return ApiResponse::error($e->getMessage(), 'Error', 500);
    //     }
    // }


    public function getOpenTicketsByTeam($teamId)
    {
        try {
            $tickets = DB::table('open_tickets as t')
                ->leftJoin('categories as c', 't.cat_id', '=', 'c.id')
                ->leftJoin('sub_categories as sc', 't.subcat_id', '=', 'sc.id')
                ->where('t.team_id', $teamId)
                ->whereNotIn('t.ticket_number', function ($query) {
                    $query->select('mt.ticket_number')
                        ->from('merge_tickets as mt');
                })
                ->select(
                    't.ticket_number',
                    'c.category_in_english',
                    'sc.sub_category_in_english'
                )
                ->orderBy('t.ticket_number', 'desc')
                ->get();

            return ApiResponse::success($tickets, 'Success', 200);

        } catch (\Throwable $e) {
            return ApiResponse::error($e->getMessage(), 'Error', 500);
        }
    }



    public function mergeTickets(Request $request)
    {
        DB::beginTransaction();

        try {
            $parentTicketNumber = $request->parent_ticket_number;
            $tickets = $request->tickets;
            $mergedBy = $request->merged_by;

            // 1️⃣ Update open_tickets → set is_parent = 1
            OpenTicket::where('ticket_number', $parentTicketNumber)
                ->update(['is_parent' => 1]);

            // 2️⃣ Insert into merge_tickets
            foreach ($tickets as $ticketNumber) {

                MergeTicket::create([
                    'ticket_number' => $ticketNumber,
                    'child_exists' => $ticketNumber === $parentTicketNumber ? 1 : null,
                    'parent_ticket_number' => $ticketNumber === $parentTicketNumber
                        ? null
                        : $parentTicketNumber,
                    'merged_by' => $mergedBy,
                ]);
            }

            DB::commit();

            return ApiResponse::success([], 'Tickets merged successfully', 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), 'Merge failed', 500);
        }
    }

}
