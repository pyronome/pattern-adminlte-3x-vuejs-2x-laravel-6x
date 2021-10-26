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
        /* {{@snippet:begin_adminltelayouttable_migration}} */        
        if (!Schema::hasTable('adminltelayouttable')) {
            Schema::create('adminltelayouttable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted');
                $table->string('pagename');
                $table->text('widgets');
            });

            $adminLTE = new AdminLTE();
            $adminLTE->setAdminLTEDefaultLayout();
        } else {
            Schema::table('adminltelayouttable', function (Blueprint $table) {
                if (Schema::hasColumn('adminltelayouttable', 'pagename')) { 
                    $table->string('pagename')->change();
                } else {
                    $table->string('pagename');
                }

                if (Schema::hasColumn('adminltelayouttable', 'widgets')) { 
                    $table->text('widgets')->change();
                } else {
                    $table->text('widgets');
                }
            });
        }
        /* {{@snippet:end_adminltelayouttable_migration}} */

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

        /* {{@snippet:begin_adminltepermissiontable_migration}} */        
        if (!Schema::hasTable('adminltepermissiontable')) {
            Schema::create('adminltepermissiontable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted')->default(0);
                $table->bigInteger('usergroup_id', false, true);
                $table->bigInteger('user_id', false, true);
                $table->string('meta_key')->nullable();
                $table->text('permissions')->nullable();
            });
        } else {
            Schema::table('adminltepermissiontable', function (Blueprint $table) {
                if (Schema::hasColumn('adminltepermissiontable', 'usergroup_id')) { 
                    $table->bigInteger('usergroup_id', false, true)->change();
                } else {
                    $table->bigInteger('usergroup_id', false, true);
                }

                if (Schema::hasColumn('adminltepermissiontable', 'user_id')) { 
                    $table->bigInteger('user_id', false, true)->change();
                } else {
                    $table->bigInteger('user_id', false, true);
                }
                
                if (Schema::hasColumn('adminltepermissiontable', 'meta_key')) { 
                    $table->string('meta_key')->nullable()->change();
                } else {
                    $table->string('meta_key')->nullable();
                }

                if (Schema::hasColumn('adminltepermissiontable', 'permissions')) { 
                    $table->text('permissions')->nullable()->change();
                } else {
                    $table->text('permissions')->nullable();
                }
            });
        }
        /* {{@snippet:end_adminltepermissiontable_migration}} */

        /* {{@snippet:begin_adminlteusertable_migration}} */        
        if (!Schema::hasTable('adminlteusertable')) {
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
        } else {
            Schema::table('adminlteusertable', function (Blueprint $table) {
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
                $table->boolean('deleted')->default(0);
                $table->boolean('enabled')->default(0);
                $table->boolean('admin')->default(0);
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
                    'admin' => 1,
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
                    'admin' => 0,
                    'widget_permission' => 1
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
                    'enabled' => 1,
                    'fullname' => 'AdminLTE Root',
                    'username' => 'root',
                    'email' => 'root',
                    'password' => bcrypt('adminlte'),
                    'remember_token' => Str::random(10)
                )
            );
        } else {
            Schema::table('adminlteusergrouptable', function (Blueprint $table) {
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

                if (Schema::hasColumn('adminlteusergrouptable', 'widget_permission')) { 
                    $table->boolean('widget_permission')->default(0)->change();
                } else {
                    $table->boolean('widget_permission')->default(0);
                }
            });
        }
        /* {{@snippet:end_adminlteusergrouptable_migration}} */

        /* {{@snippet:begin_adminlteuserlayouttable_migration}} */        
        if (!Schema::hasTable('adminlteuserlayouttable')) {
            Schema::create('adminlteuserlayouttable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted')->default(0);
                $table->bigInteger('adminlteuser_id', false, true);
                $table->string('pagename')->nullable();
                $table->text('widgets')->nullable();
            });
        } else {
            Schema::table('adminlteuserlayouttable', function (Blueprint $table) {
                if (Schema::hasColumn('adminlteuserlayouttable', 'adminlteuser_id')) { 
                    $table->bigInteger('adminlteuser_id', false, true)->change();
                } else {
                    $table->bigInteger('adminlteuser_id', false, true);
                }

                if (Schema::hasColumn('adminlteuserlayouttable', 'pagename')) { 
                    $table->string('pagename')->nullable()->change();
                } else {
                    $table->string('pagename')->nullable();
                }

                if (Schema::hasColumn('adminlteuserlayouttable', 'widgets')) { 
                    $table->text('widgets')->default(0)->change();
                } else {
                    $table->text('widgets')->default(0);
                }
            });
        }
        /* {{@snippet:end_adminlteuserlayouttable_migration}} */

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

        /* {{@snippet:begin_adminltemenutable_migration}} */        
        if (!Schema::hasTable('adminltemenutable')) {
            Schema::create('adminltemenutable', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->boolean('deleted')->default(0);
                $table->smallInteger('visibility')->default(0);
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
        Schema::dropIfExists('adminltepermissiontable');
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