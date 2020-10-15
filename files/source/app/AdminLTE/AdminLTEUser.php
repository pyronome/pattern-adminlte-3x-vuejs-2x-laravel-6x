<?php

namespace App\AdminLTE;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\AdminLTE\AdminLTE;

/* {{snippet:begin_class}} */

class AdminLTEUser extends Authenticatable
{

    /* {{snippet:begin_properties}} */

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
            'display_property' => 'id'
        ],
        [
            'name' => 'deleted',
            'type' => 'checkbox',
            'belongs_to' => 'AdminLTEUser',
            'display_property' => 'deleted'
        ],
        [
            'name' => 'created_at',
            'type' => 'date',
            'belongs_to' => 'AdminLTEUser',
            'display_property' => 'created_at'
        ],
        [
            'name' => 'updated_at',
            'type' => 'date',
            'belongs_to' => 'AdminLTEUser',
            'display_property' => 'updated_at'
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
            'display_property' => 'title'
        ],
        [
            'name' => 'enabled',
            'type' => 'checkbox',
            'belongs_to' => 'AdminLTEUser',
            'display_property' => 'enabled'
        ],
        [
            'name' => 'fullname',
            'type' => 'text',
            'belongs_to' => 'AdminLTEUser',
            'display_property' => 'fullname'
        ],
        [
            'name' => 'username',
            'type' => 'text',
            'belongs_to' => 'AdminLTEUser',
            'display_property' => 'username'
        ],
        [
            'name' => 'email',
            'type' => 'text',
            'belongs_to' => 'AdminLTEUser',
            'display_property' => 'email'
        ],
        [
            'name' => 'password',
            'type' => 'text',
            'belongs_to' => 'AdminLTEUser',
            'display_property' => 'password'
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

    /* {{snippet:end_properties}} */

    /* {{snippet:begin_methods}} */
    
    public function scopeDefaultQuery($query, $search_text, $sort_variable, $sort_direction) {
        $objectAdminLTE = new AdminLTE();
        $query = $objectAdminLTE->getQuery($query, 'AdminLTEUser', $this::$property_list, $search_text, $sort_variable, $sort_direction);
        return $query;
    }

    public function adminlteusergroup_id()
    {
        return $this->belongsTo(AdminLTEUserGroup::class, 'adminlteusergroup_id');
    }

    /* {{snippet:end_methods}} */
}

/* {{snippet:begin_class}} */
