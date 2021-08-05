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
            $table->enum("sd_status",["queue","process","ready"])->nullable();
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
