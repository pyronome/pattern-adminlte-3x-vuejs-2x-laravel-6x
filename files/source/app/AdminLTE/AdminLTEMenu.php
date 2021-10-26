<?php

namespace App\AdminLTE;

use Illuminate\Database\Eloquent\Model;

class AdminLTEMenu extends Model
{
	/* {{@snippet:begin_properties}} */

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'adminltemenutable';

	protected $fillable = [
		'visibility',
		'__order',
		'parent_id',
		'text',
		'href',
		'icon'
	];

	public static $property_list = [
		[
			'name' => 'id',
			'type' => 'integer',
			'belongs_to' => 'AdminLTEMenu',
			'display_property' => 'id',
			'title' => 'Id'
		],

		[
			'name' => 'deleted',
			'type' => 'checkbox',
			'belongs_to' => 'AdminLTEMenu',
			'display_property' => 'deleted',
			'title' => 'Deleted'
		],

		[
			'name' => 'created_at',
			'type' => 'date',
			'belongs_to' => 'AdminLTEMenu',
			'display_property' => 'created_at',
			'title' => 'Created At'
		],

		[
			'name' => 'updated_at',
			'type' => 'date',
			'belongs_to' => 'AdminLTEMenu',
			'display_property' => 'updated_at',
			'title' => 'Updated at'
		],
        [
            'name' => 'visibility',
            'type' => 'checkbox',
            'belongs_to' => 'AdminLTEMenu',
            'display_property' => 'visibility',
            'title' => 'Visibility'
        ],
        [
            'name' => '__order',
            'type' => 'integer',
            'belongs_to' => 'AdminLTEMenu',
            'display_property' => '__order',
            'title' => 'Order'
        ],
        [
            'name' => 'parent_id',
            'type' => 'integer',
            'belongs_to' => 'AdminLTEMenu',
            'display_property' => 'parent_id',
            'title' => 'Parent'
        ],
        [
            'name' => 'text',
            'type' => 'text',
            'belongs_to' => 'AdminLTEMenu',
            'display_property' => 'text',
            'title' => 'Text'
        ],
        [
            'name' => 'href',
            'type' => 'text',
            'belongs_to' => 'AdminLTEMenu',
            'display_property' => 'href',
            'title' => 'Href'
        ],
        [
            'name' => 'icon',
            'type' => 'text',
            'belongs_to' => 'AdminLTEMenu',
            'display_property' => 'icon',
            'title' => 'Icon'
        ]
	];

	/* {{@snippet:end_properties}} */

	public function scopeDefaultQuery($query, $search_text, $sort_variable, $sort_direction) {
		$objectAdminLTE = new AdminLTE();
		$query = $objectAdminLTE->getQuery($query, 'AdminLTEMenu', $this::$property_list, $search_text, $sort_variable, $sort_direction);
		return $query;
	}



	/* {{@snippet:end_methods}} */
}

/* {{@snippet:end_class}} */