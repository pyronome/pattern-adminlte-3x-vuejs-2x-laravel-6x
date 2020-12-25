<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/* {{@snippet:begin_class}} */

class CreateAdminLTEPermissionTable extends Migration
{

    /* {{@snippet:begin_properties}} */

    /* {{@snippet:end_properties}} */

    /* {{@snippet:begin_methods}} */

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /* {{@snippet:begin_up_method}} */

        Schema::create('adminltepermissiontable', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->boolean('deleted')->default(0);
            $table->bigInteger('usergroup_id', false, true);
            $table->bigInteger('user_id', false, true);
            $table->string('meta_key')->nullable();
            $table->text('permissions')->nullable();
        });

        /* {{@snippet:end_up_method}} */

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        /* {{@snippet:begin_down_method}} */

        Schema::dropIfExists('adminltepermissiontable');

        /* {{@snippet:end_down_method}} */
    }

    /* {{@snippet:end_methods}} */

}

/* {{@snippet:end_class}} */
