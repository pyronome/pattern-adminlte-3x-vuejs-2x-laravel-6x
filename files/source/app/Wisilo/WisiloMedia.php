<?php

namespace App\Wisilo;

use Illuminate\Database\Eloquent\Model;

use App\Wisilo\Wisilo;

class WisiloMedia extends Model
{
	/* {{@snippet:begin_properties}} */

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'wisilomediatable';

	protected $fillable = [
		'group',
        'guid',
        'file_title',
		'file_name',
        'description',
		'mime_type',
        'file_size',
        'file_type',
		'file'
	];

	public static $property_list = [
		[
			'name' => 'id',
			'type' => 'integer',
			'belongs_to' => 'WisiloMedia',
			'display_property' => 'id',
			'title' => 'Id'
		],

		[
			'name' => 'deleted',
			'type' => 'checkbox',
			'belongs_to' => 'WisiloMedia',
			'display_property' => 'deleted',
			'title' => 'Deleted'
		],

		[
			'name' => 'created_at',
			'type' => 'date',
			'belongs_to' => 'WisiloMedia',
			'display_property' => 'created_at',
			'title' => 'Created At'
		],

		[
			'name' => 'updated_at',
			'type' => 'date',
			'belongs_to' => 'WisiloMedia',
			'display_property' => 'updated_at',
			'title' => 'Updated at'
		],
        [
            'name' => 'created_by',
            'type' => 'integer',
            'belongs_to' => 'WisiloMedia',
            'display_property' => 'created_by',
            'title' => 'Created By'
        ],
        [
            'name' => 'updated_by',
            'type' => 'integer',
            'belongs_to' => 'WisiloMedia',
            'display_property' => 'updated_by',
            'title' => 'Updated By'
        ],
        [
            'name' => 'group',
            'type' => 'text',
            'belongs_to' => 'WisiloMedia',
            'display_property' => 'group',
            'title' => 'Group'
        ],
        [
            'name' => 'guid',
            'type' => 'text',
            'belongs_to' => 'WisiloMedia',
            'display_property' => 'guid',
            'title' => 'GUID'
        ],
        [
            'name' => 'file_title',
            'type' => 'text',
            'belongs_to' => 'WisiloMedia',
            'display_property' => 'file_title',
            'title' => 'Title'
        ],
        [
            'name' => 'file_name',
            'type' => 'text',
            'belongs_to' => 'WisiloMedia',
            'display_property' => 'file_name',
            'title' => 'Name'
        ],
        [
            'name' => 'description',
            'type' => 'text',
            'belongs_to' => 'WisiloMedia',
            'display_property' => 'description',
            'title' => 'Description'
        ],
        [
            'name' => 'mime_type',
            'type' => 'text',
            'belongs_to' => 'WisiloMedia',
            'display_property' => 'mime_type',
            'title' => 'Mime Type'
        ],
        [
            'name' => 'file_size',
            'type' => 'text',
            'belongs_to' => 'WisiloMedia',
            'display_property' => 'file_size',
            'title' => 'Size'
        ],
        [
            'name' => 'file_type',
            'type' => 'text',
            'belongs_to' => 'WisiloMedia',
            'display_property' => 'file_type',
            'title' => 'Type'
        ],
        [
            'name' => 'file',
            'type' => 'text',
            'belongs_to' => 'WisiloMedia',
            'display_property' => 'file',
            'title' => 'File'
        ]
	];

	/* {{@snippet:end_properties}} */

	public function scopeDefaultQuery($query, $search_text, $sort_variable, $sort_direction) {
		$objectWisilo = new Wisilo();
		$query = $objectWisilo->getQuery($query, 'WisiloMedia', $this::$property_list, $search_text, $sort_variable, $sort_direction);
		return $query;
	}

	/* {{@snippet:end_methods}} */
}

/* {{@snippet:end_class}} */