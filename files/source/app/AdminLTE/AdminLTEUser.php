<?php

namespace App\AdminLTE;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\AdminLTE\AdminLTE;

/* {{@snippet:begin_class}} */

class AdminLTEUser extends Authenticatable
{

    /* {{@snippet:begin_properties}} */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adminlteusertable';

    protected $fillable = [
        'profile_img',
        'adminlteusergroup_id',
        'enabled',
        'fullname',
        'username',
        'email',
        'password',
        'passwordHash'
    ];
    
    public static $property_list = [
        [
            'name' => 'id',
            'type' => 'integer',
            'belongs_to' => 'AdminLTEUser',
            'display_property' => 'id',
            'title' => 'Id'
        ],
        [
            'name' => 'deleted',
            'type' => 'checkbox',
            'belongs_to' => 'AdminLTEUser',
            'display_property' => 'deleted',
            'title' => 'Deleted'
        ],
        [
            'name' => 'created_at',
            'type' => 'date',
            'belongs_to' => 'AdminLTEUser',
            'display_property' => 'created_at',
            'title' => 'Created At'
        ],
        [
            'name' => 'updated_at',
            'type' => 'date',
            'belongs_to' => 'AdminLTEUser',
            'display_property' => 'updated_at',
            'title' => 'Updated At'
        ],
        [
            'name' => 'profile_img',
            'type' => 'image',
            'belongs_to' => 'AdminLTEUser',
            'display_property' => 'profile_img'
        ],
        [
            'name' => 'adminlteusergroup_id',
            'type' => 'class_selection_single',
            'belongs_to' => 'AdminLTEUserGroup',
            'display_property' => 'title',
            'title' => 'User Group'
        ],
        [
            'name' => 'enabled',
            'type' => 'checkbox',
            'belongs_to' => 'AdminLTEUser',
            'display_property' => 'enabled',
            'title' => 'Enabled'
        ],
        [
            'name' => 'fullname',
            'type' => 'text',
            'belongs_to' => 'AdminLTEUser',
            'display_property' => 'fullname',
            'title' => 'Fullname'
        ],
        [
            'name' => 'username',
            'type' => 'text',
            'belongs_to' => 'AdminLTEUser',
            'display_property' => 'username',
            'title' => 'Username'
        ],
        [
            'name' => 'email',
            'type' => 'text',
            'belongs_to' => 'AdminLTEUser',
            'display_property' => 'email',
            'title' => 'Email'
        ],
        [
            'name' => 'password',
            'type' => 'text',
            'belongs_to' => 'AdminLTEUser',
            'display_property' => 'password',
            'title' => 'Password'
        ]
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /* {{@snippet:end_properties}} */

    /* {{@snippet:begin_methods}} */
    
    public function scopeDefaultQuery($query, $search_text, $sort_variable, $sort_direction) {
        $objectAdminLTE = new AdminLTE();
        $query = $objectAdminLTE->getQuery($query, 'AdminLTEUser', $this::$property_list, $search_text, $sort_variable, $sort_direction);
        return $query;
    }

    public function adminlteusergroup_id()
    {
        return $this->belongsTo(AdminLTEUserGroup::class, 'adminlteusergroup_id');
    }

    public function get_widget_permission() {
        $objectAdminLTEUserGroup = AdminLTEUserGroup::find($this->adminlteusergroup_id);
                
        if (null != $objectAdminLTEUserGroup) {
            return (1 == $objectAdminLTEUserGroup->widget_permission);
        }

        return false;
    }

    public function is_admin() {
        $objectAdminLTEUserGroup = AdminLTEUserGroup::find($this->adminlteusergroup_id);
                
        if (null != $objectAdminLTEUserGroup) {
            return (1 == $objectAdminLTEUserGroup->admin);
        }

        return false;
    }

    /* {{@snippet:end_methods}} */
}

/* {{@snippet:begin_class}} */
