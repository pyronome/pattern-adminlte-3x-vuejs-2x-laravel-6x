<?php

namespace App\Wisilo;

use Illuminate\Database\Eloquent\Model;

/* {{@snippet:begin_class}} */

class WisiloModelOption extends Model
{
	/* {{@snippet:begin_properties}} */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wisilomodeloptiontable';

	protected $fillable = [
		'model',
		'property',
		'value',
		'title'
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
            'name' => 'model',
            'type' => 'text'
        ],
        [
            'name' => 'property',
            'type' => 'text'
        ],
        [
            'name' => 'value',
            'type' => 'text'
        ],
        [
            'name' => 'title',
            'type' => 'text'
        ]
    ];

	/* {{@snippet:end_properties}} */

	/* {{@snippet:begin_methods}} */
	
	/* {{@snippet:end_methods}} */

}

/* {{@snippet:end_class}} */