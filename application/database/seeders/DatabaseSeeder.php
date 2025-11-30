<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        ini_set('memory_limit', '2048M'); 
        set_time_limit(600); // 10 minutes

        $this->seedUsersWithAddress();
    }

    private function seedUsersWithAddress(): void
    {
        $targetCount = 1_000_000;
        $chunkSize = 10_000;
        $chunks = ceil($targetCount / $chunkSize);
        
        DB::connection()->disableQueryLog(); // a little performance optimization

        echo "Starting to seed $targetCount users...\n";
        $startTime = microtime(true);

        try {
            for ($i = 0; $i < $chunks; $i++) {
                $users = [];
                $currentChunkSize = min($chunkSize, $targetCount - ($i * $chunkSize)); // in case we change targetCount and chunkSize

                // create users
                for ($j = 0; $j < $currentChunkSize; $j++) {
                    $userId = ($i * $chunkSize) + $j + 1;
                    $users[] = [
                        'first_name' => fake()->firstName(),
                        'last_name' => fake()->lastName(),
                        'email' => 'user' . $userId . '@example.com',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                DB::table('users')->insert($users);

                // attach addresses to users
                $startId = DB::getPdo()->lastInsertId();
                $addresses = [];
                for ($j = 0; $j < $currentChunkSize; $j++) {
                    $addresses[] = [
                        'user_id' => $startId + $j,
                        'country' => fake()->country(),
                        'city' => fake()->city(),
                        'post_code' => fake()->postcode(),
                        'address' => fake()->streetAddress(),
                    ];
                }
                DB::table('user_addresses')->insert($addresses);

                // show progress for current chunk
                $progress = round(($i + 1) / $chunks * 100,2);
                $duration = round(microtime(true) - $startTime, 2);
                $usersCreated = min(($i + 1) * $chunkSize, $targetCount);
                echo "Progress: $progress% ($usersCreated/$targetCount users) - $duration seconds\n";
            }

            $totalTime = round(microtime(true) - $startTime, 2);
            echo "Completed! Total time: $totalTime seconds\n";
            
            // make sure we created $targetCount users and addresses
            $userCount = DB::table('users')->count();
            $addressCount = DB::table('user_addresses')->count();
            echo "Users created: $userCount\n";
            echo "Addresses created: $addressCount\n";
        } catch (\Exception $e) {
            echo "Error occurred: " . $e->getMessage() . "\n";
            echo "Stack trace: " . $e->getTraceAsString() . "\n";
            throw $e;
        }
    }
}