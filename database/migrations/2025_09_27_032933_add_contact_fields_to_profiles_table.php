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
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('email')->nullable()->after('worked_technologies');
            $table->string('phone')->nullable()->after('email');
            $table->string('location')->nullable()->after('phone');
            $table->string('github')->nullable()->after('location');
            $table->string('linkedin')->nullable()->after('github');
            $table->string('twitter')->nullable()->after('linkedin');
            $table->json('availability')->nullable()->after('twitter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'email',
                'phone',
                'location',
                'github',
                'linkedin',
                'twitter',
                'availability'
            ]);
        });
    }
};
