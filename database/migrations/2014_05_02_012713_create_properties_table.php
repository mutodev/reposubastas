<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */


    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('type_id');
            $table->unsignedInteger('status_id')->nullable();
            $table->string('address');
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->decimal('price', 16)->nullable();
            $table->decimal('deposit', 16)->nullable();
            $table->decimal('reserve', 16)->nullable();
            $table->text('open_house_es')->nullable();
            $table->text('open_house_en')->nullable();
            $table->decimal('sqf_area')->nullable();
            $table->decimal('sqm_area')->nullable();
            $table->decimal('cuerdas')->nullable();
            $table->string('internal_number')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('city')->nullable();
            $table->text('description_es')->nullable();
            $table->text('description_en')->nullable();
            $table->string('zonification_es')->nullable();
            $table->string('zonification_en')->nullable();
            $table->string('roof_height')->nullable();
            $table->string('lot_size')->nullable();
            $table->string('levels')->nullable();
            $table->string('amenities_es')->nullable();
            $table->string('amenities_en')->nullable();
            $table->string('region_es')->nullable();
            $table->string('region_en')->nullable();
            $table->string('catastro')->nullable();
            $table->string('image1')->nullable();
            $table->string('image2')->nullable();
            $table->string('image3')->nullable();
            $table->string('image4')->nullable();
            $table->string('image5')->nullable();
            $table->string('image6')->nullable();
            $table->string('image7')->nullable();
            $table->string('image8')->nullable();
            $table->string('image9')->nullable();
            $table->string('image10')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('property_status');
            $table->foreign('type_id')->references('id')->on('property_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties');
    }
}
