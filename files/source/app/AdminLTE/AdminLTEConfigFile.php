<?php

namespace App\AdminLTE;

use Illuminate\Database\Eloquent\Model;

use App\AdminLTE\AdminLTE;

class AdminLTEConfigFile extends Model
{
	/* {{@snippet:begin_properties}} */

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'adminlteconfigfiletable';

	protected $fillable = [
		'parameter',
		'file_name',
        'description',
		'mime_type',
        'file_size',
        'guid',
		'file'
	];

	public static $property_list = [
		[
			'name' => 'id',
			'type' => 'integer',
			'belongs_to' => 'AdminLTEConfigFile',
			'display_property' => 'id',
			'title' => 'Id'
		],

		[
			'name' => 'deleted',
			'type' => 'checkbox',
			'belongs_to' => 'AdminLTEConfigFile',
			'display_property' => 'deleted',
			'title' => 'Deleted'
		],

		[
			'name' => 'created_at',
			'type' => 'date',
			'belongs_to' => 'AdminLTEConfigFile',
			'display_property' => 'created_at',
			'title' => 'Created At'
		],

		[
			'name' => 'updated_at',
			'type' => 'date',
			'belongs_to' => 'AdminLTEConfigFile',
			'display_property' => 'updated_at',
			'title' => 'Updated at'
		],
        [
            'name' => 'parameter',
            'type' => 'text',
            'belongs_to' => 'AdminLTEConfigFile',
            'display_property' => 'parameter',
            'title' => 'parameter'
        ],
        [
            'name' => 'file_name',
            'type' => 'text',
            'belongs_to' => 'AdminLTEConfigFile',
            'display_property' => 'file_name',
            'title' => 'Key'
        ],
        [
            'name' => 'description',
            'type' => 'text',
            'belongs_to' => 'AdminLTEConfigFile',
            'display_property' => 'description',
            'title' => 'Default Value'
        ],
        [
            'name' => 'mime_type',
            'type' => 'text',
            'belongs_to' => 'AdminLTEConfigFile',
            'display_property' => 'mime_type',
            'title' => 'Value'
        ],
        [
            'name' => 'file_size',
            'type' => 'text',
            'belongs_to' => 'AdminLTEConfigFile',
            'display_property' => 'file_size',
            'title' => 'Meta Data'
        ],
        [
            'name' => 'guid',
            'type' => 'text',
            'belongs_to' => 'AdminLTEConfigFile',
            'display_property' => 'guid',
            'title' => 'Value'
        ],
        [
            'name' => 'file',
            'type' => 'text',
            'belongs_to' => 'AdminLTEConfigFile',
            'display_property' => 'file',
            'title' => 'Meta Data'
        ]
	];

	/* {{@snippet:end_properties}} */

	public function scopeDefaultQuery($query, $search_text, $sort_variable, $sort_direction) {
		$objectAdminLTE = new AdminLTE();
		$query = $objectAdminLTE->getQuery($query, 'AdminLTEConfigFile', $this::$property_list, $search_text, $sort_variable, $sort_direction);
		return $query;
	}

	/* {{@snippet:end_methods}} */
}

/* {{@snippet:end_class}} */