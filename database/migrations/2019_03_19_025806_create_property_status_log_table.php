<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyStatusLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_status_log', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('property_id');
            $table->unsignedInteger('old_status_id')->nullable();
            $table->unsignedInteger('new_status_id');
            $table->unsignedInteger('optioned_by')->nullable();
            $table->text('payload');
            $table->timestamps();

            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->foreign('old_status_id')->references('id')->on('property_status')->onDelete('cascade');
            $table->foreign('new_status_id')->references('id')->on('property_status')->onDelete('cascade');
            $table->foreign('optioned_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_status_log');
    }
}
