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
        Schema::create('promotion_codes', function(Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Promotion::class)->constrained();
            $table->string("code")->unique();
            $table->foreignId("locked_for")->nullable()->constrained('customers', 'id');
            $table->timestamp("locked_until")->nullable();
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
        Schema::dropIfExists('promotion_codes');
    }
};
