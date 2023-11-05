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
        Schema::table('devices', function (Blueprint $table) {
            $table->text('welcomeMessage')->nullable()->default(null);
            $table->text('errorMessage')->nullable()->default(null);
            $table->integer('errorMessageEnable')->default(0);
            $table->integer('welcomeMessageEnable')->default(0);
            $table->integer('resendToMain')->default(10);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn('welcomeMessage');
            $table->dropColumn('errorMessage');
            $table->dropColumn('welcomeMessageEnable');
            $table->dropColumn('errorMessageEnable');
            $table->dropColumn('resendToMain');
        });
    }
};
