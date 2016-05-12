<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/12/2016
 * Time: 3:46 PM
 */

//include shared code
include '../lib/common.php';
include '../lib/db.php';
include '../lib/functions.php';
include '../lib/User.php';

//make sure a user id and activation token were received
if(!isset($_GET['uid']) || !isset($_GET['token']))
{
    $GLOBALS['TEMPLATE']['content'] = '<p><strong>缺失uid或者token</strong></p><p>请重试。</p>';
    include '../templates/template-page.php';
    exit();
}

//validate uid
if(!$user = User::getById($_GET['uid']))
{
    $GLOBALS['TEMPLATE']['content']='<p><strong>找不到此用户。</strong></p><p>请重试。</p>';
}
else
{
    if($user-> isActive)
    {
        $GLOBALS['TEMPLATE']['content'] ='<p><strong>此用户已经激活。</strong></p>';
    }
    else
    {
        if($user-> setActive($_GET['token']))
        {
            $GLOBALS['TEMPLATE']['content']='<p><strong>您的账户已经激活！</strong></p><p>请<a href=""login.php"> 登录 </a></p>';
        }
        else
        {
            $GLOBALS['TEMPLATE']['content'] = '<p><strong>无效的token</strong></p><p>请重试。</p>';
        }
    }
}

//display the page
include '../templates/template-page.php';