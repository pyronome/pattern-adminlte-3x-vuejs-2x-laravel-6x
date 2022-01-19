<?php

namespace App\AdminLTE;

use Illuminate\Database\Eloquent\Model;

use App\AdminLTE\AdminLTE;

/* {{@snippet:begin_class}} */

class AdminLTELog extends Model
{

    /* {{@snippet:begin_properties}} */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adminltelogtable';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'sub_title',
        'object_id',
        'object_old_values',
        'object_new_values',
        'message'
    ];

    public static $property_list = [
        [
            'name' => 'id',
            'type' => 'integer',
            'belongs_to' => 'AdminLTELog',
            'display_property' => 'id',
            'title' => 'Id'
        ],
        [
            'name' => 'deleted',
            'type' => 'checkbox',
            'belongs_to' => 'AdminLTELog',
            'display_property' => 'deleted',
            'title' => 'Deleted'
        ],
        [
            'name' => 'created_at',
            'type' => 'date',
            'belongs_to' => 'AdminLTELog',
            'display_property' => 'created_at',
            'title' => 'Created At'
        ],
        [
            'name' => 'updated_at',
            'type' => 'date',
            'belongs_to' => 'AdminLTELog',
            'display_property' => 'updated_at',
            'title' => 'Updated At'
        ],
        [
            'name' => 'user_id',
            'type' => 'integer',
            'belongs_to' => 'AdminLTELog',
            'display_property' => 'user_id'
        ],
        [
            'name' => 'type',
            'type' => 'text',
            'belongs_to' => 'AdminLTELog',
            'display_property' => 'type'
        ],
        [
            'name' => 'title',
            'type' => 'text',
            'belongs_to' => 'AdminLTELog',
            'display_property' => 'title'
        ],
        [
            'name' => 'sub_title',
            'type' => 'text',
            'belongs_to' => 'AdminLTELog',
            'display_property' => 'sub_title'
        ],
        [
            'name' => 'object_id',
            'type' => 'integer',
            'belongs_to' => 'AdminLTELog',
            'display_property' => 'object_id'
        ],
        [
            'name' => 'object_old_values',
            'type' => 'text',
            'belongs_to' => 'AdminLTELog',
            'display_property' => 'object_old_values'
        ],
        [
            'name' => 'object_new_values',
            'type' => 'text',
            'belongs_to' => 'AdminLTELog',
            'display_property' => 'object_new_values'
        ],
        [
            'name' => 'message',
            'type' => 'text',
            'belongs_to' => 'AdminLTELog',
            'display_property' => 'message'
        ]
    ];
    /* {{@snippet:end_properties}} */

    /* {{@snippet:begin_methods}} */
    
    public function scopeDefaultQuery($query, $search_text, $sort_variable, $sort_direction) {
        $objectAdminLTE = new AdminLTE();
        $query = $objectAdminLTE->getQuery($query, 'AdminLTELog', $this::$property_list, $search_text, $sort_variable, $sort_direction);
        return $query;
    } 
    
    public function getObjectStoredValues($obj) {
		$exceptions = array('id', 'created_at', 'updated_at', 'deleted');
        $objectAdminLTE = new AdminLTE();

        $modelNameWithNamespace = get_class($obj);
        $objStored = $modelNameWithNamespace::find($obj->id);
        $objVars = $objStored->getAttributes();

		//$langPrefix = '_lang_' . strtolower($model); // model:APP/Student şeklinde geliyor
        
		$itemDescription = '';
		foreach ($objVars as $key => $value) {  
			if (in_array($key, $exceptions)) {
       			continue;
       		}

       		if ('' != $itemDescription) {
				$itemDescription .= ',';
			}
			
			$itemDescription = $itemDescription 
				. '"' /* . $langPrefix . '_' */ . $key 
				. '" : "' 
				. $value . '"';
		}

		$itemDescriptionJSON = '{' . $itemDescription . '}';   

        return $itemDescriptionJSON; 
	}

    public function getObjectNewValues($obj) {
        $exceptions = array('id', 'created_at', 'updated_at', 'deleted');
        $objectAdminLTE = new AdminLTE();

        $model = get_class($obj);
        $modelNameWithNamespace = $objectAdminLTE->getModelNameWithNamespace($model);
  
        $objVars = $obj->getAttributes();

		//$langPrefix = '_lang_' . strtolower($model); // model:APP/Student şeklinde geliyor
        
		$itemDescription = '';
		foreach ($objVars as $key => $value) {  
			if (in_array($key, $exceptions)) {
       			continue;
       		}

       		if ('' != $itemDescription) {
				$itemDescription .= ',';
			}
			
			$itemDescription = $itemDescription 
				. '"' /* . $langPrefix . '_' */ . $key 
				. '" : "' 
				. $value . '"';
		}

		$itemDescriptionJSON = '{' . $itemDescription . '}';   

        return $itemDescriptionJSON; 
    }

    /* {{@snippet:end_methods}} */
}

/* {{@snippet:begin_class}} */
