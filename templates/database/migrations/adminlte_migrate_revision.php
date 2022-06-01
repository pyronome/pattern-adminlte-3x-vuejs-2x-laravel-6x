<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\AdminLTE\AdminLTE;

/* {{@snippet:begin_class}} */

class AdminLTEMigrateRevision{{$ __globals__/PYRONOME_CURRENT_DATE}}{{$ __globals__/PYRONOME_CURRENT_TIME}} extends Migration
{
    /* {{@snippet:begin_methods}} */

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* {{@snippet:begin_up_method}} */

        /* {{@snippet:begin_up_method}} */
        /* {{@snippet:begin_adminltecustomvariabletable_migration}} */        
        if (!Schema::hasTable('adminltecustomvariabletable')) {
            Schema::create('adminltecustomvariabletable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted');
                $table->bigInteger('created_by')->default(0);
                $table->bigInteger('updated_by')->default(0);
                $table->smallInteger('__system')->default(0);
                $table->string('title');
                $table->string('group');
                $table->string('name');
                $table->string('value');
                $table->string('default_value')->nullable();
                $table->smallInteger('remember')->default(0);
                $table->string('remember_type')->nullable();
                $table->bigInteger('__order', false, true);
            });
        } else {
            Schema::table('adminltecustomvariabletable', function (Blueprint $table) {
                if (Schema::hasColumn('adminltecustomvariabletable', 'created_by')) { 
                    $table->bigInteger('created_by')->default(0)->change();
                } else {
                    $table->bigInteger('created_by')->default(0);
                }

                if (Schema::hasColumn('adminltecustomvariabletable', 'updated_by')) { 
                    $table->bigInteger('updated_by')->default(0)->change();
                } else {
                    $table->bigInteger('updated_by')->default(0);
                }

                if (Schema::hasColumn('adminltecustomvariabletable', '__system')) { 
                    $table->smallInteger('__system')->default(0)->change();
                } else {
                    $table->smallInteger('__system')->default(0);
                }

                if (Schema::hasColumn('adminltecustomvariabletable', 'title')) { 
                    $table->string('title')->change();
                } else {
                    $table->string('title');
                }

                if (Schema::hasColumn('adminltecustomvariabletable', 'group')) { 
                    $table->string('group')->change();
                } else {
                    $table->string('group');
                }

                if (Schema::hasColumn('adminltecustomvariabletable', 'name')) { 
                    $table->string('name')->change();
                } else {
                    $table->string('name');
                }

                if (Schema::hasColumn('adminltecustomvariabletable', 'value')) { 
                    $table->string('value')->change();
                } else {
                    $table->string('value');
                }

                if (Schema::hasColumn('adminltecustomvariabletable', 'default_value')) { 
                    $table->longText('default_value')->nullable()->change();
                } else {
                    $table->longText('default_value')->nullable();
                }

                if (Schema::hasColumn('adminltecustomvariabletable', 'remember')) { 
                    $table->smallInteger('remember')->default(0)->change();
                } else {
                    $table->smallInteger('remember')->default(0);
                }

                if (Schema::hasColumn('adminltecustomvariabletable', 'remember_type')) { 
                    $table->longText('remember_type')->nullable()->change();
                } else {
                    $table->longText('remember_type')->nullable();
                }

                if (Schema::hasColumn('adminltecustomvariabletable', '__order')) { 
                    $table->bigInteger('__order', false, true)->change();
                } else {
                    $table->bigInteger('__order', false, true);
                }
            });
        }
        /* {{@snippet:end_adminltecustomvariabletable_migration}} */

        /* {{@snippet:begin_adminltecustomvariablevaluetable_migration}} */        
        if (!Schema::hasTable('adminltecustomvariablevaluetable')) {
            Schema::create('adminltecustomvariablevaluetable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted');
                $table->text('value');
            });
        } else {
            Schema::table('adminltecustomvariablevaluetable', function (Blueprint $table) {
                if (Schema::hasColumn('adminltecustomvariablevaluetable', 'value')) { 
                    $table->text('value')->change();
                } else {
                    $table->text('value');
                }
            });
        }
        /* {{@snippet:end_adminltelayouttable_migration}} */

        /* {{@snippet:begin_adminlteuserconfigfiletable_migration}} */        
        if (!Schema::hasTable('adminlteuserconfigfiletable')) {
            Schema::create('adminlteuserconfigfiletable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted')->default(0);
                $table->longText('__key')->nullable();
                $table->longText('file_name')->nullable();
                $table->longText('description')->nullable();
                $table->longText('mime_type')->nullable();
                $table->bigInteger('file_size')->default(0);
                $table->string('file_type')->nullable();
                $table->binary('file')->nullable();
            });
        } else {
            Schema::table('adminlteuserconfigfiletable', function (Blueprint $table) {
                if (Schema::hasColumn('adminlteuserconfigfiletable', '__key')) { 
                    $table->longText('__key')->nullable()->change();
                } else {
                    $table->longText('__key')->nullable();
                }
                if (Schema::hasColumn('adminlteuserconfigfiletable', 'file_name')) { 
                    $table->longText('file_name')->nullable()->change();
                } else {
                    $table->longText('file_name')->nullable();
                }
                if (Schema::hasColumn('adminlteuserconfigfiletable', 'description')) { 
                    $table->longText('description')->nullable()->change();
                } else {
                    $table->longText('description')->nullable();
                }
                if (Schema::hasColumn('adminlteuserconfigfiletable', 'mime_type')) { 
                    $table->longText('mime_type')->nullable()->change();
                } else {
                    $table->longText('mime_type')->nullable();
                }
                if (Schema::hasColumn('adminlteuserconfigfiletable', 'file_size')) { 
                    $table->bigInteger('file_size')->default(0)->change();
                } else {
                    $table->bigInteger('file_size')->default(0);
                }
                if (Schema::hasColumn('adminlteuserconfigfiletable', 'file_type')) { 
                    $table->string('file_type')->nullable()->change();
                } else {
                    $table->string('file_type')->nullable();
                }
                if (Schema::hasColumn('adminlteuserconfigfiletable', 'file')) { 
                    $table->binary('file')->nullable()->change();
                } else {
                    $table->binary('file')->nullable();
                }
            });
            
        } // if (!Schema::hasTable('adminlteuserconfigfiletable')) {
        
        /* {{@snippet:end_adminlteuserconfigfiletable_migration}} */
        
        /* {{@snippet:begin_adminltelogtable_migration}} */        
        if (!Schema::hasTable('adminltelogtable')) {
            Schema::create('adminltelogtable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted');
                $table->bigInteger('user_id')->default(0);
                $table->string('type');
                $table->string('title');
                $table->string('sub_title');
                $table->bigInteger('object_id')->default(0);
                $table->text('object_old_values');
                $table->text('object_new_values');
                $table->text('message');
            });
        } else {
            Schema::table('adminltelogtable', function (Blueprint $table) {
                if (Schema::hasColumn('adminltelogtable', 'user_id')) { 
                    $table->bigInteger('user_id')->default(0)->change();
                } else {
                    $table->bigInteger('user_id')->default(0);
                }

                if (Schema::hasColumn('adminltelogtable', 'type')) { 
                    $table->string('type')->change();
                } else {
                    $table->string('type');
                }

                if (Schema::hasColumn('adminltelogtable', 'title')) { 
                    $table->string('title')->change();
                } else {
                    $table->string('title');
                }

                if (Schema::hasColumn('adminltelogtable', 'sub_title')) { 
                    $table->string('sub_title')->change();
                } else {
                    $table->string('sub_title');
                }
                
                if (Schema::hasColumn('adminltelogtable', 'object_id')) { 
                    $table->bigInteger('object_id')->default(0)->change();
                } else {
                    $table->bigInteger('object_id')->default(0);
                }

                if (Schema::hasColumn('adminltelogtable', 'object_old_values')) { 
                    $table->text('object_old_values')->change();
                } else {
                    $table->text('object_old_values');
                }

                if (Schema::hasColumn('adminltelogtable', 'object_new_values')) { 
                    $table->text('object_new_values')->change();
                } else {
                    $table->text('object_new_values');
                }

                if (Schema::hasColumn('adminltelogtable', 'message')) { 
                    $table->text('message')->change();
                } else {
                    $table->text('message');
                }
            });
        }
        /* {{@snippet:end_adminltelogtable_migration}} */

        /* {{@snippet:begin_adminltelayouttable_migration}} */        
        if (!Schema::hasTable('adminltelayouttable')) {
            Schema::create('adminltelayouttable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted');
                $table->smallInteger('__system')->default(0);
                $table->boolean('enabled')->default(0);
                $table->bigInteger('__order')->default(0);
                $table->string('pagename');
                $table->string('widget');
                $table->string('title')->nullable();
                $table->string('grid_size');
                $table->string('icon')->nullable();
                $table->text('meta_data_json');
                $table->text('data_source_json');
                $table->text('variable_mapping_json');
                $table->text('conditional_data_json');
            });
        } else {
            Schema::table('adminltelayouttable', function (Blueprint $table) {
                if (Schema::hasColumn('adminltelayouttable', '__system')) { 
                    $table->smallInteger('__system')->default(0)->change();
                } else {
                    $table->smallInteger('__system')->default(0);
                }
                if (Schema::hasColumn('adminltelayouttable', 'enabled')) { 
                    $table->boolean('enabled')->default(0)->change();
                } else {
                    $table->boolean('enabled')->default(0);
                }

                if (Schema::hasColumn('adminltelayouttable', '__order')) { 
                    $table->bigInteger('__order')->default(0)->change();
                } else {
                    $table->bigInteger('__order')->default(0);
                }

                if (Schema::hasColumn('adminltelayouttable', 'pagename')) { 
                    $table->string('pagename')->change();
                } else {
                    $table->string('pagename');
                }

                if (Schema::hasColumn('adminltelayouttable', 'widget')) { 
                    $table->string('widget')->change();
                } else {
                    $table->string('widget');
                }

                if (Schema::hasColumn('adminltelayouttable', 'title')) { 
                    $table->string('title')->change();
                } else {
                    $table->string('title');
                }

                if (Schema::hasColumn('adminltelayouttable', 'grid_size')) { 
                    $table->string('grid_size')->change();
                } else {
                    $table->string('grid_size');
                }

                if (Schema::hasColumn('adminltelayouttable', 'icon')) { 
                    $table->string('icon')->change();
                } else {
                    $table->string('icon');
                }

                if (Schema::hasColumn('adminltelayouttable', 'meta_data_json')) { 
                    $table->text('meta_data_json')->change();
                } else {
                    $table->text('meta_data_json');
                }

                if (Schema::hasColumn('adminltelayouttable', 'data_source_json')) { 
                    $table->text('data_source_json')->change();
                } else {
                    $table->text('data_source_json');
                }

                if (Schema::hasColumn('adminltelayouttable', 'variable_mapping_json')) { 
                    $table->text('variable_mapping_json')->change();
                } else {
                    $table->text('variable_mapping_json');
                }

                if (Schema::hasColumn('adminltelayouttable', 'conditional_data_json')) { 
                    $table->text('conditional_data_json')->change();
                } else {
                    $table->text('conditional_data_json');
                }
            });
        }
        /* {{@snippet:end_adminltelayouttable_migration}} */
        
        /* {{@snippet:begin_adminltemediatable_migration}} */        
        if (!Schema::hasTable('adminltemediatable')) {
            Schema::create('adminltemediatable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted')->default(0);
                $table->bigInteger('created_by')->default(0);
                $table->bigInteger('updated_by')->default(0);
                $table->string('guid')->nullable();
                $table->string('group')->nullable();
                $table->string('file_title')->nullable();
                $table->longText('file_name')->nullable();
                $table->longText('description')->nullable();
                $table->longText('mime_type')->nullable();
                $table->bigInteger('file_size')->default(0);
                $table->string('file_type')->nullable();
                $table->binary('file')->nullable();
            });
        } else {
            Schema::table('adminltemediatable', function (Blueprint $table) {
                if (Schema::hasColumn('adminltemediatable', 'guid')) { 
                    $table->longText('guid')->nullable()->change();
                } else {
                    $table->longText('guid')->nullable();
                }
                if (Schema::hasColumn('adminltemediatable', 'group')) { 
                    $table->longText('group')->nullable()->change();
                } else {
                    $table->longText('group')->nullable();
                }
                if (Schema::hasColumn('adminltemediatable', 'file_title')) { 
                    $table->longText('file_title')->nullable()->change();
                } else {
                    $table->longText('file_title')->nullable();
                }
                if (Schema::hasColumn('adminltemediatable', 'file_name')) { 
                    $table->longText('file_name')->nullable()->change();
                } else {
                    $table->longText('file_name')->nullable();
                }
                if (Schema::hasColumn('adminltemediatable', 'description')) { 
                    $table->longText('description')->nullable()->change();
                } else {
                    $table->longText('description')->nullable();
                }
                if (Schema::hasColumn('adminltemediatable', 'mime_type')) { 
                    $table->longText('mime_type')->nullable()->change();
                } else {
                    $table->longText('mime_type')->nullable();
                }
                if (Schema::hasColumn('adminltemediatable', 'file_size')) { 
                    $table->bigInteger('file_size')->default(0)->change();
                } else {
                    $table->bigInteger('file_size')->default(0);
                }
                if (Schema::hasColumn('adminltemediatable', 'file_type')) { 
                    $table->string('file_type')->nullable()->change();
                } else {
                    $table->string('file_type')->nullable();
                }
                if (Schema::hasColumn('adminltemediatable', 'file')) { 
                    $table->binary('file')->nullable()->change();
                } else {
                    $table->binary('file')->nullable();
                }
            });
            
        } // if (!Schema::hasTable('adminltemediatable')) {
        /* {{@snippet:end_adminltemediatable_migration}} */

        /* {{@snippet:begin_adminltemetatable_migration}} */        
        if (!Schema::hasTable('adminltemetatable')) {
            Schema::create('adminltemetatable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted');
                $table->bigInteger('term_id')->default(0);
                $table->string('meta_key');
                $table->text('meta_value');
            });
        } else {
            Schema::table('adminltemetatable', function (Blueprint $table) {
                if (Schema::hasColumn('adminltemetatable', 'term_id')) { 
                    $table->bigInteger('term_id')->default(0)->change();
                } else {
                    $table->bigInteger('term_id')->default(0);
                }

                if (Schema::hasColumn('adminltemetatable', 'meta_key')) { 
                    $table->string('meta_key')->change();
                } else {
                    $table->string('meta_key');
                }

                if (Schema::hasColumn('adminltemetatable', 'meta_value')) { 
                    $table->text('meta_value')->change();
                } else {
                    $table->text('meta_value');
                }
            });
        }
        /* {{@snippet:end_adminltelayouttable_migration}} */

        /* {{@snippet:begin_adminltemodeldisplaytexttable_migration}} */        
        if (!Schema::hasTable('adminltemodeldisplaytexttable')) {
            Schema::create('adminltemodeldisplaytexttable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted')->default(0);
                $table->string('model')->nullable();
                $table->longText('display_texts')->nullable();
            });
        } else {
            Schema::table('adminltemodeldisplaytexttable', function (Blueprint $table) {
                if (Schema::hasColumn('adminltemodeldisplaytexttable', 'model')) { 
                    $table->string('model')->nullable()->change();
                } else {
                    $table->string('model')->nullable();
                }

                if (Schema::hasColumn('adminltemodeldisplaytexttable', 'display_texts')) { 
                    $table->longText('display_texts')->nullable()->change();
                } else {
                    $table->longText('display_texts')->nullable();
                }
            });
        }
        /* {{@snippet:end_adminltemodeldisplaytexttable_migration}} */

        /* {{@snippet:begin_adminlteusertable_migration}} */        
        if (!Schema::hasTable('adminlteusertable')) {
            Schema::create('adminlteusertable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->bigInteger('created_by')->default(0);
                $table->bigInteger('updated_by')->default(0);
                $table->boolean('deleted')->default(0);
                $table->boolean('enabled')->default(0);
                $table->string('fullname')->nullable();
                $table->string('username')->unique();
                $table->string('email')->unique();
                $table->string('password')->nullable();
                $table->rememberToken();
            });
        } else {
            Schema::table('adminlteusertable', function (Blueprint $table) {
                if (Schema::hasColumn('adminlteusertable', 'created_by')) { 
                    $table->bigInteger('created_by')->default(0)->change();
                } else {
                    $table->bigInteger('created_by')->default(0);
                }

                if (Schema::hasColumn('adminlteusertable', 'updated_by')) { 
                    $table->bigInteger('updated_by')->default(0)->change();
                } else {
                    $table->bigInteger('updated_by')->default(0);
                }

                if (Schema::hasColumn('adminlteusertable', 'enabled')) { 
                    $table->boolean('enabled')->default(0)->change();
                } else {
                    $table->boolean('enabled')->default(0);
                }
                
                if (Schema::hasColumn('adminlteusertable', 'fullname')) { 
                    $table->string('fullname')->nullable()->change();
                } else {
                    $table->string('fullname')->nullable();
                }

                if (Schema::hasColumn('adminlteusertable', 'username')) { 
                    $table->string('username')->change();
                } else {
                    $table->string('username')->unique();
                }

                if (Schema::hasColumn('adminlteusertable', 'email')) { 
                    $table->string('email')->change();
                } else {
                    $table->string('email')->unique();
                }

                if (Schema::hasColumn('adminlteusertable', 'password')) { 
                    $table->string('password')->nullable()->change();
                } else {
                    $table->string('password')->nullable();
                }
            });
        }

        if (!Schema::hasTable('adminlteuser__filetable')) {
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
        }
        /* {{@snippet:end_adminlteusertable_migration}} */

        /* {{@snippet:begin_adminlteusergrouptable_migration}} */        
        if (!Schema::hasTable('adminlteusergrouptable')) {
            Schema::create('adminlteusergrouptable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->bigInteger('created_by')->default(0);
                $table->bigInteger('updated_by')->default(0);
                $table->boolean('deleted')->default(0);
                $table->boolean('enabled')->default(0);
                $table->boolean('admin')->default(0);
                $table->string('title')->nullable();
            });

            DB::table('adminlteusergrouptable')->insert(
                array(
                    'id' => 1,
                    'deleted' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'created_by' => 0,
                    'updated_by' => 0,
                    'title' => 'Administrators',
                    'enabled' => 1,
                    'admin' => 1,
                )
            );
    
            DB::table('adminlteusergrouptable')->insert(
                array(
                    'id' => 2,
                    'deleted' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'created_by' => 0,
                    'updated_by' => 0,
                    'title' => 'Users',
                    'enabled' => 1,
                    'admin' => 0,
                )
            );

            Schema::table('adminlteusertable', function(Blueprint $table) {
                if (Schema::hasColumn('adminlteusertable', 'adminlteusergroup_id')) { 
                    $table->unsignedBigInteger('adminlteusergroup_id')->nullable()->unsigned()->change();
                } else {
                    $table->unsignedBigInteger('adminlteusergroup_id')->nullable()->unsigned();
                }
    
                $foreignKeys = $this->listTableForeignKeys('adminlteusertable');
    
                if (!in_array('adminlteusertable_adminlteusergroup_id_foreign', $foreignKeys)) {
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
                    'created_by' => 0,
                    'updated_by' => 0,
                    'enabled' => 1,
                    'fullname' => 'AdminLTE Root',
                    'username' => 'root',
                    'email' => 'root',
                    'password' => bcrypt('adminlte'),
                    'remember_token' => Str::random(10)
                )
            );

            Schema::table('adminltelayouttable', function(Blueprint $table) {
                if (Schema::hasColumn('adminltelayouttable', 'adminlteusergroup_id')) { 
                    $table->unsignedBigInteger('adminlteusergroup_id')->nullable()->unsigned()->change();
                } else {
                    $table->unsignedBigInteger('adminlteusergroup_id')->nullable()->unsigned();
                }
    
                $foreignKeys = $this->listTableForeignKeys('adminltelayouttable');
    
                if (!in_array('adminltelayouttable_adminlteusergroup_id_foreign', $foreignKeys)) {
                    $table->foreign('adminlteusergroup_id')->references('id')->on('adminlteusergrouptable'); 
                }     
            });

            Schema::table('adminltecustomvariabletable', function(Blueprint $table) {
                if (Schema::hasColumn('adminltecustomvariabletable', 'adminlteusergroup_id')) { 
                    $table->unsignedBigInteger('adminlteusergroup_id')->nullable()->unsigned()->change();
                } else {
                    $table->unsignedBigInteger('adminlteusergroup_id')->nullable()->unsigned();
                }
    
                $foreignKeys = $this->listTableForeignKeys('adminltecustomvariabletable');
    
                if (!in_array('adminltecustomvariabletable_adminlteusergroup_id_foreign', $foreignKeys)) {
                    $table->foreign('adminlteusergroup_id')->references('id')->on('adminlteusergrouptable'); 
                }     
            });

            Schema::table('adminltecustomvariablevaluetable', function(Blueprint $table) {
                if (Schema::hasColumn('adminltecustomvariablevaluetable', 'adminlteusergroup_id')) { 
                    $table->unsignedBigInteger('adminlteusergroup_id')->nullable()->unsigned()->change();
                } else {
                    $table->unsignedBigInteger('adminlteusergroup_id')->nullable()->unsigned();
                }

                if (Schema::hasColumn('adminltecustomvariablevaluetable', 'customvariable_id')) { 
                    $table->unsignedBigInteger('customvariable_id')->nullable()->unsigned()->change();
                } else {
                    $table->unsignedBigInteger('customvariable_id')->nullable()->unsigned();
                }
    
                $foreignKeys = $this->listTableForeignKeys('adminltecustomvariablevaluetable');
    
                if (!in_array('adminltecustomvariablevaluetable_adminlteusergroup_id_foreign', $foreignKeys)) {
                    $table->foreign('adminlteusergroup_id')->references('id')->on('adminlteusergrouptable'); 
                }  
                
                if (!in_array('adminltecustomvariablevaluetable_customvariable_id_foreign', $foreignKeys)) {
                    $table->foreign('customvariable_id')->references('id')->on('adminltecustomvariabletable'); 
                }
            });
        } else {
            Schema::table('adminlteusergrouptable', function (Blueprint $table) {
                if (Schema::hasColumn('adminlteusergrouptable', 'created_by')) { 
                    $table->bigInteger('created_by')->default(0)->change();
                } else {
                    $table->bigInteger('created_by')->default(0);
                }

                if (Schema::hasColumn('adminlteusergrouptable', 'updated_by')) { 
                    $table->bigInteger('updated_by')->default(0)->change();
                } else {
                    $table->bigInteger('updated_by')->default(0);
                }

                if (Schema::hasColumn('adminlteusergrouptable', 'enabled')) { 
                    $table->boolean('enabled')->default(0)->change();
                } else {
                    $table->boolean('enabled')->default(0);
                }

                if (Schema::hasColumn('adminlteusergrouptable', 'admin')) { 
                    $table->boolean('admin')->default(0)->change();
                } else {
                    $table->boolean('admin')->default(0);
                }
                
                if (Schema::hasColumn('adminlteusergrouptable', 'title')) { 
                    $table->string('title')->nullable()->change();
                } else {
                    $table->string('title')->nullable();
                }
            });
        }
        /* {{@snippet:end_adminlteusergrouptable_migration}} */

        /* {{@snippet:begin_adminltevariabletable_migration}} */        
        if (!Schema::hasTable('adminltevariabletable')) {
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
        } else {
            Schema::table('adminltevariabletable', function (Blueprint $table) {
                if (Schema::hasColumn('adminltevariabletable', 'title')) { 
                    $table->string('title')->change();
                } else {
                    $table->string('title');
                }

                if (Schema::hasColumn('adminltevariabletable', 'group')) { 
                    $table->string('group')->change();
                } else {
                    $table->string('group');
                }

                if (Schema::hasColumn('adminltevariabletable', 'value1')) { 
                    $table->string('value1')->change();
                } else {
                    $table->string('value1');
                }

                if (Schema::hasColumn('adminltevariabletable', 'value2')) { 
                    $table->string('value2')->change();
                } else {
                    $table->string('value2');
                }

                if (Schema::hasColumn('adminltevariabletable', 'value3')) { 
                    $table->string('value3')->change();
                } else {
                    $table->string('value3');
                }

                if (Schema::hasColumn('adminltevariabletable', '__order')) { 
                    $table->bigInteger('__order', false, true)->change();
                } else {
                    $table->bigInteger('__order', false, true);
                }
            });
        }
        /* {{@snippet:end_adminltevariabletable_migration}} */

        /* {{@snippet:begin_adminltemodeloptiontable_migration}} */        
        if (!Schema::hasTable('adminltemodeloptiontable')) {
            Schema::create('adminltemodeloptiontable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted')->default(0);
                $table->string('model')->nullable();
                $table->string('property')->nullable();
                $table->string('value')->nullable();
                $table->string('title')->nullable();
            });
        } else {
            Schema::table('adminltemodeloptiontable', function (Blueprint $table) {
                if (Schema::hasColumn('adminltemodeloptiontable', 'model')) { 
                    $table->string('model')->change();
                } else {
                    $table->string('model');
                }

                if (Schema::hasColumn('adminltemodeloptiontable', 'property')) { 
                    $table->string('property')->change();
                } else {
                    $table->string('property');
                }

                if (Schema::hasColumn('adminltemodeloptiontable', 'value')) { 
                    $table->string('value')->change();
                } else {
                    $table->string('value');
                }

                if (Schema::hasColumn('adminltemodeloptiontable', 'title')) { 
                    $table->string('title')->change();
                } else {
                    $table->string('title');
                }
            });
        }
        /* {{@snippet:end_adminltemodeloptiontable_migration}} */

        /* {{@snippet:begin_adminlteconfigtable_migration}} */        
        if (!Schema::hasTable('adminlteconfigtable')) {
            Schema::create('adminlteconfigtable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted')->default(0);
                $table->smallInteger('system')->default(0);
                $table->smallInteger('enabled')->default(0);
                $table->smallInteger('only_admins')->default(0);
                $table->smallInteger('required')->default(0);
                $table->smallInteger('locked')->default(0);
                $table->smallInteger('owner')->default(0);
                $table->bigInteger('__order')->default(0);
                $table->string('type')->nullable();
                $table->bigInteger('parent_id')->default(0);
                $table->longText('__key')->nullable();
                $table->longText('title')->nullable();
                $table->longText('default_value')->nullable();
                $table->longText('value')->nullable();
                $table->longText('hint')->nullable();
                $table->longText('description')->nullable();
                $table->longText('meta_data_json')->nullable();
            });
        } else {
            Schema::table('adminlteconfigtable', function (Blueprint $table) {
                if (Schema::hasColumn('adminlteconfigtable', 'system')) { 
                    $table->smallInteger('system')->default(0)->change();
                } else {
                    $table->smallInteger('system')->default(0);
                }
                if (Schema::hasColumn('adminlteconfigtable', 'enabled')) { 
                    $table->smallInteger('enabled')->default(0)->change();
                } else {
                    $table->smallInteger('enabled')->default(0);
                }
                if (Schema::hasColumn('adminlteconfigtable', 'only_admins')) { 
                    $table->smallInteger('only_admins')->default(0)->change();
                } else {
                    $table->smallInteger('only_admins')->default(0);
                }
                if (Schema::hasColumn('adminlteconfigtable', 'required')) { 
                    $table->smallInteger('required')->default(0)->change();
                } else {
                    $table->smallInteger('required')->default(0);
                }
                if (Schema::hasColumn('adminlteconfigtable', 'locked')) { 
                    $table->smallInteger('locked')->default(0)->change();
                } else {
                    $table->smallInteger('locked')->default(0);
                }
                if (Schema::hasColumn('adminlteconfigtable', 'owner')) { 
                    $table->bigInteger('owner')->default(0)->change();
                } else {
                    $table->bigInteger('owner')->default(0);
                }
                if (Schema::hasColumn('adminlteconfigtable', '__order')) { 
                    $table->bigInteger('__order')->default(0)->change();
                } else {
                    $table->bigInteger('__order')->default(0);
                }
                if (Schema::hasColumn('adminlteconfigtable', 'type')) { 
                    $table->string('type')->nullable()->change();
                } else {
                    $table->string('type')->nullable();
                }
                if (Schema::hasColumn('adminlteconfigtable', 'parent_id')) { 
                    $table->bigInteger('parent_id')->default(0)->change();
                } else {
                    $table->bigInteger('parent_id')->default(0);
                }
                if (Schema::hasColumn('adminlteconfigtable', '__key')) { 
                    $table->longText('__key')->nullable()->change();
                } else {
                    $table->longText('__key')->nullable();
                }
                if (Schema::hasColumn('adminlteconfigtable', 'title')) { 
                    $table->longText('title')->nullable()->change();
                } else {
                    $table->longText('title')->nullable();
                }
                if (Schema::hasColumn('adminlteconfigtable', 'default_value')) { 
                    $table->longText('default_value')->nullable()->change();
                } else {
                    $table->longText('default_value')->nullable();
                }
                if (Schema::hasColumn('adminlteconfigtable', 'value')) { 
                    $table->longText('value')->nullable()->change();
                } else {
                    $table->longText('value')->nullable();
                }
                if (Schema::hasColumn('adminlteconfigtable', 'hint')) { 
                    $table->longText('hint')->nullable()->change();
                } else {
                    $table->longText('hint')->nullable();
                }
                if (Schema::hasColumn('adminlteconfigtable', 'description')) { 
                    $table->longText('description')->nullable()->change();
                } else {
                    $table->longText('description')->nullable();
                }
                if (Schema::hasColumn('adminlteconfigtable', 'meta_data_json')) { 
                    $table->longText('meta_data_json')->nullable()->change();
                } else {
                    $table->longText('meta_data_json')->nullable();
                }
            });
            
        } // if (!Schema::hasTable('adminlteconfigtable')) {

        /* {{@snippet:end_adminlteconfigtable_migration}} */

        /* {{@snippet:begin_adminlteconfigfiletable_migration}} */        
        if (!Schema::hasTable('adminlteconfigfiletable')) {
            Schema::create('adminlteconfigfiletable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted')->default(0);
                $table->longText('parameter')->nullable();
                $table->longText('file_name')->nullable();
                $table->longText('description')->nullable();
                $table->longText('mime_type')->nullable();
                $table->bigInteger('file_size')->default(0);
                $table->string('file_type')->nullable();
                $table->binary('file')->nullable();
            });
        } else {
            Schema::table('adminlteconfigfiletable', function (Blueprint $table) {
                if (Schema::hasColumn('adminlteconfigfiletable', 'parameter')) { 
                    $table->longText('parameter')->nullable()->change();
                } else {
                    $table->longText('parameter')->nullable();
                }
                if (Schema::hasColumn('adminlteconfigfiletable', 'file_name')) { 
                    $table->longText('file_name')->nullable()->change();
                } else {
                    $table->longText('file_name')->nullable();
                }
                if (Schema::hasColumn('adminlteconfigfiletable', 'description')) { 
                    $table->longText('description')->nullable()->change();
                } else {
                    $table->longText('description')->nullable();
                }
                if (Schema::hasColumn('adminlteconfigfiletable', 'mime_type')) { 
                    $table->longText('mime_type')->nullable()->change();
                } else {
                    $table->longText('mime_type')->nullable();
                }
                if (Schema::hasColumn('adminlteconfigfiletable', 'file_size')) { 
                    $table->bigInteger('file_size')->default(0)->change();
                } else {
                    $table->bigInteger('file_size')->default(0);
                }
                if (Schema::hasColumn('adminlteconfigfiletable', 'file_type')) { 
                    $table->string('file_type')->nullable()->change();
                } else {
                    $table->string('file_type')->nullable();
                }
                if (Schema::hasColumn('adminlteconfigfiletable', 'file')) { 
                    $table->binary('file')->nullable()->change();
                } else {
                    $table->binary('file')->nullable();
                }
            });
            
        } // if (!Schema::hasTable('adminlteconfigfiletable')) {

        /* {{@snippet:end_adminlteconfigfiletable_migration}} */

        /* {{@snippet:begin_adminlteuserconfigtable_migration}} */        
        if (!Schema::hasTable('adminlteuserconfigtable')) {
            Schema::create('adminlteuserconfigtable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted')->default(0);
                $table->smallInteger('system')->default(0);
                $table->smallInteger('enabled')->default(0);
                $table->smallInteger('required')->default(0);
                $table->smallInteger('locked')->default(0);
                $table->smallInteger('owner')->default(0);
                $table->bigInteger('__order')->default(0);
                $table->string('type')->nullable();
                $table->bigInteger('parent_id')->default(0);
                $table->longText('__key')->nullable();
                $table->longText('title')->nullable();
                $table->longText('default_value')->nullable();
                $table->longText('value')->nullable();
                $table->longText('hint')->nullable();
                $table->longText('description')->nullable();
                $table->longText('meta_data_json')->nullable();
            });
        } else {
            Schema::table('adminlteuserconfigtable', function (Blueprint $table) {
                if (Schema::hasColumn('adminlteuserconfigtable', 'system')) { 
                    $table->smallInteger('system')->default(0)->change();
                } else {
                    $table->smallInteger('system')->default(0);
                }
                if (Schema::hasColumn('adminlteuserconfigtable', 'enabled')) { 
                    $table->smallInteger('enabled')->default(0)->change();
                } else {
                    $table->smallInteger('enabled')->default(0);
                }
                if (Schema::hasColumn('adminlteuserconfigtable', 'required')) { 
                    $table->smallInteger('required')->default(0)->change();
                } else {
                    $table->smallInteger('required')->default(0);
                }
                if (Schema::hasColumn('adminlteuserconfigtable', 'locked')) { 
                    $table->smallInteger('locked')->default(0)->change();
                } else {
                    $table->smallInteger('locked')->default(0);
                }
                if (Schema::hasColumn('adminlteuserconfigtable', 'owner')) { 
                    $table->bigInteger('owner')->default(0)->change();
                } else {
                    $table->bigInteger('owner')->default(0);
                }
                if (Schema::hasColumn('adminlteuserconfigtable', '__order')) { 
                    $table->bigInteger('__order')->default(0)->change();
                } else {
                    $table->bigInteger('__order')->default(0);
                }
                if (Schema::hasColumn('adminlteuserconfigtable', 'type')) { 
                    $table->string('type')->nullable()->change();
                } else {
                    $table->string('type')->nullable();
                }
                if (Schema::hasColumn('adminlteuserconfigtable', 'parent_id')) { 
                    $table->bigInteger('parent_id')->default(0)->change();
                } else {
                    $table->bigInteger('parent_id')->default(0);
                }
                if (Schema::hasColumn('adminlteuserconfigtable', '__key')) { 
                    $table->longText('__key')->nullable()->change();
                } else {
                    $table->longText('__key')->nullable();
                }
                if (Schema::hasColumn('adminlteuserconfigtable', 'title')) { 
                    $table->longText('title')->nullable()->change();
                } else {
                    $table->longText('title')->nullable();
                }
                if (Schema::hasColumn('adminlteuserconfigtable', 'default_value')) { 
                    $table->longText('default_value')->nullable()->change();
                } else {
                    $table->longText('default_value')->nullable();
                }
                if (Schema::hasColumn('adminlteuserconfigtable', 'value')) { 
                    $table->longText('value')->nullable()->change();
                } else {
                    $table->longText('value')->nullable();
                }
                if (Schema::hasColumn('adminlteuserconfigtable', 'hint')) { 
                    $table->longText('hint')->nullable()->change();
                } else {
                    $table->longText('hint')->nullable();
                }
                if (Schema::hasColumn('adminlteuserconfigtable', 'description')) { 
                    $table->longText('description')->nullable()->change();
                } else {
                    $table->longText('description')->nullable();
                }
                if (Schema::hasColumn('adminlteuserconfigtable', 'meta_data_json')) { 
                    $table->longText('meta_data_json')->nullable()->change();
                } else {
                    $table->longText('meta_data_json')->nullable();
                }
            });
            
        } // if (!Schema::hasTable('adminlteuserconfigtable')) {

        Schema::table('adminlteuserconfigtable', function(Blueprint $table) {
            if (Schema::hasColumn('adminlteuserconfigtable', 'owner_group')) { 
                $table->unsignedBigInteger('owner_group')->nullable()->unsigned()->change();
            } else {
                $table->unsignedBigInteger('owner_group')->nullable()->unsigned();
            }

            $foreignKeys = $this->listTableForeignKeys('adminlteuserconfigtable');

            if (!in_array('adminlteuserconfigtable_owner_group_foreign', $foreignKeys)) {
                $table->foreign('owner_group')->references('id')->on('adminlteusergrouptable'); 
            }     
        });
        /* {{@snippet:end_adminlteuserconfigtable_migration}} */

        /* {{@snippet:begin_adminlteuserconfigvaltable_migration}} */        
        if (!Schema::hasTable('adminlteuserconfigvaltable')) {
            Schema::create('adminlteuserconfigvaltable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted')->default(0);
                $table->longText('__key')->nullable();
                $table->longText('value')->nullable();
            });
        } else {
            Schema::table('adminlteuserconfigvaltable', function (Blueprint $table) {
                if (Schema::hasColumn('adminlteuserconfigvaltable', '__key')) { 
                    $table->longText('__key')->nullable()->change();
                } else {
                    $table->longText('__key')->nullable();
                }
                if (Schema::hasColumn('adminlteuserconfigvaltable', 'value')) { 
                    $table->longText('value')->nullable()->change();
                } else {
                    $table->longText('value')->nullable();
                }
            });
            
        } // if (!Schema::hasTable('adminlteuserconfigvaltable')) {
        /* {{@snippet:end_adminlteuserconfigvaltable_migration}} */

        /* {{@snippet:begin_adminlteuserconfigfiletable_migration}} */        
        if (!Schema::hasTable('adminlteuserconfigfiletable')) {
            Schema::create('adminlteuserconfigfiletable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted')->default(0);
                $table->longText('__key')->nullable();
                $table->longText('file_name')->nullable();
                $table->longText('description')->nullable();
                $table->longText('mime_type')->nullable();
                $table->bigInteger('file_size')->default(0);
                $table->string('file_type')->nullable();
                $table->binary('file')->nullable();
            });
        } else {
            Schema::table('adminlteuserconfigfiletable', function (Blueprint $table) {
                if (Schema::hasColumn('adminlteuserconfigfiletable', '__key')) { 
                    $table->longText('__key')->nullable()->change();
                } else {
                    $table->longText('__key')->nullable();
                }
                if (Schema::hasColumn('adminlteuserconfigfiletable', 'file_name')) { 
                    $table->longText('file_name')->nullable()->change();
                } else {
                    $table->longText('file_name')->nullable();
                }
                if (Schema::hasColumn('adminlteuserconfigfiletable', 'description')) { 
                    $table->longText('description')->nullable()->change();
                } else {
                    $table->longText('description')->nullable();
                }
                if (Schema::hasColumn('adminlteuserconfigfiletable', 'mime_type')) { 
                    $table->longText('mime_type')->nullable()->change();
                } else {
                    $table->longText('mime_type')->nullable();
                }
                if (Schema::hasColumn('adminlteuserconfigfiletable', 'file_size')) { 
                    $table->bigInteger('file_size')->default(0)->change();
                } else {
                    $table->bigInteger('file_size')->default(0);
                }
                if (Schema::hasColumn('adminlteuserconfigfiletable', 'file_type')) { 
                    $table->string('file_type')->nullable()->change();
                } else {
                    $table->string('file_type')->nullable();
                }
                if (Schema::hasColumn('adminlteuserconfigfiletable', 'file')) { 
                    $table->binary('file')->nullable()->change();
                } else {
                    $table->binary('file')->nullable();
                }
            });
            
        } // if (!Schema::hasTable('adminlteuserconfigfiletable')) {
        
        /* {{@snippet:end_adminlteuserconfigfiletable_migration}} */

        /* {{@snippet:begin_adminltemenutable_migration}} */        
        if (!Schema::hasTable('adminltemenutable')) {
            Schema::create('adminltemenutable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted')->default(0);
                $table->smallInteger('visibility')->default(0);
                $table->smallInteger('__group')->default(0);
                $table->bigInteger('__order')->default(0);
                $table->bigInteger('parent_id')->default(0);
                $table->string('text')->nullable();
                $table->string('href')->nullable();
                $table->string('icon')->nullable();
            });
        } else {
            Schema::table('adminltemenutable', function (Blueprint $table) {
                $foreignKeys = $this->listTableForeignKeys('adminltemenutable');
                //Schema::disableForeignKeyConstraints();
                if (Schema::hasColumn('adminltemenutable', 'visibility')) {                    
                    if (in_array('adminltemenutable_visibility_foreign', $foreignKeys)) {
                        $table->dropForeign('adminltemenutable_visibility_foreign');
                        $table->dropIndex('adminltemenutable_visibility_foreign');
                    }
                }
                if (Schema::hasColumn('adminltemenutable', '__group')) {                    
                    if (in_array('adminltemenutable___group_foreign', $foreignKeys)) {
                        $table->dropForeign('adminltemenutable___group_foreign');
                        $table->dropIndex('adminltemenutable___group_foreign');
                    }
                }
                if (Schema::hasColumn('adminltemenutable', '__order')) {                    
                    if (in_array('adminltemenutable___order_foreign', $foreignKeys)) {
                        $table->dropForeign('adminltemenutable___order_foreign');
                        $table->dropIndex('adminltemenutable___order_foreign');
                    }
                }
                if (Schema::hasColumn('adminltemenutable', 'parent_id')) {                    
                    if (in_array('adminltemenutable_parent_id_foreign', $foreignKeys)) {
                        $table->dropForeign('adminltemenutable_parent_id_foreign');
                        $table->dropIndex('adminltemenutable_parent_id_foreign');
                    }
                }
                if (Schema::hasColumn('adminltemenutable', 'text')) {                    
                    if (in_array('adminltemenutable_text_foreign', $foreignKeys)) {
                        $table->dropForeign('adminltemenutable_text_foreign');
                        $table->dropIndex('adminltemenutable_text_foreign');
                    }
                }
                if (Schema::hasColumn('adminltemenutable', 'href')) {                    
                    if (in_array('adminltemenutable_href_foreign', $foreignKeys)) {
                        $table->dropForeign('adminltemenutable_href_foreign');
                        $table->dropIndex('adminltemenutable_href_foreign');
                    }
                }
                if (Schema::hasColumn('adminltemenutable', 'icon')) {                    
                    if (in_array('adminltemenutable_icon_foreign', $foreignKeys)) {
                        $table->dropForeign('adminltemenutable_icon_foreign');
                        $table->dropIndex('adminltemenutable_icon_foreign');
                    }
                }
                //Schema::enableForeignKeyConstraints();
            });

            Schema::table('adminltemenutable', function (Blueprint $table) {
                if (Schema::hasColumn('adminltemenutable', 'visibility')) { 
                    $table->smallInteger('visibility')->default(0)->change();
                } else {
                    $table->smallInteger('visibility')->default(0);
                }
                if (Schema::hasColumn('adminltemenutable', '__group')) { 
                    $table->smallInteger('__group')->default(0)->change();
                } else {
                    $table->smallInteger('__group')->default(0);
                }
                if (Schema::hasColumn('adminltemenutable', '__order')) { 
                    $table->bigInteger('__order')->default(0)->change();
                } else {
                    $table->bigInteger('__order')->default(0);
                }
                if (Schema::hasColumn('adminltemenutable', 'parent_id')) { 
                    $table->bigInteger('parent_id')->default(0)->change();
                } else {
                    $table->bigInteger('parent_id')->default(0);
                }
                if (Schema::hasColumn('adminltemenutable', 'text')) { 
                    $table->string('text')->nullable()->change();
                } else {
                    $table->string('text')->nullable();
                }
                if (Schema::hasColumn('adminltemenutable', 'href')) { 
                    $table->string('href')->nullable()->change();
                } else {
                    $table->string('href')->nullable();
                }
                if (Schema::hasColumn('adminltemenutable', 'icon')) { 
                    $table->string('icon')->nullable()->change();
                } else {
                    $table->string('icon')->nullable();
                }
            });
            
        } // if (!Schema::hasTable('adminltemenutable')) {
        /* {{@snippet:end_adminltemenutable_migration}} */

        $menu = [];

        $menu_item = [];
        $menu_item['text'] = 'Home';
        $menu_item['href'] = 'home';
        $menu_item['icon'] = 'fas fa-home';
        $menu_item['visibility'] = 1;
        $menu_item['parent'] = '';
        array_push($menu, $menu_item);

        $menu_item = [];
        $menu_item['text'] = 'Contents';
        $menu_item['href'] = 'contents';
        $menu_item['icon'] = 'fas fa-align-justify';
        $menu_item['visibility'] = 1;
        $menu_item['parent'] = '';
        array_push($menu, $menu_item);

        $menu_item = [];
        $menu_item['text'] = 'Configuration';
        $menu_item['href'] = 'configuration';
        $menu_item['icon'] = 'fas fa-cog';
        $menu_item['visibility'] = 1;
        $menu_item['parent'] = '';
        array_push($menu, $menu_item);

        $menu_item = [];
        $menu_item['text'] = 'Logout';
        $menu_item['href'] = 'logout';
        $menu_item['icon'] = 'fas fa-power-off';
        $menu_item['visibility'] = 1;
        $menu_item['parent'] = '';
        array_push($menu, $menu_item);

        $adminLTE = new AdminLTE();
        $adminLTE->updateAdminLTEMenu($menu);

        Artisan::call('db:seed', ['--force' => true]);

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
        /*
        Schema::dropIfExists('adminltelayouttable');
        Schema::dropIfExists('adminltemetatable');
        Schema::dropIfExists('adminltemodeldisplaytexttable');
        Schema::dropIfExists('adminlteusertable');
        Schema::dropIfExists('adminlteuser__filetable');
        Schema::dropIfExists('adminlteusergrouptable');
        Schema::dropIfExists('adminlteuserlayouttable');
        Schema::dropIfExists('adminltevariabletable');
        Schema::dropIfExists('adminltemodeloptiontable');
        Schema::dropIfExists('adminltemenuoptiontable');
        */
        /* {{@snippet:end_down_method}} */
    }

    public function listTableForeignKeys($table)
    {
        $conn = Schema::getConnection()->getDoctrineSchemaManager();

        return array_map(function($key) {
            return $key->getName();
        }, $conn->listTableForeignKeys($table));
    }

    /* {{@snippet:end_methods}} */
}

/* {{@snippet:end_class}} */