<?php

namespace App\AdminLTE;

use Illuminate\Database\Eloquent\Model;

/* {{@snippet:begin_class}} */

class AdminLTELayout extends Model
{
	/* {{@snippet:begin_properties}} */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adminltelayouttable';

	protected $fillable = [
        '__system',
        'enabled',
        '__order',
        'adminlteusergroup_id',
		'pagename',
        'widget',
		'title',
		'grid_size',
        'icon',
		'meta_data_json',
        'data_source_json',
		'conditional_data_json'
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
            'name' => '__system',
            'type' => 'checkbox'
        ],
        [
            'name' => 'enabled',
            'type' => 'checkbox'
        ],
        [
            'name' => '__order',
            'type' => 'integer'
        ],
        [
            'name' => 'adminlteusergroup_id',
            'type' => 'class_selection_single',
        ],
		[
            'name' => 'pagename',
            'type' => 'text'
        ],
        [
            'name' => 'widget',
            'type' => 'text'
        ],
        [
            'name' => 'title',
            'type' => 'text'
        ],
        [
            'name' => 'grid_size',
            'type' => 'text'
        ],
        [
            'name' => 'icon',
            'type' => 'text'
        ],
        [
            'name' => 'meta_data_json',
            'type' => 'text'
        ],
        [
            'name' => 'data_source_json',
            'type' => 'text'
        ],
        [
            'name' => 'conditional_data_json',
            'type' => 'text'
        ]
    ];

	/* {{@snippet:end_properties}} */

	/* {{@snippet:begin_methods}} */
	
    public function adminlteusergroup_id()
    {
        return $this->belongsTo(AdminLTEUserGroup::class, 'adminlteusergroup_id');
    }

	/* {{@snippet:end_methods}} */

}

/* {{@snippet:end_class}} */