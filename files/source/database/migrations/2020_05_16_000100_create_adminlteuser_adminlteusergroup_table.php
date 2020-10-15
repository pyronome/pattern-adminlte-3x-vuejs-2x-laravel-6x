<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminLTEUserAdminLTEUserGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adminlteusertable', function(Blueprint $table) {
            if (!Schema::hasColumn('adminlteusertable', 'adminlteusergroup_id')) {
                $table->unsignedBigInteger('adminlteusergroup_id')->default(0);
            	$table->foreign('adminlteusergroup_id')->references('id')->on('adminlteusergrouptable');
			}                
        });

        DB::table('adminlteusertable')->insert(
            array(
                'id' => 1,
                'deleted' => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'adminlteusergroup_id' => 1,
                'enabled' => 1,
                'fullname' => 'AdminLTE Root',
                'username' => 'root',
                'email' => 'root',
                'password' => bcrypt('adminlte'),
                'remember_token' => Str::random(10)
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adminlteusertable', function(Blueprint $table) {
            
        });
    }
}
