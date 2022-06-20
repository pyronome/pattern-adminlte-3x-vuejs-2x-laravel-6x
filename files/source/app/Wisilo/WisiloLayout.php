<?php

namespace App\Wisilo;

use Illuminate\Database\Eloquent\Model;

/* {{@snippet:begin_class}} */

class WisiloLayout extends Model
{
	/* {{@snippet:begin_properties}} */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wisilolayouttable';

	protected $fillable = [
        '__system',
        'enabled',
        '__order',
        'wisilousergroup_id',
		'pagename',
        'container_index',
        'container_title',
        'widget',
		'title',
		'grid_size',
        'icon',
		'meta_data_json',
        'data_source_json',
        'variable_mapping_json',
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
            'name' => 'wisilousergroup_id',
            'type' => 'class_selection_single',
        ],
		[
            'name' => 'pagename',
            'type' => 'text'
        ],
        [
            'name' => 'container_index',
            'type' => 'text'
        ],
        [
            'name' => 'container_title',
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
            'name' => 'variable_mapping_json',
            'type' => 'text'
        ],
        [
            'name' => 'conditional_data_json',
            'type' => 'text'
        ]
    ];

	/* {{@snippet:end_properties}} */

	/* {{@snippet:begin_methods}} */
	
    public function wisilousergroup_id()
    {
        return $this->belongsTo(WisiloUserGroup::class, 'wisilousergroup_id');
    }

	/* {{@snippet:end_methods}} */

}

/* {{@snippet:end_class}} */