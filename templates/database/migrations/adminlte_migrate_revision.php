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

        /* {{@snippet:begin_adminlteconfigtable_migration}} */        
        if (!Schema::hasTable('adminlteconfigtable')) {
            Schema::create('adminlteconfigtable', function (Blueprint $table) {
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