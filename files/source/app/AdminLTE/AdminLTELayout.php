<?php

namespace App\AdminLTE;

use Illuminate\Database\Eloquent\Model;

/* {{snippet:begin_class}} */

class AdminLTELayout extends Model
{
	/* {{snippet:begin_properties}} */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adminltelayouttable';

	protected $fillable = [
		'pagename',
		'widgets'
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
            'name' => 'pagename',
            'type' => 'text'
        ],
        [
            'name' => 'widgets',
            'type' => 'text'
        ]
    ];

	/* {{snippet:end_properties}} */

	/* {{snippet:begin_methods}} */
	
	/* {{snippet:end_methods}} */

}

/* {{snippet:end_class}} */