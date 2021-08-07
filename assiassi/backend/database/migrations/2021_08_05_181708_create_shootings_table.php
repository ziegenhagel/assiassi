<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShootingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shootings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("team_id");
            $table->integer("day_of_week");
            $table->text("location")->nullable();
            $table->text("annotations")->nullable();
            $table->time("departure")->nullable();
            $table->time("arrival")->nullable();
            $table->time("camera_checkout")->nullable();
            $table->time("camera_checkin")->nullable();
            $table->boolean("camera_checkout_completed")->default(false);
            $table->boolean("camera_checkin_completed")->default(false);
            $table->time("studio_checkout")->nullable();
            $table->time("studio_checkin")->nullable();
            $table->boolean("studio_checkout_completed")->default(false);
            $table->boolean("studio_checkin_completed")->default(false);
            $table->timestamps();

            $table->foreign("team_id")->references("id")->on("teams");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shootings');
    }
}
