<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('item_master')) {
            return;
        }

        Schema::create('item_master', function (Blueprint $table) {
            $table->increments('ID');
            $table->string('ItemName', 100);
            $table->string('Description', 100)->nullable();
            $table->string('Status', 100);
            $table->string('Created_by', 100)->default('');
            $table->timestamp('Created_on')->useCurrent();
            $table->string('updated_by', 100)->default('');
            $table->timestamp('updated_on')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_master');
    }
};
