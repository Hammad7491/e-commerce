<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')->constrained()->cascadeOnDelete();

            $table->string('name');
            $table->decimal('actual_price', 12, 2);
            $table->decimal('discounted_price', 12, 2);
            $table->decimal('discount_percentage', 5, 2)->default(0);

            $table->integer('small_stock')->default(0);
            $table->integer('medium_stock')->default(0);
            $table->integer('large_stock')->default(0);
            $table->integer('xl_stock')->default(0);

            $table->string('bottom_style')->nullable();
            $table->string('color_type')->nullable();
            $table->string('product_code')->nullable();
            $table->string('lining_attached')->nullable();
            $table->string('number_of_pieces')->nullable();
            $table->string('product_type')->nullable();
            $table->string('season')->nullable();
            $table->string('shirt_fabric')->nullable();
            $table->string('top_style')->nullable();
            $table->string('trouser_fabrics')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};