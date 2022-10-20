<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('telephone')->nullable();
            $table->string('addresse')->nullable();
            $table->string('description')->nullable();
            // NIF DU CLIENT
            $table->string('customer_TIN')->nullable();
            //Si le client est assujetti à la TVA Valeur : « 0 » ou « 1 »
            $table->string('vat_customer_payer')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
