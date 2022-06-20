<?php

namespace App\Wisilo;

use Illuminate\Database\Eloquent\Model;

use App\Wisilo\Wisilo;

class WisiloConfig extends Model
{
	/* {{@snippet:begin_properties}} */

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'wisiloconfigtable';

	protected $fillable = [
        'system',
        'locked',
        'owner',
		'enabled',
        'only_admins',
		'required',
		'__order',
		'type',
		'__key',
        'parent_id',
		'title',
        'default_value',
		'value',
        'hint',
        'description',
		'meta_data_json'
	];

	public static $property_list = [
		[
			'name' => 'id',
			'type' => 'integer',
			'belongs_to' => 'WisiloConfig',
			'display_property' => 'id',
			'title' => 'Id'
		],

		[
			'name' => 'deleted',
			'type' => 'checkbox',
			'belongs_to' => 'WisiloConfig',
			'display_property' => 'deleted',
			'title' => 'Deleted'
		],

		[
			'name' => 'created_at',
			'type' => 'date',
			'belongs_to' => 'WisiloConfig',
			'display_property' => 'created_at',
			'title' => 'Created At'
		],

		[
			'name' => 'updated_at',
			'type' => 'date',
			'belongs_to' => 'WisiloConfig',
			'display_property' => 'updated_at',
			'title' => 'Updated at'
		],
        [
            'name' => 'system',
            'type' => 'checkbox',
            'belongs_to' => 'WisiloConfig',
            'display_property' => 'system',
            'title' => 'System'
        ],
        [
            'name' => 'locked',
            'type' => 'checkbox',
            'belongs_to' => 'WisiloConfig',
            'display_property' => 'locked',
            'title' => 'Locked'
        ],
        [
			'name' => 'owner',
			'type' => 'integer',
			'belongs_to' => 'WisiloConfig',
			'display_property' => 'owner',
			'title' => 'Owner'
		],
        [
            'name' => 'enabled',
            'type' => 'checkbox',
            'belongs_to' => 'WisiloConfig',
            'display_property' => 'enabled',
            'title' => 'Enabled'
        ],
        [
            'name' => 'only_admins',
            'type' => 'checkbox',
            'belongs_to' => 'WisiloConfig',
            'display_property' => 'only_admins',
            'title' => 'Show For Only Admins'
        ],
        [
            'name' => 'required',
            'type' => 'checkbox',
            'belongs_to' => 'WisiloConfig',
            'display_property' => 'required',
            'title' => 'Required'
        ],
        [
            'name' => '__order',
            'type' => 'integer',
            'belongs_to' => 'WisiloConfig',
            'display_property' => '__order',
            'title' => 'Order'
        ],
        [
            'name' => 'type',
            'type' => 'text',
            'belongs_to' => 'WisiloConfig',
            'display_property' => 'type',
            'title' => 'Type'
        ],
        [
            'name' => '__key',
            'type' => 'text',
            'belongs_to' => 'WisiloConfig',
            'display_property' => '__key',
            'title' => 'Key'
        ],
        [
			'name' => 'parent_id',
			'type' => 'integer',
			'belongs_to' => 'WisiloConfig',
			'display_property' => 'parent_id',
			'title' => 'Parent'
		],
        [
            'name' => 'title',
            'type' => 'text',
            'belongs_to' => 'WisiloConfig',
            'display_property' => 'title',
            'title' => 'Title'
        ],
        [
            'name' => 'default_value',
            'type' => 'text',
            'belongs_to' => 'WisiloConfig',
            'display_property' => 'default_value',
            'title' => 'Default Value'
        ],
        [
            'name' => 'value',
            'type' => 'text',
            'belongs_to' => 'WisiloConfig',
            'display_property' => 'value',
            'title' => 'Value'
        ],
        [
            'name' => 'hint',
            'type' => 'text',
            'belongs_to' => 'WisiloConfig',
            'display_property' => 'hint',
            'title' => 'Hint'
        ],
        [
            'name' => 'description',
            'type' => 'text',
            'belongs_to' => 'WisiloConfig',
            'display_property' => 'description',
            'title' => 'Description'
        ],
        [
            'name' => 'meta_data_json',
            'type' => 'text',
            'belongs_to' => 'WisiloConfig',
            'display_property' => 'meta_data_json',
            'title' => 'Meta Data JSON'
        ]
	];

	/* {{@snippet:end_properties}} */

	public function scopeDefaultQuery($query, $search_text, $sort_variable, $sort_direction) {
		$objectWisilo = new Wisilo();
		$query = $objectWisilo->getQuery($query, 'WisiloConfig', $this::$property_list, $search_text, $sort_variable, $sort_direction);
		return $query;
	}

	/* {{@snippet:end_methods}} */
}

/* {{@snippet:end_class}} */