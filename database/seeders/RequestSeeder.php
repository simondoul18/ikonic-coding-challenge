<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Request;

class RequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        //accepted requests
        for ($i=2; $i <= 10; $i++) { 
            $data[] = [
                'from_user_id' => $i,
                'to_user_id' => 1,
                'status' => 'accepted',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        //received requests
        for ($i=11; $i <= 30; $i++) { 
            $data[] = [
                'from_user_id' => $i,
                'to_user_id' => 1,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        //sent requests
        for ($i=31; $i <= 40; $i++) { 
            $data[] = [
                'from_user_id' => 1,
                'to_user_id' => $i,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        Request::insert($data);
    }
}
