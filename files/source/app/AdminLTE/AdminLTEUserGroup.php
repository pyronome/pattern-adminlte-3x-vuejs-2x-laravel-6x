<?php

namespace App\AdminLTE;

use Illuminate\Database\Eloquent\Model;

/* {{snippet:begin_class}} */

class AdminLTEUserGroup extends Model
{

    /* {{snippet:begin_properties}} */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adminlteusergrouptable';

    protected $fillable = [
        'enabled',
        'title',
        'widget_permission'
    ];
    
    public static $searchable = [
        'title'
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
            'name' => 'enabled',
            'type' => 'checkbox'
        ],
        [
            'name' => 'title',
            'type' => 'text'
        ],
        [
            'name' => 'widget_permission',
            'type' => 'checkbox'
        ]
    ];

    /* {{snippet:end_properties}} */

    /* {{snippet:begin_methods}} */
    
    public function AdminLTEUser_adminlteusergroup_id()
    {
        return $this->hasMany(AdminLTEUser::class);
    }
    
    /* {{snippet:end_methods}} */
}

/* {{snippet:end_class}} */
