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
        Schema::create('feature_group_tenant', function (Blueprint $table) {
            $table->unsignedBigInteger('feature_group_id');
            $table->string('tenant_id');

            $table->foreign('feature_group_id')->references('id')->on('feature_groups')->onDelete('CASCADE');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('CASCADE');

            $table->primary(['feature_group_id', 'tenant_id']);

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
        Schema::dropIfExists('feature_group_tenant');
    }
};
