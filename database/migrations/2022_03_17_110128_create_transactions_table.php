<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->references('id')->on('categories');
            $table->foreignId('subCategory_id')->nullable();
            $table->decimal('amount');
            $table->foreignId('payer')->references('id')->on('users');

            $table->dateTime('dueOn');
            $table->float('VAT');
            $table->boolean('is_VAT_inclusive')->default(false);
            $table->enum('status', ['paid','outStanding', 'Overdue'])->default('outStanding');

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
        Schema::dropIfExists('transactions');
    }
}
