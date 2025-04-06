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
        Schema::create('phones', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->unsignedBigInteger('category_id');
                $table->foreign('category_id')
                ->references('id')
                ->on('product_categories')
                ->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->decimal('discountPercentage', 5, 2);
            $table->decimal('rating', 5, 2);
            $table->integer('stock');
            $table->unsignedBigInteger('brand_id')->nullable();
                $table->foreign('brand_id')
                ->references('id')
                ->on('brands')
                ->onDelete('cascade');
            $table->string('sku');
            $table->integer('weight');

            $table->json('dimensions');

            $table->string('warrantyInformation');
            $table->string('shippingInformation');
            $table->string('availabilityStatus');
            $table->string('returnPolicy');
            $table->integer('minimumOrderQuantity');

            $table->json('meta');

            $table->string('thumbnail');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phones');
    }
};
