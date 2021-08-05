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
            $table->text("location");
            $table->text("annotations");
            $table->time("departure");
            $table->time("arrival");
            $table->time("camera_checkout");
            $table->time("camera_checkin");
            $table->boolean("camera_checkout_completed");
            $table->boolean("camera_checkin_completed");
            $table->time("studio_checkout");
            $table->time("studio_checkin");
            $table->boolean("studio_checkout_completed");
            $table->boolean("studio_checkin_completed");
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
