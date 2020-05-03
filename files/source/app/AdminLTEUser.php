<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/* {{snippet:begin_class}} */

class AdminLTEUser extends Authenticatable
{

	/* {{snippet:begin_properties}} */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adminlteusertable';

    protected $fillable = [
		'adminlteusergroup_id',
		'profile_img',
		'enabled',
		'fullname',
		'username',
		'email',
		'password',
		'passwordHash',
		'menu_permission',
		'service_permission'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

	/* {{snippet:end_properties}} */

	/* {{snippet:begin_methods}} */

	/* {{snippet:end_methods}} */
}

/* {{snippet:begin_class}} */