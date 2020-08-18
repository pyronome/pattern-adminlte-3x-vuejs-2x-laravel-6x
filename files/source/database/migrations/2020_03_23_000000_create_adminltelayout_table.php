<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\AdminLTE\AdminLTE;

/* {{snippet:begin_class}} */

class CreateAdminLTELayoutTable extends Migration
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

        Schema::create('adminltelayouttable', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->boolean('deleted');
            $table->string('pagename');
            $table->text('widgets');
        });

        $adminLTE = new AdminLTE();
        $adminLTE->setAdminLTEDefaultLayout();

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

        Schema::dropIfExists('adminltelayouttable');

        /* {{snippet:end_down_method}} */
    }

    /* {{snippet:end_methods}} */

}

/* {{snippet:end_class}} */
