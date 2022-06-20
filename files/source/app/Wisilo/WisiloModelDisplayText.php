<?php

namespace App\Wisilo;

use Illuminate\Database\Eloquent\Model;

/* {{@snippet:begin_class}} */

class WisiloModelDisplayText extends Model
{

	/* {{@snippet:begin_properties}} */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wisilomodeldisplaytexttable';

	protected $fillable = [
		'model',
		'display_texts'
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
            'name' => 'display_texts',
            'type' => 'text'
        ]
    ];

	/* {{@snippet:end_properties}} */

	/* {{@snippet:begin_methods}} */
	
	/* {{@snippet:end_methods}} */
}

/* {{@snippet:end_class}} */