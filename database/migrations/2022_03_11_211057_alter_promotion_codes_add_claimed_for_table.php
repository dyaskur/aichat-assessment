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
        Schema::table('promotion_codes', function(Blueprint $table) {
            $table->foreignId("claimed_for")->nullable()->constrained('customers', 'id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promotion_codes', function(Blueprint $table) {
            $table->dropForeign(["claimed_for"]);
            $table->dropColumn("claimed_for");
        });
    }
};
