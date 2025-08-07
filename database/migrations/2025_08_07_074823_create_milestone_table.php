<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("category");
            $table->text("name");
            $table->text("description");
            $table->datetime("startDate");
            $table->datetime("endDate");
            $table->boolean("achieved");
            $table->integer("goal_id");
            // $table->foreign("goal_id")->references("id")->on("goal")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milestones');
    }
};
