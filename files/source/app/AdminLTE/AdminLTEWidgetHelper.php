<?php

namespace App\AdminLTE;
use Illuminate\Support\Facades\Storage;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTECustomVariable;
use App\AdminLTE\AdminLTECustomVariableValue;
use App\AdminLTE\AdminLTELayout;

class AdminLTEWidgetHelper
{	
	public function __construct()
	{
	}

	/* public function getUserGroupWidgets($parameters) {
		$default_parameters = [
			'filters' => [
				'group_id' => 0,
				'enabled' => 1
			],
			'order_by' => [
				'property' => '__order',
				'direction' => 'asc'
			]
		];

		if (null == $parameters) {
			return [];
		}

		if (!isset($parameters['filters']['group_id'])) {
			return [];
		}

		if (!isset($parameters['order_by'])) {
			$parameters['order_by'] = [
				'property' => '__order',
				'direction' => 'asc'
			];
		}

        $objectAdminLTELayouts = AdminLTELayout::where('deleted', false)
				->where('adminlteusergroup_id', $parameters['filters']['group_id'])
				->where('enabled', $parameters['filters']['group_id'])
				->orderBy($parameters['order_by']['property'], $parameters['order_by']['direction'])
				->get();

        if (0 == count($objectAdminLTELayouts)) {
            $objectAdminLTELayouts = [];
        }

		return $objectAdminLTELayouts;
	} */

	public function getUserWidgets($pagename) {
		$currentUser = auth()->guard('adminlteuser')->user();

        $objectAdminLTELayouts = AdminLTELayout::where('deleted', false)
				->where('adminlteusergroup_id', $currentUser->adminlteusergroup_id)
				->where('pagename', $pagename)
				->orderBy('__order','asc')
				->get();

        if (0 == count($objectAdminLTELayouts)) {
            $objectAdminLTELayouts = AdminLTELayout::where('deleted', false)
				->where('adminlteusergroup_id', null)
				->where('pagename', $pagename)
				->orderBy('__order','asc')
				->get();
        }

		return $objectAdminLTELayouts;
	}

	public function getUserActiveWidgets($pagename) {
		$currentUser = auth()->guard('adminlteuser')->user();

		$objectAdminLTELayout = null;
        $objectAdminLTELayouts = AdminLTELayout::where('deleted', false)
				->where('enabled', true)
				->where('adminlteusergroup_id', $currentUser->adminlteusergroup_id)
				->where('pagename', $pagename)
				->orderBy('__order','asc')
				->get();

        if (0 == count($objectAdminLTELayouts)) {
            $objectAdminLTELayouts = AdminLTELayout::where('deleted', false)
				->where('adminlteusergroup_id', null)
				->where('pagename', $pagename)
				->orderBy('__order','asc')
				->get();
        }

		return $objectAdminLTELayouts;
	}

	public function saveConditionalData($layoutId, $conditional_data) {
		if (!empty($conditional_data)) {
			foreach ($conditional_data as $condition_data) {
				$this->createWidgetConditionFunctionFile($layoutId, $condition_data);
			}
		}
	}

	public function calculateConditionVariablesValue($variables, $queryResult, $url_parameters, $request_parameters) {
		$objectAdminLTE = new AdminLTE();
		$currentUser = auth()->guard('adminlteuser')->user();

		foreach ($variables as $key => $value) {
			$keyPart = explode('/', $key);

			if ('GlobalParameters' == $keyPart[0]) {
                $__key = $keyPart[1];
                $partResult = $objectAdminLTE->getConfigParameterValue($__key);
            } else if ('UserParameters' == $keyPart[0]) {
                $__key = $keyPart[1];
                $partResult = $objectAdminLTE->getUserConfigParameterValue($__key, 'user', $currentUser->id);
            } else if ('URLParameters' == $keyPart[0]) {
                $__key = $keyPart[1];
                $partResult = isset($url_parameters[$__key]) 
                    ? $url_parameters[$__key]
                    : $__key;
            } else if ('RequestParameters' == $keyPart[0]) {
                $__key = $keyPart[1];
                $partResult = isset($request_parameters[$__key]) 
                    ? $request_parameters[$__key]
                    : $__key;
            } else if ('QueryResultFields' == $keyPart[0]) {
                $__key = $keyPart[1];
                $partResult = isset($queryResult[$__key]) 
                    ? $queryResult[$__key] 
                    : $__key;
            }  else if ('CustomVariables' == $keyPart[0]) {
                $__key = $keyPart[1];
				$customVariableValue = $this->getCustomVariableValue($currentUser->adminlteusergroup_id, $__key, $queryResult, $url_parameters, $request_parameters);
				$partResult = ('' != $customVariableValue)
                    ? $customVariableValue
                    : $__key;
            } else {
                $__key = $key;
                $partResult = isset($queryResult[$__key]) 
                    ? $queryResult[$__key] 
                    : $__key;
            }

			$variables[$key] = $partResult;
		}

		return $variables;
	}

