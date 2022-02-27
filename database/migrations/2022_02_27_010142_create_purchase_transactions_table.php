<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_transactions', function(Blueprint $table) {
            $table->id();
            $table->foreignId("customer_id")->constrained('customers', 'id');
            $table->foreignId("promotion_code_id")->nullable()->constrained('promotion_codes', 'id');
            $table->decimal("total_spent", 10, 2);
            $table->decimal("total_saving", 10, 2)->default(0)->comment("Total saving/discount from the purchase");
            $table->timestamp('transaction_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_transactions');
    }
};
