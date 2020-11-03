<?php

namespace App\AdminLTE;

use Illuminate\Database\Eloquent\Model;

/* {{snippet:begin_class}} */

class AdminLTEPermission extends Model
{

    /* {{snippet:begin_properties}} */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adminltepermissiontable';

    protected $fillable = [
        'usergroup_id',
        'user_id',
        'meta_key',
        'permissions'
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
            'name' => 'usergroup_id',
            'type' => 'integer'
        ],
        [
            'name' => 'user_id',
            'type' => 'integer'
        ],
        [
            'name' => 'meta_key',
            'type' => 'text'
        ],
        [
            'name' => 'permissions',
            'type' => 'text'
        ]
    ];
    /* {{snippet:end_properties}} */

    /* {{snippet:begin_methods}} */
    
    /* {{snippet:end_methods}} */
}

/* {{snippet:end_class}} */