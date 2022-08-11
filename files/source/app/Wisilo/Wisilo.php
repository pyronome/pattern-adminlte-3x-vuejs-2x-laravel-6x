<?php

namespace App\Wisilo;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Wisilo\WisiloUser;
use App\Wisilo\WisiloLayout;
use App\Wisilo\WisiloUserLayout;
use App\Wisilo\WisiloUserGroup;
use App\Wisilo\WisiloModelDisplayText;
use App\Wisilo\WisiloModelOption;
use App\Wisilo\WisiloMenu;
use App\Wisilo\WisiloMeta;
use App\Wisilo\WisiloConfig;
use App\Wisilo\WisiloUserConfig;
use App\Wisilo\WisiloUserConfigVal;
use App\Wisilo\WisiloUserConfigFile;
use App\Wisilo\WisiloLog;
use App\Wisilo\WisiloWidgetHelper;
use App\Wisilo\WisiloVariable;
use App\Wisilo\WisiloCustomVariable;
use App\Wisilo\WisiloCustomVariableValue;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use PDO;

/* {{@snippet:begin_class}} */

class Wisilo
{

	/* {{@snippet:begin_properties}} */
	public $system_models = [
		'Wisilo',
		'WisiloConfig',
		'WisiloConfigFile',
		'WisiloLayout',
		'WisiloMenu',
		'WisiloMeta',
		'WisiloModelDisplayText',
		'WisiloModelOption',
		'WisiloUser',
		'WisiloUserGroup',
		'WisiloUserConfig',
		'WisiloUserConfigVal',
		'WisiloUserConfigFile',
		'WisiloUserLayout',
		'WisiloVariable',
		'WisiloLog',
		'WisiloCustomVariable',
		'WisiloCustomVariableValue',
		'WisiloMedia',
		'WisiloWidgetHelper',
		'User'
	];

	/* {{@snippet:end_properties}} */

	/* {{@snippet:begin_methods}} */
	
	public function __construct()
    {
		/* {{@snippet:begin_construct}} */
		$this->initConfig();
		/* {{@snippet:end_construct}} */             
	}
	
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

	public function convertTitleToConfigName($strName) {

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
		$strReturnValue = str_replace('"', '', $strReturnValue);
		$strReturnValue = str_replace('\'', '', $strReturnValue);
		$strReturnValue = str_replace('?', '', $strReturnValue);
		$strReturnValue = str_replace(':', '', $strReturnValue);
		$strReturnValue = str_replace('/', '', $strReturnValue);
		$strReturnValue = str_replace('!', '', $strReturnValue);
		$strReturnValue = str_replace(',', '', $strReturnValue);
		$strReturnValue = str_replace('(', '', $strReturnValue);
		$strReturnValue = str_replace(')', '', $strReturnValue);
		$strReturnValue = str_replace('-', '', $strReturnValue);
		$strReturnValue = str_replace('.', '', $strReturnValue);
		$strReturnValue = str_replace('+', '', $strReturnValue);
		$strReturnValue = str_replace('*', '', $strReturnValue);
		$strReturnValue = str_replace('#', '', $strReturnValue);
		$strReturnValue = str_replace(' ', '', $strReturnValue);
		$strReturnValue = str_replace('__', '', $strReturnValue);
		$strReturnValue = strtolower($strReturnValue);
	    
	    return $strReturnValue;
	}
	
	public function generateGUID($strPrefix = '') {
		$strCharacters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$strToken0 = date('YmdHis');
		$strToken1 = substr(str_shuffle(str_repeat($strCharacters, ceil(4 / strlen($strCharacters)))), 1, 4);
		$strToken2 = substr(str_shuffle(str_repeat($strCharacters, ceil(4 / strlen($strCharacters)))), 1, 4);
		return ($strPrefix . $strToken0 . $strToken1 . $strToken2);
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
	
	public function resetUserPassword($objectWisiloUser) {
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
		$objectWisiloUser->password = bcrypt($strNewPassword);
		$objectWisiloUser->save();

		return $strNewPassword;
	}

	public function getUserPreferences() {
		$currentUser = auth()->guard('wisilouser')->user();

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
			$layout = WisiloUserLayout::where('deleted', false)
					->where('wisilouser_id', $currentUser['id'])
					->where('pagename', 'preferences')
					->first();
			
			if (null != $layout)
			{
				$user_preferences = json_decode($this->base64Decode($layout->widgets), (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));
				if (!empty($user_preferences)) {
					$preferences = $user_preferences;
				}
			} // if (null == $layout)			
		} // if ($currentUser != null)

		return $preferences;
	}

	public function getCustomization() {
		$values = array();

		$values['body'] = '';
		$textsize = $this->getConfigParameterValue("wisilo.preferences.body.textsize");
		if ('' != $textsize) {
			$values['body'] = $values['body'] . ' ' . $textsize . ' ';
		}

		$color = $this->getConfigParameterValue("wisilo.preferences.body.accent");
		if ('' != $color) {
			$values['body'] = $values['body'] . ' ' . $color . ' ';
		}


		$values['navbar'] = '';
		$noborder = $this->getConfigParameterValue("wisilo.preferences.navbar.noborder");
		if ('on' == $noborder) {
			$values['navbar'] = $values['navbar'] . ' border-bottom-0 ';
		}

		$textsize = $this->getConfigParameterValue("wisilo.preferences.navbar.textsize");
		if ('' != $textsize) {
			$values['navbar'] = $values['navbar'] . ' ' . $textsize . ' ';
		}

		$color = $this->getConfigParameterValue("wisilo.preferences.navbar.variants");
		if ('' != $color) {
			$parts = explode(',', $color);
			$color = implode(' ', $parts);
			$values['navbar'] = $values['navbar'] . ' ' . $color . ' ';
		}

		$values['brand'] = '';
		$textsize = $this->getConfigParameterValue("wisilo.preferences.brand.textsize");
		if ('' != $textsize) {
			$values['brand'] = $values['brand'] . ' ' . $textsize . ' ';
		}

		$color = $this->getConfigParameterValue("wisilo.preferences.brand.logo");
		if ('' != $color) {
			$values['brand'] = $values['brand'] . ' ' . $color . ' ';
		}

		$values['main-sidebar'] = '';
		$noexpand = $this->getConfigParameterValue("wisilo.preferences.sidebar.noexpand");
		if ('on' == $noexpand) {
			$values['main-sidebar'] = $values['main-sidebar'] . ' sidebar-no-expand ';
		}

		$darkvariants = $this->getConfigParameterValue("wisilo.preferences.sidebar.darkvariants");
		if ('' != $darkvariants) {
			$values['main-sidebar'] = $values['main-sidebar'] . ' ' . $darkvariants . ' ';
		} else {
			$lightvariants = $this->getConfigParameterValue("wisilo.preferences.sidebar.lightvariants");
			if ('' != $lightvariants) {
				$values['main-sidebar'] = $values['main-sidebar'] . ' ' . $lightvariants . ' ';
			}
		}

		$values['nav-sidebar'] = '';
		$textsize = $this->getConfigParameterValue("wisilo.preferences.sidebar.textsize");
		if ('' != $textsize) {
			$values['nav-sidebar'] = $values['nav-sidebar'] . ' ' . $textsize . ' ';
		}

		$color = $this->getConfigParameterValue("wisilo.preferences.sidebar.style");
		if ('' != $color) {
			$values['nav-sidebar'] = $values['nav-sidebar'] . ' ' . $color . ' ';
		}

		return $values;
	}

	public function getUserGroupTitle($group_id) {
		$title = '';

		$objectWisiloUserGroup = WisiloUserGroup::where('id', $group_id)->first();

		if (null != $objectWisiloUserGroup) {
			$title = $objectWisiloUserGroup->title;
		}

		return $title;
	}

	public function getUserData()
	{
        $wisiloUser = auth()->guard('wisilouser')->user();

		$image_path = '/img/wisilo/default-user-image.png';

		$userData = [
			'id' => 0,
			'deleted' => false,
			'created_at' => 0,
			'updated_at' => 0,
			'enabled' => true,
			'wisilousergroup_id' => 0,
			'wisilousergroup_title' => '',
			'admin' => false,
			'impersonated' => false,
			'type' => 'user',
			'email' => '',
			'username' => '',
			'name' => '',
			'fullname' => '',
			'menu_permission' => '',
			'service_permission' => '',
			'image' => $image_path
		];

		if ($wisiloUser != null) {

			$userType = 'user';

			if (1 == $wisiloUser->id) {
				$userType = 'root';
			} // if (1 == $wisiloUser->id) {

			
			$arr_files = $this->get_model_files_by_property('WisiloUser', $wisiloUser->id, 'profile_img');
			
			if (count($arr_files) > 0) {
				$fileDetail = $arr_files[0];
				$image_path = asset('storage/' . $fileDetail['path']);
			}

			// is user admin ?
			$admin = false;
			if (Gate::allows('isAdmin')) {
				$admin = true;
			}

			$userData = [
				'id' => $wisiloUser->id,
				'deleted' => $wisiloUser->deleted,
				'created_at' => $wisiloUser->created_at->timestamp,
				'updated_at' => $wisiloUser->updated_at->timestamp,
				'enabled' => $wisiloUser->enabled,
				'wisilousergroup_id' => $wisiloUser->wisilousergroup_id,
				'wisilousergroup_title' => $this->getUserGroupTitle($wisiloUser->wisilousergroup_id),
				'admin' => $admin,
				'impersonated' => false,
				'type' => $userType,
				'email' => $wisiloUser->email,
				'username' => $wisiloUser->username,
				'name' => ('' != $wisiloUser->fullname) ? $wisiloUser->fullname : $wisiloUser->username,
				'fullname' => $wisiloUser->fullname,
				'menu_permission' => $this->getUserMenuPermission($wisiloUser),
				'service_permission' => $this->getUserServicePermission($wisiloUser),
				'image' => $image_path
			];

			if (session()->has(sha1('wisilo_impersonate')))
			{
				$userData['admin'] = true;
				$userData['impersonated'] = true;
			}
		} // if ($wisiloUser == null) {

		return $userData;
	}


	public function getContentFromURI($URIContent) {
		$dataPosition = strpos($URIContent, 'base64,', 11);
		$URIContent = substr($URIContent, ($dataPosition + 7));
		$URIContent = str_replace(' ', '+', $URIContent);
		return base64_decode($URIContent);
	}

