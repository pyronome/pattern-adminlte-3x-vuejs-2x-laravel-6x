<?php

namespace App\Wisilo;

use Illuminate\Database\Eloquent\Model;

/* {{@snippet:begin_class}} */

class WisiloMeta extends Model
{

	/* {{@snippet:begin_properties}} */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wisilometatable';

	protected $fillable = [
		'term_id',
		'meta_key',
		'meta_value'
	];

	/* {{@snippet:end_properties}} */

	/* {{@snippet:begin_methods}} */

	/* {{@snippet:end_methods}} */
}

/* {{@snippet:end_class}} */