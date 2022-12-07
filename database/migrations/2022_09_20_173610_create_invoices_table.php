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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id('id');
            $table->string('invoice_number');
            $table->date('invoice_Date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('product');
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->decimal('Amount_collection' , 8  , 2) ;
            $table->decimal('Amount_commission', 8  , 2) ;
            $table->decimal('discount' , 8, 2);
            $table->decimal('value_vat',8,2);
            $table->string('Rate_Vat', 50);
            $table->decimal('Total',8,2);
            $table->string('Status', 50);
            $table->date('Payment_Date')->nullable();
            $table->integer('value_status');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('invoices');
    }
};
