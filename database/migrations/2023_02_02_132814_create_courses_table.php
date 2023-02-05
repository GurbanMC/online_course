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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->index();
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
            $table->string('code')->index();
            $table->string('name_tm');
            $table->string('name_en')->nullable();
            $table->string('full_name_tm');
            $table->string('full_name_en')->nullable();
            $table->string('slug');
            $table->string('video')->nullable();
            $table->unsignedDouble('price')->default(0);
            $table->unsignedFloat('discount_percent')->default(0);
            $table->dateTime('discount_start')->useCurrent();
            $table->dateTime('discount_end')->useCurrent();
            $table->text('description')->nullable();
            $table->unsignedInteger('favorites')->default(0);
            $table->unsignedInteger('viewed')->default(0);
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
        Schema::dropIfExists('courses');
    }
};