	public function getBrandData()
	{
		$brand_data['name'] = $this->getConfigParameterValue('wisilo.branding.name');

        $item = WisiloConfigFile::where('parameter', 'wisilo.branding.logo')
            ->where('deleted', 0)
            ->first();

        if (is_null($item)) {
            $brand_data['logo'] = '/img/wisilo/WisiloLogo.png';
        } else {
			$file_contents = base64_decode($item->file);

			$response = response($item->file)
				->header('Cache-Control', 'no-cache private')
				->header('Content-Description', 'File Transfer')
				->header('Content-Type', $item->mime_type)
				->header('Content-length', strlen($file_contents))
				->header('Content-Disposition', 'attachment; filename=' . $item->file_name)
				->header('Content-Transfer-Encoding', 'binary');

			$brand_data['logo'] = "data:" . $item->mime_type . ";base64," . $response->content();
		}
		
		return $brand_data;
	}

	public function getWisiloFolder()
	{
        $wisiloFolder = $this->getConfigParameterValue('wisilo.generalsettings.mainfolder');

        if ($wisiloFolder === false
				|| ('' == $wisiloFolder))
        {
            $wisiloFolder = 'wisilo';
        } // if ($wisiloFolder === false) {

		$wisiloFolder = rtrim($wisiloFolder, '/') . '/';

		return $wisiloFolder; 
	}

	public function getMenuIdByHref($href) {
		$id = 0;

		$WisiloMenuItem = WisiloMenu::where('href', $href)->first();
		if (null !== $WisiloMenuItem) {
			$id = $WisiloMenuItem->id;
		}

		return $id;
	}

	public function updateWisiloMenu($menu) {
		$__order = 0;
		
		foreach ($menu as $menu_item) {
			$id = $this->getMenuIdByHref($menu_item['href']);

			if (0 == $id) {
				$WisiloMenu = new WisiloMenu();
				$WisiloMenu->visibility = $menu_item['visibility'];
				$WisiloMenu->__group = isset($menu_item['__group']) ? intval($menu_item['__group']) : 0;
				$WisiloMenu->__order = $__order;

				$parent_id = 0;
				if ('' != $menu_item['parent']) {
					$parent_id = $this->getMenuIdByHref($menu_item['parent']);
				}

				$WisiloMenu->parent_id = $parent_id;

				$WisiloMenu->text = $menu_item['text'];
				$WisiloMenu->href = $menu_item['href'];
				$WisiloMenu->icon = $menu_item['icon'];
				$WisiloMenu->save();

				$__order++;
			}
		}
	}

	public function getWisiloMenu() {
		$menu = [];
		$parentIndexHistory = [];
		$index_parent = 0;
		$index_child = 0;

		$WisiloMenuList = WisiloMenu::get();

		foreach ($WisiloMenuList as $object) {
			
			if (0 == $object->parent_id) {
				// main
				$parentIndexHistory[$object->id] = $index_parent;

				$parent = [];
				$parent['text'] = $object->text;
				$parent['href'] = $object->href;
				$parent['icon'] = $object->icon;
				$parent['visibility'] = $object->visibility;
				$parent['__group'] = $object->__group;

				$menu[$index_parent] = $parent;
				$index_parent++;
			} else {
				//sub
				$parentIndex = $parentIndexHistory[$object->parent_id];

				if (!isset($menu[$parentIndex]['children'])) {
					$menu[$parentIndex]['children'] = [];
				}

				$childIndex = count($menu[$parentIndex]['children']);

				$child = [];
				$child['text'] = $object->text;
				$child['href'] = $object->href;
				$child['icon'] = $object->icon;
				$child['visibility'] = $object->visibility;
				$child['__group'] = $object->__group;

				$menu[$parentIndex]['children'][$childIndex] = $child;
			}
		}

		return $menu;
	}

	public function saveWisiloMenu($menu) {
		WisiloMenu::query()->truncate();

		foreach ($menu as $__order => $data) {
			$WisiloMenu = new WisiloMenu();
			$WisiloMenu->visibility = $data['visibility'];
			$WisiloMenu->__group = $data['__group'];
			$WisiloMenu->__order = $__order;
			$WisiloMenu->parent_id = 0;
			$WisiloMenu->text = $data['text'];
			$WisiloMenu->href = $data['href'];
			$WisiloMenu->icon = $data['icon'];
			$WisiloMenu->save();

			$parent_id = $WisiloMenu->id;

			if (isset($data['children'])) {
				foreach ($data['children'] as $__orderChild => $dataChild) {
					$WisiloMenu = new WisiloMenu();
					$WisiloMenu->visibility = $dataChild['visibility'];
					$WisiloMenu->__group = $dataChild['__group'];
					$WisiloMenu->__order = $__orderChild;
					$WisiloMenu->parent_id = $parent_id;
					$WisiloMenu->text = $dataChild['text'];
					$WisiloMenu->href = $dataChild['href'];
					$WisiloMenu->icon = $dataChild['icon'];
					$WisiloMenu->save();
				}
			}
		}
	}

