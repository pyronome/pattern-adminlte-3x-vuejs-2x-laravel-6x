<?php

namespace App\Wisilo;

use Illuminate\Database\Eloquent\Model;
use App\Wisilo\Wisilo;
/* {{@snippet:begin_class}} */

class WisiloUserGroup extends Model
{

    /* {{@snippet:begin_properties}} */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wisilousergrouptable';

    protected $fillable = [
        'enabled',
        'admin',
        'title',
    ];

    public static $property_list = [
        [
            'name' => 'id',
            'type' => 'integer',
            'belongs_to' => 'WisiloUserGroup',
            'display_property' => 'id',
            'title' => 'Id'
        ],
        [
            'name' => 'deleted',
            'type' => 'checkbox',
            'belongs_to' => 'WisiloUserGroup',
            'display_property' => 'deleted',
            'title' => 'Deleted'
        ],
        [
            'name' => 'created_at',
            'type' => 'date',
            'belongs_to' => 'WisiloUserGroup',
            'display_property' => 'created_at',
            'title' => 'Created At'
        ],
        [
            'name' => 'updated_at',
            'type' => 'date',
            'belongs_to' => 'WisiloUserGroup',
            'display_property' => 'updated_at',
            'title' => 'Updated At'
        ],
        [
            'name' => 'created_by',
            'type' => 'integer',
            'belongs_to' => 'WisiloUserGroup',
            'display_property' => 'created_by',
            'title' => 'Created By'
        ],
        [
            'name' => 'updated_by',
            'type' => 'integer',
            'belongs_to' => 'WisiloUserGroup',
            'display_property' => 'updated_by',
            'title' => 'Updated By'
        ],
        [
            'name' => 'enabled',
            'type' => 'checkbox',
            'belongs_to' => 'WisiloUserGroup',
            'display_property' => 'enabled',
            'title' => 'Enabled'
        ],
        [
            'name' => 'admin',
            'type' => 'checkbox',
            'belongs_to' => 'WisiloUserGroup',
            'display_property' => 'admin',
            'title' => 'Admin'
        ],
        [
            'name' => 'title',
            'type' => 'text',
            'belongs_to' => 'WisiloUserGroup',
            'display_property' => 'title',
            'title' => 'Title'
        ]
    ];

    /* {{@snippet:end_properties}} */

    /* {{@snippet:begin_methods}} */
    
    public function scopeDefaultQuery($query, $search_text, $sort_variable, $sort_direction) {
        $objectWisilo = new Wisilo();
        $query = $objectWisilo->getQuery($query, 'WisiloUserGroup', $this::$property_list, $search_text, $sort_variable, $sort_direction);
        return $query;
    }

    public function WisiloLayout_wisilousergroup_id()
    {
        return $this->hasMany(WisiloLayout::class);
    }

    public function WisiloUser_wisilousergroup_id()
    {
        return $this->hasMany(WisiloUser::class);
    }

    public function WisiloUserConfig_owner_group()
	{
		return $this->hasMany(WisiloUserConfig::class);
	}

    public function WisiloCustomVariable_wisilousergroup_id()
    {
        return $this->hasMany(WisiloCustomVariable::class);
    }

    public function WisiloCustomVariableValue_wisilousergroup_id()
    {
        return $this->hasMany(WisiloCustomVariableValue::class);
    }
    
    /* {{@snippet:end_methods}} */
}

/* {{@snippet:end_class}} */
