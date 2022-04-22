<?php

namespace App\AdminLTE;

use Illuminate\Database\Eloquent\Model;

/* {{@snippet:begin_class}} */

class AdminLTECustomVariableValue extends Model
{

	/* {{@snippet:begin_properties}} */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adminltecustomvariablevaluetable';

	protected $fillable = [
        'adminlteusergroup_id',
        'customvariable_id',
		'value'
	];

	/* {{@snippet:end_properties}} */

	/* {{@snippet:begin_methods}} */

    public function adminlteusergroup_id()
    {
        return $this->belongsTo(AdminLTEUserGroup::class, 'adminlteusergroup_id');
    }

    public function customvariable_id()
    {
        return $this->belongsTo(AdminLTECustomVariable::class, 'customvariable_id');
    }

	/* {{@snippet:end_methods}} */
}

/* {{@snippet:end_class}} */