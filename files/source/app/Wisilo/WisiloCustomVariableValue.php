<?php

namespace App\Wisilo;

use Illuminate\Database\Eloquent\Model;

/* {{@snippet:begin_class}} */

class WisiloCustomVariableValue extends Model
{

	/* {{@snippet:begin_properties}} */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wisilocustomvariablevaluetable';

	protected $fillable = [
        'wisilousergroup_id',
        'customvariable_id',
		'value'
	];

	/* {{@snippet:end_properties}} */

	/* {{@snippet:begin_methods}} */

    public function wisilousergroup_id()
    {
        return $this->belongsTo(WisiloUserGroup::class, 'wisilousergroup_id');
    }

    public function customvariable_id()
    {
        return $this->belongsTo(WisiloCustomVariable::class, 'customvariable_id');
    }

	/* {{@snippet:end_methods}} */
}

/* {{@snippet:end_class}} */