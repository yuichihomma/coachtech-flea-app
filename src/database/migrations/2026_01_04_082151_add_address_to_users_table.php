<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('postcode')->nullable()->after('email');
        $table->string('address')->nullable()->after('postcode');
        $table->string('building')->nullable()->after('address');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['postcode', 'address', 'building']);
    });
}

};
