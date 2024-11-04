<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->string('product_name');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->string('unit');
            $table->unsignedBigInteger('color_id');
            $table->foreign('color_id')->references('id')->on('colors');
            $table->integer('is_product_variant')->default(0);
            $table->string('product_price');
            $table->string('product_discount');
            $table->string('seller_price');
            $table->string('seller_discount');
            $table->string('current_stock');
            $table->string('product_sku');
            $table->string('tags');
            $table->string('product_slug');
            $table->string('product_image')->nullable();
            $table->string('product_gallery_image');
            $table->string('product_video_url');
            $table->string('short_description');
            $table->string('long_description');
            $table->integer('status')->default(0);
            $table->integer('is_featured')->nullable();
            $table->integer('is_newArrival')->nullable();
            $table->integer('is_offers')->nullable();
            $table->integer('is_bestSelling')->nullable();
            $table->integer('is_favourite')->default(0);
            $table->string('meta_title');
            $table->string('meta_description');
            $table->string('meta_keywords');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
