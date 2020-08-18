<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/* {{snippet:begin_class}} */

class CreateAdminLTEModelOptionTable extends Migration
{

    /* {{snippet:begin_properties}} */

    /* {{snippet:end_properties}} */

    /* {{snippet:begin_methods}} */

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /* {{snippet:begin_up_method}} */

        Schema::create('adminltemodeloptiontable', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->boolean('deleted')->default(0);
            $table->string('model')->nullable();
            $table->string('property')->nullable();
            $table->string('value')->nullable();
            $table->string('title')->nullable();
        });
        
        Artisan::call('db:seed', ['--force' => true]);
        
        /* {{snippet:end_up_method}} */

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        /* {{snippet:begin_down_method}} */

        Schema::dropIfExists('adminltemodeloptiontable');

        /* {{snippet:end_down_method}} */

    }

    /* {{snippet:end_methods}} */

}

/* {{snippet:end_class}} */