	public function getCustomVariableValue($adminlteusergroup_id, $variableName, $queryResult, $url_parameters, $request_parameters) {
		$resultValue = '';

		$object = AdminLTECustomVariable::where('deleted', 0)
            ->where('adminlteusergroup_id', 0)
            ->where('name', $variableName)
            ->first();

        if (null !== $object) {
            $resultValue = $this->getVariableCalculatedValue($object, $queryResult, $url_parameters, $request_parameters);
        } else {
			$object = AdminLTECustomVariable::where('deleted', 0)
				->where('adminlteusergroup_id', $adminlteusergroup_id)
				->where('name', $variableName)
				->first();

			if (null !== $object) {
				$resultValue = $this->getVariableCalculatedValue($object, $queryResult, $url_parameters, $request_parameters);
			}
		}

		return $resultValue;
	}

	public function getVariableCalculatedValue($objCV, $queryResult, $url_parameters, $request_parameters) {
		$customvariable_key = 'customvariable' . $objCV->id;
		$remember_type = $objCV->remember_type;
		$defaultValue = $objCV->default_value;
		$variableValue = $defaultValue;
		$remember = (1 == $objCV->remember);

		$objectAdminLTE = new AdminLTE();
		$currentUser = auth()->guard('adminlteuser')->user();

		$parsed = $objectAdminLTE->getStringBetween($variableValue, '{{', '}}');

		$is_last_row = false;
		if (isset($queryResult['__is_last_row'])) {
			$is_last_row = (1 == $queryResult['__is_last_row']);
		}
		
		if (strlen($parsed) > 0) {
			// GlobalParameters
			// UserParameters
			// URLParameters
			// RequestParameters
			// QueryResultFields
			// CurrentUser

			while (strlen($parsed) > 0) {
				$parsedWithMustache = '{{' . $parsed . '}}';
				$partResult = '';

				$textPart = explode('/', $parsed);

				if ('GlobalParameters' == $textPart[0]) {
					$__key = $textPart[1];
					$partResult = $objectAdminLTE->getConfigParameterValue($__key);
				} else if ('UserParameters' == $textPart[0]) {
					$__key = $textPart[1];
					$partResult = $objectAdminLTE->getUserConfigParameterValue($__key, 'user', $currentUser->id);
				} else if ('URLParameters' == $textPart[0]) {
					$__key = $textPart[1];
					$partResult = isset($url_parameters[$__key]) 
						? $url_parameters[$__key]
						: $__key;
				} else if ('RequestParameters' == $textPart[0]) {
					$__key = $textPart[1];
					$partResult = isset($request_parameters[$__key]) 
						? $request_parameters[$__key]
						: $__key;
				} else if ('QueryResultFields' == $textPart[0]) {
					$__key = $textPart[1];
					$partResult = isset($queryResult[$__key]) 
						? $queryResult[$__key] 
						: $__key;
				} else if ('CurrentUser' == $textPart[0]) {
					$__key = $textPart[1];
					$partResult = $currentUser->$__key;
				} else {
					$partResult = isset($queryResult[$customvariable_key]) 
						? $queryResult[$customvariable_key] 
						: $defaultValue;
				}

				if($remember) {
					if ('session' == $remember_type) {
						if (session()->has($customvariable_key)) {
							$partResult = session()->get($customvariable_key);
						}
					} else if ('database' == $remember_type) {
						$object = AdminLTECustomVariableValue::where('deleted', 0)
							->where('customvariable_id', $objCV->id)
							->where('adminlteusergroup_id', $objCV->adminlteusergroup_id)
							->first();
			
						if (null !== $object) {
							$partResult = $object->value;
						}
					}
				}

				$variableValue = str_replace($parsedWithMustache, $partResult, $variableValue);
				$temp_text = $variableValue;
				$parsed = $objectAdminLTE->getStringBetween($temp_text, '{{', '}}');
			} // while (strlen($parsed) > 0) {
		} else {
			if (isset($queryResult[$customvariable_key]) ) {
				$variableValue = $queryResult[$customvariable_key];
			} else {
				if ($remember) {
					if ('session' == $remember_type) {
						if (session()->has($customvariable_key)) {
							$variableValue = session()->get($customvariable_key);
						} /* else {
							$variableValue = isset($queryResult[$customvariable_key]) 
								? $queryResult[$customvariable_key] 
								: $variableValue;
	
							session()->put($customvariable_key, $variableValue);
						} */
					} else if ('database' == $remember_type) {
						$object = AdminLTECustomVariableValue::where('deleted', 0)
							->where('customvariable_id', $objCV->id)
							->where('adminlteusergroup_id', $objCV->adminlteusergroup_id)
							->first();
			
						if (null !== $object) {
							$variableValue = $object->value;
						} /* else {
							$variableValue = isset($queryResult[$customvariable_key]) 
								? $queryResult[$customvariable_key] 
								: $variableValue;
	
							$object = new AdminLTECustomVariableValue();
							$object->customvariable_id = $objCV->id;
							$object->adminlteusergroup_id = $objCV->adminlteusergroup_id;
							$object->value = $variableValue;
							$object->save();
						} */
					}
				}
			}
		}

		if ($is_last_row) {
			if ($remember) {
				if ('session' == $remember_type) {
					session()->put($customvariable_key, $variableValue);
				} else if ('database' == $remember_type) {
					$object = AdminLTECustomVariableValue::where('deleted', 0)
						->where('customvariable_id', $objCV->id)
						->where('adminlteusergroup_id', $objCV->adminlteusergroup_id)
						->first();
		
					if (null !== $object) {
						$object->value = $variableValue;
						$object->save();
					} else {
						$object = new AdminLTECustomVariableValue();
						$object->customvariable_id = $objCV->id;
						$object->adminlteusergroup_id = $objCV->adminlteusergroup_id;
						$object->value = $variableValue;
						$object->save();
					}
				}
			}
		}

        return htmlspecialchars_decode($variableValue);
	}

