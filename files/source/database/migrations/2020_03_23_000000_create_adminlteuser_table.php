<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/* {{snippet:begin_class}} */

class CreateAdminLTEUserTable extends Migration
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

        Schema::create('adminlteusertable', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->boolean('deleted')->default(0);
            $table->boolean('enabled')->default(0);
            $table->string('fullname')->nullable();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->rememberToken();
        });

        Schema::create('adminlteuser__filetable', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->boolean('deleted')->default(0);
            $table->smallInteger('enabled')->default(0);
            $table->string('guid')->default(NULL);
            $table->bigInteger('file_index')->default(0);
            $table->bigInteger('object_id')->default(0);
            $table->string('object_property')->default(NULL);
            $table->string('file_name')->default(NULL);
            $table->bigInteger('file_size')->default(0);
            $table->smallInteger('media_type')->default(0);
            $table->string('path')->default(NULL);
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

        Schema::dropIfExists('adminlteusertable');

        /* {{snippet:end_down_method}} */

    }

    /* {{snippet:end_methods}} */

}

/* {{snippet:end_class}} */
