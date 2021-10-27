<?php

namespace App\AdminLTE;

use Illuminate\Database\Eloquent\Model;

class AdminLTEMenu extends Model
{
	/* {{@snippet:begin_properties}} */

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'adminltemenutable';

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