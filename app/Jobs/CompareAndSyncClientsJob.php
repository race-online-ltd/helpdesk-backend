<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\BusinessEntityWiseClient;
use Illuminate\Support\Facades\DB;

class CompareAndSyncClientsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $businessEntityId;
    protected array $apiData;

    public function __construct(int $businessEntityId, array $apiData)
    {
        $this->businessEntityId = $businessEntityId;
        $this->apiData = $apiData;
    }

    public function handle(): void
    {
        // 🔹 API data → keyed by client_id (FAST lookup)
        $apiClients = collect($this->apiData)->keyBy('client_id');

        // 🔹 DB data chunking (memory safe)
        BusinessEntityWiseClient::where('business_entity_id', $this->businessEntityId)
            ->select('client_id')
            ->chunk(1000, function ($dbChunk) use (&$apiClients) {

                $dbClientIds = $dbChunk->pluck('client_id')->toArray();

                // remove existing ids from API list
                foreach ($dbClientIds as $id) {
                    unset($apiClients[$id]);
                }
            });

        // 🔹 remaining apiClients = NEW DATA ONLY
        if ($apiClients->isEmpty()) {
            return;
        }

        // 🔹 bulk insert (FASTEST)
        $insertData = [];

        foreach ($apiClients as $client) {
            $insertData[] = [
                'business_entity_id' => $this->businessEntityId,
                'client_id'          => $client['client_id'],
                'client_name'        => $client['client_name'],
                'created_at'         => now(),
                'updated_at'         => now(),
            ];
        }

        // insert in chunks (avoid max packet issue)
        foreach (array_chunk($insertData, 1000) as $chunk) {
            DB::table('business_entity_wise_clients')->insert($chunk);
        }
        
    }
}

