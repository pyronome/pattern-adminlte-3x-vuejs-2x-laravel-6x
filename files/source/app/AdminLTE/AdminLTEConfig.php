<?php

namespace App\AdminLTE;

use Illuminate\Database\Eloquent\Model;

use App\AdminLTE\AdminLTE;

class AdminLTEConfig extends Model
{
	/* {{@snippet:begin_properties}} */

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'adminlteconfigtable';

	protected $fillable = [
		'enabled',
		'required',
		'__order',
		'type',
		'__key',
        'parent_id',
		'title',
        'default_value',
		'value',
		'meta_data'
	];

	public static $property_list = [
		[
			'name' => 'id',
			'type' => 'integer',
			'belongs_to' => 'AdminLTEConfig',
			'display_property' => 'id',
			'title' => 'Id'
		],

		[
			'name' => 'deleted',
			'type' => 'checkbox',
			'belongs_to' => 'AdminLTEConfig',
			'display_property' => 'deleted',
			'title' => 'Deleted'
		],

		[
			'name' => 'created_at',
			'type' => 'date',
			'belongs_to' => 'AdminLTEConfig',
			'display_property' => 'created_at',
			'title' => 'Created At'
		],

		[
			'name' => 'updated_at',
			'type' => 'date',
			'belongs_to' => 'AdminLTEConfig',
			'display_property' => 'updated_at',
			'title' => 'Updated at'
		],
        [
            'name' => 'enabled',
            'type' => 'checkbox',
            'belongs_to' => 'AdminLTEConfig',
            'display_property' => 'enabled',
            'title' => 'Enabled'
        ],
        [
            'name' => 'required',
            'type' => 'checkbox',
            'belongs_to' => 'AdminLTEConfig',
            'display_property' => 'required',
            'title' => 'Required'
        ],
        [
            'name' => '__order',
            'type' => 'integer',
            'belongs_to' => 'AdminLTEConfig',
            'display_property' => '__order',
            'title' => 'Order'
        ],
        [
            'name' => 'type',
            'type' => 'text',
            'belongs_to' => 'AdminLTEConfig',
            'display_property' => 'type',
            'title' => 'Type'
        ],
        [
            'name' => '__key',
            'type' => 'text',
            'belongs_to' => 'AdminLTEConfig',
            'display_property' => '__key',
            'title' => 'Key'
        ],
        [
			'name' => 'parent_id',
			'type' => 'integer',
			'belongs_to' => 'AdminLTEConfig',
			'display_property' => 'parent_id',
			'title' => 'Parent'
		],
        [
            'name' => 'title',
            'type' => 'text',
            'belongs_to' => 'AdminLTEConfig',
            'display_property' => 'title',
            'title' => 'Title'
        ],
        [
            'name' => 'default_value',
            'type' => 'text',
            'belongs_to' => 'AdminLTEConfig',
            'display_property' => 'default_value',
            'title' => 'Default Value'
        ],
        [
            'name' => 'value',
            'type' => 'text',
            'belongs_to' => 'AdminLTEConfig',
            'display_property' => 'value',
            'title' => 'Value'
        ],
        [
            'name' => 'meta_data',
            'type' => 'text',
            'belongs_to' => 'AdminLTEConfig',
            'display_property' => 'meta_data',
            'title' => 'Meta Data'
        ]
	];

	/* {{@snippet:end_properties}} */

	public function scopeDefaultQuery($query, $search_text, $sort_variable, $sort_direction) {
		$objectAdminLTE = new AdminLTE();
		$query = $objectAdminLTE->getQuery($query, 'AdminLTEConfig', $this::$property_list, $search_text, $sort_variable, $sort_direction);
		return $query;
	}

	/* {{@snippet:end_methods}} */
}

/* {{@snippet:end_class}} */