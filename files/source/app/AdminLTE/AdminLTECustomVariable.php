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
        'adminlteusergroup_id',
        'group',
        'name',
		'title',
		'value',
		'__order'
	];

	/* {{@snippet:end_properties}} */

	/* {{@snippet:begin_methods}} */

    public function adminlteusergroup_id()
    {
        return $this->belongsTo(AdminLTEUserGroup::class, 'adminlteusergroup_id');
    }

	/* {{@snippet:end_methods}} */
}

/* {{@snippet:end_class}} */