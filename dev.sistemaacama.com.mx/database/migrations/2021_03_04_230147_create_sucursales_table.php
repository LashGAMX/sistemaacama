<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSucursalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sucursales', function (Blueprint $table) {
            $table->id('Id_sucursal');
            $table->string('Sucursal');
            $table->integer('Id_user_c')->nullable(); 
            $table->integer('Id_user_m')->nullable();
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
        Schema::dropIfExists('sucursales');
    }
}
