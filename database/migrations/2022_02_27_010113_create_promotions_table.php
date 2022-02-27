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
        Schema::create('promotions', function(Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("description")->nullable();
            $table->string("code")->comment("Unique code for promotion");
            $table->decimal("discount")->default(100);
            $table->timestamp("start_date")->nullable()->comment(
                "Start date of promotion, if null then it will be active from the beginning"
            );
            $table->timestamp("end_date")->nullable()->comment("End date of promotion, if null then it will be active until forever");
            $table->smallInteger("min_transaction_count")->nullable()->comment("Minimum transaction count for promotion to be applied");
            $table->smallInteger("last_transaction_days")->nullable()->comment("Last transaction days, if null or zero then lifetime");
            $table->decimal("min_transaction_total")->nullable()->comment(
                "Minimum transaction total for promotion to be applied, if not set or zero, then promotion will be applied to all transactions"
            );
//            $table->smallInteger("max_redemption_count")->nullable();
            $table->smallInteger("max_redemption_per_user_count")->nullable()->comment(
                "Max redemption count per user, null or 0 means unlimited"
            );
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
        Schema::dropIfExists('promotions');
    }
};
