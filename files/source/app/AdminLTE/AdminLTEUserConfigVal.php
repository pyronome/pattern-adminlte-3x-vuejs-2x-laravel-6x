<?php

namespace App\AdminLTE;

use Illuminate\Database\Eloquent\Model;

use App\AdminLTE\AdminLTE;

class AdminLTEUserConfigVal extends Model
{
	/* {{@snippet:begin_properties}} */

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'adminlteuserconfigvaltable';

	protected $fillable = [
		'__key',
		'value'
	];

	public static $property_list = [
		[
			'name' => 'id',
			'type' => 'integer',
			'belongs_to' => 'AdminLTEUserConfigVal',
			'display_property' => 'id',
			'title' => 'Id'
		],

		[
			'name' => 'deleted',
			'type' => 'checkbox',
			'belongs_to' => 'AdminLTEUserConfigVal',
			'display_property' => 'deleted',
			'title' => 'Deleted'
		],

		[
			'name' => 'created_at',
			'type' => 'date',
			'belongs_to' => 'AdminLTEUserConfigVal',
			'display_property' => 'created_at',
			'title' => 'Created At'
		],

		[
			'name' => 'updated_at',
			'type' => 'date',
			'belongs_to' => 'AdminLTEUserConfigVal',
			'display_property' => 'updated_at',
			'title' => 'Updated at'
		],
        [
            'name' => '__key',
            'type' => 'text',
            'belongs_to' => 'AdminLTEUserConfigVal',
            'display_property' => '__key',
            'title' => 'Key'
        ],
        [
            'name' => 'value',
            'type' => 'text',
            'belongs_to' => 'AdminLTEUserConfigVal',
            'display_property' => 'value',
            'title' => 'Value'
        ]
	];

	/* {{@snippet:end_properties}} */

	public function scopeDefaultQuery($query, $search_text, $sort_variable, $sort_direction) {
		$objectAdminLTE = new AdminLTE();
		$query = $objectAdminLTE->getQuery($query, 'AdminLTEUserConfigVal', $this::$property_list, $search_text, $sort_variable, $sort_direction);
		return $query;
	}

	/* {{@snippet:end_methods}} */
}

/* {{@snippet:end_class}} */