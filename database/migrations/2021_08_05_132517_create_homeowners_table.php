<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeownersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homeowners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('first_name')->nullable();
            $table->string('initial')->nullable();
            $table->string('last_name');
            $table->string('upload_file')->nullable();
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
        Schema::dropIfExists('homeowners');
    }
}
