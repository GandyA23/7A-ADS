<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('isbn', 15)->unique();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->date('publication_date')->nullable();
            $table->foreignId('editorial_id');
            $table->foreignId('category_id');
            $table->timestamps();

            $table->foreign('editorial_id')->references('id')->on('editorials')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}
