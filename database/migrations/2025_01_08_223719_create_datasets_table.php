<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatasetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datasets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id');
            $table->integer('serialNumber');
            $table->year('year');
            $table->string('dataset');
            $table->string('kindOfTraffic');
            $table->enum('publicallyAvailable', ['yes', 'no'])->nullable();
            $table->string('countRecords')->nullable();
            $table->string('featuresCount')->nullable();
            $table->text('cite');
            $table->integer('citations');
            $table->string('attackType')->nullable();
            $table->text('downloadLinks')->nullable();
            $table->text('abstract');
            // $table->json('custom_attributes')->nullable();
            $table->text('custom_attributes')->nullable();

            $table->timestamps();


            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('datasets');
    }
}
