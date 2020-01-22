<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlanceBankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blance_bank', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('balance')->default(0);
            $table->integer('balance_achieve')->default(0);
            $table->string('code');
            $table->boolean('enable');
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
        Schema::dropIfExists('blance_bank');
    }
}
