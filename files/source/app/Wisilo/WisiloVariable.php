<?php

namespace App\Wisilo;

use Illuminate\Database\Eloquent\Model;

/* {{@snippet:begin_class}} */

class WisiloVariable extends Model
{

	/* {{@snippet:begin_properties}} */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wisilovariabletable';

	protected $fillable = [
		'title',
		'group',
		'value1',
		'value2',
		'value3',
		'__order'
	];

	/* {{@snippet:end_properties}} */

	/* {{@snippet:begin_methods}} */

	/* {{@snippet:end_methods}} */
}

/* {{@snippet:end_class}} */