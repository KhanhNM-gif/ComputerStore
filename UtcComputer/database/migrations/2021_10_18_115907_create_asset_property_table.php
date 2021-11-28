<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetPropertyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_property', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('asset_id');
            $table->string('asset_property_name');
            $table->boolean('is_active');
            $table->bigInteger('type_property_asset_ID');
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
        Schema::dropIfExists('asset_property');
    }
}
