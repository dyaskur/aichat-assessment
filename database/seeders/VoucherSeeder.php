<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Promotion;
use App\Models\PromotionCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create demo promotion for anniversary  as the assessment of the project

        $promo = Promotion::factory()->create([
                                                  'name'                          => 'Anniversary Promotion',
                                                  'code'                          => 'anniversary',
                                                  'start_date'                    => now(),
                                                  'min_transaction_count'         => 3,
                                                  'last_transaction_days'         => 30,
                                                  'min_transaction_total'         => 100,
                                                  'max_redemption_per_user_count' => 1,
                                              ]);
        $promo->codes()->saveMany(
            PromotionCode::factory(1000)->make()->each(function($promo) {
                $promo->code = 'anniv'.$promo->code;
            })
        );
    }
}
