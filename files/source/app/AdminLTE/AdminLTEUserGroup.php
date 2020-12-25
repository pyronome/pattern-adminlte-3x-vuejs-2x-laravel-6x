<?php

namespace App\AdminLTE;

use Illuminate\Database\Eloquent\Model;
use App\AdminLTE\AdminLTE;
/* {{@snippet:begin_class}} */

class AdminLTEUserGroup extends Model
{

    /* {{@snippet:begin_properties}} */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adminlteusergrouptable';

    protected $fillable = [
        'enabled',
        'admin',
        'title',
        'widget_permission'
    ];

    public static $property_list = [
        [
            'name' => 'id',
            'type' => 'integer',
            'belongs_to' => 'AdminLTEUserGroup',
            'display_property' => 'id',
            'title' => 'Id'
        ],
        [
            'name' => 'deleted',
            'type' => 'checkbox',
            'belongs_to' => 'AdminLTEUserGroup',
            'display_property' => 'deleted',
            'title' => 'Deleted'
        ],
        [
            'name' => 'created_at',
            'type' => 'date',
            'belongs_to' => 'AdminLTEUserGroup',
            'display_property' => 'created_at',
            'title' => 'Created At'
        ],
        [
            'name' => 'updated_at',
            'type' => 'date',
            'belongs_to' => 'AdminLTEUserGroup',
            'display_property' => 'updated_at',
            'title' => 'Updated At'
        ],
        [
            'name' => 'enabled',
            'type' => 'checkbox',
            'belongs_to' => 'AdminLTEUserGroup',
            'display_property' => 'enabled',
            'title' => 'Enabled'
        ],
        [
            'name' => 'admin',
            'type' => 'checkbox',
            'belongs_to' => 'AdminLTEUserGroup',
            'display_property' => 'admin',
            'title' => 'Admin'
        ],
        [
            'name' => 'title',
            'type' => 'text',
            'belongs_to' => 'AdminLTEUserGroup',
            'display_property' => 'title',
            'title' => 'Title'
        ],
        [
            'name' => 'widget_permission',
            'type' => 'checkbox',
            'belongs_to' => 'AdminLTEUserGroup',
            'display_property' => 'widget_permission',
            'title' => 'Edit Widget Permission'
        ]
    ];

    /* {{@snippet:end_properties}} */

    /* {{@snippet:begin_methods}} */
    
    public function scopeDefaultQuery($query, $search_text, $sort_variable, $sort_direction) {
        $objectAdminLTE = new AdminLTE();
        $query = $objectAdminLTE->getQuery($query, 'AdminLTEUserGroup', $this::$property_list, $search_text, $sort_variable, $sort_direction);
        return $query;
    }

    public function AdminLTEUser_adminlteusergroup_id()
    {
        return $this->hasMany(AdminLTEUser::class);
    }
    
    /* {{@snippet:end_methods}} */
}

/* {{@snippet:end_class}} */
