<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamMealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_meals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("team_id");
            $table->integer("day_of_week");
            $table->enum("meal_of_day",["Frühstück","Mittagessen","Abendbrot"]);
            $table->enum("type",["Im Camp", "Am Set", "Selbstversorgung"]);
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
        Schema::dropIfExists('team_meals');
    }
}
