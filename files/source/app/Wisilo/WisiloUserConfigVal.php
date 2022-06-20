<?php

namespace App\Wisilo;

use Illuminate\Database\Eloquent\Model;

use App\Wisilo\Wisilo;

class WisiloUserConfigVal extends Model
{
	/* {{@snippet:begin_properties}} */

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'wisilouserconfigvaltable';

	protected $fillable = [
		'__key',
		'value'
	];

	public static $property_list = [
		[
			'name' => 'id',
			'type' => 'integer',
			'belongs_to' => 'WisiloUserConfigVal',
			'display_property' => 'id',
			'title' => 'Id'
		],

		[
			'name' => 'deleted',
			'type' => 'checkbox',
			'belongs_to' => 'WisiloUserConfigVal',
			'display_property' => 'deleted',
			'title' => 'Deleted'
		],

		[
			'name' => 'created_at',
			'type' => 'date',
			'belongs_to' => 'WisiloUserConfigVal',
			'display_property' => 'created_at',
			'title' => 'Created At'
		],

		[
			'name' => 'updated_at',
			'type' => 'date',
			'belongs_to' => 'WisiloUserConfigVal',
			'display_property' => 'updated_at',
			'title' => 'Updated at'
		],
        [
            'name' => '__key',
            'type' => 'text',
            'belongs_to' => 'WisiloUserConfigVal',
            'display_property' => '__key',
            'title' => 'Key'
        ],
        [
            'name' => 'value',
            'type' => 'text',
            'belongs_to' => 'WisiloUserConfigVal',
            'display_property' => 'value',
            'title' => 'Value'
        ]
	];

	/* {{@snippet:end_properties}} */

	public function scopeDefaultQuery($query, $search_text, $sort_variable, $sort_direction) {
		$objectWisilo = new Wisilo();
		$query = $objectWisilo->getQuery($query, 'WisiloUserConfigVal', $this::$property_list, $search_text, $sort_variable, $sort_direction);
		return $query;
	}

	/* {{@snippet:end_methods}} */
}

/* {{@snippet:end_class}} */