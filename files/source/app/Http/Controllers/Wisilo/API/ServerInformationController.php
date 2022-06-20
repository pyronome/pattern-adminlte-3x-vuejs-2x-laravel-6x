<?php

namespace App\Http\Controllers\Wisilo\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloUser;

class ServerInformationController extends Controller
{
    public function get(Request $request)
    {
		$wisilo = new Wisilo();
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

		$data['os_header'] = $OS_header;
		$data['os_detail'] = $OS_detail;
		$data['os_icon_src'] = ('/img/wisilo/' . $OS_icon_src);

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

		$data['web_header'] = $WEB_header;
		$data['web_detail'] = $WEB_detail;
		$data['web_icon_src'] = ('/img/wisilo/' . $WEB_icon_src);

		// Application
		$data['app_header'] = 'PHP';
		$data['app_detail'] = 'PHP ' . PHP_VERSION;
		$data['app_icon_src'] = ('/img/wisilo/php.png');

		$DB_icon_src = 'mysql.png';
		$DB_header = 'MySQL';
		$DB_detail = '';

		/*
		$DB_detail = ('MySQL '
				. $DB_name
				. ' Database on '
				. $DB_host);
		*/

		$data['db_header'] = $DB_header;
		$data['db_detail'] = $DB_detail;
		$data['db_icon_src'] = ('/img/wisilo/' . $DB_icon_src);

        return $data;
    }

}
