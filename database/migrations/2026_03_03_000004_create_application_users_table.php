<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('application_users')) {
            return;
        }

        Schema::create('application_users', function (Blueprint $table) {
            $table->id();
            $table->string('user_name', 190)->nullable();
            $table->date('visit_date');
            $table->time('visit_time');
            $table->string('device_type', 20);
            $table->string('ip_address', 45);
            $table->text('user_agent');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_users');
    }
};