	public function createWidgetConditionFunctionFile($layoutId, $condition_data)
	{
		$guid = $condition_data->guid;
		$conditions = $condition_data->condition_json;
		$fields = $condition_data->conditional_fields;

		$functionCodeHeader = '<' . '?' . 'php'
				. "\n"
				. 'function ' . $guid . '($objectHelper, $queryResult, $url_parameters, $request_parameters) {'
				. "\n\t";


		$functionCodeVariables = '';
		$functionCodeVariables = $functionCodeVariables 
			. '$result = [];' . "\n\t"
			. '$result[\'success\'] = false;' . "\n\t"
			. '$result[\'data\'] = [];' . "\n\n\t";

		$functionCodeVariables = $functionCodeVariables
			. '$fieldsJSON = \'' . json_encode($fields) . '\';' . "\n\n\t";

		$functionCodeVariables = $functionCodeVariables . '$variables = [];';

		$functionCodeBody = '';
		$functionCodeFooter = "\n\n\t" . 'return $result;';
		$functionCodeFooter = $functionCodeFooter . "\n" . '}';


		$history = [];
		$functionCodeBody = $this->generateWidgetConditionCode($conditions, $history);

		// Initialize Used Variables
		if (!isset($history['used_variables']))
		{
			$history['used_variables'] = [];
		}

		$keys = array_keys($history['used_variables']);
		$keyCount = count($keys);

		for ($i = 0; $i < $keyCount; $i++)
		{
			$functionCodeVariables .= "\n\t"
					. '$'
					. 'variables[\''
					. $keys[$i]
					. '\'] = '
					. '\'' . $keys[$i] . '\''
					. ';';
		}

		$functionCodeVariables = $functionCodeVariables
			. "\n\t"
			. '$variables = $objectHelper->calculateConditionVariablesValue($variables, $queryResult, $url_parameters, $request_parameters);'
			. "\n";

		$functionFilePath = ('/widget/condition/'
				. $guid
				. '.php');

		Storage::disk('local')->put($functionFilePath,
				($functionCodeHeader
				. $functionCodeVariables
				. $functionCodeBody
				. $functionCodeFooter));
	}

