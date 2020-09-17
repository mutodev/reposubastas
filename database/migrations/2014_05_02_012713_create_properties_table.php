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
            $table->unsignedInteger('investor_id')->nullable();
            $table->unsignedInteger('optioned_by')->nullable();
            $table->string('address');
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->string('investor_reference_id')->nullable();
            $table->string('source_id')->nullable();
            $table->string('check_number')->nullable();
            $table->decimal('check_amount', 16)->nullable();
            $table->string('check_type')->nullable();
            $table->string('bank')->nullable();
            $table->decimal('price', 16)->nullable();
            $table->decimal('deposit', 16)->nullable();
            $table->decimal('reserve', 16)->nullable();
            $table->string('capacity')->nullable();
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
            $table->string('main_image')->default(1);
            $table->string('lister_broker')->nullable();
            $table->string('seller_broker')->nullable();
            $table->decimal('commission')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('bidding_start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->boolean('is_cash_only');
            $table->dateTime('optioned_approved_at')->nullable();
            $table->dateTime('sold_closing_at')->nullable();
            $table->dateTime('optioned_end_at')->nullable();
            $table->string('optioned_method')->nullable();
            $table->string('financing_bank')->nullable();
            $table->string('financing_phone')->nullable();
            $table->string('financing_contact')->nullable();
            $table->decimal('optioned_price', 16)->nullable();
            $table->string('user_number')->nullable();
            $table->text('comments')->nullable();
            $table->decimal('notes_original_loan_amount', 16)->nullable();
            $table->string('notes_position', 16)->nullable();
            $table->decimal('notes_current_balance', 16)->nullable();
            $table->string('notes_loan_type', 16)->nullable();
            $table->decimal('notes_interest_rate', 16)->nullable();
            $table->integer('notes_term')->nullable();
            $table->decimal('notes_monthly_payment', 16)->nullable();
            $table->dateTime('notes_date_of_origination')->nullable();
            $table->boolean('notes_title_report');
            $table->boolean('notes_lien_judgements_against_property');
            $table->boolean('notes_mortgage_note');
            $table->string('notes_deed', 16)->nullable();
            $table->string('notes_crim', 16)->nullable();
            $table->string('notes_annual_payment', 16)->nullable();
            $table->string('notes_hoa', 16)->nullable();
            $table->string('notes_ley_seven', 16)->nullable();
            $table->string('notes_crim_debt', 16)->nullable();
            $table->string('notes_past_due_hoa', 16)->nullable();
            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('property_status');
            $table->foreign('type_id')->references('id')->on('property_type');
            $table->foreign('investor_id')->references('id')->on('investor');
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
        Schema::dropIfExists('properties');
    }
}
