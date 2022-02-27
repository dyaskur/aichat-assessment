<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Promotion;
use App\Models\PurchaseTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create demo customer that eligible for vouchers
        $eligibleCustomer = Customer::factory()->create([
                                                            'first_name' => 'Eligible Customer',
                                                            'email'      => 'eligible@customer.com',
                                                        ]);
        $eligibleCustomer->transactions()
            ->saveMany(
                PurchaseTransaction::factory(3)
                    ->make([
                               'total_spent' => rand(35, 100),
                           ]
                    )
            );

        //create demo customer that not eligible for vouchers because not enough spent

        $poorCustomer = Customer::factory()->create([
                                                            'first_name' => 'Poor Customer',
                                                            'email'      => 'poor@customer.com',
                                                        ]);
        $poorCustomer->transactions()
            ->saveMany(
                PurchaseTransaction::factory(1)
                    ->make([
                               'total_spent' => rand(1, 20),
                           ]
                    )
            );

        //create demo customer that not eligible for vouchers because already used voucher

        $redeemedCustomer = Customer::factory()->create([
                                                            'first_name' => 'Redeemed Customer',
                                                            'email'      => 'redeemed@customer.com',
                                                        ]);
        $redeemedCustomer->transactions()
            ->saveMany(
                PurchaseTransaction::factory(3)
                    ->make([
                               'promotion_code_id' => Promotion::first()->codes()->first()->id,
                               'total_spent'       => rand(1, 20),
                           ]
                    )
            );

        // create customers from factory with random transactions
        Customer::factory(1000)->create()->each(function($item, $key) {
            //
            $item->transactions()->saveMany(PurchaseTransaction::factory(rand(0, 5))->make());
        });
    }
}