	private function generateWidgetConditionCode($conditions, &$history)
	{
		$operationHeaderCode = '';
		$operationFooterCode = '';

		if (isset($conditions->condition))
		{
			$operationHeaderCode = $operationHeaderCode . "\n\t" . 'if ';
			$operationHeaderCode = $operationHeaderCode . $this->generateConditionCode(
					$conditions->rules,
					$history,
					$conditions->condition);
			$operationHeaderCode = $operationHeaderCode . ' {' . "\n";
			$operationFooterCode = $operationFooterCode . "\n\t" . '}';
		}

		$operationBodyCode = "\t\t" 
			. '$result[\'success\'] = true;' . "\n\t\t"
			. '$result[\'data\'] = json_decode($fieldsJSON);' . "\n\t\t"
			. 'return $result;';

		return ($operationHeaderCode . $operationBodyCode . $operationFooterCode);
	}

	private function generateConditionCode($rules, &$history, $condition = 'AND')
	{
		$ruleCount = count($rules);
		$rule = null;
		$andOr = ' && ';

		if ($condition[0] == 'A')
		{
			$andOr = ' && ';
		} else {
			$andOr = ' || ';
		}

		$code = '(';

		for ($i = 0; $i < $ruleCount; $i++)
		{
			if ($i > 0)
			{
				$code .= $andOr;
			}

			$rule = $rules[$i];

			if (isset($rule->rules))
			{
				$code .= $this->generateConditionCode(
						$rule->rules,
						$history,
						$rule->condition);
			} else {
				$code .= $this->generateConditionRuleCode(
						$rule,
						$history);
			}
		}

		$code .= ')';

		if ($code == '()')
		{
			$code = '(1)';
		}

		return $code;
	}

