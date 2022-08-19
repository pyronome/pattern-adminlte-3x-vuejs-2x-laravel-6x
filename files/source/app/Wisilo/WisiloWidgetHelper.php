<?php

namespace App\Wisilo;
use Illuminate\Support\Facades\Storage;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloCustomVariable;
use App\Wisilo\WisiloCustomVariableValue;
use App\Wisilo\WisiloLayout;

/* {{@snippet:begin_class}} */

class WisiloWidgetHelper
{	
	/* {{@snippet:begin_methods}} */

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

        $objectWisiloLayouts = WisiloLayout::where('deleted', false)
				->where('wisilousergroup_id', $parameters['filters']['group_id'])
				->where('enabled', $parameters['filters']['group_id'])
				->orderBy($parameters['order_by']['property'], $parameters['order_by']['direction'])
				->get();

        if (0 == count($objectWisiloLayouts)) {
            $objectWisiloLayouts = [];
        }

		return $objectWisiloLayouts;
	} */

	public function getUserWidgets($pagename) {
		$currentUser = auth()->guard('wisilouser')->user();

        $objectWisiloLayouts = WisiloLayout::where('deleted', false)
				->where('wisilousergroup_id', $currentUser->wisilousergroup_id)
				->where('pagename', $pagename)
				->orderBy('__order','asc')
				->get();

        if (0 == count($objectWisiloLayouts)) {
            $objectWisiloLayouts = WisiloLayout::where('deleted', false)
				->where('wisilousergroup_id', null)
				->where('pagename', $pagename)
				->orderBy('__order','asc')
				->get();
        }

		return $objectWisiloLayouts;
	}

	public function getUserActiveWidgets($pagename) {
		$currentUser = auth()->guard('wisilouser')->user();

		$objectWisiloLayout = null;
        $objectWisiloLayouts = WisiloLayout::where('deleted', false)
				->where('enabled', true)
				->where('wisilousergroup_id', $currentUser->wisilousergroup_id)
				->where('pagename', $pagename)
				->orderBy('__order','asc')
				->get();

        if (0 == count($objectWisiloLayouts)) {
            $objectWisiloLayouts = WisiloLayout::where('deleted', false)
				->where('wisilousergroup_id', null)
				->where('pagename', $pagename)
				->orderBy('__order','asc')
				->get();
        }

		return $objectWisiloLayouts;
	}

	public function saveConditionalData($layoutId, $conditional_data) {
		if (!empty($conditional_data)) {
			foreach ($conditional_data as $condition_data) {
				$this->createWidgetConditionFunctionFile($layoutId, $condition_data);
			}
		}
	}

	public function calculateConditionVariablesValue($variables, $queryResult, $url_parameters, $request_parameters, $external_parameters) {
		$objectWisilo = new Wisilo();
		$currentUser = auth()->guard('wisilouser')->user();

		foreach ($variables as $key => $value) {
			$keyPart = explode('/', $key);

			if ('GlobalParameters' == $keyPart[0]) {
                $__key = $keyPart[1];
                $partResult = $objectWisilo->getConfigParameterValue($__key);
            } else if ('UserParameters' == $keyPart[0]) {
                $__key = $keyPart[1];
                $partResult = $objectWisilo->getUserConfigParameterValue($__key, 'user', $currentUser->id);
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
				$customVariableValue = $this->getCustomVariableValue($currentUser->wisilousergroup_id, $__key, $queryResult, $url_parameters, $request_parameters, $external_parameters);
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

	public function getCustomVariableValue($wisilousergroup_id, $variableName, $queryResult, $url_parameters, $request_parameters, $external_parameters) {
		$resultValue = '';

		$object = WisiloCustomVariable::where('deleted', 0)
			->where('wisilousergroup_id', $wisilousergroup_id)
			->where('name', $variableName)
			->first();

		if (null !== $object) {
			$resultValue = $this->getVariableCalculatedValue($object, $queryResult, $url_parameters, $request_parameters, $external_parameters);
		} else {
			$object = WisiloCustomVariable::where('deleted', 0)
				->where('wisilousergroup_id', 0)
				->where('name', $variableName)
				->first();

			if (null !== $object) {
				$resultValue = $this->getVariableCalculatedValue($object, $queryResult, $url_parameters, $request_parameters, $external_parameters);
			}
		}

		return $resultValue;
	}

	public function getCustomVariableById($id) {
		return WisiloCustomVariable::where('id', $id)->first();
	}

	public function getCustomVariableByName($variableName, $wisilousergroup_id) {
		$object = WisiloCustomVariable::where('deleted', 0)
            ->where('wisilousergroup_id', $wisilousergroup_id)
            ->where('name', $variableName)
            ->first();

        if (null == $object) {
			$object = WisiloCustomVariable::where('deleted', 0)
				->where('wisilousergroup_id', 0)
				->where('name', $variableName)
				->first();
		}

		return $object;
	}

	public function getVariableCalculatedValue($objCV, $queryResult, $url_parameters, $request_parameters, $external_parameters) {
		$customvariable_key = 'customvariable' . $objCV->id;
		$remember_type = $objCV->remember_type;
		$defaultValue = $objCV->default_value;
		$variableValue = $defaultValue;
		$remember = (1 == $objCV->remember);

		$objectWisilo = new Wisilo();
		$currentUser = auth()->guard('wisilouser')->user();

		$parsed = $objectWisilo->getStringBetween($variableValue, '{{', '}}');

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
					if ($__key == '__storage_url') {
						$partResult = asset('storage/')/*  . '/' */;
					} else {
						$partResult = $objectWisilo->getConfigParameterValue($__key);
					}
				} else if ('UserParameters' == $textPart[0]) {
					$__key = $textPart[1];
					$partResult = $objectWisilo->getUserConfigParameterValue($__key, 'user', $currentUser->id);
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
				} else if ('ExternalParameters' == $textPart[0]) {
					$__key = $textPart[1];
					$partResult = isset($external_parameters[$__key]) 
						? $external_parameters[$__key]
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
						$object = WisiloCustomVariableValue::where('deleted', 0)
							->where('customvariable_id', $objCV->id)
							->where('wisilousergroup_id', $objCV->wisilousergroup_id)
							->first();
			
						if (null !== $object) {
							$partResult = $object->value;
						}
					}
				}

				$variableValue = str_replace($parsedWithMustache, $partResult, $variableValue);
				$temp_text = $variableValue;
				$parsed = $objectWisilo->getStringBetween($temp_text, '{{', '}}');
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
						$object = WisiloCustomVariableValue::where('deleted', 0)
							->where('customvariable_id', $objCV->id)
							->where('wisilousergroup_id', $objCV->wisilousergroup_id)
							->first();
			
						if (null !== $object) {
							$variableValue = $object->value;
						} /* else {
							$variableValue = isset($queryResult[$customvariable_key]) 
								? $queryResult[$customvariable_key] 
								: $variableValue;
	
							$object = new WisiloCustomVariableValue();
							$object->customvariable_id = $objCV->id;
							$object->wisilousergroup_id = $objCV->wisilousergroup_id;
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
					$object = WisiloCustomVariableValue::where('deleted', 0)
						->where('customvariable_id', $objCV->id)
						->where('wisilousergroup_id', $objCV->wisilousergroup_id)
						->first();
		
					if (null !== $object) {
						$object->value = $variableValue;
						$object->save();
					} else {
						$object = new WisiloCustomVariableValue();
						$object->customvariable_id = $objCV->id;
						$object->wisilousergroup_id = $objCV->wisilousergroup_id;
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

	/* public function __setWhereSQL(&$where_SQL, $conditions) {
		$history = [];
		$sql = '';

		if (!empty($conditions)) {
			foreach ($conditions as $condition_data) {
				$condition = (object) $condition_data['condition'];

				if ('' != $sql) {
					$sql = $sql . ' AND ';
				}

				$sql = $sql . $this->__sql__generateWhereConditionCode($where_SQL, $condition, $history);
			}
		}
		
		if ('' == $sql) {
			$where_SQL = ' WHERE 1';
		} else {
			$where_SQL = ' HAVING ' . $sql;
		}
		
		return;
	} */

	public function __setWhereSQL(&$where_SQL, $searchSQL, $conditions) {
		$history = [];
		$sql = '';

		if (!empty($conditions)) {
			foreach ($conditions as $condition_data) {
				$condition = (object) $condition_data['condition'];

				if ('' != $sql) {
					$sql = $sql . ' AND ';
				}

				$sql = $sql . $this->__sql__generateWhereConditionCode($where_SQL, $condition, $history);
			}
		}

		// no search no condition
		if (('' == $searchSQL) && ('' == $sql)) {
			$where_SQL = ' WHERE 1';
			return;
		}

		// both search and condition
		if (('' != $searchSQL) && ('' != $sql)) {
			$where_SQL = ' HAVING ((' . $searchSQL . ') AND ' . $sql . ')';
			return;
		}
		
		// only search
		if (('' != $searchSQL) && ('' == $sql)) {
			$where_SQL = ' HAVING (' . $searchSQL . ')';
			return;
		}

		// only condition
		$where_SQL = ' HAVING (' . $sql . ')';
		return;
	}

	private function __sql__generateWhereConditionCode(&$where_SQL, $condition_data, &$history)
	{
		
		$operationHeaderCode = '';

		if (isset($condition_data->condition))
		{
			$operationHeaderCode = $operationHeaderCode . $this->__sql__generateConditionCode(
					$condition_data->rules,
					$history,
					$condition_data->condition);
		}

		return $operationHeaderCode;
	}

	private function __sql__generateConditionCode($rules, &$history, $condition = 'AND')
	{
		$ruleCount = count($rules);
		$rule = null;
		$andOr = ' AND ';

		if ($condition[0] == 'A')
		{
			$andOr = ' AND ';
		} else {
			$andOr = ' OR ';
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
				$code .= $this->__sql__generateConditionCode(
						$rule->rules,
						$history,
						$rule->condition);
			} else {
				$code .= $this->__sql__generateConditionRuleCode(
						(object) $rule,
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

	private function __sql__generateConditionRuleCode($rule, &$history)
	{
		$code = '';

		if (!isset($rule->field))
		{
			return $code;
		}

		$history['used_variables'][$rule->field] = 1;

		$fieldName = 'customvariable' . $rule->id;

		$code = '(';
		
		switch ($rule->operator)
		{
			case 'equal':
				$ruleValue = $this->__sql__generateConditionRuleValueCode($rule->value, $history);
				$ruleValue = '\'' . $ruleValue . '\'';

				$code = $code 
					. $fieldName
					. ' = '
					. $ruleValue;
				break;

			case 'not_equal':
				$ruleValue = $this->__sql__generateConditionRuleValueCode($rule->value, $history);
				$ruleValue = '\'' . $ruleValue . '\'';

				$code = $code 
					. $fieldName
					. ' != '
					. $ruleValue;
				break;

			case 'contains':
				$ruleValue = $this->__sql__generateConditionRuleValueCode($rule->value, $history);
				$ruleValue = '\'%' . $ruleValue . '%\'';

				$code = $code 
					. $fieldName
					. ' like '
					. $ruleValue;
				break;

			case 'not_contains':
				$ruleValue = $this->__sql__generateConditionRuleValueCode($rule->value, $history);
				$ruleValue = '\'%' . $ruleValue . '%\'';

				$code = $code 
					. $fieldName
					. ' not like '
					. $ruleValue;
				break;

			case 'in':
				$ruleValue = $this->__sql__generateConditionRuleValueCode($rule->value, $history);
				$ruleValue = '(' . $ruleValue . ')';

				$code = $code 
					. $fieldName
					. ' in '
					. $ruleValue;
				break;

			case 'not_in':
				$ruleValue = $this->__sql__generateConditionRuleValueCode($rule->value, $history);
				$ruleValue = '(' . $ruleValue . ')';

				$code = $code 
					. $fieldName
					. ' in '
					. $ruleValue;
				break;

			case 'less':
				$ruleValue = $this->__sql__generateConditionRuleValueCode($rule->value, $history);
				$ruleValue = '\'' . $ruleValue . '\'';

				$code = $code 
					. $fieldName
					. ' < '
					. $ruleValue;
				break;

			case 'less_or_equal':
				$ruleValue = $this->__sql__generateConditionRuleValueCode($rule->value, $history);
				$ruleValue = '\'' . $ruleValue . '\'';

				$code = $code 
					. $fieldName
					. ' <= '
					. $ruleValue;
				break;

			case 'greater':
				$ruleValue = $this->__sql__generateConditionRuleValueCode($rule->value, $history);
				$ruleValue = '\'' . $ruleValue . '\'';

				$code = $code 
					. $fieldName
					. ' > '
					. $ruleValue;
				break;
	
			case 'greater_or_equal':
				$ruleValue = $this->__sql__generateConditionRuleValueCode($rule->value, $history);
				$ruleValue = '\'' . $ruleValue . '\'';

				$code = $code 
					. $fieldName
					. ' >= '
					. $ruleValue;
				break;

			case 'begins_with':
				$ruleValue = $this->__sql__generateConditionRuleValueCode($rule->value, $history);
				$ruleValue = '\'' . $ruleValue . '%\'';

				$code = $code 
					. $fieldName
					. ' like '
					. $ruleValue;
				break;

			case 'not_begins_with':
				$ruleValue = $this->__sql__generateConditionRuleValueCode($rule->value, $history);
				$ruleValue = '\'' . $ruleValue . '%\'';

				$code = $code 
					. $fieldName
					. ' not like '
					. $ruleValue;
				break;

			case 'ends_with':
				$ruleValue = $this->__sql__generateConditionRuleValueCode($rule->value, $history);
				$ruleValue = '\'%' . $ruleValue . '\'';

				$code = $code 
					. $fieldName
					. ' like '
					. $ruleValue;
				break;

			case 'not_ends_with':
				$ruleValue = $this->__sql__generateConditionRuleValueCode($rule->value, $history);
				$ruleValue = '\'%' . $ruleValue . '\'';

				$code = $code 
					. $fieldName
					. ' not like '
					. $ruleValue;
				break;

			case 'is_empty':
				$code = $code 
					. $fieldName
					. ' = \'\'';
				break;

			case 'is_not_empty':
				$code = $code 
					. $fieldName
					. ' != \'\'';
				break;

			case 'is_null':
				$code = $code 
					. $fieldName
					. ' is null';
				break;
				
			case 'is_not_null':
				$code = $code 
					. $fieldName
					. ' is not null';
				break;

			case 'is_integer':
				$code = $code
					. ' (' . $fieldName
					. ' regexp \'^[-+]?[0-9]+$\''
					. ') = 1 ';
				break;

			case 'is_not_integer':
				$code = $code
					. ' ((' . $fieldName . ' regexp \'^[-+]?[0-9]+$\') is null)'
					. ' OR '
					. ' ((' . $fieldName . ' regexp \'^[-+]?[0-9]+$\') = 0)';
				break;

			case 'is_numeric':
				$code = $code
					. ' (' . $fieldName
					. ' regexp \'^[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?$\''
					. ') = 1 ';
				break;

			case 'is_not_numeric':
				$code = $code
					. ' ((' . $fieldName . ' regexp \'^[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?$\') is null)'
					. ' OR '
					. ' ((' . $fieldName . ' regexp \'^[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?$\') = 0)';
				break;

			case 'matching_regex':
				$ruleValue = $this->__sql__generateConditionRuleValueCode($rule->value, $history);
				$regex = '\'' . $ruleValue . '\'';

				$code = $code
					. ' (' . $fieldName
					. ' regexp ' . $ruleValue
					. ') = 1 ';
				break;

			case 'not_matching_regex':
				$ruleValue = $this->__sql__generateConditionRuleValueCode($rule->value, $history);
				$regex = '\'' . $ruleValue . '\'';

				$code = $code
					. ' ((' . $fieldName . ' regexp ' . $regex . ') is null)'
					. ' OR '
					. ' ((' . $fieldName . ' regexp ' . $regex . ') = 0)';
				break;
		}

		$code = $code . ')';

		return $code;
	}

	private function __sql__generateConditionRuleValueCode($value, &$history)
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
			$code = '{{__custom_variable__/' . $value . '}}';
		} else {
			$code = $value;
		}

		return $code;
	}

	public function convertCustomVariableNameToIdSyntax($text) {
		$objectWisilo = new Wisilo();
        $parsed = $objectWisilo->getStringBetween($text, '{{', '}}');
		$breakWhile = false;

		while (!$breakWhile && (strlen($parsed) > 0)) {
            $parsedWithMustache = '{{' . $parsed . '}}';
			$idSyntaxMasked = $parsedWithMustache;
			$mas = '{{' . $parsed . '}}';
			$partResult = '';

			$textPart = explode('/', $parsed);

            if ('CustomVariables' == $textPart[0]) {
                $__key = $textPart[1];
                $customVariableId = $this->getCustomVariableId($__key);
                
				$idSyntaxMasked = '#_#__#___#' . $parsed . '#___#__#_#'; // aynı kalsın
				if (0 != $customVariableId) {
					$idSyntaxMasked = '#_#__#___#__custom_variable__/' . $customVariableId . '#___#__#_#';
				}
            }

            $text = str_replace($parsedWithMustache, $idSyntaxMasked, $text);
			$temp_text = $text;
			$parsed = $objectWisilo->getStringBetween($temp_text, '{{', '}}');

			if (!strpos($temp_text, '{{CustomVariables')) {
				$breakWhile = true;
			}
		} // while (strlen($parsed) > 0) {

		$text = str_replace('#_#__#___#', '{{', $text);
		$text = str_replace('#___#__#_#', '}}', $text);

        return htmlspecialchars_decode($text);
	}

	public function getCustomVariableId($variableName) {
		$currentUser = auth()->guard('wisilouser')->user();

		$variableId = 0;

		$object = WisiloCustomVariable::where('deleted', 0)
            ->where('wisilousergroup_id', 0)
            ->where('name', $variableName)
            ->first();

        if (null !== $object) {
            $variableId = $object->id;
        } else {
			$object = WisiloCustomVariable::where('deleted', 0)
				->where('wisilousergroup_id', $currentUser->wisilousergroup_id)
				->where('name', $variableName)
				->first();

			if (null !== $object) {
				$variableId = $object->id;
			}
		}

		return $variableId;
	}
	/* {{@snippet:end_methods}} */
}
/* {{@snippet:end_class}} */