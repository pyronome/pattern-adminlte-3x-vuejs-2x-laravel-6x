<?php

namespace App\Wisilo;

use Illuminate\Database\Eloquent\Model;

/* {{@snippet:begin_class}} */

class WisiloCustomVariable extends Model
{

	/* {{@snippet:begin_properties}} */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wisilocustomvariabletable';

	protected $fillable = [
        'created_by',
        'updated_by',
        '__system',
        'wisilousergroup_id',
        'group',
        'name',
		'title',
        'default_value',
        'remember',
        'remember_type',
		'value',
		'__order'
	];

	/* {{@snippet:end_properties}} */

	/* {{@snippet:begin_methods}} */

    public function wisilousergroup_id()
    {
        return $this->belongsTo(WisiloUserGroup::class, 'wisilousergroup_id');
    }

    public function WisiloCustomVariableValue_customvariable_id()
    {
        return $this->hasMany(WisiloCustomVariableValue::class);
    }

	/* {{@snippet:end_methods}} */
}

/* {{@snippet:end_class}} */