	private function generateConditionRuleCode($rule, &$history)
	{
		$code = '';

		if (!isset($rule->field))
		{
			return $code;
		}
		
		$history['used_variables'][$rule->field] = 1;

		$code = '(';
		
		switch ($rule->operator)
		{
			case 'equal':
				$code = $code 
						. '$' . 'variables[\'' . $rule->field . '\']'
						. ' == '
						. $this->generateConditionRuleValueCode($rule->value, $history);
				break;

			case 'not_equal':
				$code = $code
						. '$' . 'variables[\'' . $rule->field . '\']'
						. ' != '
						. $this->generateConditionRuleValueCode($rule->value, $history);
				break;

			case 'contains':
				$code = $code
						. 'strpos('
						. '$' . 'variables[\'' . $rule->field . '\']'
						. ', '
						. $this->generateConditionRuleValueCode($rule->value, $history)
						. ')'
						. ' !== false';
				break;

			case 'in':
				$code = $code
						. 'strpos('
						. $this->generateConditionRuleValueCode($rule->value, $history)
						. ', '
						. '$' . 'variables[\'' . $rule->field . '\']'
						. ')'
						. ' !== false';
				break;

			case 'not_in':
				$code = $code
						. 'strpos('
						. $this->generateConditionRuleValueCode($rule->value, $history)
						. ', '
						. '$' . 'variables[\'' . $rule->field . '\']'
						. ')'
						. ' === false';
				break;

			case 'not_contains':
				$code = $code
						. 'strpos('
						. '$' . 'variables[\'' . $rule->field . '\']'
						. ', '
						. $this->generateConditionRuleValueCode($rule->value, $history)
						. ')'
						. ' === false';
				break;

			case 'less':
				$code = $code
						. 'floatval('
						. '$' . 'variables[\'' . $rule->field . '\']'
						. ')'
						. ' < '
						. 'floatval('
						. $this->generateConditionRuleValueCode($rule->value,$history)
						. ')';
				break;

			case 'less_or_equal':
				$code = $code
						. 'floatval('
						. '$' . 'variables[\'' . $rule->field . '\']'
						. ')'
						. ' <= '
						. 'floatval('
						. $this->generateConditionRuleValueCode($rule->value,$history)
						. ')';
				break;

			case 'greater':
				$code = $code
						. 'floatval('
						. '$' . 'variables[\'' . $rule->field . '\']'
						. ')'
						. ' > '
						. 'floatval('
						. $this->generateConditionRuleValueCode($rule->value,$history)
						. ')';
				break;
	
			case 'greater_or_equal':
				$code = $code
						. 'floatval('
						. '$' . 'variables[\'' . $rule->field . '\']'
						. ')'
						. ' >= '
						. 'floatval('
						. $this->generateConditionRuleValueCode($rule->value,$history)
						. ')';
				break;

			case 'begins_with':
				$code = $code
						. 'strpos('
						. '$' . 'variables[\'' . $rule->field . '\']'
						. ', '
						. $this->generateConditionRuleValueCode($rule->value, $history)
						. ')'
						. ' === 0';

				break;

			case 'not_begins_with':
				$code = $code
						. 'strpos('
						. '$' . 'variables[\'' . $rule->field . '\']'
						. ', '
						. $this->generateConditionRuleValueCode($rule->value, $history)
						. ')'
						. ' !== 0';
				break;

			case 'ends_with':
				$code = $code
						. '('
						. 'strlen(' . $this->generateConditionRuleValueCode($rule->value, $history) . ')'
						. ' - '
						. 'strlen(' . '$' . 'variables[\'' . $rule->field . '\'])'
						. ')'
						. ' == strrpos('
						. '$' . 'variables[\'' . $rule->field . '\']'
						. ', '
						. $this->generateConditionRuleValueCode($rule->value,$history)
						. ')';
				break;

			case 'not_ends_with':
				$code = $code
					. '('
					. 'strlen(' . $this->generateConditionRuleValueCode($rule->value, $history) . ')'
					. ' - '
					. 'strlen(' . '$' . 'variables[\'' . $rule->field . '\'])'
					. ')'
					. ' != strrpos('
					. '$' . 'variables[\'' . $rule->field . '\']'
					. ', '
					. $this->generateConditionRuleValueCode($rule->value,$history)
					. ')';
				break;

			case 'is_empty':
				$code = $code
						. '$' . 'variables[\'' . $rule->field . '\']'
						. ' == '
						. '\'\'';
				break;

			case 'is_not_empty':
				$code = $code
						. '$' . 'variables[\'' . $rule->field . '\']'
						. ' != '
						. '\'\'';
				break;

			case 'is_null':
				$code = $code
						. '$' . 'variables[\'' . $rule->field . '\']'
						. ' === '
						. 'null';
				break;
				
			case 'is_not_null':
				$code = $code
						. '$' . 'variables[\'' . $rule->field . '\']'
						. ' !== '
						. 'null';
				break;

			case 'is_integer':
				$code = $code
						. 'is_int('
						. '$' . 'variables[\'' . $rule->field . '\']'
						. ')';
				break;

			case 'is_not_integer':
				$code = $code
						. '!is_int('
						. '$' . 'variables[\'' . $rule->field . '\']'
						. ')';
				break;

			case 'is_numeric':
				$code = $code
						. 'is_numeric(' 
						. '$' . 'variables[\'' . $rule->field . '\']'
						. ')';
				break;

			case 'is_not_numeric':
				$code = $code
						. '!is_numeric('
						. '$' . 'variables[\'' . $rule->field . '\']'
						. ')';
				break;

			case 'matching_regex':
				$code = $code
						. 'preg_match('
						. $this->generateConditionRuleValueCode($rule->value, $history)
						. ', '
						. '$' . 'variables[\'' . $rule->field . '\']'
						. ')';
				break;

			case 'not_matching_regex':
				$code = $code
						. '!preg_match('
						. $this->generateConditionRuleValueCode($rule->value, $history)
						. ', '
						. '$' . 'variables[\'' . $rule->field . '\']'
						. ')';
				break;
		}

		$code = $code . ')';
		return $code;
	}

	private function generateConditionRuleValueCode($value, &$history)
	{
		$code = '';
		if ('' == $value)
		{
			return 'null';
		}

		$valueType = intval($value[0]);
		$value = substr($value, 2);

		if (2 == $valueType)
		{
			$history['used_variables'][$value] = 1;
			$code = '$variables[\''
					. $value
					. '\']';
		} else {
			$code = ('\'' . $value . '\'');
		}

		return $code;
	}
}
/* {{@snippet:end_class}} */