<?php

namespace App\AdminLTE;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\AdminLTE\AdminLTEUser;
use App\AdminLTE\AdminLTELayout;
use App\AdminLTE\AdminLTEUserLayout;
use App\AdminLTE\AdminLTEUserGroup;
use App\AdminLTE\AdminLTEModelDisplayText;
use App\AdminLTE\AdminLTEModelOption;
use PDO;

/* {{snippet:begin_class}} */

class AdminLTE
{

	/* {{snippet:begin_properties}} */

	/* {{snippet:end_properties}} */

	/* {{snippet:begin_methods}} */
	
	public function convertNameToFileName($strName) {

	    $urlbrackets    = '\[\]\(\)';
	    $urlspacebefore = ':;\'_\*%@&?!' . $urlbrackets;
	    $urlspaceafter  = '\.,:;\'\-_\*@&\/\\\\\?!#' . $urlbrackets;
	    $urlall         = '\.,:;\'\-_\*%@&\/\\\\\?!#' . $urlbrackets;
	 
	    $specialquotes  = '\'"\*<>';
	 
	    $fullstop       = '\x{002E}\x{FE52}\x{FF0E}';
	    $comma          = '\x{002C}\x{FE50}\x{FF0C}';
	    $arabsep        = '\x{066B}\x{066C}';
	    $numseparators  = $fullstop . $comma . $arabsep;
	 
	    $numbersign     = '\x{0023}\x{FE5F}\x{FF03}';
	    $percent        = '\x{066A}\x{0025}\x{066A}\x{FE6A}\x{FF05}\x{2030}\x{2031}';
	    $prime          = '\x{2032}\x{2033}\x{2034}\x{2057}';
	    $nummodifiers   = $numbersign . $percent . $prime;
	 
		$strReturnValue = $strName;

		$strReturnValue = str_replace('<br>', '', $strReturnValue);
		$strReturnValue = str_replace('<br/>', '', $strReturnValue);
		$strReturnValue = str_replace('<BR/>', '', $strReturnValue);
		$strReturnValue = str_replace('<BR>', '', $strReturnValue);
		$strReturnValue = str_replace('Ç', 'c', $strReturnValue);
		$strReturnValue = str_replace('ç', 'c', $strReturnValue);
		$strReturnValue = str_replace('Ý', 'i', $strReturnValue);
		$strReturnValue = str_replace('ý', 'i', $strReturnValue);
		$strReturnValue = str_replace('I', 'i', $strReturnValue);
		$strReturnValue = str_replace('İ', 'i', $strReturnValue);
		$strReturnValue = str_replace('ı', 'i', $strReturnValue);
		$strReturnValue = str_replace('Ð', 'g', $strReturnValue);
		$strReturnValue = str_replace('ð', 'g', $strReturnValue);
		$strReturnValue = str_replace('Ğ', 'g', $strReturnValue);
		$strReturnValue = str_replace('ğ', 'g', $strReturnValue);
		$strReturnValue = str_replace('Ö', 'o', $strReturnValue);
		$strReturnValue = str_replace('ö', 'o', $strReturnValue);
		$strReturnValue = str_replace('Þ', 's', $strReturnValue);
		$strReturnValue = str_replace('þ', 's', $strReturnValue);
		$strReturnValue = str_replace('ş', 's', $strReturnValue);
		$strReturnValue = str_replace('Ş', 's', $strReturnValue);
		$strReturnValue = str_replace('Ü', 'u', $strReturnValue);
		$strReturnValue = str_replace('ü', 'u', $strReturnValue);
		$strReturnValue = str_replace('"', '_', $strReturnValue);
		$strReturnValue = str_replace('\'', '', $strReturnValue);
		$strReturnValue = str_replace('?', '_', $strReturnValue);
		$strReturnValue = str_replace(':', '_', $strReturnValue);
		$strReturnValue = str_replace('/', '_', $strReturnValue);
		$strReturnValue = str_replace('!', '_', $strReturnValue);
		$strReturnValue = str_replace(',', '_', $strReturnValue);
		$strReturnValue = str_replace('(', '_', $strReturnValue);
		$strReturnValue = str_replace(')', '_', $strReturnValue);
		$strReturnValue = str_replace('-', '_', $strReturnValue);
		$strReturnValue = str_replace('.', '_', $strReturnValue);
		$strReturnValue = str_replace('+', '_', $strReturnValue);
		$strReturnValue = str_replace('*', '_', $strReturnValue);
		$strReturnValue = str_replace('#', '_', $strReturnValue);
		$strReturnValue = str_replace(' ', '_', $strReturnValue);
		$strReturnValue = str_replace('__', '_', $strReturnValue);
		$strReturnValue = strtolower($strReturnValue);
	    
	    return $strReturnValue;
	}
	
