<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/* {{snippet:begin_class}} */

class AdminLTEModelDisplayText extends Model
{

	/* {{snippet:begin_properties}} */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adminltemodeldisplaytexttable';

	protected $fillable = [
		'model',
		'display_texts'
	];

	/* {{snippet:end_properties}} */

	/* {{snippet:begin_methods}} */

	/* {{snippet:end_methods}} */
}

/* {{snippet:end_class}} */