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
        Schema::table('device', function (Blueprint $table) {
//            $table->text('welcomeMessage')->nullable();
//            $table->text('errorMessage')->nullable();
//            $table->unsignedInteger('resendToMain')->nullable();
//            $table->string('webhook_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device', function (Blueprint $table) {
//            $table->dropColumn('welcomeMessage');
//            $table->dropColumn('errorMessage');
//            $table->dropColumn('resendToMain');
//            $table->dropColumn('webhook_url');
        });
    }
};
