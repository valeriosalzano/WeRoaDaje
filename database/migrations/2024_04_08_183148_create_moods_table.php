<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moods', function (Blueprint $table) {
            $table->uuid('travelId');
            $table->foreign('travelId')->references('id')->on('travels')->cascadeOnDelete();
            $table->unsignedTinyInteger('nature');
            $table->unsignedTinyInteger('relax');
            $table->unsignedTinyInteger('history');
            $table->unsignedTinyInteger('culture');
            $table->unsignedTinyInteger('party');
            $table->index('travelId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('moods');
    }
};
