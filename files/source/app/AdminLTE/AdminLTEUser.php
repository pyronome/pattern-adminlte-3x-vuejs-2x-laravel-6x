<?php

namespace App\AdminLTE;

use Illuminate\Foundation\Auth\User as Authenticatable;

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
            'type' => 'integer'
        ],
        [
            'name' => 'deleted',
            'type' => 'checkbox'
        ],
        [
            'name' => 'created_at',
            'type' => 'date'
        ],
        [
            'name' => 'updated_at',
            'type' => 'date'
        ],
        [
            'name' => 'profile_img',
            'type' => 'image'
        ],
        [
            'name' => 'adminlteusergroup_id',
            'type' => 'class_selection_single'
        ],
        [
            'name' => 'enabled',
            'type' => 'checkbox'
        ],
        [
            'name' => 'fullname',
            'type' => 'text'
        ],
        [
            'name' => 'username',
            'type' => 'text'
        ],
        [
            'name' => 'email',
            'type' => 'text'
        ],
        [
            'name' => 'password',
            'type' => 'text'
        ]
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /* {{snippet:end_properties}} */

    /* {{snippet:begin_methods}} */

    public function adminlteusergroup_id()
    {
        return $this->belongsTo(AdminLTEUserGroup::class, 'adminlteusergroup_id');
    }

    /* {{snippet:end_methods}} */
}

/* {{snippet:begin_class}} */