	public function validateEmailAddress($email)
	{

		$isValid = true;
		$atIndex = strrpos($email, "@");

		if (is_bool($atIndex) && !$atIndex)
		{
			$isValid = false;
		}
		else
		{
			$domain = substr($email, $atIndex+1);
			$local = substr($email, 0, $atIndex);
			$localLen = strlen($local);
			$domainLen = strlen($domain);
			
			if ($localLen < 1 || $localLen > 64)
			{
				// local part length exceeded
				$isValid = false;
			}
			else if ($domainLen < 1 || $domainLen > 255)
			{
				// domain part length exceeded
				$isValid = false;
			}
			else if ($local[0] == '.' || $local[$localLen-1] == '.')
			{
				// local part starts or ends with '.'
				$isValid = false;
			}
			else if (preg_match('/\\.\\./', $local))
			{
				// local part has two consecutive dots
				$isValid = false;
			}
			else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
			{
				// character not valid in domain part
				$isValid = false;
			}
			else if (preg_match('/\\.\\./', $domain))
			{
				// domain part has two consecutive dots
				$isValid = false;
			}
			else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
					str_replace("\\\\","",$local)))
			{
				// character not valid in local part unless 
				// local part is quoted
				if (!preg_match('/^"(\\\\"|[^"])+"$/',
						str_replace("\\\\","",$local)))
				{
					$isValid = false;
				} // if (!preg_match('/^"(\\\\"|[^"])+"$/',
			} // if ($localLen < 1 || $localLen > 64) {
		} // if (is_bool($atIndex) && !$atIndex) {

		return $isValid;

	}
	
	public function resetUserPassword($objectAdminLTEUser) {
		$arrCities = array("shanghai",
				"istanbul",
				"karachi",
				"mumbai",
				"beijing",
				"moscow",
				"saopaulo",
				"tianjin",
				"guangzhou",
				"delhi",
				"seoul",
				"shenzhen",
				"jakarta",
				"tokyo",
				"mexicocity",
				"kinshasa",
				"tehran",
				"bangalore",
				"dongguan",
				"newyorkcity",
				"lagos",
				"london",
				"lima",
				"bogota",
				"hochiminhcity",
				"hongkong",
				"bangkok",
				"dhaka",
				"hyderabad",
				"cairo",
				"hanoi",
				"wuhan",
				"riodejaneiro",
				"lahore",
				"ahmedabad",
				"baghdad",
				"riyadh",
				"singapore",
				"santiago",
				"saintpetersburg",
				"chennai",
				"chongqing",
				"kolkata",
				"surat",
				"yangon",
				"ankara",
				"alexandria",
				"shenyang",
				"suzhou",
				"newtaipei",
				"johannesburg",
				"losangeles",
				"yokohama",
				"abidjan",
				"busan",
				"berlin",
				"capetown",
				"durban",
				"jeddah",
				"pyongyang",
				"madrid",
				"nairobi",
				"pune",
				"jaipur",
				"casablanca");

		$strNewPassword = $arrCities[rand(0, 64)] . date('si');
		$objectAdminLTEUser->password = bcrypt($strNewPassword);
		$objectAdminLTEUser->save();

		return $strNewPassword;
	}

	public function getUserPreferences() {
		$currentUser = auth()->guard('adminlteuser')->user();

		$preferences = [
			'main-header_border-bottom-0' => 0,
		    'body_text-sm' => 0,
		    'main-header_text-sm' => 0,
		    'nav-sidebar_text-sm' => 0,
		    'main-footer_text-sm' => 0,
		    'nav-sidebar_nav-flat' => 0,
		    'nav-sidebar_nav-legacy' => 0,
		    'nav-sidebar_nav-compact' => 0,
		    'nav-sidebar_nav-child-indent' => 0,
		    'main-sidebar_sidebar-no-expand' => 0,
		    'brand-link_text-sm' => 0,
		    'navbar_variants' => 'navbar-white navbar-light',
		    'accent_variants' => '',
		    'sidebar_variants' => 'sidebar-dark-primary',
		    'logo_variants' => '',
		];

		if ($currentUser != null)
		{
			$layout = AdminLTEUserLayout::where('deleted', false)
					->where('adminlteuser_id', $currentUser['id'])
					->where('pagename', 'preferences')
					->first();
			
			if (null != $layout)
			{
				$preferences = json_decode($this->base64decode($layout->widgets), (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));
			} // if (null == $layout)			
		} // if ($currentUser != null)

		return $preferences;
	}

	public function getCustomization() {
		$preferences = $this->getUserPreferences();

		$values = array();
		$values['main-header'] = '';
		$values['body'] = '';
		$values['nav-sidebar'] = '';
		$values['main-sidebar'] = '';
		$values['brand-link'] = '';
		$values['main-footer'] = '';

		if (1 == $preferences['main-header_border-bottom-0']) {
			$values['main-header'] .= ' border-bottom-0 ';
		}

		if (1 == $preferences['main-header_text-sm']) {
			$values['main-header'] .= ' text-sm ';
		}

		if ('' != $preferences['navbar_variants']) {
			$values['main-header'] .= $preferences['navbar_variants'];
		}

		if (1 == $preferences['body_text-sm']) {
			$values['body'] .= ' text-sm ';
		}

		if (1 == $preferences['nav-sidebar_text-sm']) {
			$values['nav-sidebar'] .= ' text-sm ';
		}

		if (1 == $preferences['nav-sidebar_nav-flat']) {
			$values['nav-sidebar'] .= ' nav-flat ';
		}

		if (1 == $preferences['nav-sidebar_nav-legacy']) {
			$values['nav-sidebar'] .= ' nav-legacy ';
		}

		if (1 == $preferences['nav-sidebar_nav-compact']) {
			$values['nav-sidebar'] .= ' nav-compact ';
		}

		if (1 == $preferences['nav-sidebar_nav-child-indent']) {
			$values['nav-sidebar'] .= ' nav-child-indent ';
		}

		if (1 == $preferences['main-sidebar_sidebar-no-expand']) {
			$values['main-sidebar'] .= ' sidebar-no-expand ';
		}
 
		if (1 == $preferences['brand-link_text-sm']) {
			$values['brand-link'] .= ' text-sm ';
		}

		if ('' != $preferences['accent_variants']) {
			$values['body'] .= $preferences['accent_variants'];
		}

		if ('' != $preferences['sidebar_variants']) {
			$values['main-sidebar'] .= $preferences['sidebar_variants'];
		}

		if ('' != $preferences['logo_variants']) {
			$values['brand-link'] .= $preferences['logo_variants'];
		}

		if (1 == $preferences['main-footer_text-sm']) {
			$values['main-footer'] .= ' text-sm ';
		}

		return $values;
	}

	public function getUserData()
	{
        $adminLTEUser = auth()->guard('adminlteuser')->user();

		$image_path = '/assets/adminlte/img/default-user-image.png';

		$userData = [
			'id' => 0,
			'deleted' => false,
			'created_at' => 0,
			'updated_at' => 0,
			'enabled' => true,
			'adminlteusergroup_id' => 0,
			'type' => 'user',
			'email' => '',
			'username' => '',
			'name' => '',
			'fullname' => '',
			'menu_permission' => '',
			'service_permission' => '',
			'widget_permission' => '',
			'image' => $image_path
		];

		if ($adminLTEUser != null) {

			$userType = 'user';

			if (1 == $adminLTEUser->id) {
				$userType = 'root';
			} // if (1 == $adminLTEUser->id) {

			
			$arr_files = $this->get_model_files_by_property('AdminLTEUser', $adminLTEUser->id, 'profile_img');
			
			if (count($arr_files) > 0) {
				$fileDetail = $arr_files[0];
				$image_path = asset('storage/' . $fileDetail['path']);
			}

			$userData = [
				'id' => $adminLTEUser->id,
				'deleted' => $adminLTEUser->deleted,
				'created_at' => $adminLTEUser->created_at->timestamp,
				'updated_at' => $adminLTEUser->updated_at->timestamp,
				'enabled' => $adminLTEUser->enabled,
				'adminlteusergroup_id' => $adminLTEUser->adminlteusergroup_id,
				'type' => $userType,
				'email' => $adminLTEUser->email,
				'username' => $adminLTEUser->username,
				'name' => $adminLTEUser->fullname,
				'fullname' => $adminLTEUser->fullname,
				'menu_permission' => $this->getUserMenuPermission($adminLTEUser),
				'service_permission' => $this->getUserServicePermission($adminLTEUser),
				'widget_permission' => '',
				'image' => $image_path
			];

            $adminLTEUserGroup = AdminLTEUserGroup::find(
					$adminLTEUser->adminlteusergroup_id);

			if ($adminLTEUserGroup != null)
			{
				$userData['widget_permission']
						= $adminLTEUserGroup->widget_permission;
			} // if ($adminLTEUserGroup != null)

		} // if ($adminLTEUser == null) {

		return $userData;
	}

	public function getAdminLTEFolder()
	{
        $adminLTEFolder = config('adminlte.main_folder');

        if ($adminLTEFolder === false
				|| ('' == $adminLTEFolder))
        {
            $adminLTEFolder = 'adminlte';
        } // if ($adminLTEFolder === false) {

		$adminLTEFolder = rtrim($adminLTEFolder, '/') . '/';

		return $adminLTEFolder; 
	}

	public function getSideMenu($forceDefault = false)
	{

		if (!$forceDefault
				&& Storage::disk('local')->exists('adminlte_menu.json'))
		{
			$menuArray = json_decode(Storage::disk('local')->get('adminlte_menu.json'),
					(JSON_HEX_QUOT
					| JSON_HEX_TAG
					| JSON_HEX_AMP
					| JSON_HEX_APOS));
		}
		else
		{
			$menuArray = json_decode(config('adminlte_menu_json'),
					(JSON_HEX_QUOT
					| JSON_HEX_TAG
					| JSON_HEX_AMP
					| JSON_HEX_APOS));

		} // if (!$forceDefault

		$Menu = array();
		$main_index = 0;

		$countMenuArray = count($menuArray);

		for ($i=0; $i < $countMenuArray; $i++) { 
			if (1 == $menuArray[$i]['visibility']) {
				if (!isset($menuArray[$i]['children'])) {
					$Menu[$main_index]['id'] = 'p' . $i;
					$Menu[$main_index]['permission_token'] = $menuArray[$i]['href'];
					$Menu[$main_index]['url'] = $menuArray[$i]['href'];
					$Menu[$main_index]['href'] = $menuArray[$i]['href'];
					$Menu[$main_index]['title'] = $menuArray[$i]['text'];
					
					$icon = $menuArray[$i]['icon'];
					if ('empty' == $icon) {
						$icon = 'far fa-circle';
					}

					$Menu[$main_index]['icon'] = $icon;
					$Menu[$main_index]['children'] = array();
					$main_index++;
				} else {
					$Menu[$main_index]['id'] = 'p' . $i;
					$Menu[$main_index]['permission_token'] = '';
					$Menu[$main_index]['url'] = '';
					$Menu[$main_index]['href'] = '';
					$Menu[$main_index]['title'] = $menuArray[$i]['text'];
					
					$icon = $menuArray[$i]['icon'];
					if ('empty' == $icon) {
						$icon = 'fas fa-list';
					}

					$Menu[$main_index]['icon'] = $icon;
					$Menu[$main_index]['children'] = array();
					$parentPermissionToken = '';
					$childrenMenu = array();
					$sub_index = 0;

					$subMenuArray = $menuArray[$i]['children'];
					$countSubmenuArray = count($subMenuArray);
					for ($j=0; $j < $countSubmenuArray; $j++) {
						$parentPermissionToken .= $subMenuArray[$j]['href'];
						if (1 == $subMenuArray[$j]['visibility']) {
							$childrenMenu[$sub_index]['id'] = 'c' . $j;
							$childrenMenu[$sub_index]['permission_token'] = $subMenuArray[$j]['href'];
							$childrenMenu[$sub_index]['url'] = $subMenuArray[$j]['href'];
							$childrenMenu[$sub_index]['href'] = $subMenuArray[$j]['href'];
							$childrenMenu[$sub_index]['title'] = $subMenuArray[$j]['text'];

							$icon = $subMenuArray[$j]['icon'];
							if ('empty' == $icon) {
								$icon = 'far fa-circle';
							}

							$childrenMenu[$sub_index]['icon'] = $icon;
							$childrenMenu[$sub_index]['children'] = array();
							$sub_index++;
						} // if($subMenus[$j]['visibility']) {
					} // for ($j=0; $j < $countSubmenuArray; $j++) {

					$Menu[$main_index]['children'] = $childrenMenu;
					$Menu[$main_index]['permission_token'] = $parentPermissionToken;
					$main_index++;
				} // if (0 == count($menuArray[$i]['subMenus'])) {
			} // if ($menuArray[$i]['visibility']) {
		} // for ($i=0; $i < $countMenuArray; $i++) { 

		return $Menu;

	}

	public function getUserMenuPermission($adminLTEUser)
	{
		$permissions = [];
		$joinedPermissions = []; // join group and user permissions
		
		// group permissions
		// add group permissions

		$adminLTEUserGroup = AdminLTEUserGroup::find(
				$adminLTEUser->adminlteusergroup_id);
		
		$decodedPermission = '';

		if ($adminLTEUserGroup != null)
		{
			$decodedPermission = $this->base64Decode(
					$adminLTEUserGroup->menu_permission);
		} // if ($adminLTEUserGroup != null)

		if ('' != $decodedPermission)
		{
			$permissionKeys = explode(',', $decodedPermission);
			$countKeys = count($permissionKeys);

			for ($i=0; $i < $countKeys; $i++)
			{ 
				if (!isset($joinedPermissions[$permissionKeys[$i]]))
				{
					$joinedPermissions[$permissionKeys[$i]] = 1;
				} // if (!isset($joinedPermissions[$permissionKeys[$i]]))
			} // for ($i=0; $i < $countKeys; $i++)
		} // if ('' != $decodedPermission)
		
		// user permissions
		// remove group permissions
		$decodedPermission = $this->base64Decode(
				$adminLTEUser->menu_permission);

		if ('' != $decodedPermission)
		{
			$permissionKeys = explode(',', $decodedPermission);
			$countKeys = count($permissionKeys);

			for ($i=0; $i < $countKeys; $i++)
			{
				$key = $permissionKeys[$i];

				if (false !== strpos($key, '/l'))
				{
					$group_view_key = str_replace('/l', '/v', $key);
					
					if (isset($joinedPermissions[$group_view_key]))
					{
						unset($joinedPermissions[$group_view_key]);
					} // if (isset($joinedPermissions[$group_view_key]))

					$group_edit_key = str_replace('/l', '/e', $key);

					if (isset($joinedPermissions[$group_edit_key]))
					{
						unset($joinedPermissions[$group_edit_key]);
					} // if (isset($joinedPermissions[$group_edit_key]))
				} // if (false !== strpos($key, '/l'))
			} // for ($i=0; $i < $countKeys; $i++)

			// add user permissions
			for ($i=0; $i < $countKeys; $i++)
			{
				$key = $permissionKeys[$i];	

				if (!isset($joinedPermissions[$key]))
				{
					$joinedPermissions[$key] = 1;
				} // if (!isset($joinedPermissions[$key]))
			} // for ($i=0; $i < $countKeys; $i++)
		} // if ('' != $decodedPermission)

		$permissions = array_keys($joinedPermissions);
		
		return $permissions;
	}

	public function getUserServicePermission($adminLTEUser)
	{
		$permissions = [];
		$joinedPermissions = []; // join group and user permissions
		
		// group permissions
		// add group permissions

		$adminLTEUserGroup = AdminLTEUserGroup::find(
				$adminLTEUser->adminlteusergroup_id);
		
		$decodedPermission = '';

		if ($adminLTEUserGroup != null)
		{
			$decodedPermission = $this->base64Decode(
					$adminLTEUserGroup->service_permission);
		} // if ($adminLTEUserGroup != null)

		if ('' != $decodedPermission)
		{
			$permissionKeys = explode(',', $decodedPermission);
			$countKeys = count($permissionKeys);

			for ($i=0; $i < $countKeys; $i++)
			{ 
				if (!isset($joinedPermissions[$permissionKeys[$i]]))
				{
					$joinedPermissions[$permissionKeys[$i]] = 1;
				} // if (!isset($joinedPermissions[$permissionKeys[$i]]))
			} // for ($i=0; $i < $countKeys; $i++)
		} // if ('' != $decodedPermission)
		
		// user permissions
		// remove group permissions
		$decodedPermission = $this->base64Decode(
				$adminLTEUser->service_permission);

		if ('' != $decodedPermission)
		{
			$permissionKeys = explode(',', $decodedPermission);
			$countKeys = count($permissionKeys);

			for ($i=0; $i < $countKeys; $i++)
			{
				$key = $permissionKeys[$i];

				if (false !== strpos($key, '/l'))
				{
					$group_get_key = str_replace('/l', '/g', $key);
					
					if (isset($joinedPermissions[$group_get_key]))
					{
						unset($joinedPermissions[$group_get_key]);
					} // if (isset($joinedPermissions[$group_get_key]))

					$group_post_key = str_replace('/l', '/p', $key);

					if (isset($joinedPermissions[$group_post_key]))
					{
						unset($joinedPermissions[$group_post_key]);
					} // if (isset($joinedPermissions[$group_post_key]))

					$group_delete_key = str_replace('/l', '/d', $key);

					if (isset($joinedPermissions[$group_delete_key]))
					{
						unset($joinedPermissions[$group_delete_key]);
					} // if (isset($joinedPermissions[$group_delete_key]))
				} // if (false !== strpos($key, '/l'))
			} // for ($i=0; $i < $countKeys; $i++)

			// add user permissions
			for ($i=0; $i < $countKeys; $i++)
			{
				$key = $permissionKeys[$i];	

				if (!isset($joinedPermissions[$key]))
				{
					$joinedPermissions[$key] = 1;
				} // if (!isset($joinedPermissions[$key]))
			} // for ($i=0; $i < $countKeys; $i++)
		} // if ('' != $decodedPermission)

		$permissions = array_keys($joinedPermissions);
		
		return $permissions;
	}

	public function base64Decode($data)
	{
		$formattedData = '';

		if ('' != $data) {
			$formattedData = implode(',', unserialize(base64_decode($data)));
		}

		return $formattedData;
	}

	public function base64Encode($data)
	{
		$arrayData = array();

		if ('' != $data) {
			$arrayData = explode(',', $data);
		}
		
		return base64_encode(serialize($arrayData));
	}

	public function getModelList($exceptions = [])
	{
		if (0 == count($exceptions))
		{
			$exceptions = [
				'AdminLTE',
				'AdminLTELayout',
				'AdminLTEModelDisplayText',
				'AdminLTEUserLayout',
				'AdminLTEVariable',
				'HTMLDB',
				'User'
			];
		} // if (0 == count($exceptions))
		
		$Models = array();
		$index = 0;

		$path = dirname(dirname(__FILE__));
		if (is_dir($path))
		{ 
			$files = scandir($path);

			foreach ($files as $file)
			{ 
				if (($file != ".") && ($file != ".."))
				{
					$current_path = ($path . "/" . $file);

					if (is_dir($current_path))
					{
						continue;
					} // if (is_dir($current_path))

					$file_name = basename($current_path);

					$extension = pathinfo($file_name, PATHINFO_EXTENSION);
					$extension = '.' . $extension;

					$file_name = str_replace($extension, '', $file_name);
					
					if (!in_array($file_name, $exceptions))
					{
						$Models[] = $file_name;
					} // if (!in_array($file_name, $exceptions))
				} // if (($file != ".") && ($file != "..")) {
			} // foreach ($files as $file) {
		} // if (is_dir($path))

		return $Models;
	}

	public function getPageLayout($pageName)
	{

		$Widgets = array();

		$layout = AdminLTELayout::where('deleted', false)
				->where('pagename', $pageName)
				->orderBy('id', 'DESC')
				->first();
		
		if (null == $layout)
		{
			return $Widgets;
		} // if (null == $layout)

		$defaultWidgets = json_decode(
				$this->base64decode($layout->widgets),
				(JSON_HEX_QUOT
				| JSON_HEX_TAG
				| JSON_HEX_AMP
				| JSON_HEX_APOS));

		$userWidgetsFormatted = $this->getUserWidgetsFormatted($pageName);

		$countWidgets = count($defaultWidgets);
		$emptyIndex = 0;
		$emptyIndexHistory = array();

		for ($i=0; $i < $countWidgets; $i++) { 
			$defaultWidget = $defaultWidgets[$i];

			$type = $defaultWidget['type'];
			$model = $defaultWidget['model'];

			if ('empty' == $type) {
				$userWidgetIndex = $type.$emptyIndex;
				$emptyIndex++;

				$emptyIndexHistory[] = $userWidgetIndex;
			} else {
				$userWidgetIndex = $type.$model;
			}
			
			if (!isset($userWidgetsFormatted[$userWidgetIndex])) {
				/*if ('empty' != $type) {*/
					$Widgets[] = $defaultWidget;
				/*}*/
			} else {
				/*if ('empty' != $type) {*/
					$Widgets[] = $userWidgetsFormatted[$userWidgetIndex];
				/*}*/
			}
		} // for ($i=0; $i < $countWidgets; $i++) {

		// Add User Empty Widgets
		$keys = array_keys($userWidgetsFormatted);
		$countKeys = count($keys);

		for ($i=0; $i < $countKeys; $i++) { 
			$key = $keys[$i];

			if (false === strpos($key, 'empty'))
			{
				continue;
			}
			
			if (in_array($key, $emptyIndexHistory))
			{
				continue;
			}

			$Widgets[] = $userWidgetsFormatted[$key];
		}

		$Widgets = $this->sortListByKey($Widgets, true, 'order');

		return $Widgets;

	}

	public function sortListByKey($arrList, $sortType, $property)
	{
		if ('title' == $property) {
			usort($arrList, $this->tr_build_sorter($property));
		} else {
			usort($arrList, $this->build_sorter($property));
		}


		if (!$sortType) {
			$arrList = array_reverse($arrList);
		}

		return $arrList;
	}

	private function build_sorter($key)
	{
		return function ($a, $b) use ($key) {
			return strnatcmp($a[$key], $b[$key]);
		};
	}

	private function tr_build_sorter($key)
	{
		return function ($a, $b) use ($key) {
			return $this->tr_strcmp($a[$key], $b[$key]);
		};
	}

	private function tr_strcmp ($a , $b)
	{
		$lcases = array( 'a' , 'b' , 'c' , 'ç' , 'd' , 'e' , 'f' , 'g' , 'ğ' , 'h' , 'ı' , 'i' , 'j' , 'k' , 'l' , 'm' , 'n' , 'o' , 'ö' , 'p' , 'q' , 'r' , 's' , 'ş' , 't' , 'u' , 'ü' , 'w' , 'v' , 'y' , 'z' );
		$ucases = array ( 'A' , 'B' , 'C' , 'Ç' , 'D' , 'E' , 'F' , 'G' , 'Ğ' , 'H' , 'I' , 'İ' , 'J' , 'K' , 'L' , 'M' , 'N' , 'O' , 'Ö' , 'P' , 'Q' , 'R' , 'S' , 'Ş' , 'T' , 'U' , 'Ü' , 'W' , 'V' , 'Y' , 'Z' );
		$am = mb_strlen ( $a , 'UTF-8' );
		$bm = mb_strlen ( $b , 'UTF-8' );
		$maxlen = $am > $bm ? $bm : $am;
		for ( $ai = 0; $ai < $maxlen; $ai++ ) {
			$aa = mb_substr ( $a , $ai , 1 , 'UTF-8' );
			$ba = mb_substr ( $b , $ai , 1 , 'UTF-8' );
			if ( $aa != $ba ) {
				$apos = in_array ( $aa , $lcases ) ? array_search ( $aa , $lcases ) : array_search ( $aa , $ucases );
				$bpos = in_array ( $ba , $lcases ) ? array_search ( $ba , $lcases ) : array_search ( $ba , $ucases );
				if ( $apos !== $bpos ) {
					return $apos > $bpos ? 1 : -1;
				}
			}
		}
		return 0;
	}

	public function getUserWidgetsFormatted($pageName)
	{
		$userWidgetsFormatted = array();
		
		$currentUser = $this->getUserData();

		$layout = AdminLTEUserLayout::where('deleted', false)
				->where('adminlteuser_id', $currentUser['id'])
				->where('pagename', $pageName)
				->first();
		
		if (null == $layout)
		{
			return $userWidgetsFormatted;
		} // if (null == $layout)


		$userWidgets = json_decode($this->base64decode($layout->widgets), (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));

		$countWidgets = count($userWidgets);
		$emptyIndex = 0;

		for ($i=0; $i < $countWidgets; $i++)
		{ 
			$widget = $userWidgets[$i];
			
			$type = $widget['type'];
			$model = $widget['model'];
			$userWidgetIndex = $type.$model;

			if ('empty' == $type)
			{
				$userWidgetIndex = 'empty' . $emptyIndex;
				$emptyIndex++;
			} // if ('empty' == $type)

			if (!isset($userWidgetsFormatted[$userWidgetIndex]))
			{
				$userWidgetsFormatted[$userWidgetIndex] = array();
				$userWidgetsFormatted[$userWidgetIndex]['type'] = $widget['type'];
				$userWidgetsFormatted[$userWidgetIndex]['model'] = $widget['model'];
			} // if (!isset($userWidgetsFormatted[$userWidgetIndex]))

			$userWidgetsFormatted[$userWidgetIndex]['text'] = $widget['text'];
			$userWidgetsFormatted[$userWidgetIndex]['href'] = $widget['href'];
			$userWidgetsFormatted[$userWidgetIndex]['size'] = $widget['size'];
			$userWidgetsFormatted[$userWidgetIndex]['visibility'] = $widget['visibility'];
			$userWidgetsFormatted[$userWidgetIndex]['order'] = $i;
			$userWidgetsFormatted[$userWidgetIndex]['icon'] = $widget['icon'];
			$userWidgetsFormatted[$userWidgetIndex]['iconbackground'] = $widget['iconbackground'];

			$userWidgetsFormatted[$userWidgetIndex]['value'] = 0;
			
			$userWidgetsFormatted[$userWidgetIndex]['limit'] = $widget['limit'];
			$userWidgetsFormatted[$userWidgetIndex]['onlylastrecord'] = $widget['onlylastrecord'];
			$userWidgetsFormatted[$userWidgetIndex]['columns'] = $widget['columns'];
			$userWidgetsFormatted[$userWidgetIndex]['values'] = $widget['values'];
			$userWidgetsFormatted[$userWidgetIndex]['graphtype'] = $widget['graphtype'];
			$userWidgetsFormatted[$userWidgetIndex]['graphperiod'] = $widget['graphperiod'];

			$userWidgetIndex++;
		} // for ($i=0; $i < $countWidgets; $i++) { 

		return $userWidgetsFormatted;
	}

	public function checkUserEditPermission($token, $userData)
	{
		if (1 == $userData['id'])
		{
			return true;
		} // if (1 == $userData['id'])
		
		$permissions = $userData['menu_permission'];
		$permission_token = $token . '/e';
		if (!in_array($permission_token, $permissions))
		{
			return false;
		} // if (!in_array($permission_token, $permissions))

		return true;
	}

	public function checkUserViewPermission($token, $userData)
	{
		if (1 == $userData['id'])
		{
			return true;
		} // if (1 == $userData['id'])
		
		$permissions = $userData['menu_permission'];
		$permission_token = $token . '/v';
		if (!in_array($permission_token, $permissions))
		{
			return false;
		} // if (!in_array($permission_token, $permissions))

		return true;
	}

	public function updateDotEnv($key, $newValue, $delimiter='')
	{
		$path = base_path('.env');
		// get old value from current env
		$oldValue = env($key);

		// was there any change?
		if ($oldValue === $newValue)
		{
			return;
		} // if ($oldValue === $newValue)

		if (file_exists($path))
		{
			file_put_contents(
				$path, str_replace(
					$key.'='.$delimiter.$oldValue.$delimiter, 
					$key.'='.$delimiter.$newValue.$delimiter, 
					file_get_contents($path)
				)
			);
		} // if (file_exists($path))
	}

	public function getObjectDisplayTexts($model, $objectCurrent)
	{
		$solvedDisplayTexts = array();

		$displayTextDefinitions = $this->getModelDisplayTexts($model);
		$propertyList = array_keys($displayTextDefinitions);
		$countProperty = count($propertyList);
		$property = '';
		$display_text = '';

		for ($i=0; $i < $countProperty; $i++)
		{ 
			$property = $propertyList[$i];
			$display_text = $displayTextDefinitions[$property]['value'];
			$type = $displayTextDefinitions[$property]['type'];

			$solvedDisplayTexts[$property] = $this->getPropertyDisplayText(
					$displayTextDefinitions,
					$model,
					$objectCurrent,
					$property,
					$display_text,
					$type);
		} // for ($i=0; $i < $countProperty; $i++)

		return $solvedDisplayTexts;
	}

	public function getModelDisplayTexts($model)
	{
		$displayTexts = [];
		
		$modelNameWithNamespace = ('\\App\\AdminLTE\\' . $model);
        
        if (!class_exists($modelNameWithNamespace)) {
        	$modelNameWithNamespace = ('\\App\\' . $model);
        }

		$property_list = $modelNameWithNamespace::$property_list;

		$countProperty = count($property_list);

		for ($j=0; $j < $countProperty; $j++) { 
			$property = $property_list[$j];

			$displayTexts[$property['name']]['value'] = '{{' . $model . '/' . $property['name'] . '}}';
			$displayTexts[$property['name']]['type'] = $property['type'];
		} // for ($j=0; $j < $countProperty; $j++) {

		$adminLTEModelDisplayText = AdminLTEModelDisplayText::where('deleted', false)
				->where('model', $model)
				->first();

		if ($adminLTEModelDisplayText != null)
		{
			$display_texts = $adminLTEModelDisplayText->display_texts;

			if ('' != $display_texts)
			{
				$arrDisplayTexts = json_decode($this->base64decode($display_texts), (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));
				$countDisplayText = count($arrDisplayTexts);

				for ($j=0; $j < $countDisplayText; $j++)
				{ 
					$item = $arrDisplayTexts[$j];
					$keys = array_keys($item);
					$countKey = count($keys);

					for ($k=0; $k < $countKey; $k++)
					{ 
						$property = $keys[$k];
						$displayTexts[$property]['value'] = $item[$property];
					} // for ($k=0; $k < $countKey; $k++)
				} // for ($j=0; $j < $countDisplayText; $j++)
			} // if ('' != $display_texts)
		} // if ($adminLTEModelDisplayText != null) {
	
		return $displayTexts;
	}

	public function getPropertyDisplayText($displayTextDefinitions, $model, $objectCurrent, $property, $display_text, $type)
	{
		$parsed = $this->getStringBetween($display_text, '{{', '}}');

		while (strlen($parsed) > 0) {
			$parsedWithMustache = '{{' . $parsed . '}}';
			$partResult = '';

			$textPart = explode('/', $parsed);
			$countPart = count($textPart);
			
			if ('date' == $type) {
				if ($textPart[0] == $model) { // current model
					$display_text_Property = $textPart[1];
					$time = strtotime($objectCurrent->$display_text_Property);
				} else {
					$time = time();
				}

				$format = 'Y-m-d H:i:s';

				if (3 == $countPart) {
					$format = $textPart[2];
				}

				$partResult = date($format, $time);
			} else if ('image' == $type) {
				if ($textPart[0] == $model) { // current model
					$arr_files = $this->get_model_files_by_property($model, $objectCurrent->id, $property);
					$fileCount = count($arr_files);

					if ($fileCount > 0) {
						$partResult = '';

						for ($i = 0; $i < $fileCount; $i++) {

							if ($partResult != '') {
								$partResult .= '<br>';
							} // if ($partResult != '') {
							
							$fileDetail = $arr_files[$i];

							$imageHTML = '<image class="showBigPhoto" src="'
									. asset('storage/' . $fileDetail['path'])
									. '">';

							$partResult .= $imageHTML;
						} // for ($i = 0; $i < $fileCount; $i++) {
					}
				}
			} else if ('file' == $type) {
				if ($textPart[0] == $model) { // current model
					$arr_files = $this->get_model_files_by_property($model, $objectCurrent->id, $property);
					$fileCount = count($arr_files);

					if ($fileCount > 0) {
						$partResult = '';

						for ($i = 0; $i < $fileCount; $i++) {

							if ($partResult != '') {
								$partResult .= '<br>';
							} // if ($partResult != '') {
							
							$fileDetail = $arr_files[$i];

							$fileHTML = '<a class="btn-link text-secondary" target="_blank" href="'
								. asset('storage/' . $fileDetail['path'])
								. '">'
								. '<img class="extension_icon" src="'
								. asset('assets/adminlte/img/' . $fileDetail['extension'])
								. '.png">' 
								. $fileDetail['file_name']
								. '</a>';

							$partResult .= $fileHTML;
						} // for ($i = 0; $i < $fileCount; $i++) {
					}
				}
			} else if ('class_selection_single' == $type) {
				if ($textPart[0] == $model) { // current model
					$display_text_Property = $textPart[1];
					$partResult = $objectCurrent->$display_text_Property;
				} else {
					$id = $objectCurrent->$property;

					$externalModel = $textPart[0];

					$externalModelNameWithNamespace = ('\\App\\AdminLTE\\' . $externalModel);

					if (!class_exists($externalModelNameWithNamespace)) {
						$externalModelNameWithNamespace = ('\\App\\' . $externalModel);
					}

					$objectExternal = new $externalModelNameWithNamespace;
					$objectExternal = $objectExternal::find($id);

					$display_text_Property = $textPart[1];
					$partResult = $objectExternal->$display_text_Property;
				}
			} else if ('class_selection_multiple' == $type) {
				if ($textPart[0] == $model) { // current model
					$display_text_Property = $textPart[1];

					foreach ($objectCurrent->$property as $externalObject) {
		                $arr_display_text_Property[] = $externalObject->id;
		            }

		            if(empty($arr_display_text_Property)){
		                $current_display_text_Property = '';
		            } else {
		                $current_display_text_Property = implode(',', $arr_display_text_Property);
		            }

		            $partResult = $current_display_text_Property;
		        } else {
					$objectExternals = $objectCurrent->$property;
					$display_text_Property = $textPart[1];

					foreach ($objectExternals as $objectExternal) {
						if ('' != $partResult) {
							$partResult .= ', ';
						}

						$partResult .= $objectExternal->$display_text_Property;
					}
		        } // if ($textPart[0] == $model) { // current model
			} else if ('selection_single' == $type) {
				$id = $objectCurrent->$property;
				if($id > 0) {
					$objectExternal = AdminLTEModelOption::find($id);
					$partResult = $objectExternal->title;
				}
			} else if ('selection_multiple' == $type) {
				$objectExternals = $objectCurrent->$property;

				foreach ($objectExternals as $objectExternal) {
					if ('' != $partResult) {
						$partResult .= ', ';
					}

					$partResult .= $objectExternal->title;
				}
			} else {
				if ($textPart[0] == $model) { // current model
					$display_text_Property = $textPart[1];
					$partResult = $objectCurrent->$display_text_Property;
				} else {
					$partResult = '-';
				} // if ($textPart[0] == $model) { // current model
			} // if ('date' == $type) {

			$display_text = str_replace($parsedWithMustache, $partResult, $display_text);
			$temp_text = $display_text;
			$parsed = $this->getStringBetween($temp_text, '{{', '}}');
		} // while (strlen($parsed) > 0) {
		
		return $display_text;
	}

	private function getStringBetween($string, $start, $end)
	{
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) return '';
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		return substr($string, $ini, $len);
	}

	private function getFileData($model, $id)
	{
		$fileData = array();

		$tablename = strtolower($model) . "__filetable";

	    $connection = DB::connection()->getPdo();
	
		$selectSQL = "SELECT * FROM `". $tablename . "` WHERE `id`=:id;";
		$objPDO = $connection->prepare($selectSQL);
		$objPDO->bindParam(':id', $id, PDO::PARAM_INT);
		/*$objPDO->bindParam(':propertyName', $propertyName, PDO::PARAM_STR);*/
		$objPDO->execute();
		$data = $objPDO->fetchAll();
		
		foreach ($data as $row)
		{
			$fileData["id"] = $row["id"];
			$fileData["object_property"] = $row["object_property"];
			$fileData["file_name"] = $row["file_name"];
			$fileData["path"] = $row["path"];
			$fileData["media_type"] = $row["media_type"];

			$fileNameTokens = explode('.', $row["file_name"]);
			$fileData["extension"] = strtolower(end($fileNameTokens));
		} // foreach ($data as $row)

		return $fileData;
	}

	public function setModelSessionParameters($request, $modelName, $variables)
	{

		$sessionHash = sha1($modelName);
		$variableKeys = array_keys($variables);
		$variableKeyCount = count($variableKeys);
		$variableKey = '';

		for ($i = 0; $i < $variableKeyCount; $i++)
		{
			$variableKey = $variableKeys[$i];
			if (null == $variables[$variableKey])
			{
				if ($request->session()->has($sessionHash . $variableKey))
				{
					$request->session()->forget($sessionHash . $variableKey);
				}
			} else {
				$request->session()->put(
						($sessionHash . $variableKey),
						$variables[$variableKey]);
			} // if (null == $variables[$variableKey])
		} // for ($i = 0; $i < $variableKeyCount; $i++) {

		return;

	}

	public function getModelSessionParameters($request, $modelName)
	{

		$sessionValues = $request->session()->all();
		$sessionKeys = array_keys($sessionValues);
		$sessionKeyCount = count($sessionKeys);
		$sessionKey = '';
		$sessionHash = sha1($modelName);
		$sessionValue = '';
		$sessionParameters = [];

		for ($i = 0; $i < $sessionKeyCount; $i++) {
			$sessionKey = $sessionKeys[$i];

			if (strpos($sessionKey, $sessionHash) !== false) {
				$sessionValue = $sessionValues[$sessionKey];
				$sessionParameters[
						substr($sessionKey,
						strlen($sessionHash))] = $sessionValue;
			} // if (strpos($sessionKey, $sessionHash) !== false) {
		} // for ($i = 0; $i < $sessionKeyCount; $i++) {

		$updateSessionParameters = false;

		if (!isset($sessionParameters['searchText'])) {
			$sessionParameters['searchText'] = '';
			$updateSessionParameters = true;
		}

		if (!isset($sessionParameters['sortingColumn'])) {
			$sessionParameters['sortingColumn'] = 'id';
			$updateSessionParameters = true;
		}

		if (!isset($sessionParameters['sortingASC'])) {
			$sessionParameters['sortingASC'] = 2;
			$updateSessionParameters = true;
		}

		if (!isset($sessionParameters['page'])) {
			$sessionParameters['page'] = 0;
			$updateSessionParameters = true;
		}

		if (!isset($sessionParameters['pageCount'])) {
			$sessionParameters['pageCount'] = 0;
			$updateSessionParameters = true;
		}

		if (!isset($sessionParameters['bufferSize'])) {
			$sessionParameters['bufferSize'] = 50;
			$updateSessionParameters = true;
		}

		if ($updateSessionParameters) {
			$this->setModelSessionParameters(
					$request,
					$modelName,
					$sessionParameters);
		}

		return $sessionParameters;

	}

	public function getRecordListLimit($request, $Widgets, $modelName)
	{
		$limit = 0;
		$countWidgets = count($Widgets);
		
		for ($i=0; $i < $countWidgets; $i++) { 
			$Widget = $Widgets[$i];
			
			if ('recordlist' != $Widget['type']) {
				continue;
			}
			
			if ($modelName == $Widget['model']) {
				$limit = $Widget['limit'];
				break;
			}
		} // for ($i=0; $i < $countWidgets; $i++) {

		$sessionParameters = $this->getModelSessionParameters(
				$request,
				$modelName);

		$listCount = 0;
		$modelNameWithNamespace = '';

		if (!isset($sessionParameters['bufferSize'])
				|| ($limit != $sessionParameters['bufferSize']))
		{
			$searchText = '';

			if (isset($sessionParameters['searchText']))
			{
				$searchText = $sessionParameters['searchText'];
				$sessionParameters['sortingColumn'] = 'id';
				$sessionParameters['sortingAscending'] = false;
			} // if (isset($sessionParameters['searchText']))

			$sessionParameters['bufferSize'] = $limit;
			$sessionParameters['page'] = 0;

			$modelNameWithNamespace = ('\\App\\AdminLTE\\' . $modelName);

			if (!class_exists($modelNameWithNamespace)) {
				$modelNameWithNamespace = ('\\App\\' . $modelName);
			}

			$listObject = new $modelNameWithNamespace();

			$listCount = $listObject->where('deleted', false)->count();

			$sessionParameters['pageCount'] = ceil(
					$listCount
					/ $sessionParameters['bufferSize']);
			
			$this->setModelSessionParameters($request,
					$modelName,
					$sessionParameters);

		} // if (!isset($sessionParameters['bufferSize'])

		return $limit;
	}

	public function getRecordListValueVariables($Widgets, $modelName)
	{
		$arrayValues = array();
		$countWidgets = count($Widgets);
		
		for ($i=0; $i < $countWidgets; $i++) { 
			$Widget = $Widgets[$i];

			if ('recordlist' != $Widget['type']) {
				continue;
			}
			
			if ($modelName == $Widget['model']) {
				$columns_csv = $Widget['columns'];

				if ('' != $columns_csv) {
					$values_csv = $Widget['values'];
					$arrayValues = explode(',', $values_csv);
				}
				
				break;
			}
		} // for ($i=0; $i < $countWidgets; $i++) {

		return $arrayValues;
	}

	public function getRecordListType($Widgets, $modelName)
	{
		$onlylastrecord = false;
		$countWidgets = count($Widgets);
		
		for ($i=0; $i < $countWidgets; $i++) { 
			$Widget = $Widgets[$i];
			
			if ('recordlist' != $Widget['type']) {
				continue;
			}
			
			if ($modelName == $Widget['model']) {
				$onlylastrecord = (1 == intval($Widget['onlylastrecord']));
				break;
			}
		} // for ($i=0; $i < $countWidgets; $i++) {

		return $onlylastrecord;
	}

	public function getModelForeignSortColumn($model, $identifier)
	{
		$dictionary = array();
		$dictionary['AdminLTEUser']['adminlteusergroup_id'] = 'title';
		$dictionary['AdminLTEUserLayout']['adminlteuser_id'] = 'fullname';
		
		$identifier = str_replace('DisplayText', '', $identifier);

		if (!isset($dictionary[$model])) {
			return $identifier;
		}

		if (!isset($dictionary[$model][$identifier])) {
			return $identifier;
		}

		return ($identifier . '/' . $dictionary[$model][$identifier]);
	}

	public function getRecordGraphProperties($Widgets, $modelName)
	{
		$result = array();
		$result['type'] = 'daily';
		$result['period'] = 1;

		$countWidgets = count($Widgets);
		
		for ($i=0; $i < $countWidgets; $i++) { 
			$widget = $Widgets[$i];
			
			if ('recordgraph' != $widget['type']) {
				continue;
			}
			
			if ($modelName == $widget['model']) {
				$result['type'] = $widget['graphtype'];
				$result['period'] = intval($widget['graphperiod']);
				break;
			}            
		} // for ($i=0; $i < $countWidgets; $i++) {

		return $result;
	}

	public function getModelPropertyList($object)
	{
		$propertyList = $object->getFillable();

		array_unshift($propertyList,
				'id',
				'deleted',
				'created_at',
				'updated_at');

		return $propertyList;
	}

	public function getAllModelDisplayTexts()
	{

		$displayTexts = [];
		
		$exceptions = [
			'AdminLTE',
			'AdminLTELayout',
			'AdminLTEModelDisplayText',
			'AdminLTEUserLayout',
			'AdminLTEVariable',
			'HTMLDB',
			'User'
		];

		$Models = $this->getModelList($exceptions);
		$countModels = count($Models);
		$modelNameWithNamespace = '';

		// get default display texts
		for ($i=0; $i < $countModels; $i++) {
			$model = $Models[$i];

			$modelNameWithNamespace = ('\\App\\AdminLTE\\' . $model);

			if (!class_exists($modelNameWithNamespace)) {
				$modelNameWithNamespace = ('\\App\\' . $model);
			}

			$object = new $modelNameWithNamespace;
			$property_list = $this->getModelPropertyList($object);
			$countProperty = count($property_list);

			for ($j=0; $j < $countProperty; $j++) { 
				$property = $property_list[$j];
				$displayTexts[$model][$property] = '{{' . $model . '/' . $property . '}}';
			} // for ($j=0; $j < $countProperty; $j++) { 
		} // for ($i=0; $i < $countModels; $i++) {

		$adminLTEModelDisplayText = null;
		$adminLTEModelDisplayTexts = \App\AdminLTE\AdminLTEModelDisplayText::where('deleted', false)
				->get();

		foreach ($adminLTEModelDisplayTexts as $adminLTEModelDisplayText)
		{
			$model = $adminLTEModelDisplayText->model;
			$display_texts = $adminLTEModelDisplayText->display_texts;

			if ('' != $display_texts)
			{

				
				$arrDisplayTexts = json_decode(
						$this->base64Decode($display_texts),
						(JSON_HEX_QUOT
						| JSON_HEX_TAG
						| JSON_HEX_AMP
						| JSON_HEX_APOS));

				$countDisplayText = count($arrDisplayTexts);

				for ($j=0; $j < $countDisplayText; $j++)
				{
					$item = $arrDisplayTexts[$j];
					$keys = array_keys($item);
					$countKey = count($keys);

					for ($k=0; $k < $countKey; $k++)
					{
						$property = $keys[$k];
						$displayTexts[$model][$property] = $item[$property];
					} // for ($k=0; $k < $countKey; $k++)
				} // for ($j=0; $j < $countDisplayText; $j++)
			} // if ('' != $display_texts)
		} // foreach ($adminLTEModelDisplayTexts as $adminLTEModelDisplayText)

		return $displayTexts;

	}

	public function setAdminLTEDefaultLayout()
	{
		$widgets = json_decode(config('adminlte_widget_json'), (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));
		$countWidgets = count($widgets);
		
		try {
			$connection = DB::connection()->getPdo();
		} catch (PDOException $e) {
			print($e->getMessage());
		}

		$SQLText = 'TRUNCATE `adminltelayouttable`;';
		$objPDO = $connection->prepare($SQLText);
		$objPDO->execute();

		// set widgets order
		for ($w=0; $w < $countWidgets; $w++) { 
			$widgets[$w]['order'] = ($w + 1);
		}
	
		// home
		$temp_widgets = $widgets;
		for ($w=0; $w < $countWidgets; $w++) { 
			$temp_widgets[$w]['visibility'] = 1;
		}
		
		$encoded = $this->base64encode(json_encode($temp_widgets, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS)));
		$SQLText = "INSERT INTO `adminltelayouttable` (`deleted`, `pagename`, `widgets`) VALUES ('0', 'home', '" . $encoded . "');";
		$objPDO = $connection->prepare($SQLText);
		$objPDO->execute();

		// adminlteusergroup
		$temp_widgets = $widgets;
		for ($w=0; $w < $countWidgets; $w++) {
			if ('AdminLTEUserGroup' == $temp_widgets[$w]['model']) {
				$temp_widgets[$w]['visibility'] = 1;
			}
		}
		
		$encoded = $this->base64encode(json_encode($temp_widgets, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS)));
		$SQLText = "INSERT INTO `adminltelayouttable` (`deleted`, `pagename`, `widgets`) VALUES ('0', 'adminlteusergroup', '" . $encoded . "');";
		$objPDO = $connection->prepare($SQLText);
		$objPDO->execute();

		// adminlteuser
		$temp_widgets = $widgets;
		for ($w=0; $w < $countWidgets; $w++) {
			if ('AdminLTEUser' == $temp_widgets[$w]['model']) {
				$temp_widgets[$w]['visibility'] = 1;
			}
		}
		
		$encoded = $this->base64encode(json_encode($temp_widgets, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS)));
		$SQLText = "INSERT INTO `adminltelayouttable` (`deleted`, `pagename`, `widgets`) VALUES ('0', 'adminlteuser', '" . $encoded . "');";
		$objPDO = $connection->prepare($SQLText);
		$objPDO->execute();

		$exceptions = [
			'AdminLTE',
			'AdminLTELayout',
			'AdminLTEModelDisplayText',
			'AdminLTEUser',
			'AdminLTEUserGroup',
			'AdminLTEUserLayout',
			'AdminLTEVariable',
			'HTMLDB',
			'User'
		];

		$models = $this->getModelList($exceptions);
		
		$modelCount = count($models);
		$SQLText = '';

		for ($m=0; $m < $modelCount; $m++) { 
			$model = $models[$m];
			$temp_widgets = $widgets;
			for ($w=0; $w < $countWidgets; $w++) {
				if ($model == $temp_widgets[$w]['model']) {
					$temp_widgets[$w]['visibility'] = 1;
				}
			}
			
			$pagename = strtolower($model);
			$encoded = $this->base64encode(json_encode($temp_widgets, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS)));
			$SQLText = "INSERT INTO `adminltelayouttable` (`deleted`, `pagename`, `widgets`) VALUES ('0', '" . $pagename . "', '" . $encoded . "');";
			$objPDO = $connection->prepare($SQLText);
			$objPDO->execute();
		}

		return;
	}
	
	public function updateModelFileObject($className, $classId, $propertyName, $fileIdCSV)
	{
		// initialize variables
		$tablename = strtolower($className) . "__filetable";
		$fileIds = explode(',', $fileIdCSV);
		
		// initialize connection
		try {
			$connection = DB::connection()->getPdo();
		} catch (PDOException $e) {
			print($e->getMessage());
		}
		
		// get existed file objectIds	
		$existedIds = array();
		$selectSQL = "SELECT `id` FROM `". $tablename . "` WHERE `object_id`=:classId and `object_property`=:propertyName;";
		$objPDO = $connection->prepare($selectSQL);
		$objPDO->bindParam(':classId', $classId, PDO::PARAM_INT);
		$objPDO->bindParam(':propertyName', $propertyName, PDO::PARAM_STR);
		$objPDO->execute();
		$data = $objPDO->fetchAll();

		foreach($data as $row) {
			$existedIds[] = $row["id"];
		}

		// delete existed file Objects
		if (count($existedIds) > 0) {
			// Delete if necessary
			$countFileIds = count($fileIds);
			for ($i=0; $i < $countFileIds; $i++) { 
				if (($key = array_search($fileIds[$i], $existedIds)) !== false) {
					unset($existedIds[$key]);
				}
			}

			if (count($existedIds) > 0) {
				$deleteFileSQL = "DELETE FROM `". $tablename . "` WHERE `id` IN (" . implode(',', $existedIds) . ") AND `object_property`=:propertyName;";
				$objPDO = $connection->prepare($deleteFileSQL);
				$objPDO->bindParam(':propertyName', $propertyName, PDO::PARAM_STR);
				$objPDO->execute();
			}
		}

		// update file objects
		if (count($fileIds) > 0) {

			$updateSQL = "UPDATE `" . $tablename . "` SET `object_id`=:classId,`object_property`=:propertyName,`file_index`=:file_index WHERE `id`=:id;";
			$objPDO = $connection->prepare($updateSQL);

			$countFileIds = count($fileIds);
			for ($i=0; $i < $countFileIds; $i++) {

				$objPDO->bindParam(':classId', $classId, PDO::PARAM_INT);
				$objPDO->bindParam(':propertyName', $propertyName, PDO::PARAM_STR);
				$objPDO->bindParam(':file_index', $i, PDO::PARAM_INT);
				$objPDO->bindParam(':id', $fileIds[$i], PDO::PARAM_INT);
				$objPDO->execute();
			}		
		}
	}
	
	public function insertModelPropertyFile($target, $media_type, $file_name, $path) {
		// initialize connection
		try {
			$connection = DB::connection()->getPdo();
		} catch (PDOException $e) {
			print($e->getMessage());
		}

		$arrTemp = explode('/', $target);
		$className = $arrTemp[0];
		$tablename = strtolower($className) . "__filetable";

		$object_property = $arrTemp[1];
		$guid = Str::uuid();
		$lastInsertId = 0;
		
		$SQLText = "INSERT INTO " . $tablename . " (`id`, `guid`, `object_property`, `file_name`, `path`, `media_type`)"
			. " VALUES (0, :guid, :object_property, :file_name, :path, :media_type);";

		$objPDO = $connection->prepare($SQLText);
		$objPDO->bindParam(':guid', $guid, PDO::PARAM_STR);
		$objPDO->bindParam(':object_property', $object_property, PDO::PARAM_STR);
		$objPDO->bindParam(':file_name', $file_name, PDO::PARAM_STR);
		$objPDO->bindParam(':path', $path, PDO::PARAM_STR);
		$objPDO->bindParam(':media_type', $media_type, PDO::PARAM_INT);
		$objPDO->execute();

		$lastInsertId = intval($connection->lastInsertId());

		return $lastInsertId;
	}

	public function get_model_files($model, $object_id) {
		// initialize connection
        try {
            $connection = DB::connection()->getPdo();
        } catch (PDOException $e) {
            print($e->getMessage());
        }

        $tablename = strtolower($model) . "__filetable";
        
        $files = array();
        $index = 0;
        
        $selectSQL = "SELECT * FROM `". $tablename . "` WHERE `object_id`=:object_id ORDER BY file_index;";
        $objPDO = $connection->prepare($selectSQL);
        $objPDO->bindParam(':object_id', $object_id, PDO::PARAM_INT);
        
        $objPDO->execute();
        $data = $objPDO->fetchAll();

        foreach($data as $row) {
            $files[$index]["id"] = $row["id"];
            $files[$index]["object_property"] = $row["object_property"];
            $files[$index]["file_name"] = $row["file_name"];
            $files[$index]["path"] = $row["path"];
            $files[$index]["media_type"] = $row["media_type"];

            $fileNameTokens = explode('.', $row["file_name"]);
            $files[$index]["extension"] = strtolower(end($fileNameTokens));

            $index++;
        }

        return $files;
	}
	
	public function get_model_files_by_property($modelName, $object_id, $object_property) {
        // initialize connection
        try {
            $connection = DB::connection()->getPdo();
        } catch (PDOException $e) {
            print($e->getMessage());
        }
        
        $files = array();
        $index = 0;
        $tablename = strtolower($modelName) . '__filetable';
        
        $selectSQL = "SELECT * FROM `" . $tablename . "` WHERE `object_id`=:object_id and `object_property`=:object_property ORDER BY file_index;";
        $objPDO = $connection->prepare($selectSQL);
        $objPDO->bindParam(':object_id', $object_id, PDO::PARAM_INT);
        $objPDO->bindParam(':object_property', $object_property, PDO::PARAM_STR);
        
        $objPDO->execute();
        $data = $objPDO->fetchAll();

        foreach($data as $row) {
            $files[$index]["id"] = $row["id"];
            $files[$index]["object_property"] = $row["object_property"];
            $files[$index]["file_name"] = $row["file_name"];
            $files[$index]["path"] = $row["path"];
            $files[$index]["media_type"] = $row["media_type"];

            $fileNameTokens = explode('.', $row["file_name"]);
            $files[$index]["extension"] = strtolower(end($fileNameTokens));

            $index++;
        }

        return $files;
    }
    
	public function getModel_DisplayTexts($model) {
		$displayTexts = array();
	    
	    $modelNameWithNamespace = ('\\App\\AdminLTE\\' . $model);

		if (!class_exists($modelNameWithNamespace)) {
			$modelNameWithNamespace = ('\\App\\' . $model);
		}

	    $property_list = $modelNameWithNamespace::$property_list;
	    $countProperty = count($property_list);

	    for ($j=0; $j < $countProperty; $j++) { 
	        $property = $property_list[$j]['property'];

	        $displayTexts[$property]['value'] = '{{' . $model . '/' . $property . '}}';
	        $displayTexts[$property]['type'] = $property_list[$j]['type'];
	    } // for ($j=0; $j < $countProperty; $j++) {
		
		$adminLTEModelDisplayText = null;
		$adminLTEModelDisplayTexts = \App\AdminLTE\AdminLTEModelDisplayText::where('deleted', false)->where('model', $model)->get();

		foreach ($adminLTEModelDisplayTexts as $adminLTEModelDisplayText)
		{

			$display_texts = $adminLTEModelDisplayText->display_texts;

			if ('' != $display_texts)
			{
				$arrDisplayTexts = json_decode(
						$this->base64Decode($display_texts),
						(JSON_HEX_QUOT
						| JSON_HEX_TAG
						| JSON_HEX_AMP
						| JSON_HEX_APOS));
				$countDisplayText = count($arrDisplayTexts);

				for ($j=0; $j < $countDisplayText; $j++)
				{
					$item = $arrDisplayTexts[$j];
					$keys = array_keys($item);
					$countKey = count($keys);

					for ($k=0; $k < $countKey; $k++)
					{
						$property = $keys[$k];
						$displayTexts[$property]['value'] = $item[$property];
					} // for ($k=0; $k < $countKey; $k++)
				} // for ($j=0; $j < $countDisplayText; $j++)
			} // if ('' != $display_texts)
		} // foreach ($adminLTEModelDisplayTexts as $adminLTEModelDisplayText)
	 
	    return $displayTexts;
	}

	public function seedModelDropdownOptions($option_data_list)
	{
		foreach ($option_data_list as $option_data)
		{

			$search = $option_data['model'] . $option_data['property'] . $option_data['value'];
			$title = $option_data['title'];
			
			$option = DB::table('adminltemodeloptiontable')
                ->where(DB::raw("Concat(`model`,`property`,`value`)"), '=', $search)
                ->first();

			if (null !== $option) {
				DB::table('adminltemodeloptiontable')
					->where('id', $option->id)
					->update(['title' => ($title . date('H:i:s'))]); 
			} else {
            	DB::table('adminltemodeloptiontable')->insert($option_data);
        	} // if (null !== $option) {	                         
		} // foreach ($option_data_list as $option_data)  
	}

	/* {{snippet:end_methods}} */
}

/* {{snippet:end_class}} */
