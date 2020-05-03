<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/* {{snippet:begin_class}} */

class CreateAdminLTEVariableTable extends Migration
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

        Schema::create('adminltevariabletable', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->boolean('deleted');
            $table->string('title');
            $table->string('group');
            $table->string('value1');
            $table->string('value2');
            $table->string('value3');
            $table->bigInteger('__order', false, true);
        });

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

        Schema::dropIfExists('adminltevariabletable');

        /* {{snippet:end_down_method}} */

    }

    /* {{snippet:end_methods}} */
}

/* {{snippet:end_class}} */