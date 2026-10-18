<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('order_number')->unique(); // مثال: PSH-483920
            $table->unsignedBigInteger('total');       // به تومان
            $table->string('status')->default('pending'); // pending|paid|processing|shipped|delivered|cancelled
            $table->string('payment_method')->default('zarinpal');
            $table->string('payment_authority')->nullable();
            $table->string('payment_ref')->nullable();  // کد پیگیری درگاه
            $table->string('recipient_name');
            $table->string('phone', 20);
            $table->text('address');
            $table->string('postal_code', 12)->nullable();
            $table->text('note')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');          // نسخه‌ی اسنپ‌شات از نام محصول
            $table->unsignedBigInteger('price');
            $table->unsignedInteger('qty')->default(1);
            $table->string('color')->nullable();
            $table->json('options')->nullable();
            $table->timestamps();
        });

        Schema::create('custom_designs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_type'); // plush|bag|stationery|cosmetics|mug|gift
            $table->string('color')->nullable();
            $table->string('custom_text')->nullable(); // نوشته‌ای که روی محصول می‌آید
            $table->text('description')->nullable();
            $table->string('image_path')->nullable(); // تصویر آپلودی کاربر
            $table->string('status')->default('pending'); // pending|approved|in_progress|completed|rejected
            $table->text('admin_note')->nullable();
            $table->string('name')->nullable();
            $table->string('phone', 20)->nullable();
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('custom_designs');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
