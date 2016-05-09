<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/8/2016
 * Time: 8:20 AM
 */

$mysql_server_name='localhost';
$mysql_username='root';
$mysql_password='abc123,./';
$mysql_database='box';
$db = new mysqli('localhost', 'root', 'abc123,./','box');
if($db->connect_errno > 0){
    die('Unable to connect to database ['.$db->connect_error.']');
}
mysqli_query($db,'SET NAMES utf8');

$sql = <<<SQL
    SELECT * FROM USER
SQL;

if(!$result = $db->query($sql)){
    die('There was an error running the query ['.$db-error.']');
}
while($row = $result->fetch_assoc()){
    echo $row['name'].'<br/>';
}

$result->free();
$db->close();