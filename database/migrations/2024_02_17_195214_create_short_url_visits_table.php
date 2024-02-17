<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShortUrlVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('short_url_visits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('short_url_id')->constrained()->onDelete('cascade');
            $table->string('ip_address')->nullable();
            $table->string('operating_system')->nullable();
            $table->string('operating_system_version')->nullable();
            $table->string('browser')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('referer_url')->nullable();
            $table->string('device_type')->nullable();
            $table->timestamp('visited_at');
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
        Schema::connection(config('short-url.connection'))->dropIfExists('short_url_visits');
    }
}
