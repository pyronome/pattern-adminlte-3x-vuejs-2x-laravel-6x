<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE;
use App\AdminLTEUser;

class ServerInformationController extends Controller
{
    public function get(Request $request)
    {
		$data = array();

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

		$data['OS_header'] = $OS_header;
		$data['OS_detail'] = $OS_detail;
		$data['OS_icon_src'] = $OS_icon_src;

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

		$data['WEB_header'] = $WEB_header;
		$data['WEB_detail'] = $WEB_detail;
		$data['WEB_icon_src'] = $WEB_icon_src;

		// Application
		$data['APP_header'] = 'PHP';
		$data['APP_detail'] = 'PHP ' . PHP_VERSION;
		$data['APP_icon_src'] = 'php.png';

		$DB_icon_src = 'mysql.png';
		$DB_header = 'MySQL';
		$DB_detail = '';

		/*
		$DB_detail = ('MySQL '
				. $DB_name
				. ' Database on '
				. $DB_host);
		*/

		$data['DB_header'] = $DB_header;
		$data['DB_detail'] = $DB_detail;
		$data['DB_icon_src'] = $DB_icon_src;

        return $data;
    }

}
