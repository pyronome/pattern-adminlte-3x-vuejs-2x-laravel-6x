<?php

namespace App\Wisilo;

use Illuminate\Database\Eloquent\Model;

use App\Wisilo\Wisilo;

class WisiloConfigFile extends Model
{
	/* {{@snippet:begin_properties}} */

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'wisiloconfigfiletable';

	protected $fillable = [
		'parameter',
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
			'belongs_to' => 'WisiloConfigFile',
			'display_property' => 'id',
			'title' => 'Id'
		],

		[
			'name' => 'deleted',
			'type' => 'checkbox',
			'belongs_to' => 'WisiloConfigFile',
			'display_property' => 'deleted',
			'title' => 'Deleted'
		],

		[
			'name' => 'created_at',
			'type' => 'date',
			'belongs_to' => 'WisiloConfigFile',
			'display_property' => 'created_at',
			'title' => 'Created At'
		],

		[
			'name' => 'updated_at',
			'type' => 'date',
			'belongs_to' => 'WisiloConfigFile',
			'display_property' => 'updated_at',
			'title' => 'Updated at'
		],
        [
            'name' => 'parameter',
            'type' => 'text',
            'belongs_to' => 'WisiloConfigFile',
            'display_property' => 'parameter',
            'title' => 'parameter'
        ],
        [
            'name' => 'file_name',
            'type' => 'text',
            'belongs_to' => 'WisiloConfigFile',
            'display_property' => 'file_name',
            'title' => 'Key'
        ],
        [
            'name' => 'description',
            'type' => 'text',
            'belongs_to' => 'WisiloConfigFile',
            'display_property' => 'description',
            'title' => 'Default Value'
        ],
        [
            'name' => 'mime_type',
            'type' => 'text',
            'belongs_to' => 'WisiloConfigFile',
            'display_property' => 'mime_type',
            'title' => 'Value'
        ],
        [
            'name' => 'file_size',
            'type' => 'text',
            'belongs_to' => 'WisiloConfigFile',
            'display_property' => 'file_size',
            'title' => 'Meta Data'
        ],
        [
            'name' => 'file_type',
            'type' => 'text',
            'belongs_to' => 'WisiloConfigFile',
            'display_property' => 'file_type',
            'title' => 'File Type'
        ],
        [
            'name' => 'file',
            'type' => 'text',
            'belongs_to' => 'WisiloConfigFile',
            'display_property' => 'file',
            'title' => 'Meta Data'
        ]
	];

	/* {{@snippet:end_properties}} */

	public function scopeDefaultQuery($query, $search_text, $sort_variable, $sort_direction) {
		$objectWisilo = new Wisilo();
		$query = $objectWisilo->getQuery($query, 'WisiloConfigFile', $this::$property_list, $search_text, $sort_variable, $sort_direction);
		return $query;
	}

	/* {{@snippet:end_methods}} */
}

/* {{@snippet:end_class}} */