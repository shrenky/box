<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/10/2016
 * Time: 8:18 PM
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'abc123,./');
define('DB_SCHEMA', 'box');
define('DB_TBL_PREFIX', 'BOX_');
//define('DB_PORT', '');

//establish db connection
if(!$GLOBALS['DB'] = new mysqli(DB_HOST, DB_USER, DB_PASSWORD))
{
    die('Error: unable to connect DB server');
}

if(!mysqli_select_db(DB_SCHEMA, $GLOBALS['DN']))
{
    mysqli_close($GLOBALS['DB']);
    die('Error:Unable to select DB schema');
}