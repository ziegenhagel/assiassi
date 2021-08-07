<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->text("title");
            $table->integer("meal_vegans")->nullable();
            $table->integer("meal_vegetarians")->nullable();
            $table->integer("meal_omnivores")->nullable();
            $table->integer("week")->nullable();
            $table->integer("year")->nullable();
            $table->text("meal_annotation")->nullable();
            $table->char("letter",1)->nullable();
            $table->boolean("is_special_course")->default(false);
            $table->enum("sd_status",["none","queue","process","ready"])->default("none");

            $table->enum("meal_2_0",["Im Camp", "Am Set", "Selbstversorgung"])->default("Im Camp");
            $table->enum("meal_2_1",["Im Camp", "Am Set", "Selbstversorgung"])->default("Im Camp");
            $table->enum("meal_2_2",["Im Camp", "Am Set", "Selbstversorgung"])->default("Im Camp");
            $table->enum("meal_3_0",["Im Camp", "Am Set", "Selbstversorgung"])->default("Im Camp");
            $table->enum("meal_3_1",["Im Camp", "Am Set", "Selbstversorgung"])->default("Im Camp");
            $table->enum("meal_3_2",["Im Camp", "Am Set", "Selbstversorgung"])->default("Im Camp");
            $table->enum("meal_4_0",["Im Camp", "Am Set", "Selbstversorgung"])->default("Im Camp");
            $table->enum("meal_4_1",["Im Camp", "Am Set", "Selbstversorgung"])->default("Im Camp");
            $table->enum("meal_4_2",["Im Camp", "Am Set", "Selbstversorgung"])->default("Im Camp");
            $table->enum("meal_5_0",["Im Camp", "Am Set", "Selbstversorgung"])->default("Im Camp");
            $table->enum("meal_5_1",["Im Camp", "Am Set", "Selbstversorgung"])->default("Im Camp");
            $table->enum("meal_5_2",["Im Camp", "Am Set", "Selbstversorgung"])->default("Im Camp");

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
        Schema::dropIfExists('teams');
    }
}
