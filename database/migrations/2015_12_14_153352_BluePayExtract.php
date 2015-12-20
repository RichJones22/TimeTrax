<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BluePayExtract extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bluePay_extract', function (Blueprint $table) {
            $table->increments('id');
            $table->date('extract_date');
            $table->unsignedBigInteger('bp_id');
            $table->string('payment_type', 8);
            $table->string('trans_type', 8);
            $table->double('amount', 7.2);
            $table->string('card_type', 4);
            $table->string('payment_account', 32);
            $table->string('order_id', 128);
            $table->string('invoice_id', 64);
            $table->string('custom_id', 16);
            $table->string('custom_id2', 64);
            $table->string('master_id', 12);
            $table->string('status', 1);
            $table->string('f_void', 1);
            $table->string('message', 64);
            $table->string('origin', 16);
            $table->date('issue_date', 16);
            $table->date('settle_date', 16);
            $table->unsignedBigInteger('rebilling_id');
            $table->unsignedBigInteger('settlement_id');
            $table->string('card_expire', 4);
            $table->string('bank_name', 64);
            $table->string('addr1', 64);
            $table->string('addr2', 64);
            $table->string('city', 32);
            $table->string('state', 16);
            $table->string('zip', 16);
            $table->string('phone', 16);
            $table->string('email', 64);
            $table->string('auth_code', 8);
            $table->string('name1', 32);
            $table->string('name2', 32);
            $table->string('company_name', 64);
            $table->string('memo', 2048);
            $table->string('backend_id', 2048);
            $table->string('doc_type', 32);
            $table->string('f_captured', 1);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bluePay_extract');
    }
}