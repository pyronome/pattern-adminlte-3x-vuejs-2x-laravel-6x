<?php

namespace App\AdminLTE;

use Illuminate\Database\Eloquent\Model;

/* {{@snippet:begin_class}} */

class AdminLTECustomVariable extends Model
{

	/* {{@snippet:begin_properties}} */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adminltecustomvariabletable';

	protected $fillable = [
        'created_by',
        'updated_by',
        '__system',
        'adminlteusergroup_id',
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

    public function adminlteusergroup_id()
    {
        return $this->belongsTo(AdminLTEUserGroup::class, 'adminlteusergroup_id');
    }

    public function AdminLTECustomVariableValue_customvariable_id()
    {
        return $this->hasMany(AdminLTECustomVariableValue::class);
    }

	/* {{@snippet:end_methods}} */
}

/* {{@snippet:end_class}} */