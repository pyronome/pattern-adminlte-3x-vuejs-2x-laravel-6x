<?php

namespace App\Wisilo;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Wisilo\Wisilo;

/* {{@snippet:begin_class}} */

class WisiloUser extends Authenticatable
{

    /* {{@snippet:begin_properties}} */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wisilousertable';

    protected $fillable = [
        'profile_img',
        'wisilousergroup_id',
        'enabled',
        'fullname',
        'username',
        'email',
        'password',
        'passwordHash'
    ];
    
    public static $property_list = [
        [
            'name' => 'id',
            'type' => 'integer',
            'belongs_to' => 'WisiloUser',
            'display_property' => 'id',
            'title' => 'Id'
        ],
        [
            'name' => 'deleted',
            'type' => 'checkbox',
            'belongs_to' => 'WisiloUser',
            'display_property' => 'deleted',
            'title' => 'Deleted'
        ],
        [
            'name' => 'created_at',
            'type' => 'date',
            'belongs_to' => 'WisiloUser',
            'display_property' => 'created_at',
            'title' => 'Created At'
        ],
        [
            'name' => 'updated_at',
            'type' => 'date',
            'belongs_to' => 'WisiloUser',
            'display_property' => 'updated_at',
            'title' => 'Updated At'
        ],
        [
            'name' => 'created_by',
            'type' => 'integer',
            'belongs_to' => 'WisiloUser',
            'display_property' => 'created_by',
            'title' => 'Created By'
        ],
        [
            'name' => 'updated_by',
            'type' => 'integer',
            'belongs_to' => 'WisiloUser',
            'display_property' => 'updated_by',
            'title' => 'Updated By'
        ],
        [
            'name' => 'profile_img',
            'type' => 'image',
            'belongs_to' => 'WisiloUser',
            'display_property' => 'profile_img',
            'title' => 'Profile Image'
        ],
        [
            'name' => 'wisilousergroup_id',
            'type' => 'class_selection_single',
            'belongs_to' => 'WisiloUserGroup',
            'display_property' => 'title',
            'title' => 'User Group'
        ],
        [
            'name' => 'enabled',
            'type' => 'checkbox',
            'belongs_to' => 'WisiloUser',
            'display_property' => 'enabled',
            'title' => 'Enabled'
        ],
        [
            'name' => 'fullname',
            'type' => 'text',
            'belongs_to' => 'WisiloUser',
            'display_property' => 'fullname',
            'title' => 'Fullname'
        ],
        [
            'name' => 'username',
            'type' => 'text',
            'belongs_to' => 'WisiloUser',
            'display_property' => 'username',
            'title' => 'Username'
        ],
        [
            'name' => 'email',
            'type' => 'text',
            'belongs_to' => 'WisiloUser',
            'display_property' => 'email',
            'title' => 'Email'
        ],
        [
            'name' => 'password',
            'type' => 'text',
            'belongs_to' => 'WisiloUser',
            'display_property' => 'password',
            'title' => 'Password'
        ]
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /* {{@snippet:end_properties}} */

    /* {{@snippet:begin_methods}} */
    
    public function scopeDefaultQuery($query, $search_text, $sort_variable, $sort_direction) {
        $objectWisilo = new Wisilo();
        $query = $objectWisilo->getQuery($query, 'WisiloUser', $this::$property_list, $search_text, $sort_variable, $sort_direction);
        return $query;
    }

    public function wisilousergroup_id()
    {
        return $this->belongsTo(WisiloUserGroup::class, 'wisilousergroup_id');
    }

    public function is_admin() {
        if (session()->has(sha1('wisilo_impersonate')))
        {
            return true;
        }

        $objectWisiloUserGroup = WisiloUserGroup::find($this->wisilousergroup_id);
                
        if (null != $objectWisiloUserGroup) {
            return (1 == $objectWisiloUserGroup->admin);
        }

        return false;
    }

    /* {{@snippet:end_methods}} */
}

/* {{@snippet:begin_class}} */
