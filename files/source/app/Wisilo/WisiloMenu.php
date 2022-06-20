<?php

namespace App\Wisilo;

use Illuminate\Database\Eloquent\Model;

class WisiloMenu extends Model
{
	/* {{@snippet:begin_properties}} */

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'wisilomenutable';

	protected $fillable = [
		'visibility',
		'__order',
		'parent_id',
		'__group',
		'text',
		'href',
		'icon'
	];

	/* {{@snippet:end_properties}} */

	/* {{@snippet:end_methods}} */
}

/* {{@snippet:end_class}} */