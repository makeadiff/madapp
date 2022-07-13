<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('APP_NAME', 'HumApp');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 					'ab');
define('FOPEN_READ_WRITE_CREATE', 				'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 			'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
|--------------------------------------------------------------------------
| Pagination Constant
|--------------------------------------------------------------------------
|
|
*/

define('PAGINATION_CONSTANT', 	'5');

$_main_site_url = 'https://humanityorg.binnyva.com';

if(isset($_SERVER['HTTP_HOST'])) {
	if($_SERVER['HTTP_HOST'] == 'humanityorg.binnyva.com') $_main_site_url = 'https://humanityorg.binnyva.com';
	elseif($_SERVER['HTTP_HOST'] == 'testing.makeadiff.in') $_main_site_url = 'http://testing.makeadiff.in';
	elseif($_SERVER['HTTP_HOST'] == 'localhost') $_main_site_url = 'http://localhost/Sites/binnyva/humanityorg.binnyva.com';
}
define('MAD_APPS_FOLDER', $_main_site_url . '/apps/');

/* End of file constants.php */
/* Location: ./system/application/config/constants.php */