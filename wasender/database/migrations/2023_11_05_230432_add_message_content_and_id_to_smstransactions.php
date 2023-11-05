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
        Schema::table('smstransactions', function (Blueprint $table) {
            $table->string('message_content')->nullable();
            $table->string('message_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('smstransactions', function (Blueprint $table) {
            $table->dropColumn('message_content');
            $table->dropColumn('id');
        });
    }
};
