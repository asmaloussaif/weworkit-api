<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
                   $table->id();
                   $table->foreignId('freelancer_id')->constrained('users')->onDelete('cascade');
                   $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
                   $table->text('motivation');
                   $table->text('statut')->nullable();
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
        Schema::dropIfExists('applications');
    }
};
