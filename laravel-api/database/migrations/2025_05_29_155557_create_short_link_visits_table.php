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
        Schema::create('short_link_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('short_link_id')->constrained()->onDelete('cascade');
            $table->ipAddress('ip_address');
            $table->timestamp('visited_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('short_link_visits');
    }
};
