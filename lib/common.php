<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/10/2016
 * Time: 8:08 PM
 */
//true if running on production
define ('IS_ENV_PRODUCTION', true);

//configure error reporting options
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', !IS_ENV_PRODUCTION);
ini_set('error_log', 'log/phperror.txt');

//set timezone to use date/time functions without warnings
date_default_timezone_get('Asia/Shanghai');

//compensate for magic quotes if necessary
if(get_magic_quotes_gpc())
{

}