	public function getSideMenu()
	{
		$menuArray = $this->getWisiloMenu();

		$Menu = array();
		$main_index = 0;
		$countMenuArray = count($menuArray);

		for ($i=0; $i < $countMenuArray; $i++) { 
			if (1 == $menuArray[$i]['visibility']) {
				if (1 == $menuArray[$i]['__group']) {
					$Menu[$main_index]['id'] = 'p' . $i;
					$Menu[$main_index]['__group'] = 1;
					$Menu[$main_index]['url'] = $menuArray[$i]['href'];
					$Menu[$main_index]['href'] = $menuArray[$i]['href'];
					$Menu[$main_index]['title'] = $menuArray[$i]['text'];
					$Menu[$main_index]['icon'] = 'far fa-circle';
					$Menu[$main_index]['children'] = array();
					$main_index++;
				} else {
					if (!isset($menuArray[$i]['children'])) {
						$Menu[$main_index]['id'] = 'p' . $i;
						$Menu[$main_index]['__group'] = 0;
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
						$Menu[$main_index]['__group'] = 0;
						$Menu[$main_index]['url'] = '';
						$Menu[$main_index]['href'] = $menuArray[$i]['href'];
						$Menu[$main_index]['title'] = $menuArray[$i]['text'];
						
						$icon = $menuArray[$i]['icon'];
						if ('empty' == $icon) {
							$icon = 'fas fa-list';
						}

						$Menu[$main_index]['icon'] = $icon;
						$Menu[$main_index]['children'] = array();
						$childrenMenu = array();
						$sub_index = 0;

						$subMenuArray = $menuArray[$i]['children'];
						$countSubmenuArray = count($subMenuArray);
						for ($j=0; $j < $countSubmenuArray; $j++) {
							if (1 == $subMenuArray[$j]['visibility']) {
								$childrenMenu[$sub_index]['id'] = 'c' . $j;
								$childrenMenu[$sub_index]['__group'] = 0;
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
						$main_index++;
					} // if (0 == count($menuArray[$i]['subMenus'])) {
				} // if (1 == $menuArray[$i]['__group']) {
			} // if ($menuArray[$i]['visibility']) {
		} // for ($i=0; $i < $countMenuArray; $i++) { 

		return $Menu;
	}

	public function getUserMenuPermission()
	{
		$user = auth()->guard('wisilouser')->user();

		$permissions = [];
		$joinedPermissions = []; // join group and user permissions
		
		// group permissions
		// add group permissions

		$userGroup = WisiloUserGroup::find($user->wisilousergroup_id);
		
		$decodedPermission = '';

		if ($userGroup != null)
		{
			$decodedPermission = $this->base64Decode($userGroup->permission_data);
		} // if ($userGroup != null)

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
		$decodedPermission = $this->base64Decode($user->permission_data);

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

	public function getUserServicePermission($wisiloUser)
	{
		$permissions = [];
		$joinedPermissions = []; // join group and user permissions
		
		// group permissions
		// add group permissions

		$wisiloUserGroup = WisiloUserGroup::find(
				$wisiloUser->wisilousergroup_id);
		
		$decodedPermission = '';

		if ($wisiloUserGroup != null)
		{
			$decodedPermission = $this->base64Decode(
					$wisiloUserGroup->service_permission);
		} // if ($wisiloUserGroup != null)

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
				$wisiloUser->service_permission);

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
		$exceptions = empty($exceptions) ? $this->system_models : $exceptions;
		
		$Models = array();
		$index = 0;

		$path = (dirname(__FILE__));
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

	public function getPageWidgetConfig($pageName)
	{

		$Widgets = array();

		$layout = WisiloLayout::where('deleted', false)
				->where('pagename', $pageName)
				->orderBy('id', 'DESC')
				->first();
		
		if (null == $layout)
		{
			return $Widgets;
		} // if (null == $layout)

		$defaultWidgets = json_decode(
				$this->base64Decode($layout->widgets),
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

	public function getPageLayout($pageName)
	{

		$Widgets = array();

		$layout = WisiloLayout::where('deleted', false)
				->where('pagename', $pageName)
				->orderBy('id', 'DESC')
				->first();
		
		if (null == $layout)
		{
			return $Widgets;
		} // if (null == $layout)

		$defaultWidgets = json_decode(
				$this->base64Decode($layout->widgets),
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
				if ('1' == $defaultWidget['visibility']) {
					$Widgets[] = $defaultWidget;
				}
			} else {
				if ('1' == $userWidgetsFormatted[$userWidgetIndex]['visibility']) {
					$Widgets[] = $userWidgetsFormatted[$userWidgetIndex];
				}
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

		if (is_string($sortType)) {
			if ('asc' == $sortType) {
				$sortType = true;
			} else if ('desc' == $sortType) {
				$sortType = false;
			} else {
				$sortType = true;
			}
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

		$layout = WisiloUserLayout::where('deleted', false)
				->where('wisilouser_id', $currentUser['id'])
				->where('pagename', $pageName)
				->first();
		
		if (null == $layout)
		{
			return $userWidgetsFormatted;
		} // if (null == $layout)


		$userWidgets = json_decode($this->base64Decode($layout->widgets), (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));

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

	public function writeTemplateFileToTarget($source_path, $destination_path, $variables) {
		$strContent = file_get_contents($source_path);
		$strFind = '';
		$strReplace = '';
		$arrKeys = array_keys($variables);
		$lCountKeys = count($arrKeys);

		for ($i=0; $i < $lCountKeys; $i++) {
			$strFind = '{{' . $arrKeys[$i] . '}}';        
			$strReplace = $variables[$arrKeys[$i]];
			$strContent = str_replace($strFind, $strReplace, $strContent);
		} // for ($i=0; $i < $lCountKeys; $i++) {
		
		Storage::disk('local')->put($destination_path, $strContent);
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
		
		$modelNameWithNamespace = $this->getModelNameWithNamespace($model);

		$property_list = $modelNameWithNamespace::$property_list;

		$countProperty = count($property_list);

		for ($j=0; $j < $countProperty; $j++) { 
			$property = $property_list[$j];

			$displayTexts[$property['name']]['value'] = '{{' . $property['belongs_to'] . '/' . $property['display_property'] . '}}';
			$displayTexts[$property['name']]['type'] = $property['type'];
			$displayTexts[$property['name']]['belongs_to'] = $property['belongs_to'];
			$displayTexts[$property['name']]['display_property'] = $property['display_property'];
		} // for ($j=0; $j < $countProperty; $j++) {

		$wisiloModelDisplayText = WisiloModelDisplayText::where('deleted', false)
				->where('model', $model)
				->first();

		if ($wisiloModelDisplayText != null)
		{
			$display_texts = $wisiloModelDisplayText->display_texts;

			if ('' != $display_texts)
			{
				$arrDisplayTexts = json_decode($this->base64Decode($display_texts), (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));
				$countDisplayText = count($arrDisplayTexts);

				for ($j=0; $j < $countDisplayText; $j++)
				{ 
					$item = $arrDisplayTexts[$j];
					$keys = array_keys($item);
					$countKey = count($keys);

					for ($k=0; $k < $countKey; $k++)
					{ 
						$property = $keys[$k];
						if (isset($displayTexts[$property])) 
						{
							$displayTexts[$property]['value'] = $item[$property];
						}
					} // for ($k=0; $k < $countKey; $k++)
				} // for ($j=0; $j < $countDisplayText; $j++)
			} // if ('' != $display_texts)
		} // if ($wisiloModelDisplayText != null) {
	
		return $displayTexts;
	}

	public function getPropertyDisplayText($displayTextDefinitions, $model, $objectCurrent, $property, $display_text, $type)
	{
		$dateFormat = $this->getConfigParameterValue('wisilo.generalsettings.dateformat');
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

				if (0 == $time) {
					$partResult = '-';
				} else {
					$format = $dateFormat;

					if (3 == $countPart) {
						$format = $textPart[2];
					}

					$partResult = date($format, $time);
				}
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
								. asset('img/wisilo/' . $fileDetail['extension'])
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
					if ($id > 0) {
						$externalModel = $textPart[0];

						$externalModelNameWithNamespace = $this->getModelNameWithNamespace($externalModel);
						$objectExternal = new $externalModelNameWithNamespace;
						$objectExternal = $objectExternal::find($id);
						
						if (null != $objectExternal) {
							$display_text_Property = $textPart[1];
							$partResult = $objectExternal->$display_text_Property;
						}
					}
				}

				$partResult = ('' == $partResult) ? '-' : $partResult;
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
					
				$partResult = ('' == $partResult) ? '-' : $partResult;
			} else if ('selection_single' == $type) {
				$id = $objectCurrent->$property;
				if($id > 0) {
					$objectExternal = WisiloModelOption::find($id);
					$partResult = $objectExternal->title;
				}
				
				$partResult = ('' == $partResult) ? '-' : $partResult;
			} else if ('selection_multiple' == $type) {
				$objectExternals = $objectCurrent->$property;

				foreach ($objectExternals as $objectExternal) {
					if ('' != $partResult) {
						$partResult .= ', ';
					}

					$partResult .= $objectExternal->title;
				}

				$partResult = ('' == $partResult) ? '-' : $partResult;
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
		
		return htmlspecialchars_decode($display_text);
	}

	public function getStringBetween($string, $start, $end)
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
	
		$selectSQL = "SELECT * FROM ". $tablename . " WHERE id=:id;";
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

			$modelNameWithNamespace = $this->getModelNameWithNamespace($modelName);
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

	public function getModelForeignSortColumn($model, $identifier)
	{
		$dictionary = array();
		$dictionary['WisiloUser']['wisilousergroup_id'] = 'title';
		$dictionary['WisiloUserLayout']['wisilouser_id'] = 'fullname';
		
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
		$result['text'] = 'Graph';
		$result['sizeCSV'] = '12,12,12';
		$result['type'] = 'daily';
		$result['period'] = 1;

		$countWidgets = count($Widgets);
		
		for ($i=0; $i < $countWidgets; $i++) { 
			$widget = $Widgets[$i];
			
			if ('recordgraph' != $widget['type']) {
				continue;
			}
			
			if ($modelName == $widget['model']) {
				$result['text'] = $widget['text'];
				$result['sizeCSV'] = $widget['size'];
				$result['type'] = $widget['graphtype'];
				$result['period'] = intval($widget['graphperiod']);
				break;
			}            
		} // for ($i=0; $i < $countWidgets; $i++) {

		return $result;
	}

	public function getModelPropertyList($model)
	{
		$modelNameWithNamespace = $this->getModelNameWithNamespace($model);
		$propertyList = $modelNameWithNamespace::$property_list;
		return $propertyList;
	}

	public function getModelPropertyListByIndexed($model) {
		$propertyList = [];

		$propertyListData = $this->getModelPropertyList($model);
		foreach ($propertyListData as $property_data) {
			$property = $property_data['name'];
			$propertyList[$property]['type'] = $property_data['type'];
			$propertyList[$property]['belongs_to'] = $property_data['belongs_to'];
			$propertyList[$property]['display_property'] = $property_data['display_property'];
			$propertyList[$property]['title'] = $property_data['title'];
		}

		return $propertyList;
	}

	public function getAllModelDisplayTexts()
	{

		$displayTexts = [];
		
		$exceptions = $this->system_models;

		$Models = $this->getModelList($exceptions);
		$countModels = count($Models);

		// get default display texts
		for ($i=0; $i < $countModels; $i++) {
			$model = $Models[$i];
			$property_list = $this->getModelPropertyList($model);
			$countProperty = count($property_list);

			for ($j=0; $j < $countProperty; $j++) { 
				$property = $property_list[$j]['name'];
				$belongs_to = $property_list[$j]['belongs_to'];
				$display_property = $property_list[$j]['display_property'];
				$displayTexts[$model][$property] = '{{' . $belongs_to . '/' . $display_property . '}}';
			} // for ($j=0; $j < $countProperty; $j++) { 
		} // for ($i=0; $i < $countModels; $i++) {

		$wisiloModelDisplayText = null;
		$wisiloModelDisplayTexts = \App\Wisilo\WisiloModelDisplayText::where('deleted', false)
				->get();

		foreach ($wisiloModelDisplayTexts as $wisiloModelDisplayText)
		{
			$model = $wisiloModelDisplayText->model;
			$display_texts = $wisiloModelDisplayText->display_texts;

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
		} // foreach ($wisiloModelDisplayTexts as $wisiloModelDisplayText)

		return $displayTexts;

	}

	public function getWidgetInfobox($model, $index) {
		return [
            'type' => 'infobox',
            'model' => $model,
            'text' => $model,
            'href' => strtolower($model),
            'size' => '12,12,12',
            'visibility' => 0,
            'order' => $index,
            'icon' => 'fas fa-cog',
            'iconbackground' => '#17a2b8',
            'limit' => 0,
            'onlylastrecord' => 0,
            'columns' => '',
            'values' => '',
            'graphtype' => '',
            'graphperiod' => 0
        ];
	}

	public function getWidgetRecordGraph($model, $index) {
		return [
            'type' => 'recordgraph',
            'model' => $model,
            'href' => '',
            'text' => $model . ' Graph',
            'size' => '12,12,12',
            'visibility' => 0,
            'order' => $index,
            'icon' => 'empty',
            'iconbackground' => '',
            'limit' => 0,
            'onlylastrecord' => 0,
            'columns' => '',
            'values' => '',
            'graphtype' => 'daily',
            'graphperiod' => 6
        ];
	}

	public function getWidgetRecordList($model, $index) {
		return [
            'type' => 'recordlist',
            'model' => $model,
            'href' => strtolower($model),
            'text' => $model . ' Records',
            'size' => '12,12,12',
            'visibility' => 0,
            'order' => $index,
            'icon' => 'empty',
            'iconbackground' => '',
            'limit' => '5',
            'onlylastrecord' => 0,
            'columns' => 'Id,Creation,Last Update',
            'values' => 'id,created_at__displaytext__,updated_at__displaytext__',
            'graphtype' => '',
            'graphperiod' => 0
        ];
	}

	public function getWidgetEmpty($index) {
		return [
            'type' => 'empty',
            'model' => '',
            'href' => '',
            'text' => '',
            'size' => '12,12,12',
            'visibility' => 0,
            'order' => $index,
            'icon' => '',
            'iconbackground' => '',
            'limit' => 0,
            'onlylastrecord' => 0,
            'columns' => '',
            'values' => '',
            'graphtype' => '',
            'graphperiod' => 0
        ];
	}

	public function getModelDefaultWidgets($widgets) {
		$models = $this->getModelList();
		$widget_index = 0;

		foreach ($models as $model) {
			$widgets[] = $this->getWidgetInfobox($model, $widget_index);
			$widget_index++;
			$widgets[] = $this->getWidgetRecordGraph($model, $widget_index);
			$widget_index++;
			$widgets[] = $this->getWidgetRecordList($model, $widget_index);
			$widget_index++;
			$widgets[] = $this->getWidgetEmpty($widget_index);
			$widget_index++;
		}
		
		return $widgets;
	}

	public function getWisiloDefaultWidgets($widgets) {
		$models = [
			'WisiloUser',
			'WisiloUserGroup'
		];

		$widget_index = 0;

		foreach ($models as $model) {
			$widgets[] = $this->getWidgetInfobox($model, $widget_index);
			$widget_index++;
			$widgets[] = $this->getWidgetRecordGraph($model, $widget_index);
			$widget_index++;
			$widgets[] = $this->getWidgetRecordList($model, $widget_index);
			$widget_index++;
			$widgets[] = $this->getWidgetEmpty($widget_index);
			$widget_index++;
		}
		
		return $widgets;
	}

	public function setWisiloDefaultLayout()
	{
		$exceptions = [
			'Wisilo',
			'WisiloConfig',
			'WisiloConfigFile',
			'WisiloLayout',
			'WisiloMenu',
			'WisiloMeta',
			'WisiloModelDisplayText',
			'WisiloModelOption',
			'WisiloUser',
			'WisiloUserGroup',
			'WisiloUserConfig',
			'WisiloUserConfigVal',
			'WisiloUserConfigFile',
			'WisiloUserLayout',
			'WisiloVariable',
			'WisiloCustomVariable',
			'WisiloLog',
			'WisiloCustomVariableValue',
			'WisiloMedia',
			'WisiloWidgetHelper',
			'User'
		];

		$models = $this->getModelList($exceptions);
		
		$modelCount = count($models);
		$SQLText = '';

		for ($m=0; $m < $modelCount; $m++) { 
			$model = $models[$m];
			$modelLowerCase = strtolower($model);

			$object = WisiloLayout::where('__system', 1)
				->where('deleted', 0)
				->where('pagename', $modelLowerCase)
				->where('widget', 'recordlist')
				->first();

			if (null === $object) {
				$WisiloLayout = new WisiloLayout();
				$WisiloLayout->__system = 1;
				$WisiloLayout->enabled = 1;
				$WisiloLayout->__order = 0;
				$WisiloLayout->wisilousergroup_id = 1;
				$WisiloLayout->pagename = $modelLowerCase;
				$WisiloLayout->widget = 'recordlist';
				$WisiloLayout->title = 'Record List';
				$WisiloLayout->grid_size = '12,12,12';
				$WisiloLayout->icon = '';

				$metaData = [];
				$metaData['record_list_title'] = $model . ' List';

				$column_index = 0;
				$metaData['columns'] = [];

				$column = new \stdClass();
				$column->visible = 'on';
				$column->type = 'integer';
				$column->title = 'Id';
				$column->name = 'id';
				$column->value = '{{CustomVariables/' . $modelLowerCase . '_id}}';
				$column->style = '';

				$metaData['columns'][$column_index] = $column;
				$column_index++;

				$column = new \stdClass();
				$column->visible = 'on';
				$column->type = 'date';
				$column->title = 'Created At';
				$column->name = 'created_at';
				$column->value = '{{CustomVariables/' . $modelLowerCase . '_created_at}}';
				$column->style = '';

				$metaData['columns'][$column_index] = $column;
				$column_index++;

				$column = new \stdClass();
				$column->visible = 'on';
				$column->type = 'date';
				$column->title = 'Updated At';
				$column->name = 'updated_at';
				$column->value = '{{CustomVariables/' . $modelLowerCase . '_updated_at}}';
				$column->style = '';

				$metaData['columns'][$column_index] = $column;
				$column_index++;

				$column = new \stdClass();
				$column->visible = 'on';
				$column->type = 'button';
				$column->title = '<a class="btn btn-primary btn-xs btn-on-table" href="' . $modelLowerCase . '/edit/new"><i class="fa fa-plus"></i> <span class="hidden-xxs">Add</span></a>';
				$column->name = 'buttons';
				$column->value = '<a class="btn btn-outline-primary btn-xs btn-on-table" href="' . $modelLowerCase . '/detail/{{CustomVariables/' . $modelLowerCase . '_id}}"><i class="fa fa-info-circle"></i> <span class="hidden-xxs">Detail</span></a>';
				$column->style = 'width:130px;';

				$metaData['columns'][$column_index] = $column;
				$column_index++;

				$encodedData = json_encode($metaData);
				$WisiloLayout->meta_data_json = $encodedData;

				$this->updateModelCustomVariables($model);

				$data_source = new \stdClass();
				$data_source->calculation_type = 'advanced';
				$meta_data = new \stdClass();
				$meta_data->query = 'select * from ' . $modelLowerCase . 'table where deleted=0;';
				$meta_data->fields = $this->getModelQueryFields($model);
				$data_source->meta_data = $meta_data;
				$WisiloLayout->data_source_json = json_encode($data_source, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));
				
				$WisiloLayout->conditional_data_json = '{}';
				$WisiloLayout->save();
			}
		}

		$object = WisiloLayout::where('__system', 1)
			->where('deleted', 0)
			->where('pagename', 'wisilousergroup')
			->where('widget', 'recordlist')
			->first();

		if (null === $object) {
			$WisiloLayout = new WisiloLayout();
			$WisiloLayout->__system = 1;
			$WisiloLayout->enabled = 1;
			$WisiloLayout->__order = 0;
			$WisiloLayout->wisilousergroup_id = 1;
			$WisiloLayout->pagename = 'wisilousergroup';
			$WisiloLayout->widget = 'recordlist';
			$WisiloLayout->title = 'Record List';
			$WisiloLayout->grid_size = '12,12,12';
			$WisiloLayout->icon = '';

			$metaData = [];
			$metaData['record_list_title'] = 'User Groups';

			$column_index = 0;
			$metaData['columns'] = [];

			$column = new \stdClass();
			$column->visible = 'on';
			$column->type = 'integer';
			$column->title = 'Id';
			$column->name = 'id';
			$column->value = '{{CustomVariables/wisilousergroup_id}}';
			$column->style = '';

			$metaData['columns'][$column_index] = $column;
			$column_index++;

			$column = new \stdClass();
			$column->visible = 'on';
			$column->type = 'date';
			$column->title = 'Title';
			$column->name = 'title';
			$column->value = '{{CustomVariables/wisilousergroup_title}}';
			$column->style = '';

			$metaData['columns'][$column_index] = $column;
			$column_index++;

			$column = new \stdClass();
			$column->visible = 'on';
			$column->type = 'boolean';
			$column->title = 'Admin';
			$column->name = 'admin';
			$column->value = '<span class="text-success spanIcon spanIconEnabled{{CustomVariables/wisilousergroup_admin}}"><i class="far fa-check-circle"></i></span>';
			$column->style = '';

			$metaData['columns'][$column_index] = $column;
			$column_index++;

			$column = new \stdClass();
			$column->visible = 'on';
			$column->type = 'boolean';
			$column->title = 'Enabled';
			$column->name = 'enabled';
			$column->value = '<span class="text-success spanIcon spanIconEnabled{{CustomVariables/wisilousergroup_enabled}}"><i class="far fa-check-circle"></i></span>';
			$column->style = '';

			$metaData['columns'][$column_index] = $column;
			$column_index++;

			$column = new \stdClass();
			$column->visible = 'on';
			$column->type = 'button';
			$column->title = '<a class="btn btn-primary btn-xs btn-on-table" href="wisilousergroup/edit/new"><i class="fa fa-plus"></i> <span class="hidden-xxs">Add</span></a>';
			$column->name = 'buttons';
			$column->value = '<a class="btn btn-outline-primary btn-xs btn-on-table" href="wisilousergroup/detail/{{CustomVariables/wisilousergroup_id}}"><i class="fa fa-info-circle"></i> <span class="hidden-xxs">Detail</span></a>';
			$column->style = 'width:130px;';

			$metaData['columns'][$column_index] = $column;
			$column_index++;

			$encodedData = json_encode($metaData);
			$WisiloLayout->meta_data_json = $encodedData;

			$data_source = new \stdClass();
			$data_source->calculation_type = 'advanced';
			$meta_data = new \stdClass();
			$meta_data->query = 'select * from wisilousergrouptable where deleted=0;';

			$this->updateModelCustomVariables('WisiloUserGroup');
			$meta_data->fields = $this->getModelQueryFields('WisiloUserGroup');

			$data_source->meta_data = $meta_data;
			$WisiloLayout->data_source_json = json_encode($data_source, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));
			
			$WisiloLayout->conditional_data_json = '{}';
			$WisiloLayout->save();
		}

		$object = WisiloLayout::where('__system', 1)
			->where('deleted', 0)
			->where('pagename', 'wisilouser')
			->where('widget', 'recordlist')
			->first();

		if (null === $object) {
			$WisiloLayout = new WisiloLayout();
			$WisiloLayout->__system = 1;
			$WisiloLayout->enabled = 1;
			$WisiloLayout->__order = 0;
			$WisiloLayout->wisilousergroup_id = 1;
			$WisiloLayout->pagename = 'wisilouser';
			$WisiloLayout->widget = 'recordlist';
			$WisiloLayout->title = 'Record List';
			$WisiloLayout->grid_size = '12,12,12';
			$WisiloLayout->icon = '';

			$metaData = [];
			$metaData['record_list_title'] = 'Users';

			$column_index = 0;
			$metaData['columns'] = [];

			$column = new \stdClass();
			$column->visible = 'on';
			$column->type = 'integer';
			$column->title = 'Id';
			$column->name = 'id';
			$column->value = '{{CustomVariables/wisilouser_id}}';
			$column->style = '';

			$metaData['columns'][$column_index] = $column;
			$column_index++;

			$column = new \stdClass();
			$column->visible = 'on';
			$column->type = 'text';
			$column->title = 'Name';
			$column->name = 'fullname';
			$column->value = '{{CustomVariables/wisilouser_fullname}}';
			$column->style = '';

			$metaData['columns'][$column_index] = $column;
			$column_index++;

			$column = new \stdClass();
			$column->visible = 'on';
			$column->type = 'boolean';
			$column->title = 'User Group';
			$column->name = 'usergroup_title';
			$column->value = '{{CustomVariables/wisilouser_usergroup_title}}';
			$column->style = '';

			$metaData['columns'][$column_index] = $column;
			$column_index++;

			$column = new \stdClass();
			$column->visible = 'on';
			$column->type = 'boolean';
			$column->title = 'Enabled';
			$column->name = 'enabled';
			$column->value = '<span class="text-success spanIcon spanIconEnabled{{CustomVariables/wisilouser_enabled}}"><i class="far fa-check-circle"></i></span>';
			$column->style = '';

			$metaData['columns'][$column_index] = $column;
			$column_index++;

			$column = new \stdClass();
			$column->visible = 'on';
			$column->type = 'button';
			$column->title = '<a class="btn btn-primary btn-xs btn-on-table" href="wisilouser/edit/new"><i class="fa fa-plus"></i> <span class="hidden-xxs">Add</span></a>';
			$column->name = 'buttons';
			$column->value = '<a class="btn btn-outline-primary btn-xs btn-on-table" href="wisilouser/detail/{{CustomVariables/wisilouser_id}}"><i class="fa fa-info-circle"></i> <span class="hidden-xxs">Detail</span></a>';
			$column->style = 'width:130px;';

			$metaData['columns'][$column_index] = $column;
			$column_index++;

			$encodedData = json_encode($metaData);
			$WisiloLayout->meta_data_json = $encodedData;

			$data_source = new \stdClass();
			$data_source->calculation_type = 'advanced';
			$meta_data = new \stdClass();
			$meta_data->query = 'select *, (select title from wisilousergrouptable where id=aut.wisilousergroup_id) as usergroup_title FROM `wisilousertable` as aut where deleted=0;';
			
			
			$this->updateModelCustomVariables('WisiloUser');
			$this->setModelCustomVariable('wisilouser_usergroup_title', 'WisiloUserGroup Title');

			$fields = $this->getModelQueryFields('WisiloUser');

			$field_index = count($fields);
			$fields[$field_index]['guid'] = $this->generateGUID('dsqf');
			$fields[$field_index]['field'] = 'usergroup_title';

			$variableName = 'wisilouser_usergroup_title';
			$objectWisiloWidgetHelper = new WisiloWidgetHelper();
			$objCustomVariable = $objectWisiloWidgetHelper->getCustomVariableByName($variableName, 0);
			$fields[$field_index]['customvariable'] = $objCustomVariable->id;
			$fields[$field_index]['label'] = $objCustomVariable->title;


			$meta_data->fields = $fields;

			$data_source->meta_data = $meta_data;
			$WisiloLayout->data_source_json = json_encode($data_source, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));
			
			$WisiloLayout->conditional_data_json = '{}';
			$WisiloLayout->save();
		}

		return;
	}

	public function updateModelCustomVariables($model) {
		$property_list = $this->getModelPropertyList($model);
	    $countProperty = count($property_list);

	    for ($j=0; $j < $countProperty; $j++) { 
	        $variableName = strtolower($model) . '_' . $property_list[$j]['name'];
			$variableTitle = $model . ' / ' . $property_list[$j]['title'];
			
			$this->setModelCustomVariable($variableName, $variableTitle);
	    } // for ($j=0; $j < $countProperty; $j++) {
	}

	public function setModelCustomVariable($variableName, $variableTitle) {
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

		$object = WisiloCustomVariable::where('__system', 1)
			->where('deleted', 0)
			->where('name', $variableName)
			->first();

		if (null === $object) {
			$object = new WisiloCustomVariable();
			$object->created_by = 0;
			$object->updated_by = 0;
			$object->__system = 1;
			$object->wisilousergroup_id = 0;
			$object->group = '';
			$object->name = $variableName;
			$object->title = $variableTitle;
			$object->value = '';
			$object->default_value = '-';
			$object->remember = 0;
			$object->remember_type = '';
			$object->__order = 0;
			$object->save();
		}

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

	public function getModelQueryFields($model) {
		$modelLowerCase = strtolower($model);
		$objectWisiloWidgetHelper = new WisiloWidgetHelper();
		
		$fields = [];
		$index = 0;

		$property_list = $this->getModelPropertyList($model);
	    $countProperty = count($property_list);

	    for ($j=0; $j < $countProperty; $j++) {
			$property = $property_list[$j]['name'];

			$variableName = strtolower($model) . '_' . $property;
			$objCustomVariable = $objectWisiloWidgetHelper->getCustomVariableByName($variableName, 0);

			if (null !== $objCustomVariable) {
				$fields[$index]['guid'] = $this->generateGUID('dsqf');
				$fields[$index]['field'] = $property;
				$fields[$index]['customvariable'] = $objCustomVariable->id;
				$fields[$index]['label'] = $objCustomVariable->title;
				$index++;
			}
		}

		return $fields;
	}
	
	public function setWisiloCustomVariables() {
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

		$object = WisiloCustomVariable::where('__system', 1)
			->where('deleted', 0)
			->where('name', '__wisilo_mainfolder')
			->first();

		if (null === $object) {
			$object = new WisiloCustomVariable();
			$object->created_by = 0;
			$object->updated_by = 0;
			$object->__system = 1;
			$object->wisilousergroup_id = 0;
			$object->group = '';
			$object->name = '__wisilo_mainfolder';
			$object->title = 'Wisilo Main Folder';
			$object->value = '{{GlobalParameters/__wisilo_mainfolder}}';
			$object->default_value = '{{GlobalParameters/__wisilo_mainfolder}}';
			$object->remember = 0;
			$object->remember_type = '';
			$object->__order = 0;
			$object->save();
		}

		$object = WisiloCustomVariable::where('__system', 1)
			->where('deleted', 0)
			->where('name', '__storage_url')
			->first();

		if (null === $object) {
			$object = new WisiloCustomVariable();
			$object->created_by = 0;
			$object->updated_by = 0;
			$object->__system = 1;
			$object->wisilousergroup_id = 0;
			$object->group = '';
			$object->name = '__storage_url';
			$object->title = 'Wisilo Storage URL';
			$object->value = '{{GlobalParameters/__storage_url}}';
			$object->default_value = '{{GlobalParameters/__storage_url}}';
			$object->remember = 0;
			$object->remember_type = '';
			$object->__order = 0;
			$object->save();
		}
		
		$object = WisiloCustomVariable::where('__system', 1)
			->where('deleted', 0)
			->where('name', 'currentuser_id')
			->first();

		if (null === $object) {
			$object = new WisiloCustomVariable();
			$object->created_by = 0;
			$object->updated_by = 0;
			$object->__system = 1;
			$object->wisilousergroup_id = 0;
			$object->group = '';
			$object->name = 'currentuser_id';
			$object->title = 'Current User Id';
			$object->value = '{{CurrentUser/id}}';
			$object->default_value = '{{CurrentUser/id}}';
			$object->remember = 0;
			$object->remember_type = '';
			$object->__order = 0;
			$object->save();
		}
		
		$object = WisiloCustomVariable::where('__system', 1)
			->where('deleted', 0)
			->where('name', 'currentuser_email')
			->first();

		if (null === $object) {
			$object = new WisiloCustomVariable();
			$object->created_by = 0;
			$object->updated_by = 0;
			$object->__system = 1;
			$object->wisilousergroup_id = 0;
			$object->group = '';
			$object->name = 'currentuser_email';
			$object->title = 'Current User Email';
			$object->value = '{{CurrentUser/email}}';
			$object->default_value = '{{CurrentUser/email}}';
			$object->remember = 0;
			$object->remember_type = '';
			$object->__order = 0;
			$object->save();
		}

		$object = WisiloCustomVariable::where('__system', 1)
			->where('deleted', 0)
			->where('name', 'currentuser_group_id')
			->first();

		if (null === $object) {
			$object = new WisiloCustomVariable();
			$object->created_by = 0;
			$object->updated_by = 0;
			$object->__system = 1;
			$object->wisilousergroup_id = 0;
			$object->group = '';
			$object->name = 'currentuser_group_id';
			$object->title = 'Current User Group Id';
			$object->value = '{{CurrentUser/wisilousergroup_id}}';
			$object->default_value = '{{CurrentUser/wisilousergroup_id}}';
			$object->remember = 0;
			$object->remember_type = '';
			$object->__order = 0;
			$object->save();
		}

		$object = WisiloCustomVariable::where('__system', 1)
			->where('deleted', 0)
			->where('name', 'wisilo_default_language')
			->first();

		if (null === $object) {
			$object = new WisiloCustomVariable();
			$object->created_by = 0;
			$object->updated_by = 0;
			$object->__system = 1;
			$object->wisilousergroup_id = 0;
			$object->group = '';
			$object->name = 'wisilo_default_language';
			$object->title = 'Wisilo Default Language';
			$object->value = '{{GlobalParameters/wisilo.generalsettings.defaultlanguage}}';
			$object->default_value = '{{GlobalParameters/wisilo.generalsettings.defaultlanguage}}';
			$object->remember = 0;
			$object->remember_type = '';
			$object->__order = 0;
			$object->save();
		}

		$object = WisiloCustomVariable::where('__system', 1)
			->where('deleted', 0)
			->where('name', 'wisilo_timezone')
			->first();

		if (null === $object) {
			$object = new WisiloCustomVariable();
			$object->created_by = 0;
			$object->updated_by = 0;
			$object->__system = 1;
			$object->wisilousergroup_id = 0;
			$object->group = '';
			$object->name = 'wisilo_timezone';
			$object->title = 'Wisilo Timezone';
			$object->value = '{{GlobalParameters/wisilo.generalsettings.timezone}}';
			$object->default_value = '{{GlobalParameters/wisilo.generalsettings.timezone}}';
			$object->remember = 0;
			$object->remember_type = '';
			$object->__order = 0;
			$object->save();
		}

		$object = WisiloCustomVariable::where('__system', 1)
			->where('deleted', 0)
			->where('name', 'search_text')
			->first();

		if (null === $object) {
			$object = new WisiloCustomVariable();
			$object->created_by = 0;
			$object->updated_by = 0;
			$object->__system = 1;
			$object->wisilousergroup_id = 0;
			$object->group = '';
			$object->name = 'search_text';
			$object->title = 'Search Text';
			$object->value = '';
			$object->default_value = '';
			$object->remember = 0;
			$object->remember_type = '';
			$object->__order = 0;
			$object->save();
		}

		$object = WisiloCustomVariable::where('__system', 1)
			->where('deleted', 0)
			->where('name', 'page_number')
			->first();

		if (null === $object) {
			$object = new WisiloCustomVariable();
			$object->created_by = 0;
			$object->updated_by = 0;
			$object->__system = 1;
			$object->wisilousergroup_id = 0;
			$object->group = '';
			$object->name = 'page_number';
			$object->title = 'Page Number';
			$object->value = '';
			$object->default_value = '0';
			$object->remember = 0;
			$object->remember_type = '';
			$object->__order = 0;
			$object->save();
		}

		$object = WisiloCustomVariable::where('__system', 1)
			->where('deleted', 0)
			->where('name', 'records_per_page')
			->first();

		if (null === $object) {
			$object = new WisiloCustomVariable();
			$object->created_by = 0;
			$object->updated_by = 0;
			$object->__system = 1;
			$object->wisilousergroup_id = 0;
			$object->group = '';
			$object->name = 'records_per_page';
			$object->title = 'Records Per Page';
			$object->value = '';
			$object->default_value = '50';
			$object->remember = 0;
			$object->remember_type = '';
			$object->__order = 0;
			$object->save();
		}

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
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
		$selectSQL = "SELECT id FROM ". $tablename . " WHERE object_id=:classId and object_property=:propertyName;";
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
				$deleteFileSQL = "DELETE FROM ". $tablename . " WHERE id IN (" . implode(',', $existedIds) . ") AND object_property=:propertyName;";
				$objPDO = $connection->prepare($deleteFileSQL);
				$objPDO->bindParam(':propertyName', $propertyName, PDO::PARAM_STR);
				$objPDO->execute();
			}
		}

		// update file objects
		if (count($fileIds) > 0) {

			$updateSQL = "UPDATE " . $tablename . " SET object_id=:classId,object_property=:propertyName,file_index=:file_index WHERE id=:id;";
			$objPDO = $connection->prepare($updateSQL);

			$countFileIds = count($fileIds);
			for ($i=0; $i < $countFileIds; $i++) {
				if (intval($fileIds[$i]) == 0)
				{
					continue;
				}

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
		
		$SQLText = "INSERT INTO " . $tablename . " (guid, object_property, file_name, path, media_type)"
			. " VALUES (:guid, :object_property, :file_name, :path, :media_type);";

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

	public function is_table_exist($connection, $tablename) {
		try {
			$result = $connection->query('SELECT 1 FROM ' . $tablename . ' LIMIT 1');
		} catch (\Exception $e) {
			// We got an exception == table not found
			return false;
		}

    	return ($result !== false);
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
		
		if ($this->is_table_exist($connection, $tablename)) {
			$selectSQL = "SELECT * FROM $tablename WHERE object_id=:object_id ORDER BY file_index;";

			$objPDO = $connection->prepare($selectSQL);
        	$objPDO->bindParam(':object_id', $object_id, PDO::PARAM_INT);
			$objPDO->execute();
			
			$data = $objPDO->fetchAll();
			$index = 0;

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
        
        $selectSQL = "SELECT * FROM " . $tablename . " WHERE object_id=:object_id and object_property=:object_property ORDER BY file_index;";
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
	    $property_list = $this->getModelPropertyList($model);
	    $countProperty = count($property_list);

	    for ($j=0; $j < $countProperty; $j++) { 
	        $property = $property_list[$j]['property'];
			$belongs_to = $property_list[$j]['belongs_to'];
			$display_property = $property_list[$j]['display_property'];
	        $displayTexts[$property]['value'] = '{{' . $belongs_to . '/' . $display_property . '}}';
	        $displayTexts[$property]['type'] = $property_list[$j]['type'];
	    } // for ($j=0; $j < $countProperty; $j++) {
		
		$wisiloModelDisplayText = null;
		$wisiloModelDisplayTexts = \App\Wisilo\WisiloModelDisplayText::where('deleted', false)->where('model', $model)->get();

		foreach ($wisiloModelDisplayTexts as $wisiloModelDisplayText)
		{

			$display_texts = $wisiloModelDisplayText->display_texts;

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
		} // foreach ($wisiloModelDisplayTexts as $wisiloModelDisplayText)
	 
	    return $displayTexts;
	}

	public function seedModelDropdownOptions($option_data_list)
	{
		foreach ($option_data_list as $option_data)
		{

			$search = $option_data['model'] . $option_data['property'] . $option_data['value'];
			$title = $option_data['title'];
			
			$option = DB::table('wisilomodeloptiontable')
                ->where(DB::raw("CONCAT(model,property,value)"), '=', $search)
                ->first();

			if (null !== $option) {
				DB::table('wisilomodeloptiontable')
					->where('id', $option->id)
					->update(['title' => ($title . date('H:i:s'))]); 
			} else {
            	DB::table('wisilomodeloptiontable')->insert($option_data);
        	} // if (null !== $option) {	                         
		} // foreach ($option_data_list as $option_data)  
	}
	
	public function getFormattedDatetime($date) {
		return (date('Y-m-d', strtotime($date)) . 'T' . date('h:i:s', strtotime($date)));
	}

	public function getModelNameWithNamespace($model) {
		$modelNameWithNamespace = ('\\App\\Wisilo\\' . $model);
        if (!class_exists($modelNameWithNamespace)) {
        	$modelNameWithNamespace = ('\\App\\' . $model);
		}

		return $modelNameWithNamespace;
	}

	public function initConfig() {

		if (Storage::disk('local')->exists('config/wisilo.json')) {
			$contentJSON = Storage::disk('local')->get('config/wisilo.json');
			$data = json_decode($contentJSON, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));
			foreach ($data as $key => $value) {
				config([$key => $value]);
			}
		}

		if (Storage::disk('local')->exists('config/mail.json')) {
			$contentJSON = Storage::disk('local')->get('config/mail.json');
			$data = json_decode($contentJSON, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));
			foreach ($data as $key => $value) {
				config([$key => $value]);
			}
		}
	}

	public function getSplittedDisplayText($display_text) {
		$variableIndex = 1;
		$parsed = $this->getStringBetween($display_text, '{{', '}}');
		while (strlen($parsed) > 0) {
			$parsedWithMustache = '{{' . $parsed . '}}';
			$display_text = str_replace($parsedWithMustache, '#_displaytextvariable_' . $variableIndex . '#', $display_text);
			$variableIndex++;
			$temp_text = $display_text;
			$parsed = $this->getStringBetween($temp_text, '{{', '}}');
		}

		$arrSplittedDisplayText = preg_split('~#~', $display_text, -1, PREG_SPLIT_NO_EMPTY);
		return $arrSplittedDisplayText;
	}
	public function getPropertyDisplayTextSQL($baseModel, $aliasIndex, $baseProperty, $display_text, $type)
	{
		$base_display_text = $display_text;

		$SQLText = '';
		if (('file' == $type) || ('image' == $type) || ('location' == $type)) {
			return $SQLText;
		}

		$baseModelAlias = strtolower($baseModel) . '__table__';

		$parsed = $this->getStringBetween($display_text, '{{', '}}');
		if ('' == $parsed) {
			$SQLText = $display_text . ' as ' . $baseProperty . '__displaytext__';
		} else if ($display_text == ('{{' . $parsed . '}}')) {
			// not need concat
			[$model, $property] = explode('/', $parsed);
			$modelTableAlias = strtolower($model) . '__table__' . $aliasIndex;

			$SQLPart = "";
			if ($baseModel == $model) { // current model
				$SQLPart = $baseModelAlias . "." . $property;
			} else {
				$SQLPart = $modelTableAlias . "." . $property;
			}

			$SQLText = $SQLPart . ' as ' . $baseProperty . '__displaytext__';
		} else {
			// with concat
			$variablesConverted = [];
			$variableIndex = 1;
			while (strlen($parsed) > 0) {
				$parsedWithMustache = '{{' . $parsed . '}}';

				[$model, $property] = explode('/', $parsed);
				$modelTableAlias = strtolower($model) . '__table__' . $aliasIndex;

				$SQLPart = "";
				if ($baseModel == $model) { // current model
					$SQLPart = $baseModelAlias . "." . $property;
				} else {
					$SQLPart = $modelTableAlias . "." . $property;
				}

				$variablesConverted['_displaytextvariable_' . $variableIndex] = $SQLPart;

				$display_text = str_replace($parsedWithMustache, '', $display_text);
				$temp_text = $display_text;
				$parsed = $this->getStringBetween($temp_text, '{{', '}}');
				$variableIndex++;
			} // while (strlen($parsed) > 0) {
			
			$SQLText = '';
			$arrSplittedDisplayText = $this->getSplittedDisplayText($base_display_text);
			foreach ($arrSplittedDisplayText as $splittedText) {
				if (false !== strpos($splittedText, '_displaytextvariable_')) {
					$SQLPart = $variablesConverted[$splittedText];
				} else {
					$SQLPart = "'" . $splittedText . "'";
				}

				if("" != $SQLText) {
					$SQLText .= ',';
				}

				$SQLText .= $SQLPart;
			}
			
			if('' != $SQLText) {
				$SQLText = DB::raw('concat(' . $SQLText . ') as ' . $baseProperty . '__displaytext__');
			}
		}

		return $SQLText;
	}

	public function getQuery($query, $model, $propertyList, $search_text, $sort_variable, $sort_direction) {
		$modelNameWithNamespace = $this->getModelNameWithNamespace($model);
		$modelTableName = strtolower($model).'table';
		$modelTableNameAlias = strtolower($model).'__table__';

		$subquery = DB::table($modelTableName . ' as ' . $modelTableNameAlias);

		// Selections
		$selections = [];
		$searchables = [];
		$sortables = [];
		
		$displayTextDefinitions = $this->getModelDisplayTexts($model);
		$propertyList = array_keys($displayTextDefinitions);
		$countProperty = count($propertyList);
		$aliasIndex = 1;

		for ($i=0; $i < $countProperty; $i++) { 
			$property = $propertyList[$i];
			$display_text = $displayTextDefinitions[$property]['value'];
			$type = $displayTextDefinitions[$property]['type'];
			$belongsTo = $displayTextDefinitions[$property]['belongs_to'];

			if (('file' != $type) && ('image' != $type) && ('location' != $type)) {
				$selection = $this->getPropertyDisplayTextSQL($model, $aliasIndex, $property, $display_text, $type);
				$selections[] = $selection;
				$searchables[$property . '__displaytext__'] = $selection;
				$sortables[$property . '__displaytext__'] = $selection;
				$aliasIndex++;
			}

			if (('file' != $type) && ('image' != $type) && ('location' != $type) && ('class_selection_multiple' != $type) && ('selection_multiple' != $type)) {
				$selection = $modelTableNameAlias . '.' . $property;
				$selections[] = $selection;
				$sortables[$property] = $selection;
			}
		} // for ($i=0; $i < $countProperty; $i++)

		$subquery = $subquery->select($selections)->distinct()->where($modelTableNameAlias.'.deleted', false);

		// Joins
		$aliasIndex = 1;
		for ($i=0; $i < $countProperty; $i++) { 
			$property = $propertyList[$i];
			$display_text = $displayTextDefinitions[$property]['value'];
			$type = $displayTextDefinitions[$property]['type'];
			$external_model = $displayTextDefinitions[$property]['belongs_to'];

			if ('class_selection_single' == $type) {
				$external_modelTableName = strtolower($external_model).'table';
				$external_modelTableNameAlias = strtolower($external_model).'__table__'.$aliasIndex;
				$subquery = $subquery->leftJoin(
					($external_modelTableName . ' as ' . $external_modelTableNameAlias), 
					($modelTableNameAlias . '.' . $property), 
					'=', 
					($external_modelTableNameAlias . '.id')
				);
			} else if ('class_selection_multiple' == $type) {
				$external_modelTableName = strtolower($external_model).'table';
				$external_modelTableNameAlias = strtolower($external_model).'__table__'.$aliasIndex;
				$relationTableName = strtolower($model) . '_' . $property;
				$subquery = $subquery
					->leftJoin(
						$relationTableName,
						($modelTableNameAlias . '.id'),
						'=',
						($relationTableName . '.' . strtolower($model) . '_id')
					)
					->leftJoin(
						($external_modelTableName . ' as ' . $external_modelTableNameAlias), 
						($relationTableName . '.' . strtolower($external_model) . '_id'),
						'=',
						($external_modelTableNameAlias . '.id')
					);
			} else if ('selection_single' == $type) {
				$external_modelTableName = 'wisilomodeloptiontable';
				$external_modelTableNameAlias = 'wisilomodeloption__table__'.$aliasIndex;

				$subquery = $subquery->leftJoin(
					($external_modelTableName . ' as ' . $external_modelTableNameAlias), 
					($modelTableNameAlias . '.' . $property), 
					'=', 
					($external_modelTableNameAlias . '.id')
				);
			} else if ('selection_multiple' == $type) {
				$external_modelTableName = 'wisilomodeloptiontable';
				$external_modelTableNameAlias = 'wisilomodeloption__table__'.$aliasIndex;
				$relationTableName = strtolower($model) . '_' . $property;
				$subquery = $subquery
					->leftJoin(
						$relationTableName,
						($modelTableNameAlias . '.id'),
						'=',
						($relationTableName . '.' . strtolower($model) . '_id')
					)
					->leftJoin(
						($external_modelTableName . ' as ' . $external_modelTableNameAlias), 
						($relationTableName . '.' . $property),
						'=',
						($external_modelTableNameAlias . '.id')
					);
			}

			if (('file' != $type) && ('image' != $type) && ('location' != $type)){
				$aliasIndex++;
			}
		} // for ($i=0; $i < $countProperty; $i++)
		
		// Search
		if ('' != $search_text) {
			foreach ($searchables as $key => $value) {
				$query = $query->orWhere($key, 'like', "%$search_text%");
			}
		}

		// Sort
		if ('' != $sort_variable) {
			$query = $query->orderBy($sort_variable, $sort_direction);
		}

		// multiple sort_variable olursa
		/* if (empty($sort_variables)) {
			foreach ($sortables as $key => $value) {
				if (in_array($key, $sort_variables)) {
					$query = $query->orderBy($key, $sort_direction);
				}
			}
		} */

		// $query = $query->from($subquery, 'subquery')->groupBy('id');
		$query = $query->from($subquery, 'subquery');

		return $query;
	}

	public function getMetaData($meta_key, $term_id = 0) {
		$objects = [];

		if ($term_id > 0) {
			$objects = WisiloMeta::where('deleted', false)->where('term_id', $term_id)->where('meta_key', $meta_key)->get();
		} else {
			$objects = WisiloMeta::where('deleted', false)->where('meta_key', $meta_key)->get();
		}
		
		return $objects;
	}

	public function setMetaData($meta_key, $term_id, $meta_value) {
		if ((0 == $term_id) || ('' == $meta_key)) {
			return false;
		}

		$object = null;
        $objects = WisiloMeta::where('deleted', false)->where('term_id', $term_id)->where('meta_key', $meta_key)->get();

        if (count($objects) > 0) {
            $object = $objects[0];
        } else {
            $object = new WisiloMeta();
        }
        
        $object->deleted = false;
		$object->term_id = $term_id;
        $object->meta_key = $meta_key;
        $object->meta_value = $meta_value;
		$object->save();
		
		return true;
	}

	public function updateWisiloConfig($config) {
		$__order = 0;

		foreach ($config as $config_item) {
			$id = $this->getConfigIdByKey($config_item['__key']);

			if (0 == $id) {
				$WisiloConfig = new WisiloConfig();
				$WisiloConfig->system = $config_item['system'];
				$WisiloConfig->locked = $config_item['locked'];
				$WisiloConfig->owner = $config_item['owner'];
				$WisiloConfig->enabled = $config_item['enabled'];
				$WisiloConfig->only_admins = $config_item['only_admins'];
				$WisiloConfig->__order = $__order;

				$parent_id = 0;
				if ('' != $config_item['__parent']) {
					$parent_id = $this->getConfigIdByKey($config_item['__parent']);
				}

				$WisiloConfig->parent_id = $parent_id;

				$WisiloConfig->default_value = $config_item['default_value'];
				$WisiloConfig->required = $config_item['required'];
				$WisiloConfig->title = $config_item['title'];
				$WisiloConfig->__key = $config_item['__key'];
				$WisiloConfig->type = $config_item['type'];
				$WisiloConfig->value = $config_item['value'];
				$WisiloConfig->hint = $config_item['hint'];
				$WisiloConfig->description = $config_item['description'];

				$metaData = [];
				$metaData['multiple'] = $config_item['multiple'];
				$metaData['option_titles'] = $config_item['option_titles'];
				$metaData['option_values'] = $config_item['option_values'];
				$metaData['toggle_elements'] = empty($config_item['toggle_elements']) ? [] : $config_item['toggle_elements'];
				$metaData['url'] = $config_item['url'];
				$metaData['content'] = $config_item['content'];
				$metaData['min'] = $config_item['min'];
				$metaData['max'] = $config_item['max'];
				$metaData['step'] = $config_item['step'];
				$metaData['file_types'] = $config_item['file_types'];
				$metaData['large_screen_size'] = $config_item['large_screen_size'];
				$metaData['medium_screen_size'] = $config_item['medium_screen_size'];
				$metaData['small_screen_size'] = $config_item['small_screen_size'];
				$metaData['min_selection'] = $config_item['min_selection'];
				$metaData['max_selection'] = $config_item['max_selection'];
				$metaData['expression'] = $config_item['expression'];
				$metaData['message'] = $config_item['message'];

				$encodedData = json_encode($metaData, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));
				$WisiloConfig->meta_data_json = $encodedData;

				$WisiloConfig->save();

				$__order++;
			}
		}
	}

	public function getConfigIdByKey($__key) {
		$id = 0;

		$WisiloConfigItem = WisiloConfig::where('__key', $__key)->first();
		if (null !== $WisiloConfigItem) {
			$id = $WisiloConfigItem->id;
		}

		return $id;
	}

	public function getConfigParameterValue($parameter) {
		$returnVal = '';

		$object = WisiloConfig::where('__key', $parameter)
			->where('deleted', 0)
			/* ->where('enabled', 1) */
			->first();

		if (null !== $object) {
			if (empty($object->value)) {
				$returnVal = $object->default_value;
			} else {
				$returnVal = $object->value;
			}

			if ('selection_group' == $object->type) {
				$returnVal = $this->getSelectionGroupVal($returnVal);
			}
		}
		
		return $returnVal;
	}
	
	public function getSelectionGroupVal($selectionGroupValue) {
		if ('' == $selectionGroupValue) {
			return $selectionGroupValue;
		}

		$parts = explode(',', $selectionGroupValue);
		$returnVal = '';

		foreach ($parts as $key) {
			$partValue = $this->getConfigParameterValue($key);
			
			if ('' != $partValue) {
				if ('' != $returnVal) {
					$returnVal .= ',';
				}

				$returnVal .= $partValue;
			}
		}

		return $returnVal;
	}

	public function updateWisiloUserConfig($config) {
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

		$__order = 0;

		foreach ($config as $config_item) {
			$id = $this->getUserConfigIdByKey($config_item['__key']);

			if (0 == $id) {
				$WisiloUserConfig = new WisiloUserConfig();
				$WisiloUserConfig->owner_group = $config_item['owner_group'];
				$WisiloUserConfig->system = $config_item['system'];
				$WisiloUserConfig->locked = $config_item['locked'];
				$WisiloUserConfig->owner = $config_item['owner'];
				$WisiloUserConfig->enabled = $config_item['enabled'];
				$WisiloUserConfig->__order = $__order;

				$parent_id = 0;
				if ('' != $config_item['__parent']) {
					$parent_id = $this->getUserConfigIdByKey($config_item['__parent']);
				}

				$WisiloUserConfig->parent_id = $parent_id;

				$WisiloUserConfig->default_value = $config_item['default_value'];
				$WisiloUserConfig->required = $config_item['required'];
				$WisiloUserConfig->title = $config_item['title'];
				$WisiloUserConfig->__key = $config_item['__key'];
				$WisiloUserConfig->type = $config_item['type'];
				$WisiloUserConfig->value = $config_item['value'];
				$WisiloUserConfig->hint = $config_item['hint'];
				$WisiloUserConfig->description = $config_item['description'];

				$metaData = [];
				$metaData['multiple'] = $config_item['multiple'];
				$metaData['option_titles'] = $config_item['option_titles'];
				$metaData['option_values'] = $config_item['option_values'];
				$metaData['toggle_elements'] = empty($config_item['toggle_elements']) ? [] : $config_item['toggle_elements'];
				$metaData['url'] = $config_item['url'];
				$metaData['content'] = $config_item['content'];
				$metaData['min'] = $config_item['min'];
				$metaData['max'] = $config_item['max'];
				$metaData['step'] = $config_item['step'];
				$metaData['file_types'] = $config_item['file_types'];
				$metaData['large_screen_size'] = $config_item['large_screen_size'];
				$metaData['medium_screen_size'] = $config_item['medium_screen_size'];
				$metaData['small_screen_size'] = $config_item['small_screen_size'];
				$metaData['min_selection'] = $config_item['min_selection'];
				$metaData['max_selection'] = $config_item['max_selection'];
				$metaData['expression'] = $config_item['expression'];
				$metaData['message'] = $config_item['message'];
				$metaData['show_on_group'] = $config_item['show_on_group'];
				$metaData['show_on_user'] = $config_item['show_on_user'];
				$metaData['show_on_profile'] = $config_item['show_on_profile'];

				$encodedData = json_encode($metaData, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));
				$WisiloUserConfig->meta_data_json = $encodedData;

				$WisiloUserConfig->save();

				$__order++;
			}
		}

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

	public function getUserConfigIdByKey($__key) {
		$id = 0;

		$WisiloUserConfigItem = WisiloUserConfig::where('__key', $__key)->where('owner_group', 0)->first();
		if (null !== $WisiloUserConfigItem) {
			$id = $WisiloUserConfigItem->id;
		}

		return $id;
	}

	public function getUserConfigParameterValue($parameter, $type, $objectId) {
		$returnVal = '';
		$groupId = 0;
		$userId = 0;

		if ('group' == $type) {
			$groupId = $objectId;
			/* $strKey = $parameter . ':' . $objectId . ':0';
			$hashedKey = hash('sha256', $strKey); */
		} else {
			$userId = $objectId;
			$objectWisiloUser = WisiloUser::find($userId);
			$groupId = $objectWisiloUser->wisilousergroup_id;

			/* $strKey = $parameter . ':0:' . $objectId;
			$hashedKey = hash('sha256', $strKey); */
		}

		$objConfig = WisiloUserConfig::where('__key', $parameter)
			->where('deleted', 0)
			->whereIn('owner_group', [0, $groupId])
			->first();

		if (null !== $objConfig) {
			$metaData = json_decode($objConfig->meta_data_json, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP| JSON_HEX_APOS));
            $show_on_group = isset($metaData['show_on_group']) ? intval($metaData['show_on_group']) : 0;

			if ("group" == $type) {
				$returnVal = $this->getGroupConfigVal($objConfig->__key, $groupId);
			} else {
				$returnVal = $this->getUserConfigVal($objConfig->__key, $userId, $show_on_group, $groupId);
			}

			if (empty($returnVal)) {
				$returnVal = $objConfig->default_value;
			}

			if ('selection_group' == $objConfig->type) {
				$returnVal = $this->getUserConfigSelectionGroupVal($returnVal, $type, $objectId);
			}
		}
		
		return $returnVal;
	}

	public function getUserConfigVal($configKey, $userId, $show_on_group, $groupId) {
        $val = '';
        $strKey = $configKey . ':0:' . $userId;
        $__key = hash('sha256', $strKey);

        $object = WisiloUserConfigVal::where('__key', $__key)
            ->where('deleted', 0)
            ->first();

        if (null !== $object) {
            try {
                $val = Crypt::decryptString($object->value);
            } catch (DecryptException $e) {
                $val = '';
            }
        }

        if (('' == $val) && (1 == $show_on_group)) {
            $val = $this->getGroupConfigVal($configKey, $groupId);
        }

        return $val;
    }

    public function getGroupConfigVal($configKey, $groupId) {
        $val = '';
        $strKey = $configKey . ':' . $groupId . ':' . '0';
        $__key = hash('sha256', $strKey);

        $object = WisiloUserConfigVal::where('__key', $__key)
            ->where('deleted', 0)
            ->first();

        if (null !== $object) {
            try {
                $val = Crypt::decryptString($object->value);
            } catch (DecryptException $e) {
                $val = '';
            }
        }

        return $val;
    }
	
	public function getUserConfigSelectionGroupVal($selectionGroupValue, $type, $objectId) {
		if ('' == $selectionGroupValue) {
			return $selectionGroupValue;
		}

		$parts = explode(',', $selectionGroupValue);
		$returnVal = '';

		foreach ($parts as $key) {
			$partValue = $this->getUserConfigSelectionItemValue($key, $type, $objectId);
			
			if ('' != $partValue) {
				if ('' != $returnVal) {
					$returnVal .= ',';
				}

				$returnVal .= $partValue;
			}
		}

		return $returnVal;
	}

	public function getUserConfigSelectionItemValue($key, $type, $objectId) {
		$returnVal = '';

		if ('group' == $type) {
			$groupId = $objectId;
		} else {
			$userId = $objectId;
			$objectWisiloUser = WisiloUser::find($userId);
			$groupId = $objectWisiloUser->wisilousergroup_id;
		}

		$objConfig = WisiloUserConfig::where('__key', $key)
			->where('deleted', 0)
			->whereIn('owner_group', [0, $groupId])
			->first();

		if (null !== $objConfig) {
			$returnVal = $objConfig->value;
		}

		return $returnVal;
	}

	public function getConfigParameterBasekey($key) {
        $basekey = '';

        if ('' != $key) {
           $parts = explode('.', $key);
           $length = count($parts);
           $basekey = $parts[$length-1];
        }

        return $basekey;
    }

	public function logInfo($title, $message) {
		$currentUser = auth()->guard('wisilouser')->user();

		$objectLog = new WisiloLog();
        $objectLog->user_id = $currentUser->id;
        $objectLog->type = 'INFO';
        $objectLog->title = $title;
        $objectLog->sub_title = '';
        $objectLog->object_id = 0;
        $objectLog->object_old_values = '';
        $objectLog->object_new_values = '';
        $objectLog->message = $message;
        $objectLog->save();
	}

	public function logWarning($title, $message) {
		$currentUser = auth()->guard('wisilouser')->user();

		$objectLog = new WisiloLog();
        $objectLog->user_id = $currentUser->id;
        $objectLog->type = 'WARNING';
        $objectLog->title = $title;
        $objectLog->sub_title = '';
        $objectLog->object_id = 0;
        $objectLog->object_old_values = '';
        $objectLog->object_new_values = '';
        $objectLog->message = $message;
        $objectLog->save();
	}

	public function logError($title, $message) {
		$currentUser = auth()->guard('wisilouser')->user();

		$objectLog = new WisiloLog();
        $objectLog->user_id = $currentUser->id;
        $objectLog->type = 'ERROR';
        $objectLog->title = $title;
        $objectLog->sub_title = '';
        $objectLog->object_id = 0;
        $objectLog->object_old_values = '';
        $objectLog->object_new_values = '';
        $objectLog->message = $message;
        $objectLog->save();
	}

	public function getUserMenuPermissions() {
		$currentUser = auth()->guard('wisilouser')->user();
		$menu_permissions = [];

		$objConfigList = WisiloUserConfig::where('deleted', 0)
			->where('__key', 'like', 'permission.wisilomenu.%')
			->get();

		foreach ($objConfigList as $objConfig) {
			$basekey = $this->getConfigParameterBasekey($objConfig->__key);
			$menu_permissions[$basekey] = ('on' == $this->getUserConfigParameterValue($objConfig->__key, 'user', $currentUser->id));
		}
		
		return $menu_permissions;
	}

	public function getUserModelPermissions() {
		$currentUser = auth()->guard('wisilouser')->user();
		$model_permissions = [];

		$objConfigList = WisiloUserConfig::where('deleted', 0)
			->where('__key', 'like', 'permission.wisilomodel.%')
			->get();

		foreach ($objConfigList as $objConfig) {
			if ('selection_group' == $objConfig->type) {
				$basekey = $this->getConfigParameterBasekey($objConfig->__key);
				$model_permissions[$basekey] = $this->getUserConfigParameterValue($objConfig->__key, 'user', $currentUser->id);
			}
		}
		
		return $model_permissions;
	}

	public function getUserPluginsPermissions() {
		$currentUser = auth()->guard('wisilouser')->user();
		$plugin_permissions = [];

		$objConfigList = WisiloUserConfig::where('deleted', 0)
			->where('__key', 'like', 'permission.plugins.%')
			->get();

		foreach ($objConfigList as $objConfig) {
			$config_key = $objConfig->__key;
			$config_value = ('on' == $this->getUserConfigParameterValue($config_key, 'user', $currentUser->id));

			$parentKey = $this->getConfigParameterParentKey($config_key);
			$basekey = $this->getConfigParameterBasekey($config_key);

			if ('permission.plugins' != $parentKey) {
				$parentBasekey = $this->getConfigParameterBasekey($parentKey);

				if (!isset($plugin_permissions[$parentBasekey])) {
					$plugin_permissions[$parentBasekey] = [];
				}

				$plugin_permissions[$parentBasekey][$basekey] = $config_value;
			} else {
				if (!isset($plugin_permissions[$basekey])) {
					$plugin_permissions[$basekey] = [];
				}
			}
		}
		
		return $plugin_permissions;
	}

	public function getConfigParameterParentKey($key) {
        $parent = '';

        if ('' != $key) {
           $parts = explode('.', $key);
           $length = count($parts);
           $base = $parts[$length-1];

           if ($length > 1) {
               $parent = str_replace(('.'.$base), '', $key);
           }
        }

        return $parent;
    }

	public function getUserGroupWidgets($group_id) {
		$objectWisiloWidgetHelper = new WisiloWidgetHelper();
		return $objectWisiloWidgetHelper->getUserGroupWidgets($group_id);
	}

	public function getUserWidgets($pagename) {
		$objectWisiloWidgetHelper = new WisiloWidgetHelper();
		return $objectWisiloWidgetHelper->getUserWidgets($pagename);
	}

	public function getUserActiveWidgets($pagename) {
		$objectWisiloWidgetHelper = new WisiloWidgetHelper();
		return $objectWisiloWidgetHelper->getUserActiveWidgets($pagename);
	}

	public function saveLayoutConditionalData($layoutId, $conditional_data) {
		$objectWisiloWidgetHelper = new WisiloWidgetHelper();
		$objectWisiloWidgetHelper->saveConditionalData($layoutId, $conditional_data);
	}

	public function getCurrentCustomVariables() {
		$custom_variables = [];

		// Session
		$sessionVariables = session()->all();
		foreach ($sessionVariables as $key => $value) {
			if (false !== strpos($key, 'customvariable')) {
				$custom_variables[$key] = $value;
			}
		}

		// Database
		$currentUser = auth()->guard('wisilouser')->user();
		$objectList = WisiloCustomVariableValue::where('deleted', 0)
            ->where('wisilousergroup_id', $currentUser->wisilousergroup_id)
            ->get();

		foreach ($objectList as $object) {
			$custom_variables['customvariable' . $object->customvariable_id] = $object->value;
		}

		return $custom_variables;
	}
	
    /* {{@snippet:end_methods}} */
}

/* {{@snippet:end_class}} */