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
                $table->bigInteger('__order')->default(0);
                $table->string('type')->nullable();
                $table->bigInteger('parent_id')->default(0);
                $table->longText('__key')->nullable();
                $table->longText('title')->nullable();
                $table->longText('default_value')->nullable();
                $table->longText('value')->nullable();
                $table->longText('meta_data')->nullable();
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
                if (Schema::hasColumn('adminlteconfigtable', 'meta_data')) { 
                    $table->longText('meta_data')->nullable()->change();
                } else {
                    $table->longText('meta_data')->nullable();
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
        $menu_item['text'] = 'Server Information';
        $menu_item['href'] = 'server_information';
        $menu_item['icon'] = 'fas fa-server';
        $menu_item['visibility'] = 1;
        $menu_item['parent'] = 'configuration';
        array_push($menu, $menu_item);

        $menu_item = [];
        $menu_item['text'] = 'Preferences';
        $menu_item['href'] = 'preferences';
        $menu_item['icon'] = 'fas fa-asterisk';
        $menu_item['visibility'] = 1;
        $menu_item['parent'] = 'configuration';
        array_push($menu, $menu_item);
            
        $menu_item = [];
        $menu_item['text'] = 'General Settings';
        $menu_item['href'] = 'general_settings';
        $menu_item['icon'] = 'fas fa-atom';
        $menu_item['visibility'] = 1;
        $menu_item['parent'] = 'configuration';
        array_push($menu, $menu_item);
            
        $menu_item = [];
        $menu_item['text'] = 'Branding';
        $menu_item['href'] = 'branding';
        $menu_item['icon'] = 'fas fa-bold';
        $menu_item['visibility'] = 1;
        $menu_item['parent'] = 'configuration';
        array_push($menu, $menu_item);
            
        $menu_item = [];
        $menu_item['text'] = 'Mail (SMTP) Server';
        $menu_item['href'] = 'email_server';
        $menu_item['icon'] = 'fas fa-mail-bulk';
        $menu_item['visibility'] = 1;
        $menu_item['parent'] = 'configuration';
        array_push($menu, $menu_item);
            
        $menu_item = [];
        $menu_item['text'] = 'Menu Configuration';
        $menu_item['href'] = 'menu_configuration';
        $menu_item['icon'] = 'fas fa-list-ol';
        $menu_item['visibility'] = 1;
        $menu_item['parent'] = 'configuration';
        array_push($menu, $menu_item);
            
        $menu_item = [];
        $menu_item['text'] = 'Model Display Settings';
        $menu_item['href'] = 'adminltemodeldisplaytext';
        $menu_item['icon'] = 'fas fa-sort-alpha-down';
        $menu_item['visibility'] = 1;
        $menu_item['parent'] = 'configuration';
        array_push($menu, $menu_item);
            
        $menu_item = [];
        $menu_item['text'] = 'Users';
        $menu_item['href'] = 'adminlteuser';
        $menu_item['icon'] = 'fas fa-user-cog';
        $menu_item['visibility'] = 1;
        $menu_item['parent'] = 'configuration';
        array_push($menu, $menu_item);
            
        $menu_item = [];
        $menu_item['text'] = 'User Groups';
        $menu_item['href'] = 'adminlteusergroup';
        $menu_item['icon'] = 'fas fa-users-cog';
        $menu_item['visibility'] = 1;
        $menu_item['parent'] = 'configuration';
        array_push($menu, $menu_item);

        $menu_item = [];
        $menu_item['text'] = 'Configuration Parameters';
        $menu_item['href'] = 'configuration_parameters';
        $menu_item['icon'] = 'fas fa-cogs';
        $menu_item['visibility'] = 1;
        $menu_item['parent'] = '';
        array_push($menu, $menu_item);

        $menu_item = [];
        $menu_item['text'] = 'Settings';
        $menu_item['href'] = 'adminlteconfigsettings';
        $menu_item['icon'] = 'fab fa-connectdevelop';
        $menu_item['visibility'] = 1;
        $menu_item['parent'] = 'configuration_parameters';
        array_push($menu, $menu_item);

        $menu_item = [];
        $menu_item['text'] = 'Form';
        $menu_item['href'] = 'adminlteconfig';
        $menu_item['icon'] = 'fab fa-wpforms';
        $menu_item['visibility'] = 1;
        $menu_item['parent'] = 'configuration_parameters';
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

        // AdminLTE Config
        $config = [];
        $config_item = [];
        $config_item['__key'] = 'adminlte';
        $config_item['__parent'] = '';
        $config_item['content'] = '';
        $config_item['default_value'] = '';
        $config_item['enabled'] = 1;
        $config_item['file_types'] = '';
        $config_item['max'] = 0;
        $config_item['min'] = 0;
        $config_item['multiple'] = 0;
        $config_item['option_titles'] = '';
        $config_item['option_values'] = '';
        $config_item['required'] = 0;
        $config_item['step'] = 0;
        $config_item['system'] = 1;
        $config_item['title'] = 'AdminLTE';
        $config_item['toggle_elements'] = '';
        $config_item['type'] = 'group';
        $config_item['url'] = '';
        $config_item['value'] = '';
        array_push($config, $config_item);

        $config_item = [];
        $config_item['__key'] = 'adminlte.generalsettings';
        $config_item['__parent'] = 'adminlte';
        $config_item['content'] = ''; 
        $config_item['default_value'] = '';
        $config_item['enabled'] = 1;
        $config_item['file_types'] = '';
        $config_item['max'] = 0;
        $config_item['min'] = 0;
        $config_item['multiple'] = '';
        $config_item['option_titles'] = '';
        $config_item['option_values'] = '';
        $config_item['required'] = 0; 
        $config_item['step'] = 0;
        $config_item['system'] = 1;
        $config_item['title'] = 'General Settings';
        $config_item['toggle_elements'] = '';
        $config_item['type'] = 'group';
        $config_item['url'] = '';
        $config_item['value'] = '';
        array_push($config, $config_item);

        $config_item = [];
        $config_item['__key'] = 'adminlte.generalsettings.projecttitle';
        $config_item['__parent'] = 'adminlte.generalsettings';
        $config_item['content'] = ''; 
        $config_item['default_value'] = 'AdminLTE 3.x + Vue.js 2.x + Laravel 6.x';
        $config_item['enabled'] = 1;
        $config_item['file_types'] = ''; 
        $config_item['max'] = 0;
        $config_item['min'] = 0;
        $config_item['multiple'] = 0;
        $config_item['option_titles'] = ''; 
        $config_item['option_values'] = ''; 
        $config_item['required'] = 0; 
        $config_item['step'] = 0;
        $config_item['system'] = 1;
        $config_item['title'] = 'Project Title';
        $config_item['toggle_elements'] = '';
        $config_item['type'] = 'shorttext';
        $config_item['url'] = '';
        $config_item['value'] = '';
        array_push($config, $config_item);

        $config_item = [];
        $config_item['__key'] = 'adminlte.generalsettings.mainfolder';
        $config_item['__parent'] = 'adminlte.generalsettings';
        $config_item['content'] = ''; 
        $config_item['default_value'] = 'adminlte';
        $config_item['enabled'] = 1;
        $config_item['file_types'] = ''; 
        $config_item['max'] = 0;
        $config_item['min'] = 0;
        $config_item['multiple']  = ''; 
        $config_item['option_titles'] = ''; 
        $config_item['option_values'] = ''; 
        $config_item['required'] = 0; 
        $config_item['step'] = 0;
        $config_item['system'] = 1;
        $config_item['title'] = 'Main Folder';
        $config_item['toggle_elements']  = '';
        $config_item['type'] = 'shorttext';
        $config_item['url'] = '';
        $config_item['value'] = '';
        array_push($config, $config_item);

        $config_item = [];
        $config_item['__key'] = 'adminlte.generalsettings.landingpage';
        $config_item['__parent'] = 'adminlte.generalsettings';
        $config_item['content'] = ''; 
        $config_item['default_value'] = 'home';
        $config_item['enabled'] = 1;
        $config_item['file_types'] = ''; 
        $config_item['max'] = 0;
        $config_item['min'] = 0;
        $config_item['multiple'] = '';
        $config_item['option_titles'] = '';
        $config_item['option_values'] = '';
        $config_item['required'] = 0; 
        $config_item['step'] = 0;
        $config_item['system'] = 1;
        $config_item['title'] = 'Landing Page';
        $config_item['toggle_elements'] = '';
        $config_item['type'] = 'shorttext';
        $config_item['url'] = '';
        $config_item['value'] = '';
        array_push($config, $config_item);

        $config_item = [];
        $config_item['__key'] = 'adminlte.generalsettings.defaultlanguage';
        $config_item['__parent'] = 'adminlte.generalsettings';
        $config_item['content'] = ''; 
        $config_item['default_value'] = 'en';
        $config_item['enabled'] = 1;
        $config_item['file_types'] = ''; 
        $config_item['max'] = 0;
        $config_item['min'] = 0;
        $config_item['multiple'] = '';
        $config_item['option_titles'] = "English\nTürkçe";
        $config_item['option_values'] = "en\ntr";
        $config_item['required'] = 0; 
        $config_item['step'] = 0;
        $config_item['system'] = 1;
        $config_item['title'] = 'Default Language';
        $config_item['toggle_elements'] = '';
        $config_item['type'] = 'dropdown';
        $config_item['url'] = '';
        $config_item['value'] = '';
        array_push($config, $config_item);

        $config_item = [];
        $config_item['__key'] = 'adminlte.generalsettings.timezone';
        $config_item['__parent'] = 'adminlte.generalsettings';
        $config_item['content'] = ''; 
        $config_item['default_value'] = 'UTC';
        $config_item['enabled'] = 1;
        $config_item['file_types'] = ''; 
        $config_item['max'] = 0;
        $config_item['min'] = 0;
        $config_item['multiple'] = 0; 
        $config_item['option_titles'] = "Africa/Abidjan\nAfrica/Accra\nAfrica/Addis_Ababa\nAfrica/Algiers\nAfrica/Asmara\nAfrica/Bamako\nAfrica/Bangui\nAfrica/Banjul\nAfrica/Bissau\nAfrica/Blantyre\nAfrica/Brazzaville\nAfrica/Bujumbura\nAfrica/Cairo\nAfrica/Casablanca\nAfrica/Ceuta\nAfrica/Conakry\nAfrica/Dakar\nAfrica/Dar_es_Salaam\nAfrica/Djibouti\nAfrica/Douala\nAfrica/El_Aaiun\nAfrica/Freetown\nAfrica/Gaborone\nAfrica/Harare\nAfrica/Johannesburg\nAfrica/Juba\nAfrica/Kampala\nAfrica/Khartoum\nAfrica/Kigali\nAfrica/Kinshasa\nAfrica/Lagos\nAfrica/Libreville\nAfrica/Lome\nAfrica/Luanda\nAfrica/Lubumbashi\nAfrica/Lusaka\nAfrica/Malabo\nAfrica/Maputo\nAfrica/Maseru\nAfrica/Mbabane\nAfrica/Mogadishu\nAfrica/Monrovia\nAfrica/Nairobi\nAfrica/Ndjamena\nAfrica/Niamey\nAfrica/Nouakchott\nAfrica/Ouagadougou\nAfrica/Porto-Novo\nAfrica/Sao_Tome\nAfrica/Tripoli\nAfrica/Tunis\nAfrica/Windhoek\nAmerica/Adak\nAmerica/Anchorage\nAmerica/Anguilla\nAmerica/Antigua\nAmerica/Araguaina\nAmerica/Argentina/Buenos_Aires\nAmerica/Argentina/Catamarca\nAmerica/Argentina/Cordoba\nAmerica/Argentina/Jujuy\nAmerica/Argentina/La_Rioja\nAmerica/Argentina/Mendoza\nAmerica/Argentina/Rio_Gallegos\nAmerica/Argentina/Salta\nAmerica/Argentina/San_Juan\nAmerica/Argentina/San_Luis\nAmerica/Argentina/Tucuman\nAmerica/Argentina/Ushuaia\nAmerica/Aruba\nAmerica/Asuncion\nAmerica/Atikokan\nAmerica/Bahia\nAmerica/Bahia_Banderas\nAmerica/Barbados\nAmerica/Belem\nAmerica/Belize\nAmerica/Blanc-Sablon\nAmerica/Boa_Vista\nAmerica/Bogota\nAmerica/Boise\nAmerica/Cambridge_Bay\nAmerica/Campo_Grande\nAmerica/Cancun\nAmerica/Caracas\nAmerica/Cayenne\nAmerica/Cayman\nAmerica/Chicago\nAmerica/Chihuahua\nAmerica/Costa_Rica\nAmerica/Creston\nAmerica/Cuiaba\nAmerica/Curacao\nAmerica/Danmarkshavn\nAmerica/Dawson\nAmerica/Dawson_Creek\nAmerica/Denver\nAmerica/Detroit\nAmerica/Dominica\nAmerica/Edmonton\nAmerica/Eirunepe\nAmerica/El_Salvador\nAmerica/Fort_Nelson\nAmerica/Fortaleza\nAmerica/Glace_Bay\nAmerica/Goose_Bay\nAmerica/Grand_Turk\nAmerica/Grenada\nAmerica/Guadeloupe\nAmerica/Guatemala\nAmerica/Guayaquil\nAmerica/Guyana\nAmerica/Halifax\nAmerica/Havana\nAmerica/Hermosillo\nAmerica/Indiana/Indianapolis\nAmerica/Indiana/Knox\nAmerica/Indiana/Marengo\nAmerica/Indiana/Petersburg\nAmerica/Indiana/Tell_City\nAmerica/Indiana/Vevay\nAmerica/Indiana/Vincennes\nAmerica/Indiana/Winamac\nAmerica/Inuvik\nAmerica/Iqaluit\nAmerica/Jamaica\nAmerica/Juneau\nAmerica/Kentucky/Louisville\nAmerica/Kentucky/Monticello\nAmerica/Kralendijk\nAmerica/La_Paz\nAmerica/Lima\nAmerica/Los_Angeles\nAmerica/Lower_Princes\nAmerica/Maceio\nAmerica/Managua\nAmerica/Manaus\nAmerica/Marigot\nAmerica/Martinique\nAmerica/Matamoros\nAmerica/Mazatlan\nAmerica/Menominee\nAmerica/Merida\nAmerica/Metlakatla\nAmerica/Mexico_City\nAmerica/Miquelon\nAmerica/Moncton\nAmerica/Monterrey\nAmerica/Montevideo\nAmerica/Montserrat\nAmerica/Nassau\nAmerica/New_York\nAmerica/Nipigon\nAmerica/Nome\nAmerica/Noronha\nAmerica/North_Dakota/Beulah\nAmerica/North_Dakota/Center\nAmerica/North_Dakota/New_Salem\nAmerica/Nuuk\nAmerica/Ojinaga\nAmerica/Panama\nAmerica/Pangnirtung\nAmerica/Paramaribo\nAmerica/Phoenix\nAmerica/Port-au-Prince\nAmerica/Port_of_Spain\nAmerica/Porto_Velho\nAmerica/Puerto_Rico\nAmerica/Punta_Arenas\nAmerica/Rainy_River\nAmerica/Rankin_Inlet\nAmerica/Recife\nAmerica/Regina\nAmerica/Resolute\nAmerica/Rio_Branco\nAmerica/Santarem\nAmerica/Santiago\nAmerica/Santo_Domingo\nAmerica/Sao_Paulo\nAmerica/Scoresbysund\nAmerica/Sitka\nAmerica/St_Barthelemy\nAmerica/St_Johns\nAmerica/St_Kitts\nAmerica/St_Lucia\nAmerica/St_Thomas\nAmerica/St_Vincent\nAmerica/Swift_Current\nAmerica/Tegucigalpa\nAmerica/Thule\nAmerica/Thunder_Bay\nAmerica/Tijuana\nAmerica/Toronto\nAmerica/Tortola\nAmerica/Vancouver\nAmerica/Whitehorse\nAmerica/Winnipeg\nAmerica/Yakutat\nAmerica/Yellowknife\nAntarctica/Casey\nAntarctica/Davis\nAntarctica/DumontDUrville\nAntarctica/Macquarie\nAntarctica/Mawson\nAntarctica/McMurdo\nAntarctica/Palmer\nAntarctica/Rothera\nAntarctica/Syowa\nAntarctica/Troll\nAntarctica/Vostok\nArctic/Longyearbyen\nAsia/Aden\nAsia/Almaty\nAsia/Amman\nAsia/Anadyr\nAsia/Aqtau\nAsia/Aqtobe\nAsia/Ashgabat\nAsia/Atyrau\nAsia/Baghdad\nAsia/Bahrain\nAsia/Baku\nAsia/Bangkok\nAsia/Barnaul\nAsia/Beirut\nAsia/Bishkek\nAsia/Brunei\nAsia/Chita\nAsia/Choibalsan\nAsia/Colombo\nAsia/Damascus\nAsia/Dhaka\nAsia/Dili\nAsia/Dubai\nAsia/Dushanbe\nAsia/Famagusta\nAsia/Gaza\nAsia/Hebron\nAsia/Ho_Chi_Minh\nAsia/Hong_Kong\nAsia/Hovd\nAsia/Irkutsk\nAsia/Jakarta\nAsia/Jayapura\nAsia/Jerusalem\nAsia/Kabul\nAsia/Kamchatka\nAsia/Karachi\nAsia/Kathmandu\nAsia/Khandyga\nAsia/Kolkata\nAsia/Krasnoyarsk\nAsia/Kuala_Lumpur\nAsia/Kuching\nAsia/Kuwait\nAsia/Macau\nAsia/Magadan\nAsia/Makassar\nAsia/Manila\nAsia/Muscat\nAsia/Nicosia\nAsia/Novokuznetsk\nAsia/Novosibirsk\nAsia/Omsk\nAsia/Oral\nAsia/Phnom_Penh\nAsia/Pontianak\nAsia/Pyongyang\nAsia/Qatar\nAsia/Qostanay\nAsia/Qyzylorda\nAsia/Riyadh\nAsia/Sakhalin\nAsia/Samarkand\nAsia/Seoul\nAsia/Shanghai\nAsia/Singapore\nAsia/Srednekolymsk\nAsia/Taipei\nAsia/Tashkent\nAsia/Tbilisi\nAsia/Tehran\nAsia/Thimphu\nAsia/Tokyo\nAsia/Tomsk\nAsia/Ulaanbaatar\nAsia/Urumqi\nAsia/Ust-Nera\nAsia/Vientiane\nAsia/Vladivostok\nAsia/Yakutsk\nAsia/Yangon\nAsia/Yekaterinburg\nAsia/Yerevan\nAtlantic/Azores\nAtlantic/Bermuda\nAtlantic/Canary\nAtlantic/Cape_Verde\nAtlantic/Faroe\nAtlantic/Madeira\nAtlantic/Reykjavik\nAtlantic/South_Georgia\nAtlantic/St_Helena\nAtlantic/Stanley\nAustralia/Adelaide\nAustralia/Brisbane\nAustralia/Broken_Hill\nAustralia/Darwin\nAustralia/Eucla\nAustralia/Hobart\nAustralia/Lindeman\nAustralia/Lord_Howe\nAustralia/Melbourne\nAustralia/Perth\nAustralia/Sydney\nEurope/Amsterdam\nEurope/Andorra\nEurope/Astrakhan\nEurope/Athens\nEurope/Belgrade\nEurope/Berlin\nEurope/Bratislava\nEurope/Brussels\nEurope/Bucharest\nEurope/Budapest\nEurope/Busingen\nEurope/Chisinau\nEurope/Copenhagen\nEurope/Dublin\nEurope/Gibraltar\nEurope/Guernsey\nEurope/Helsinki\nEurope/Isle_of_Man\nEurope/Istanbul\nEurope/Jersey\nEurope/Kaliningrad\nEurope/Kiev\nEurope/Kirov\nEurope/Lisbon\nEurope/Ljubljana\nEurope/London\nEurope/Luxembourg\nEurope/Madrid\nEurope/Malta\nEurope/Mariehamn\nEurope/Minsk\nEurope/Monaco\nEurope/Moscow\nEurope/Oslo\nEurope/Paris\nEurope/Podgorica\nEurope/Prague\nEurope/Riga\nEurope/Rome\nEurope/Samara\nEurope/San_Marino\nEurope/Sarajevo\nEurope/Saratov\nEurope/Simferopol\nEurope/Skopje\nEurope/Sofia\nEurope/Stockholm\nEurope/Tallinn\nEurope/Tirane\nEurope/Ulyanovsk\nEurope/Uzhgorod\nEurope/Vaduz\nEurope/Vatican\nEurope/Vienna\nEurope/Vilnius\nEurope/Volgograd\nEurope/Warsaw\nEurope/Zagreb\nEurope/Zaporozhye\nEurope/Zurich\nIndian/Antananarivo\nIndian/Chagos\nIndian/Christmas\nIndian/Cocos\nIndian/Comoro\nIndian/Kerguelen\nIndian/Mahe\nIndian/Maldives\nIndian/Mauritius\nIndian/Mayotte\nIndian/Reunion\nPacific/Apia\nPacific/Auckland\nPacific/Bougainville\nPacific/Chatham\nPacific/Chuuk\nPacific/Easter\nPacific/Efate\nPacific/Enderbury\nPacific/Fakaofo\nPacific/Fiji\nPacific/Funafuti\nPacific/Galapagos\nPacific/Gambier\nPacific/Guadalcanal\nPacific/Guam\nPacific/Honolulu\nPacific/Kiritimati\nPacific/Kosrae\nPacific/Kwajalein\nPacific/Majuro\nPacific/Marquesas\nPacific/Midway\nPacific/Nauru\nPacific/Niue\nPacific/Norfolk\nPacific/Noumea\nPacific/Pago_Pago\nPacific/Palau\nPacific/Pitcairn\nPacific/Pohnpei\nPacific/Port_Moresby\nPacific/Rarotonga\nPacific/Saipan\nPacific/Tahiti\nPacific/Tarawa\nPacific/Tongatapu\nPacific/Wake\nPacific/Wallis\nUTC";
        $config_item['option_values'] = "Africa/Abidjan\nAfrica/Accra\nAfrica/Addis_Ababa\nAfrica/Algiers\nAfrica/Asmara\nAfrica/Bamako\nAfrica/Bangui\nAfrica/Banjul\nAfrica/Bissau\nAfrica/Blantyre\nAfrica/Brazzaville\nAfrica/Bujumbura\nAfrica/Cairo\nAfrica/Casablanca\nAfrica/Ceuta\nAfrica/Conakry\nAfrica/Dakar\nAfrica/Dar_es_Salaam\nAfrica/Djibouti\nAfrica/Douala\nAfrica/El_Aaiun\nAfrica/Freetown\nAfrica/Gaborone\nAfrica/Harare\nAfrica/Johannesburg\nAfrica/Juba\nAfrica/Kampala\nAfrica/Khartoum\nAfrica/Kigali\nAfrica/Kinshasa\nAfrica/Lagos\nAfrica/Libreville\nAfrica/Lome\nAfrica/Luanda\nAfrica/Lubumbashi\nAfrica/Lusaka\nAfrica/Malabo\nAfrica/Maputo\nAfrica/Maseru\nAfrica/Mbabane\nAfrica/Mogadishu\nAfrica/Monrovia\nAfrica/Nairobi\nAfrica/Ndjamena\nAfrica/Niamey\nAfrica/Nouakchott\nAfrica/Ouagadougou\nAfrica/Porto-Novo\nAfrica/Sao_Tome\nAfrica/Tripoli\nAfrica/Tunis\nAfrica/Windhoek\nAmerica/Adak\nAmerica/Anchorage\nAmerica/Anguilla\nAmerica/Antigua\nAmerica/Araguaina\nAmerica/Argentina/Buenos_Aires\nAmerica/Argentina/Catamarca\nAmerica/Argentina/Cordoba\nAmerica/Argentina/Jujuy\nAmerica/Argentina/La_Rioja\nAmerica/Argentina/Mendoza\nAmerica/Argentina/Rio_Gallegos\nAmerica/Argentina/Salta\nAmerica/Argentina/San_Juan\nAmerica/Argentina/San_Luis\nAmerica/Argentina/Tucuman\nAmerica/Argentina/Ushuaia\nAmerica/Aruba\nAmerica/Asuncion\nAmerica/Atikokan\nAmerica/Bahia\nAmerica/Bahia_Banderas\nAmerica/Barbados\nAmerica/Belem\nAmerica/Belize\nAmerica/Blanc-Sablon\nAmerica/Boa_Vista\nAmerica/Bogota\nAmerica/Boise\nAmerica/Cambridge_Bay\nAmerica/Campo_Grande\nAmerica/Cancun\nAmerica/Caracas\nAmerica/Cayenne\nAmerica/Cayman\nAmerica/Chicago\nAmerica/Chihuahua\nAmerica/Costa_Rica\nAmerica/Creston\nAmerica/Cuiaba\nAmerica/Curacao\nAmerica/Danmarkshavn\nAmerica/Dawson\nAmerica/Dawson_Creek\nAmerica/Denver\nAmerica/Detroit\nAmerica/Dominica\nAmerica/Edmonton\nAmerica/Eirunepe\nAmerica/El_Salvador\nAmerica/Fort_Nelson\nAmerica/Fortaleza\nAmerica/Glace_Bay\nAmerica/Goose_Bay\nAmerica/Grand_Turk\nAmerica/Grenada\nAmerica/Guadeloupe\nAmerica/Guatemala\nAmerica/Guayaquil\nAmerica/Guyana\nAmerica/Halifax\nAmerica/Havana\nAmerica/Hermosillo\nAmerica/Indiana/Indianapolis\nAmerica/Indiana/Knox\nAmerica/Indiana/Marengo\nAmerica/Indiana/Petersburg\nAmerica/Indiana/Tell_City\nAmerica/Indiana/Vevay\nAmerica/Indiana/Vincennes\nAmerica/Indiana/Winamac\nAmerica/Inuvik\nAmerica/Iqaluit\nAmerica/Jamaica\nAmerica/Juneau\nAmerica/Kentucky/Louisville\nAmerica/Kentucky/Monticello\nAmerica/Kralendijk\nAmerica/La_Paz\nAmerica/Lima\nAmerica/Los_Angeles\nAmerica/Lower_Princes\nAmerica/Maceio\nAmerica/Managua\nAmerica/Manaus\nAmerica/Marigot\nAmerica/Martinique\nAmerica/Matamoros\nAmerica/Mazatlan\nAmerica/Menominee\nAmerica/Merida\nAmerica/Metlakatla\nAmerica/Mexico_City\nAmerica/Miquelon\nAmerica/Moncton\nAmerica/Monterrey\nAmerica/Montevideo\nAmerica/Montserrat\nAmerica/Nassau\nAmerica/New_York\nAmerica/Nipigon\nAmerica/Nome\nAmerica/Noronha\nAmerica/North_Dakota/Beulah\nAmerica/North_Dakota/Center\nAmerica/North_Dakota/New_Salem\nAmerica/Nuuk\nAmerica/Ojinaga\nAmerica/Panama\nAmerica/Pangnirtung\nAmerica/Paramaribo\nAmerica/Phoenix\nAmerica/Port-au-Prince\nAmerica/Port_of_Spain\nAmerica/Porto_Velho\nAmerica/Puerto_Rico\nAmerica/Punta_Arenas\nAmerica/Rainy_River\nAmerica/Rankin_Inlet\nAmerica/Recife\nAmerica/Regina\nAmerica/Resolute\nAmerica/Rio_Branco\nAmerica/Santarem\nAmerica/Santiago\nAmerica/Santo_Domingo\nAmerica/Sao_Paulo\nAmerica/Scoresbysund\nAmerica/Sitka\nAmerica/St_Barthelemy\nAmerica/St_Johns\nAmerica/St_Kitts\nAmerica/St_Lucia\nAmerica/St_Thomas\nAmerica/St_Vincent\nAmerica/Swift_Current\nAmerica/Tegucigalpa\nAmerica/Thule\nAmerica/Thunder_Bay\nAmerica/Tijuana\nAmerica/Toronto\nAmerica/Tortola\nAmerica/Vancouver\nAmerica/Whitehorse\nAmerica/Winnipeg\nAmerica/Yakutat\nAmerica/Yellowknife\nAntarctica/Casey\nAntarctica/Davis\nAntarctica/DumontDUrville\nAntarctica/Macquarie\nAntarctica/Mawson\nAntarctica/McMurdo\nAntarctica/Palmer\nAntarctica/Rothera\nAntarctica/Syowa\nAntarctica/Troll\nAntarctica/Vostok\nArctic/Longyearbyen\nAsia/Aden\nAsia/Almaty\nAsia/Amman\nAsia/Anadyr\nAsia/Aqtau\nAsia/Aqtobe\nAsia/Ashgabat\nAsia/Atyrau\nAsia/Baghdad\nAsia/Bahrain\nAsia/Baku\nAsia/Bangkok\nAsia/Barnaul\nAsia/Beirut\nAsia/Bishkek\nAsia/Brunei\nAsia/Chita\nAsia/Choibalsan\nAsia/Colombo\nAsia/Damascus\nAsia/Dhaka\nAsia/Dili\nAsia/Dubai\nAsia/Dushanbe\nAsia/Famagusta\nAsia/Gaza\nAsia/Hebron\nAsia/Ho_Chi_Minh\nAsia/Hong_Kong\nAsia/Hovd\nAsia/Irkutsk\nAsia/Jakarta\nAsia/Jayapura\nAsia/Jerusalem\nAsia/Kabul\nAsia/Kamchatka\nAsia/Karachi\nAsia/Kathmandu\nAsia/Khandyga\nAsia/Kolkata\nAsia/Krasnoyarsk\nAsia/Kuala_Lumpur\nAsia/Kuching\nAsia/Kuwait\nAsia/Macau\nAsia/Magadan\nAsia/Makassar\nAsia/Manila\nAsia/Muscat\nAsia/Nicosia\nAsia/Novokuznetsk\nAsia/Novosibirsk\nAsia/Omsk\nAsia/Oral\nAsia/Phnom_Penh\nAsia/Pontianak\nAsia/Pyongyang\nAsia/Qatar\nAsia/Qostanay\nAsia/Qyzylorda\nAsia/Riyadh\nAsia/Sakhalin\nAsia/Samarkand\nAsia/Seoul\nAsia/Shanghai\nAsia/Singapore\nAsia/Srednekolymsk\nAsia/Taipei\nAsia/Tashkent\nAsia/Tbilisi\nAsia/Tehran\nAsia/Thimphu\nAsia/Tokyo\nAsia/Tomsk\nAsia/Ulaanbaatar\nAsia/Urumqi\nAsia/Ust-Nera\nAsia/Vientiane\nAsia/Vladivostok\nAsia/Yakutsk\nAsia/Yangon\nAsia/Yekaterinburg\nAsia/Yerevan\nAtlantic/Azores\nAtlantic/Bermuda\nAtlantic/Canary\nAtlantic/Cape_Verde\nAtlantic/Faroe\nAtlantic/Madeira\nAtlantic/Reykjavik\nAtlantic/South_Georgia\nAtlantic/St_Helena\nAtlantic/Stanley\nAustralia/Adelaide\nAustralia/Brisbane\nAustralia/Broken_Hill\nAustralia/Darwin\nAustralia/Eucla\nAustralia/Hobart\nAustralia/Lindeman\nAustralia/Lord_Howe\nAustralia/Melbourne\nAustralia/Perth\nAustralia/Sydney\nEurope/Amsterdam\nEurope/Andorra\nEurope/Astrakhan\nEurope/Athens\nEurope/Belgrade\nEurope/Berlin\nEurope/Bratislava\nEurope/Brussels\nEurope/Bucharest\nEurope/Budapest\nEurope/Busingen\nEurope/Chisinau\nEurope/Copenhagen\nEurope/Dublin\nEurope/Gibraltar\nEurope/Guernsey\nEurope/Helsinki\nEurope/Isle_of_Man\nEurope/Istanbul\nEurope/Jersey\nEurope/Kaliningrad\nEurope/Kiev\nEurope/Kirov\nEurope/Lisbon\nEurope/Ljubljana\nEurope/London\nEurope/Luxembourg\nEurope/Madrid\nEurope/Malta\nEurope/Mariehamn\nEurope/Minsk\nEurope/Monaco\nEurope/Moscow\nEurope/Oslo\nEurope/Paris\nEurope/Podgorica\nEurope/Prague\nEurope/Riga\nEurope/Rome\nEurope/Samara\nEurope/San_Marino\nEurope/Sarajevo\nEurope/Saratov\nEurope/Simferopol\nEurope/Skopje\nEurope/Sofia\nEurope/Stockholm\nEurope/Tallinn\nEurope/Tirane\nEurope/Ulyanovsk\nEurope/Uzhgorod\nEurope/Vaduz\nEurope/Vatican\nEurope/Vienna\nEurope/Vilnius\nEurope/Volgograd\nEurope/Warsaw\nEurope/Zagreb\nEurope/Zaporozhye\nEurope/Zurich\nIndian/Antananarivo\nIndian/Chagos\nIndian/Christmas\nIndian/Cocos\nIndian/Comoro\nIndian/Kerguelen\nIndian/Mahe\nIndian/Maldives\nIndian/Mauritius\nIndian/Mayotte\nIndian/Reunion\nPacific/Apia\nPacific/Auckland\nPacific/Bougainville\nPacific/Chatham\nPacific/Chuuk\nPacific/Easter\nPacific/Efate\nPacific/Enderbury\nPacific/Fakaofo\nPacific/Fiji\nPacific/Funafuti\nPacific/Galapagos\nPacific/Gambier\nPacific/Guadalcanal\nPacific/Guam\nPacific/Honolulu\nPacific/Kiritimati\nPacific/Kosrae\nPacific/Kwajalein\nPacific/Majuro\nPacific/Marquesas\nPacific/Midway\nPacific/Nauru\nPacific/Niue\nPacific/Norfolk\nPacific/Noumea\nPacific/Pago_Pago\nPacific/Palau\nPacific/Pitcairn\nPacific/Pohnpei\nPacific/Port_Moresby\nPacific/Rarotonga\nPacific/Saipan\nPacific/Tahiti\nPacific/Tarawa\nPacific/Tongatapu\nPacific/Wake\nPacific/Wallis\nUTC";
        $config_item['required'] = 0; 
        $config_item['step'] = 0;
        $config_item['system'] = 1;
        $config_item['title'] = 'Timezone';
        $config_item['toggle_elements'] = '';
        $config_item['type'] = 'dropdown';
        $config_item['url'] = ''; 
        $config_item['value'] = '';
        array_push($config, $config_item);

        $config_item = [];
        $config_item['__key'] = 'adminlte.generalsettings.dateformat';
        $config_item['__parent'] = 'adminlte.generalsettings';
        $config_item['content'] = ''; 
        $config_item['default_value'] = 'Y-m-d';
        $config_item['enabled'] = 1;
        $config_item['file_types'] = ''; 
        $config_item['max'] = 0;
        $config_item['min'] = 0;
        $config_item['multiple'] = 0;
        $config_item['option_titles'] = "15/06/1981\n15/6/1981\n15/06/81\n15/6/81\n15-06-1981\n15-6-1981\n15-06-81\n15-6-81\n15.06.1981\n15.6.1981\n15.06.81\n15.6.81\n06/15/1981\n6/15/1981\n06/15/81\n6/15/81\n06-15-1981\n6-15-1981\n06-15-81\n6-15-81\n06.15.1981\n6.15.1981\n06.15.81\n6.15.81\n1981/06/15\n1981/6/15\n81/06/15\n81/6/15\n1981-06-15\n1981-6-15\n81-06-15\n81-6-15\n1981.06.15\n1981.6.15\n81.06.15\n81.6.15\n15 June 1981\n15 June 81\n15 Jun 1981\n15 Jun 81\nJune 15, 1981\nJune 15, 81\nJun 15, 1981\nJun 15, 81";
        $config_item['option_values'] = "d/m/Y\nj/n/Y\nd/m/y\nj/n/y\nd-m-Y\nj-n-Y\nd-m-y\nj-n-y\nd.m.Y\nj.n.Y\nd.m.y\nj.n.y\nm/d/Y\nn/j/Y\nm/d/y\nn/j/y\nm-d-Y\nn-j-Y\nm-d-y\nn-j-y\nm.d.Y\nn.j.Y\nm.d.y\nn.j.y\nY/m/d\nY/n/j\ny/m/d\ny/n/j\nY-m-d\nY-n-j\ny-m-d\ny-n-j\nY.m.d\nY.n.j\ny.m.d\ny.n.j\nj F Y\nj F y\nj M Y\nj M y\nF j, Y\nF j, y\nF j, Y\nM j, y";
        $config_item['required'] = 0; 
        $config_item['step'] = 0;
        $config_item['system'] = 1;
        $config_item['title'] = 'Date Format';
        $config_item['toggle_elements'] = '';
        $config_item['type'] = 'dropdown';
        $config_item['url'] = '';
        $config_item['value'] = '';
        array_push($config, $config_item);

        $config_item = [];
        $config_item['__key'] = 'adminlte.generalsettings.yearmonthformat';
        $config_item['__parent'] = 'adminlte.generalsettings';
        $config_item['content'] = ''; 
        $config_item['default_value'] = 'Y-m';
        $config_item['enabled'] = 1;
        $config_item['file_types'] = ''; 
        $config_item['max'] = 0;
        $config_item['min'] = 0;
        $config_item['multiple'] = '';
        $config_item['option_titles'] = "06/1981\n6/1981\n06/81\n6/81\n06-1981\n6-1981\n06-81\n6-81\n06.1981\n6.1981\n06.81\n6.81\n1981/06\n1981/6\n81/06\n81/6\n1981-06\n1981-6\n81-06\n81-6\n1981.06\n1981.6\n81.06\n81.6\nJune 1981\nJune 81\nJun 1981\nJun 81";
        $config_item['option_values'] = "m/Y\nn/Y\nm/y\nn/y\nm-Y\nn-Y\nm-y\nn-y\nm.Y\nn.Y\nm.y\nn.y\nY/m\nY/n\ny/m\ny/n\nY-m\nY-n\ny-m\ny-n\nY.m\nY.n\ny.m\ny.n\nF Y\nF y\nM Y\nM y";
        $config_item['required'] = 0; 
        $config_item['step'] = 0;
        $config_item['system'] = 1;
        $config_item['title'] = 'Year Month Format';
        $config_item['toggle_elements'] = ''; 
        $config_item['type'] = 'dropdown';
        $config_item['url'] = '';
        $config_item['value'] = '';
        array_push($config, $config_item);

        $config_item = [];
        $config_item['__key'] = 'adminlte.generalsettings.timeformat';
        $config_item['__parent'] = 'adminlte.generalsettings';
        $config_item['content'] = ''; 
        $config_item['default_value'] = 'H:i:s';
        $config_item['enabled'] = 1;
        $config_item['file_types'] = ''; 
        $config_item['max'] = 0;
        $config_item['min'] = 0;
        $config_item['multiple'] = '';
        $config_item['option_titles'] = "17:00\n05:00 pm\n17:00:00\n05:00:00 pm";
        $config_item['option_values'] = "H:i\nh:i a\nH:i:s\nh:i:s a";
        $config_item['required'] = 0; 
        $config_item['step'] = 0;
        $config_item['system'] = 1;
        $config_item['title'] = 'Time Format';
        $config_item['toggle_elements'] = '';
        $config_item['type'] = 'dropdown';
        $config_item['url'] = '';
        $config_item['value'] = '';
        array_push($config, $config_item);

        $config_item = [];
        $config_item['__key'] = 'adminlte.generalsettings.numberformat';
        $config_item['__parent'] = 'adminlte.generalsettings';
        $config_item['content'] = ''; 
        $config_item['default_value'] = '';
        $config_item['enabled'] = 1;
        $config_item['file_types'] = ''; 
        $config_item['max'] = 0;
        $config_item['min'] = 0;
        $config_item['multiple'] = '';
        $config_item['option_titles'] = "1.000.000,00\n1,000,000.00";
        $config_item['option_values'] = "tr\nen";
        $config_item['required'] = 0; 
        $config_item['step'] = 0;
        $config_item['system'] = 1;
        $config_item['title'] = 'Number Format';
        $config_item['toggle_elements'] = '';
        $config_item['type'] = 'dropdown';
        $config_item['url'] = '';
        $config_item['value'] = '';
        array_push($config, $config_item);

        $config_item = [];
        $config_item['__key'] = 'adminlte.generalsettings.googlemapsapikey';
        $config_item['__parent'] = 'adminlte.generalsettings';
        $config_item['content'] = ''; 
        $config_item['default_value'] = '';
        $config_item['enabled'] = 1;
        $config_item['file_types'] = ''; 
        $config_item['max'] = 0;
        $config_item['min'] = 0;
        $config_item['multiple'] = '';
        $config_item['option_titles'] = '';
        $config_item['option_values'] = '';
        $config_item['required'] = 0; 
        $config_item['step'] = 0;
        $config_item['system'] = 1;
        $config_item['title'] = 'Google Maps API Key';
        $config_item['toggle_elements'] = '';
        $config_item['type'] = 'shorttext';
        $config_item['url'] = '';
        $config_item['value'] = '';
        array_push($config, $config_item);

        $adminLTE->updateAdminLTEConfig($config);
        
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