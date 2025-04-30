<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 10, 2);
            $table->enum('type', ['basic', 'bonus', 'allowance', 'deduction', 'overtime']);
            $table->text('description')->nullable();
            $table->date('payment_date');
            $table->enum('payment_method', ['bank_transfer', 'cash', 'cheque']);
            $table->boolean('is_paid')->default(false);
            $table->integer('month');
            $table->integer('year');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('salaries');
    }
};
