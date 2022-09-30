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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('invitation_code')->nullable();
            $table->string('token', 255)->nullable();
            $table->string('mac_address')->unique();
            $table->integer('points')->default(0);
            $table->integer('rolls_count')->default(3);
            $table->integer('remain_rolls')->default(3);
            $table->integer('ads_count')->default(3);
            $table->integer('remain_ads_count')->default(3);
            $table->integer('invitation_count')->default(0);
            $table->string('generated_invitation_code');
            $table->integer('subscribe_plan')->default(0);
            $table->integer('ordered_point')->default(0);
            $table->boolean('pinned')->default(0);
            $table->integer('days_count')->default(30);
            $table->integer('code')->nullable();
            $table->boolean('noti')->default(0);
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
        Schema::dropIfExists('users');
    }
};
