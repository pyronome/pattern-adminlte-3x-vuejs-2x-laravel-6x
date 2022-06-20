<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Wisilo\Wisilo;

/* {{@snippet:begin_class}} */

class WisiloMigrateRevision{{$ __globals__/PYRONOME_CURRENT_DATE}}{{$ __globals__/PYRONOME_CURRENT_TIME}} extends Migration
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
        /* {{@snippet:begin_wisilocustomvariabletable_migration}} */        
        if (!Schema::hasTable('wisilocustomvariabletable')) {
            Schema::create('wisilocustomvariabletable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted');
                $table->bigInteger('created_by')->default(0);
                $table->bigInteger('updated_by')->default(0);
                $table->smallInteger('__system')->default(0);
                $table->string('title');
                $table->string('group')->nullable();
                $table->string('name');
                $table->string('value')->nullable();
                $table->string('default_value')->nullable();
                $table->smallInteger('remember')->default(0);
                $table->string('remember_type')->nullable();
                $table->bigInteger('__order', false, true);
            });
        } else {
            Schema::table('wisilocustomvariabletable', function (Blueprint $table) {
                if (Schema::hasColumn('wisilocustomvariabletable', 'created_by')) { 
                    $table->bigInteger('created_by')->default(0)->change();
                } else {
                    $table->bigInteger('created_by')->default(0);
                }

                if (Schema::hasColumn('wisilocustomvariabletable', 'updated_by')) { 
                    $table->bigInteger('updated_by')->default(0)->change();
                } else {
                    $table->bigInteger('updated_by')->default(0);
                }

                if (Schema::hasColumn('wisilocustomvariabletable', '__system')) { 
                    $table->smallInteger('__system')->default(0)->change();
                } else {
                    $table->smallInteger('__system')->default(0);
                }

                if (Schema::hasColumn('wisilocustomvariabletable', 'title')) { 
                    $table->string('title')->change();
                } else {
                    $table->string('title');
                }

                if (Schema::hasColumn('wisilocustomvariabletable', 'group')) { 
                    $table->string('group')->change();
                } else {
                    $table->string('group');
                }

                if (Schema::hasColumn('wisilocustomvariabletable', 'name')) { 
                    $table->string('name')->change();
                } else {
                    $table->string('name');
                }

                if (Schema::hasColumn('wisilocustomvariabletable', 'value')) { 
                    $table->string('value')->change();
                } else {
                    $table->string('value');
                }

                if (Schema::hasColumn('wisilocustomvariabletable', 'default_value')) { 
                    $table->longText('default_value')->nullable()->change();
                } else {
                    $table->longText('default_value')->nullable();
                }

                if (Schema::hasColumn('wisilocustomvariabletable', 'remember')) { 
                    $table->smallInteger('remember')->default(0)->change();
                } else {
                    $table->smallInteger('remember')->default(0);
                }

                if (Schema::hasColumn('wisilocustomvariabletable', 'remember_type')) { 
                    $table->longText('remember_type')->nullable()->change();
                } else {
                    $table->longText('remember_type')->nullable();
                }

                if (Schema::hasColumn('wisilocustomvariabletable', '__order')) { 
                    $table->bigInteger('__order', false, true)->change();
                } else {
                    $table->bigInteger('__order', false, true);
                }
            });
        }
        /* {{@snippet:end_wisilocustomvariabletable_migration}} */

        /* {{@snippet:begin_wisilocustomvariablevaluetable_migration}} */        
        if (!Schema::hasTable('wisilocustomvariablevaluetable')) {
            Schema::create('wisilocustomvariablevaluetable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted');
                $table->text('value');
            });
        } else {
            Schema::table('wisilocustomvariablevaluetable', function (Blueprint $table) {
                if (Schema::hasColumn('wisilocustomvariablevaluetable', 'value')) { 
                    $table->text('value')->change();
                } else {
                    $table->text('value');
                }
            });
        }
        /* {{@snippet:end_wisilolayouttable_migration}} */

        /* {{@snippet:begin_wisilouserconfigfiletable_migration}} */        
        if (!Schema::hasTable('wisilouserconfigfiletable')) {
            Schema::create('wisilouserconfigfiletable', function (Blueprint $table) {
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
            Schema::table('wisilouserconfigfiletable', function (Blueprint $table) {
                if (Schema::hasColumn('wisilouserconfigfiletable', '__key')) { 
                    $table->longText('__key')->nullable()->change();
                } else {
                    $table->longText('__key')->nullable();
                }
                if (Schema::hasColumn('wisilouserconfigfiletable', 'file_name')) { 
                    $table->longText('file_name')->nullable()->change();
                } else {
                    $table->longText('file_name')->nullable();
                }
                if (Schema::hasColumn('wisilouserconfigfiletable', 'description')) { 
                    $table->longText('description')->nullable()->change();
                } else {
                    $table->longText('description')->nullable();
                }
                if (Schema::hasColumn('wisilouserconfigfiletable', 'mime_type')) { 
                    $table->longText('mime_type')->nullable()->change();
                } else {
                    $table->longText('mime_type')->nullable();
                }
                if (Schema::hasColumn('wisilouserconfigfiletable', 'file_size')) { 
                    $table->bigInteger('file_size')->default(0)->change();
                } else {
                    $table->bigInteger('file_size')->default(0);
                }
                if (Schema::hasColumn('wisilouserconfigfiletable', 'file_type')) { 
                    $table->string('file_type')->nullable()->change();
                } else {
                    $table->string('file_type')->nullable();
                }
                if (Schema::hasColumn('wisilouserconfigfiletable', 'file')) { 
                    $table->binary('file')->nullable()->change();
                } else {
                    $table->binary('file')->nullable();
                }
            });
            
        } // if (!Schema::hasTable('wisilouserconfigfiletable')) {
        
        /* {{@snippet:end_wisilouserconfigfiletable_migration}} */
        
        /* {{@snippet:begin_wisilologtable_migration}} */        
        if (!Schema::hasTable('wisilologtable')) {
            Schema::create('wisilologtable', function (Blueprint $table) {
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
            Schema::table('wisilologtable', function (Blueprint $table) {
                if (Schema::hasColumn('wisilologtable', 'user_id')) { 
                    $table->bigInteger('user_id')->default(0)->change();
                } else {
                    $table->bigInteger('user_id')->default(0);
                }

                if (Schema::hasColumn('wisilologtable', 'type')) { 
                    $table->string('type')->change();
                } else {
                    $table->string('type');
                }

                if (Schema::hasColumn('wisilologtable', 'title')) { 
                    $table->string('title')->change();
                } else {
                    $table->string('title');
                }

                if (Schema::hasColumn('wisilologtable', 'sub_title')) { 
                    $table->string('sub_title')->change();
                } else {
                    $table->string('sub_title');
                }
                
                if (Schema::hasColumn('wisilologtable', 'object_id')) { 
                    $table->bigInteger('object_id')->default(0)->change();
                } else {
                    $table->bigInteger('object_id')->default(0);
                }

                if (Schema::hasColumn('wisilologtable', 'object_old_values')) { 
                    $table->text('object_old_values')->change();
                } else {
                    $table->text('object_old_values');
                }

                if (Schema::hasColumn('wisilologtable', 'object_new_values')) { 
                    $table->text('object_new_values')->change();
                } else {
                    $table->text('object_new_values');
                }

                if (Schema::hasColumn('wisilologtable', 'message')) { 
                    $table->text('message')->change();
                } else {
                    $table->text('message');
                }
            });
        }
        /* {{@snippet:end_wisilologtable_migration}} */

        /* {{@snippet:begin_wisilolayouttable_migration}} */        
        if (!Schema::hasTable('wisilolayouttable')) {
            Schema::create('wisilolayouttable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted');
                $table->smallInteger('__system')->default(0);
                $table->boolean('enabled')->default(0);
                $table->bigInteger('__order')->default(0);
                $table->string('pagename');
                $table->bigInteger('container_index')->default(0);
                $table->string('container_title')->nullable();
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
            Schema::table('wisilolayouttable', function (Blueprint $table) {
                if (Schema::hasColumn('wisilolayouttable', '__system')) { 
                    $table->smallInteger('__system')->default(0)->change();
                } else {
                    $table->smallInteger('__system')->default(0);
                }
                if (Schema::hasColumn('wisilolayouttable', 'enabled')) { 
                    $table->boolean('enabled')->default(0)->change();
                } else {
                    $table->boolean('enabled')->default(0);
                }

                if (Schema::hasColumn('wisilolayouttable', '__order')) { 
                    $table->bigInteger('__order')->default(0)->change();
                } else {
                    $table->bigInteger('__order')->default(0);
                }

                if (Schema::hasColumn('wisilolayouttable', 'pagename')) { 
                    $table->string('pagename')->change();
                } else {
                    $table->string('pagename');
                }

                if (Schema::hasColumn('wisilolayouttable', 'container_index')) { 
                    $table->bigInteger('container_index')->default(0)->change();
                } else {
                    $table->bigInteger('container_index')->default(0);
                }
                if (Schema::hasColumn('wisilolayouttable', 'container_title')) { 
                    $table->string('container_title')->nullable()->change();
                } else {
                    $table->string('container_title')->nullable();
                }

                if (Schema::hasColumn('wisilolayouttable', 'widget')) { 
                    $table->string('widget')->change();
                } else {
                    $table->string('widget');
                }

                if (Schema::hasColumn('wisilolayouttable', 'title')) { 
                    $table->string('title')->change();
                } else {
                    $table->string('title');
                }

                if (Schema::hasColumn('wisilolayouttable', 'grid_size')) { 
                    $table->string('grid_size')->change();
                } else {
                    $table->string('grid_size');
                }

                if (Schema::hasColumn('wisilolayouttable', 'icon')) { 
                    $table->string('icon')->change();
                } else {
                    $table->string('icon');
                }

                if (Schema::hasColumn('wisilolayouttable', 'meta_data_json')) { 
                    $table->text('meta_data_json')->change();
                } else {
                    $table->text('meta_data_json');
                }

                if (Schema::hasColumn('wisilolayouttable', 'data_source_json')) { 
                    $table->text('data_source_json')->change();
                } else {
                    $table->text('data_source_json');
                }

                if (Schema::hasColumn('wisilolayouttable', 'variable_mapping_json')) { 
                    $table->text('variable_mapping_json')->change();
                } else {
                    $table->text('variable_mapping_json');
                }

                if (Schema::hasColumn('wisilolayouttable', 'conditional_data_json')) { 
                    $table->text('conditional_data_json')->change();
                } else {
                    $table->text('conditional_data_json');
                }
            });
        }
        /* {{@snippet:end_wisilolayouttable_migration}} */
        
        /* {{@snippet:begin_wisilomediatable_migration}} */        
        if (!Schema::hasTable('wisilomediatable')) {
            Schema::create('wisilomediatable', function (Blueprint $table) {
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
            Schema::table('wisilomediatable', function (Blueprint $table) {
                if (Schema::hasColumn('wisilomediatable', 'guid')) { 
                    $table->longText('guid')->nullable()->change();
                } else {
                    $table->longText('guid')->nullable();
                }
                if (Schema::hasColumn('wisilomediatable', 'group')) { 
                    $table->longText('group')->nullable()->change();
                } else {
                    $table->longText('group')->nullable();
                }
                if (Schema::hasColumn('wisilomediatable', 'file_title')) { 
                    $table->longText('file_title')->nullable()->change();
                } else {
                    $table->longText('file_title')->nullable();
                }
                if (Schema::hasColumn('wisilomediatable', 'file_name')) { 
                    $table->longText('file_name')->nullable()->change();
                } else {
                    $table->longText('file_name')->nullable();
                }
                if (Schema::hasColumn('wisilomediatable', 'description')) { 
                    $table->longText('description')->nullable()->change();
                } else {
                    $table->longText('description')->nullable();
                }
                if (Schema::hasColumn('wisilomediatable', 'mime_type')) { 
                    $table->longText('mime_type')->nullable()->change();
                } else {
                    $table->longText('mime_type')->nullable();
                }
                if (Schema::hasColumn('wisilomediatable', 'file_size')) { 
                    $table->bigInteger('file_size')->default(0)->change();
                } else {
                    $table->bigInteger('file_size')->default(0);
                }
                if (Schema::hasColumn('wisilomediatable', 'file_type')) { 
                    $table->string('file_type')->nullable()->change();
                } else {
                    $table->string('file_type')->nullable();
                }
                if (Schema::hasColumn('wisilomediatable', 'file')) { 
                    $table->binary('file')->nullable()->change();
                } else {
                    $table->binary('file')->nullable();
                }
            });
            
        } // if (!Schema::hasTable('wisilomediatable')) {
        /* {{@snippet:end_wisilomediatable_migration}} */

        /* {{@snippet:begin_wisilometatable_migration}} */        
        if (!Schema::hasTable('wisilometatable')) {
            Schema::create('wisilometatable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted');
                $table->bigInteger('term_id')->default(0);
                $table->string('meta_key');
                $table->text('meta_value');
            });
        } else {
            Schema::table('wisilometatable', function (Blueprint $table) {
                if (Schema::hasColumn('wisilometatable', 'term_id')) { 
                    $table->bigInteger('term_id')->default(0)->change();
                } else {
                    $table->bigInteger('term_id')->default(0);
                }

                if (Schema::hasColumn('wisilometatable', 'meta_key')) { 
                    $table->string('meta_key')->change();
                } else {
                    $table->string('meta_key');
                }

                if (Schema::hasColumn('wisilometatable', 'meta_value')) { 
                    $table->text('meta_value')->change();
                } else {
                    $table->text('meta_value');
                }
            });
        }
        /* {{@snippet:end_wisilolayouttable_migration}} */

        /* {{@snippet:begin_wisilomodeldisplaytexttable_migration}} */        
        if (!Schema::hasTable('wisilomodeldisplaytexttable')) {
            Schema::create('wisilomodeldisplaytexttable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted')->default(0);
                $table->string('model')->nullable();
                $table->longText('display_texts')->nullable();
            });
        } else {
            Schema::table('wisilomodeldisplaytexttable', function (Blueprint $table) {
                if (Schema::hasColumn('wisilomodeldisplaytexttable', 'model')) { 
                    $table->string('model')->nullable()->change();
                } else {
                    $table->string('model')->nullable();
                }

                if (Schema::hasColumn('wisilomodeldisplaytexttable', 'display_texts')) { 
                    $table->longText('display_texts')->nullable()->change();
                } else {
                    $table->longText('display_texts')->nullable();
                }
            });
        }
        /* {{@snippet:end_wisilomodeldisplaytexttable_migration}} */

        /* {{@snippet:begin_wisilousertable_migration}} */        
        if (!Schema::hasTable('wisilousertable')) {
            Schema::create('wisilousertable', function (Blueprint $table) {
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
            Schema::table('wisilousertable', function (Blueprint $table) {
                if (Schema::hasColumn('wisilousertable', 'created_by')) { 
                    $table->bigInteger('created_by')->default(0)->change();
                } else {
                    $table->bigInteger('created_by')->default(0);
                }

                if (Schema::hasColumn('wisilousertable', 'updated_by')) { 
                    $table->bigInteger('updated_by')->default(0)->change();
                } else {
                    $table->bigInteger('updated_by')->default(0);
                }

                if (Schema::hasColumn('wisilousertable', 'enabled')) { 
                    $table->boolean('enabled')->default(0)->change();
                } else {
                    $table->boolean('enabled')->default(0);
                }
                
                if (Schema::hasColumn('wisilousertable', 'fullname')) { 
                    $table->string('fullname')->nullable()->change();
                } else {
                    $table->string('fullname')->nullable();
                }

                if (Schema::hasColumn('wisilousertable', 'username')) { 
                    $table->string('username')->change();
                } else {
                    $table->string('username')->unique();
                }

                if (Schema::hasColumn('wisilousertable', 'email')) { 
                    $table->string('email')->change();
                } else {
                    $table->string('email')->unique();
                }

                if (Schema::hasColumn('wisilousertable', 'password')) { 
                    $table->string('password')->nullable()->change();
                } else {
                    $table->string('password')->nullable();
                }
            });
        }

        if (!Schema::hasTable('wisilouser__filetable')) {
            Schema::create('wisilouser__filetable', function (Blueprint $table) {
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
        /* {{@snippet:end_wisilousertable_migration}} */

        /* {{@snippet:begin_wisilousergrouptable_migration}} */        
        if (!Schema::hasTable('wisilousergrouptable')) {
            Schema::create('wisilousergrouptable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->bigInteger('created_by')->default(0);
                $table->bigInteger('updated_by')->default(0);
                $table->boolean('deleted')->default(0);
                $table->boolean('enabled')->default(0);
                $table->boolean('admin')->default(0);
                $table->string('title')->nullable();
            });

            DB::table('wisilousergrouptable')->insert(
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
    
            DB::table('wisilousergrouptable')->insert(
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

            Schema::table('wisilousertable', function(Blueprint $table) {
                if (Schema::hasColumn('wisilousertable', 'wisilousergroup_id')) { 
                    $table->unsignedBigInteger('wisilousergroup_id')->nullable()->unsigned()->change();
                } else {
                    $table->unsignedBigInteger('wisilousergroup_id')->nullable()->unsigned();
                }
    
                /* $foreignKeys = $this->listTableForeignKeys('wisilousertable');
    
                if (!in_array('wisilousertable_wisilousergroup_id_foreign', $foreignKeys)) {
                    $table->foreign('wisilousergroup_id')->references('id')->on('wisilousergrouptable'); 
                }   */   
            });
    
            DB::table('wisilousertable')->insert(
                array(
                    'id' => 1,
                    'deleted' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'wisilousergroup_id' => 1,
                    'created_by' => 0,
                    'updated_by' => 0,
                    'enabled' => 1,
                    'fullname' => 'Wisilo Root',
                    'username' => 'root',
                    'email' => 'root',
                    'password' => bcrypt('wisilo'),
                    'remember_token' => Str::random(10)
                )
            );

            Schema::table('wisilolayouttable', function(Blueprint $table) {
                if (Schema::hasColumn('wisilolayouttable', 'wisilousergroup_id')) { 
                    $table->unsignedBigInteger('wisilousergroup_id')->nullable()->unsigned()->change();
                } else {
                    $table->unsignedBigInteger('wisilousergroup_id')->nullable()->unsigned();
                }
    
                /* $foreignKeys = $this->listTableForeignKeys('wisilolayouttable');
    
                if (!in_array('wisilolayouttable_wisilousergroup_id_foreign', $foreignKeys)) {
                    $table->foreign('wisilousergroup_id')->references('id')->on('wisilousergrouptable'); 
                }  */    
            });

            Schema::table('wisilocustomvariabletable', function(Blueprint $table) {
                if (Schema::hasColumn('wisilocustomvariabletable', 'wisilousergroup_id')) { 
                    $table->unsignedBigInteger('wisilousergroup_id')->nullable()->unsigned()->change();
                } else {
                    $table->unsignedBigInteger('wisilousergroup_id')->nullable()->unsigned();
                }
    
                /* $foreignKeys = $this->listTableForeignKeys('wisilocustomvariabletable');
    
                if (!in_array('wisilocustomvariabletable_wisilousergroup_id_foreign', $foreignKeys)) {
                    $table->foreign('wisilousergroup_id')->references('id')->on('wisilousergrouptable'); 
                } */     
            });

            Schema::table('wisilocustomvariablevaluetable', function(Blueprint $table) {
                if (Schema::hasColumn('wisilocustomvariablevaluetable', 'wisilousergroup_id')) { 
                    $table->unsignedBigInteger('wisilousergroup_id')->nullable()->unsigned()->change();
                } else {
                    $table->unsignedBigInteger('wisilousergroup_id')->nullable()->unsigned();
                }

                if (Schema::hasColumn('wisilocustomvariablevaluetable', 'customvariable_id')) { 
                    $table->unsignedBigInteger('customvariable_id')->nullable()->unsigned()->change();
                } else {
                    $table->unsignedBigInteger('customvariable_id')->nullable()->unsigned();
                }
    
                /* $foreignKeys = $this->listTableForeignKeys('wisilocustomvariablevaluetable');
    
                if (!in_array('wisilocustomvariablevaluetable_wisilousergroup_id_foreign', $foreignKeys)) {
                    $table->foreign('wisilousergroup_id')->references('id')->on('wisilousergrouptable'); 
                }  
                
                if (!in_array('wisilocustomvariablevaluetable_customvariable_id_foreign', $foreignKeys)) {
                    $table->foreign('customvariable_id')->references('id')->on('wisilocustomvariabletable'); 
                } */
            });
        } else {
            Schema::table('wisilousergrouptable', function (Blueprint $table) {
                if (Schema::hasColumn('wisilousergrouptable', 'created_by')) { 
                    $table->bigInteger('created_by')->default(0)->change();
                } else {
                    $table->bigInteger('created_by')->default(0);
                }

                if (Schema::hasColumn('wisilousergrouptable', 'updated_by')) { 
                    $table->bigInteger('updated_by')->default(0)->change();
                } else {
                    $table->bigInteger('updated_by')->default(0);
                }

                if (Schema::hasColumn('wisilousergrouptable', 'enabled')) { 
                    $table->boolean('enabled')->default(0)->change();
                } else {
                    $table->boolean('enabled')->default(0);
                }

                if (Schema::hasColumn('wisilousergrouptable', 'admin')) { 
                    $table->boolean('admin')->default(0)->change();
                } else {
                    $table->boolean('admin')->default(0);
                }
                
                if (Schema::hasColumn('wisilousergrouptable', 'title')) { 
                    $table->string('title')->nullable()->change();
                } else {
                    $table->string('title')->nullable();
                }
            });
        }
        /* {{@snippet:end_wisilousergrouptable_migration}} */

        /* {{@snippet:begin_wisilovariabletable_migration}} */        
        if (!Schema::hasTable('wisilovariabletable')) {
            Schema::create('wisilovariabletable', function (Blueprint $table) {
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
            Schema::table('wisilovariabletable', function (Blueprint $table) {
                if (Schema::hasColumn('wisilovariabletable', 'title')) { 
                    $table->string('title')->change();
                } else {
                    $table->string('title');
                }

                if (Schema::hasColumn('wisilovariabletable', 'group')) { 
                    $table->string('group')->change();
                } else {
                    $table->string('group');
                }

                if (Schema::hasColumn('wisilovariabletable', 'value1')) { 
                    $table->string('value1')->change();
                } else {
                    $table->string('value1');
                }

                if (Schema::hasColumn('wisilovariabletable', 'value2')) { 
                    $table->string('value2')->change();
                } else {
                    $table->string('value2');
                }

                if (Schema::hasColumn('wisilovariabletable', 'value3')) { 
                    $table->string('value3')->change();
                } else {
                    $table->string('value3');
                }

                if (Schema::hasColumn('wisilovariabletable', '__order')) { 
                    $table->bigInteger('__order', false, true)->change();
                } else {
                    $table->bigInteger('__order', false, true);
                }
            });
        }
        /* {{@snippet:end_wisilovariabletable_migration}} */

        /* {{@snippet:begin_wisilomodeloptiontable_migration}} */        
        if (!Schema::hasTable('wisilomodeloptiontable')) {
            Schema::create('wisilomodeloptiontable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted')->default(0);
                $table->string('model')->nullable();
                $table->string('property')->nullable();
                $table->string('value')->nullable();
                $table->string('title')->nullable();
            });
        } else {
            Schema::table('wisilomodeloptiontable', function (Blueprint $table) {
                if (Schema::hasColumn('wisilomodeloptiontable', 'model')) { 
                    $table->string('model')->change();
                } else {
                    $table->string('model');
                }

                if (Schema::hasColumn('wisilomodeloptiontable', 'property')) { 
                    $table->string('property')->change();
                } else {
                    $table->string('property');
                }

                if (Schema::hasColumn('wisilomodeloptiontable', 'value')) { 
                    $table->string('value')->change();
                } else {
                    $table->string('value');
                }

                if (Schema::hasColumn('wisilomodeloptiontable', 'title')) { 
                    $table->string('title')->change();
                } else {
                    $table->string('title');
                }
            });
        }
        /* {{@snippet:end_wisilomodeloptiontable_migration}} */

        /* {{@snippet:begin_wisiloconfigtable_migration}} */        
        if (!Schema::hasTable('wisiloconfigtable')) {
            Schema::create('wisiloconfigtable', function (Blueprint $table) {
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
            Schema::table('wisiloconfigtable', function (Blueprint $table) {
                if (Schema::hasColumn('wisiloconfigtable', 'system')) { 
                    $table->smallInteger('system')->default(0)->change();
                } else {
                    $table->smallInteger('system')->default(0);
                }
                if (Schema::hasColumn('wisiloconfigtable', 'enabled')) { 
                    $table->smallInteger('enabled')->default(0)->change();
                } else {
                    $table->smallInteger('enabled')->default(0);
                }
                if (Schema::hasColumn('wisiloconfigtable', 'only_admins')) { 
                    $table->smallInteger('only_admins')->default(0)->change();
                } else {
                    $table->smallInteger('only_admins')->default(0);
                }
                if (Schema::hasColumn('wisiloconfigtable', 'required')) { 
                    $table->smallInteger('required')->default(0)->change();
                } else {
                    $table->smallInteger('required')->default(0);
                }
                if (Schema::hasColumn('wisiloconfigtable', 'locked')) { 
                    $table->smallInteger('locked')->default(0)->change();
                } else {
                    $table->smallInteger('locked')->default(0);
                }
                if (Schema::hasColumn('wisiloconfigtable', 'owner')) { 
                    $table->bigInteger('owner')->default(0)->change();
                } else {
                    $table->bigInteger('owner')->default(0);
                }
                if (Schema::hasColumn('wisiloconfigtable', '__order')) { 
                    $table->bigInteger('__order')->default(0)->change();
                } else {
                    $table->bigInteger('__order')->default(0);
                }
                if (Schema::hasColumn('wisiloconfigtable', 'type')) { 
                    $table->string('type')->nullable()->change();
                } else {
                    $table->string('type')->nullable();
                }
                if (Schema::hasColumn('wisiloconfigtable', 'parent_id')) { 
                    $table->bigInteger('parent_id')->default(0)->change();
                } else {
                    $table->bigInteger('parent_id')->default(0);
                }
                if (Schema::hasColumn('wisiloconfigtable', '__key')) { 
                    $table->longText('__key')->nullable()->change();
                } else {
                    $table->longText('__key')->nullable();
                }
                if (Schema::hasColumn('wisiloconfigtable', 'title')) { 
                    $table->longText('title')->nullable()->change();
                } else {
                    $table->longText('title')->nullable();
                }
                if (Schema::hasColumn('wisiloconfigtable', 'default_value')) { 
                    $table->longText('default_value')->nullable()->change();
                } else {
                    $table->longText('default_value')->nullable();
                }
                if (Schema::hasColumn('wisiloconfigtable', 'value')) { 
                    $table->longText('value')->nullable()->change();
                } else {
                    $table->longText('value')->nullable();
                }
                if (Schema::hasColumn('wisiloconfigtable', 'hint')) { 
                    $table->longText('hint')->nullable()->change();
                } else {
                    $table->longText('hint')->nullable();
                }
                if (Schema::hasColumn('wisiloconfigtable', 'description')) { 
                    $table->longText('description')->nullable()->change();
                } else {
                    $table->longText('description')->nullable();
                }
                if (Schema::hasColumn('wisiloconfigtable', 'meta_data_json')) { 
                    $table->longText('meta_data_json')->nullable()->change();
                } else {
                    $table->longText('meta_data_json')->nullable();
                }
            });
            
        } // if (!Schema::hasTable('wisiloconfigtable')) {

        /* {{@snippet:end_wisiloconfigtable_migration}} */

        /* {{@snippet:begin_wisiloconfigfiletable_migration}} */        
        if (!Schema::hasTable('wisiloconfigfiletable')) {
            Schema::create('wisiloconfigfiletable', function (Blueprint $table) {
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
            Schema::table('wisiloconfigfiletable', function (Blueprint $table) {
                if (Schema::hasColumn('wisiloconfigfiletable', 'parameter')) { 
                    $table->longText('parameter')->nullable()->change();
                } else {
                    $table->longText('parameter')->nullable();
                }
                if (Schema::hasColumn('wisiloconfigfiletable', 'file_name')) { 
                    $table->longText('file_name')->nullable()->change();
                } else {
                    $table->longText('file_name')->nullable();
                }
                if (Schema::hasColumn('wisiloconfigfiletable', 'description')) { 
                    $table->longText('description')->nullable()->change();
                } else {
                    $table->longText('description')->nullable();
                }
                if (Schema::hasColumn('wisiloconfigfiletable', 'mime_type')) { 
                    $table->longText('mime_type')->nullable()->change();
                } else {
                    $table->longText('mime_type')->nullable();
                }
                if (Schema::hasColumn('wisiloconfigfiletable', 'file_size')) { 
                    $table->bigInteger('file_size')->default(0)->change();
                } else {
                    $table->bigInteger('file_size')->default(0);
                }
                if (Schema::hasColumn('wisiloconfigfiletable', 'file_type')) { 
                    $table->string('file_type')->nullable()->change();
                } else {
                    $table->string('file_type')->nullable();
                }
                if (Schema::hasColumn('wisiloconfigfiletable', 'file')) { 
                    $table->binary('file')->nullable()->change();
                } else {
                    $table->binary('file')->nullable();
                }
            });
            
        } // if (!Schema::hasTable('wisiloconfigfiletable')) {

        /* {{@snippet:end_wisiloconfigfiletable_migration}} */

        /* {{@snippet:begin_wisilouserconfigtable_migration}} */        
        if (!Schema::hasTable('wisilouserconfigtable')) {
            Schema::create('wisilouserconfigtable', function (Blueprint $table) {
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
            Schema::table('wisilouserconfigtable', function (Blueprint $table) {
                if (Schema::hasColumn('wisilouserconfigtable', 'system')) { 
                    $table->smallInteger('system')->default(0)->change();
                } else {
                    $table->smallInteger('system')->default(0);
                }
                if (Schema::hasColumn('wisilouserconfigtable', 'enabled')) { 
                    $table->smallInteger('enabled')->default(0)->change();
                } else {
                    $table->smallInteger('enabled')->default(0);
                }
                if (Schema::hasColumn('wisilouserconfigtable', 'required')) { 
                    $table->smallInteger('required')->default(0)->change();
                } else {
                    $table->smallInteger('required')->default(0);
                }
                if (Schema::hasColumn('wisilouserconfigtable', 'locked')) { 
                    $table->smallInteger('locked')->default(0)->change();
                } else {
                    $table->smallInteger('locked')->default(0);
                }
                if (Schema::hasColumn('wisilouserconfigtable', 'owner')) { 
                    $table->bigInteger('owner')->default(0)->change();
                } else {
                    $table->bigInteger('owner')->default(0);
                }
                if (Schema::hasColumn('wisilouserconfigtable', '__order')) { 
                    $table->bigInteger('__order')->default(0)->change();
                } else {
                    $table->bigInteger('__order')->default(0);
                }
                if (Schema::hasColumn('wisilouserconfigtable', 'type')) { 
                    $table->string('type')->nullable()->change();
                } else {
                    $table->string('type')->nullable();
                }
                if (Schema::hasColumn('wisilouserconfigtable', 'parent_id')) { 
                    $table->bigInteger('parent_id')->default(0)->change();
                } else {
                    $table->bigInteger('parent_id')->default(0);
                }
                if (Schema::hasColumn('wisilouserconfigtable', '__key')) { 
                    $table->longText('__key')->nullable()->change();
                } else {
                    $table->longText('__key')->nullable();
                }
                if (Schema::hasColumn('wisilouserconfigtable', 'title')) { 
                    $table->longText('title')->nullable()->change();
                } else {
                    $table->longText('title')->nullable();
                }
                if (Schema::hasColumn('wisilouserconfigtable', 'default_value')) { 
                    $table->longText('default_value')->nullable()->change();
                } else {
                    $table->longText('default_value')->nullable();
                }
                if (Schema::hasColumn('wisilouserconfigtable', 'value')) { 
                    $table->longText('value')->nullable()->change();
                } else {
                    $table->longText('value')->nullable();
                }
                if (Schema::hasColumn('wisilouserconfigtable', 'hint')) { 
                    $table->longText('hint')->nullable()->change();
                } else {
                    $table->longText('hint')->nullable();
                }
                if (Schema::hasColumn('wisilouserconfigtable', 'description')) { 
                    $table->longText('description')->nullable()->change();
                } else {
                    $table->longText('description')->nullable();
                }
                if (Schema::hasColumn('wisilouserconfigtable', 'meta_data_json')) { 
                    $table->longText('meta_data_json')->nullable()->change();
                } else {
                    $table->longText('meta_data_json')->nullable();
                }
            });
            
        } // if (!Schema::hasTable('wisilouserconfigtable')) {

        Schema::table('wisilouserconfigtable', function(Blueprint $table) {
            if (Schema::hasColumn('wisilouserconfigtable', 'owner_group')) { 
                $table->unsignedBigInteger('owner_group')->nullable()->unsigned()->change();
            } else {
                $table->unsignedBigInteger('owner_group')->nullable()->unsigned();
            }

            /* $foreignKeys = $this->listTableForeignKeys('wisilouserconfigtable');

            if (!in_array('wisilouserconfigtable_owner_group_foreign', $foreignKeys)) {
                $table->foreign('owner_group')->references('id')->on('wisilousergrouptable'); 
            }  */    
        });
        /* {{@snippet:end_wisilouserconfigtable_migration}} */

        /* {{@snippet:begin_wisilouserconfigvaltable_migration}} */        
        if (!Schema::hasTable('wisilouserconfigvaltable')) {
            Schema::create('wisilouserconfigvaltable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted')->default(0);
                $table->longText('__key')->nullable();
                $table->longText('value')->nullable();
            });
        } else {
            Schema::table('wisilouserconfigvaltable', function (Blueprint $table) {
                if (Schema::hasColumn('wisilouserconfigvaltable', '__key')) { 
                    $table->longText('__key')->nullable()->change();
                } else {
                    $table->longText('__key')->nullable();
                }
                if (Schema::hasColumn('wisilouserconfigvaltable', 'value')) { 
                    $table->longText('value')->nullable()->change();
                } else {
                    $table->longText('value')->nullable();
                }
            });
            
        } // if (!Schema::hasTable('wisilouserconfigvaltable')) {
        /* {{@snippet:end_wisilouserconfigvaltable_migration}} */

        /* {{@snippet:begin_wisilouserconfigfiletable_migration}} */        
        if (!Schema::hasTable('wisilouserconfigfiletable')) {
            Schema::create('wisilouserconfigfiletable', function (Blueprint $table) {
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
            Schema::table('wisilouserconfigfiletable', function (Blueprint $table) {
                if (Schema::hasColumn('wisilouserconfigfiletable', '__key')) { 
                    $table->longText('__key')->nullable()->change();
                } else {
                    $table->longText('__key')->nullable();
                }
                if (Schema::hasColumn('wisilouserconfigfiletable', 'file_name')) { 
                    $table->longText('file_name')->nullable()->change();
                } else {
                    $table->longText('file_name')->nullable();
                }
                if (Schema::hasColumn('wisilouserconfigfiletable', 'description')) { 
                    $table->longText('description')->nullable()->change();
                } else {
                    $table->longText('description')->nullable();
                }
                if (Schema::hasColumn('wisilouserconfigfiletable', 'mime_type')) { 
                    $table->longText('mime_type')->nullable()->change();
                } else {
                    $table->longText('mime_type')->nullable();
                }
                if (Schema::hasColumn('wisilouserconfigfiletable', 'file_size')) { 
                    $table->bigInteger('file_size')->default(0)->change();
                } else {
                    $table->bigInteger('file_size')->default(0);
                }
                if (Schema::hasColumn('wisilouserconfigfiletable', 'file_type')) { 
                    $table->string('file_type')->nullable()->change();
                } else {
                    $table->string('file_type')->nullable();
                }
                if (Schema::hasColumn('wisilouserconfigfiletable', 'file')) { 
                    $table->binary('file')->nullable()->change();
                } else {
                    $table->binary('file')->nullable();
                }
            });
            
        } // if (!Schema::hasTable('wisilouserconfigfiletable')) {
        
        /* {{@snippet:end_wisilouserconfigfiletable_migration}} */

        /* {{@snippet:begin_wisilomenutable_migration}} */        
        if (!Schema::hasTable('wisilomenutable')) {
            Schema::create('wisilomenutable', function (Blueprint $table) {
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
            Schema::table('wisilomenutable', function (Blueprint $table) {
                $foreignKeys = $this->listTableForeignKeys('wisilomenutable');
                //Schema::disableForeignKeyConstraints();
                if (Schema::hasColumn('wisilomenutable', 'visibility')) {                    
                    if (in_array('wisilomenutable_visibility_foreign', $foreignKeys)) {
                        $table->dropForeign('wisilomenutable_visibility_foreign');
                        $table->dropIndex('wisilomenutable_visibility_foreign');
                    }
                }
                if (Schema::hasColumn('wisilomenutable', '__group')) {                    
                    if (in_array('wisilomenutable___group_foreign', $foreignKeys)) {
                        $table->dropForeign('wisilomenutable___group_foreign');
                        $table->dropIndex('wisilomenutable___group_foreign');
                    }
                }
                if (Schema::hasColumn('wisilomenutable', '__order')) {                    
                    if (in_array('wisilomenutable___order_foreign', $foreignKeys)) {
                        $table->dropForeign('wisilomenutable___order_foreign');
                        $table->dropIndex('wisilomenutable___order_foreign');
                    }
                }
                if (Schema::hasColumn('wisilomenutable', 'parent_id')) {                    
                    if (in_array('wisilomenutable_parent_id_foreign', $foreignKeys)) {
                        $table->dropForeign('wisilomenutable_parent_id_foreign');
                        $table->dropIndex('wisilomenutable_parent_id_foreign');
                    }
                }
                if (Schema::hasColumn('wisilomenutable', 'text')) {                    
                    if (in_array('wisilomenutable_text_foreign', $foreignKeys)) {
                        $table->dropForeign('wisilomenutable_text_foreign');
                        $table->dropIndex('wisilomenutable_text_foreign');
                    }
                }
                if (Schema::hasColumn('wisilomenutable', 'href')) {                    
                    if (in_array('wisilomenutable_href_foreign', $foreignKeys)) {
                        $table->dropForeign('wisilomenutable_href_foreign');
                        $table->dropIndex('wisilomenutable_href_foreign');
                    }
                }
                if (Schema::hasColumn('wisilomenutable', 'icon')) {                    
                    if (in_array('wisilomenutable_icon_foreign', $foreignKeys)) {
                        $table->dropForeign('wisilomenutable_icon_foreign');
                        $table->dropIndex('wisilomenutable_icon_foreign');
                    }
                }
                //Schema::enableForeignKeyConstraints();
            });

            Schema::table('wisilomenutable', function (Blueprint $table) {
                if (Schema::hasColumn('wisilomenutable', 'visibility')) { 
                    $table->smallInteger('visibility')->default(0)->change();
                } else {
                    $table->smallInteger('visibility')->default(0);
                }
                if (Schema::hasColumn('wisilomenutable', '__group')) { 
                    $table->smallInteger('__group')->default(0)->change();
                } else {
                    $table->smallInteger('__group')->default(0);
                }
                if (Schema::hasColumn('wisilomenutable', '__order')) { 
                    $table->bigInteger('__order')->default(0)->change();
                } else {
                    $table->bigInteger('__order')->default(0);
                }
                if (Schema::hasColumn('wisilomenutable', 'parent_id')) { 
                    $table->bigInteger('parent_id')->default(0)->change();
                } else {
                    $table->bigInteger('parent_id')->default(0);
                }
                if (Schema::hasColumn('wisilomenutable', 'text')) { 
                    $table->string('text')->nullable()->change();
                } else {
                    $table->string('text')->nullable();
                }
                if (Schema::hasColumn('wisilomenutable', 'href')) { 
                    $table->string('href')->nullable()->change();
                } else {
                    $table->string('href')->nullable();
                }
                if (Schema::hasColumn('wisilomenutable', 'icon')) { 
                    $table->string('icon')->nullable()->change();
                } else {
                    $table->string('icon')->nullable();
                }
            });
            
        } // if (!Schema::hasTable('wisilomenutable')) {
        /* {{@snippet:end_wisilomenutable_migration}} */

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

        $wisilo = new Wisilo();
        $wisilo->updateWisiloMenu($menu);

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
        Schema::dropIfExists('wisilolayouttable');
        Schema::dropIfExists('wisilometatable');
        Schema::dropIfExists('wisilomodeldisplaytexttable');
        Schema::dropIfExists('wisilousertable');
        Schema::dropIfExists('wisilouser__filetable');
        Schema::dropIfExists('wisilousergrouptable');
        Schema::dropIfExists('wisilouserlayouttable');
        Schema::dropIfExists('wisilovariabletable');
        Schema::dropIfExists('wisilomodeloptiontable');
        Schema::dropIfExists('wisilomenuoptiontable');
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