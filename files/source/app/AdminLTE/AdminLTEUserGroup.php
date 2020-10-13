<?php

namespace App\AdminLTE;

use Illuminate\Database\Eloquent\Model;
use App\AdminLTE\AdminLTE;
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

    public static $property_list = [
        [
            'name' => 'id',
            'type' => 'integer',
            'belongs_to' => 'AdminLTEUserGroup',
            'display_property' => 'id'
        ],
        [
            'name' => 'deleted',
            'type' => 'checkbox',
            'belongs_to' => 'AdminLTEUserGroup',
            'display_property' => 'deleted'
        ],
        [
            'name' => 'created_at',
            'type' => 'date',
            'belongs_to' => 'AdminLTEUserGroup',
            'display_property' => 'created_at'
        ],
        [
            'name' => 'updated_at',
            'type' => 'date',
            'belongs_to' => 'AdminLTEUserGroup',
            'display_property' => 'updated_at'
        ],
        [
            'name' => 'enabled',
            'type' => 'checkbox',
            'belongs_to' => 'AdminLTEUserGroup',
            'display_property' => 'enabled'
        ],
        [
            'name' => 'title',
            'type' => 'text',
            'belongs_to' => 'AdminLTEUserGroup',
            'display_property' => 'title'
        ],
        [
            'name' => 'widget_permission',
            'type' => 'checkbox',
            'belongs_to' => 'AdminLTEUserGroup',
            'display_property' => 'widget_permission'
        ]
    ];

    /* {{snippet:end_properties}} */

    /* {{snippet:begin_methods}} */
    
    public function scopeDefaultQuery($query, $search_text, $sort_variable, $sort_direction) {
        $objectAdminLTE = new AdminLTE();
        $query = $objectAdminLTE->getQuery($query, 'AdminLTEUserGroup', $this::$property_list, $search_text, $sort_variable, $sort_direction);
        return $query;
    }

    public function AdminLTEUser_adminlteusergroup_id()
    {
        return $this->hasMany(AdminLTEUser::class);
    }
    
    /* {{snippet:end_methods}} */
}

/* {{snippet:end_class}} */
