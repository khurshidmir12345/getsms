<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('device_id', 128)->unique();
            $table->string('token', 128)->unique();
            $table->string('phone_number', 20)->nullable();
            $table->string('operator', 20)->nullable();
            $table->json('sim_slots')->nullable();
            $table->enum('status', ['online', 'offline'])->default('offline');
            $table->timestamp('last_seen_at')->nullable();
            $table->unsignedTinyInteger('battery_level')->nullable();
            $table->unsignedTinyInteger('signal_strength')->nullable();
            $table->string('model')->nullable();
            $table->string('android_version', 10)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
