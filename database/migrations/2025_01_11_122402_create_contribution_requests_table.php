<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContributionRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contribution_requests', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone_number');
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->integer('serialNumber');
            $table->year('year');
            $table->string('dataset');
            $table->string('kindOfTraffic');
            $table->enum('publicallyAvailable', ['yes', 'no'])->nullable();
            $table->string('countRecords')->nullable();
            $table->integer('featuresCount')->nullable();
            $table->text('cite');
            $table->integer('citations');
            $table->string('attackType')->nullable();
            $table->text('downloadLinks')->nullable();
            $table->text('abstract');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
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
        Schema::dropIfExists('contribution_requests');
    }
}
