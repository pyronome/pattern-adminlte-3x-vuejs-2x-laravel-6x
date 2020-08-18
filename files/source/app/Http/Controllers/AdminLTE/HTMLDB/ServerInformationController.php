<?php

namespace App\Http\Controllers\AdminLTE\HTMLDB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
use App\HTMLDB\HTMLDB;

class ServerInformationController extends Controller
{
    public $columns = [
		'id',
		'OS_header',
		'OS_detail',
		'OS_icon_src',
		'WEB_header',
		'WEB_detail',
		'WEB_icon_src',
		'APP_header',
		'APP_detail',
		'APP_icon_src',
		'DB_header',
		'DB_detail',
		'DB_icon_src'
    ];

    public $protectedColumns = [];
    public $row = [];

    public function get(Request $request)
    {

		$list = array();
		$list[0]['id'] = 1;

		// Server OS
		$OS_detail = php_uname();

		switch (PHP_OS) {
			case 'Darwin':
				$OS_icon_src = 'macosx.png';
				$OS_header = 'Mac OS X';
			break;

			case 'WINNT':
			case 'Windows':
			case 'WIN32':
				$OS_icon_src = 'windows.png';
				$OS_header = 'Windows';
			break;

			default:
				$OS_icon_src = 'linux.png';
				$OS_header = 'Linux';
			break;
		} // switch (PHP_OS) {

		$list[0]['OS_header'] = $OS_header;
		$list[0]['OS_detail'] = $OS_detail;
		$list[0]['OS_icon_src'] = $OS_icon_src;

		// Web Server
		$WEB_detail = $_SERVER['SERVER_SOFTWARE'];
		$WEB_header = $_SERVER['SERVER_SOFTWARE'];
		$WEB_icon_src = 'apache.png';

		if (strpos(strtolower($WEB_detail), 'apache') !== false) {
			$WEB_icon_src = 'apache.png';
			$WEB_header = 'Apache Web Server';
		} else if (strpos(strtolower($WEB_detail), 'nginx') !== false) {
			$WEB_icon_src = 'nginx.png';
			$WEB_header = 'Nginx Web Server';
		} // if (strpos(strtolower($WEB_detail), 'apache') !== false) {

		$list[0]['WEB_header'] = $WEB_header;
		$list[0]['WEB_detail'] = $WEB_detail;
		$list[0]['WEB_icon_src'] = $WEB_icon_src;

		// Application
		$list[0]['APP_header'] = 'PHP';
		$list[0]['APP_detail'] = 'PHP ' . PHP_VERSION;
		$list[0]['APP_icon_src'] = 'php.png';

		$DB_icon_src = 'mysql.png';
		$DB_header = 'MySQL';
		$DB_detail = '';

		/*
		$DB_detail = ('MySQL '
				. $DB_name
				. ' Database on '
				. $DB_host);
		*/

		$list[0]['DB_header'] = $DB_header;
		$list[0]['DB_detail'] = $DB_detail;
		$list[0]['DB_icon_src'] = $DB_icon_src;

        $objectHTMLDB = new HTMLDB();
		$objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $this->columns;
        $objectHTMLDB->printHTMLDBList();

        return;

    }

}
