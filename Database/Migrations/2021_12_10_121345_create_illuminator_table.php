<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIlluminatorsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('illuminators', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('primary_account_manager_id');
            $table->foreign('primary_account_manager_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');
            $table->string('name');
            $table->string('website')->nullable();
            $table->unsignedBigInteger('contact_address_id');
            $table->foreign('contact_address_id')
                ->references('id')
                ->on('addresses')
                ->onDelete('restrict');
            $table->unsignedBigInteger('billing_address_id');
            $table->foreign('bill_address_id')
                ->references('id')
                ->on('addresses')
                ->onDelete('restrict');
            $table->unsignedBigInteger('tech_address_id')->nullable();
            $table->foreign('tech_address_id')
                ->references('id')
                ->on('addresses')
                ->onDelete('restrict');
            $table->string('status');
            $table->unsignedBigInteger('bond_id')->nullable();
            $table->foreign('bond_id')
                ->references('id')
                ->on('bonds')
                ->onDelete('restrict');
            $table->unsignedBigInteger('rev_request_id')->nullable();
            $table->foreign('rev_request_id')
                ->references('id')
                ->on('illuminators')
                ->onDelete('set null');
            $table->unsignedBigInteger('rev_request_for')->nullable();
            $table->foreign('rev_request_for')
                ->references('id')
                ->on('illuminators')
                ->onDelete('cascade');
            $table->text('webhook_url')->nullable();
            $table->string('default_cost_model');
            $table->decimal('default_cost_value', 20, 4);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('illuminators');
    }
}
