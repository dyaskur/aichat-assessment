<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\PurchaseTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(VoucherSeeder::class);
        $this->call(CustomerSeeder::class);
    }
}
