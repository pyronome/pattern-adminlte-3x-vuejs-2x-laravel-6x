<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/* {{snippet:begin_class}} */

class CreateAdminLTEUserGroupTable extends Migration
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

        Schema::create('adminlteusergrouptable', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->boolean('deleted')->default(0);
            $table->boolean('enabled')->default(0);
            $table->string('title')->nullable();
            $table->boolean('widget_permission')->default(0);
        });

        DB::table('adminlteusergrouptable')->insert(
            array(
                'id' => 1,
                'deleted' => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'title' => 'Administrators',
                'enabled' => 1,
                'widget_permission' => 1
            )
        );

        DB::table('adminlteusergrouptable')->insert(
            array(
                'id' => 2,
                'deleted' => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'title' => 'Users',
                'enabled' => 1,
                'widget_permission' => 1
            )
        );

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

        Schema::dropIfExists('adminlteusergrouptable');

        /* {{snippet:end_down_method}} */
    }

    /* {{snippet:end_methods}} */

}

/* {{snippet:end_class}} */
