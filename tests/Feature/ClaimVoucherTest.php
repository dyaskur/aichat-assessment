<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ClaimVoucherTest extends TestCase
{

    use DatabaseMigrations;

//    use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    /**
     *  check customer that eligible to claim voucher code(has promotion requirement)
     *
     * @return void
     */
    public function test_claim_with_eligible_customer_response()
    {
        $customer = ['customer_email' => 'eligible@customer.com', 'code' => 'anniversary'];
        $response = $this->post('/api/voucher/check', $customer);

        $response->assertStatus(200);

        $data     = [
            'photo'          => UploadedFile::fake()->image('file.png', 600, 600),
            'customer_email' => 'eligible@customer.com',
            'code'           => 'anniversary',
        ];
        $response = $this->post('/api/voucher/claim', $data);

        $response->assertStatus(200);
        $response->assertJson(["status" => true]);
    }

    /**
     *  check customer that not eligible to claim voucher code(not enough transaction amount)
     *
     * @return void
     */
    public function test_check_customer_that_not_eligible_to_claim_because_not_enough_transaction_amount_response()
    {
        $customer = ['customer_email' => 'poor@customer.com', 'code' => 'anniversary'];
        $response = $this->post('/api/voucher/check', $customer);

        $response->assertStatus(422);
    }

    /**
     *  check customer that not eligible to claim voucher code(already redeemed)
     *
     * @return void
     */
    public function test_check_customer_that_not_eligible_to_claim_because_already_redeemed_response()
    {
        $customer = ['customer_email' => 'redeemed@customer.com', 'code' => 'anniversary'];
        $response = $this->post('/api/voucher/check', $customer);

        $response->assertStatus(422);
    }
}
