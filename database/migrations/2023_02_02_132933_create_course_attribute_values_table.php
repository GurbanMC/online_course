<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id')->index();
            $table->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete();
            $table->unsignedBigInteger('attribute_value_id')->index();
            $table->foreign('attribute_value_id')->references('id')->on('attribute_values')->cascadeOnDelete();
            $table->primary(['course_id', 'attribute_value_id']);
            $table->unsignedInteger('sort_order')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_attribute_values');
    }
};
