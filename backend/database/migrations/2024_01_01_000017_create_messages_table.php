<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('device_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('template_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('campaign_id')->nullable()->constrained()->nullOnDelete();
            $table->string('phone_to', 20);
            $table->string('phone_from', 20)->nullable();
            $table->text('body');
            $table->enum('status', ['pending', 'queued', 'sending', 'sent', 'delivered', 'failed'])->default('pending');
            $table->enum('direction', ['outgoing', 'incoming'])->default('outgoing');
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->decimal('cost', 8, 4)->default(0);
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['device_id', 'status']);
            $table->index('campaign_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
