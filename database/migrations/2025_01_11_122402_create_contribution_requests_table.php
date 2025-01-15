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
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade'); // foreign key to projects table
            $table->integer('serialNumber');
            $table->year('year');
            $table->string('dataset');
            $table->string('kindOfTraffic');
            $table->enum('publicallyAvailable', ['yes', 'no']);
            $table->string('countRecords');
            $table->integer('featuresCount');
            $table->text('citation_text');
            $table->integer('citations');
            $table->string('doi')->nullable();
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
