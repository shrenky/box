<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/12/2016
 * Time: 4:10 PM
 */

//include shared code
include '../lib/common.php';
include '../lib/db.php';
include '../lib/functions.php';
include '../lib/User.php';

session_start();
header('Cache-control: private');

//login
if(isset($_GET['login']))
{
    if(isset($_POST['username']) && isset($_POST['password']))
    {
        //retrieve user record
        $user = (User::validateUsername($_POST['username'])) ? User::getByUsername($_POST['username']) : new User();

        if($user-> uid && $user->password == sha1($_POST['password']))
        {
            //store value to session to track user and redirect to main page
            $_SESSION['access'] = TRUE;
            $_SESSION['uid'] = $user-> uid;
            $_SESSION['username'] = $user-> username;
            header('Location: main.php');
        }
        else
        {
            //invalid user or password
            $_SESSION['access'] = FALSE;
            $_SESSION['username'] = null;
            header('Localtion: 401.php');
        }
    }

    //missing credentials
    else
    {
        $_SESSION['access'] = FALSE;
        $_SESSION['username'] = null;
        header('Localtion: 401.php');
    }
    exit();
}

//logout logic if logout is set
//clear the session
else if(isset($_GET['logout']))
{
    if(isset($_COOKIE[session_name()]))
    {
        setcookie(session_name(), '', time() -42000, '/');
    }

    $_SESSION = array();
    session_unset();

    session_destroy();
}

ob_start();
?>

<form action=" <?PHP echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?login" method-"post">
    <table>
        <tr>
            <td>
                <label for="username"> 用户名： </label>
            </td>
            <td>
                <input type="text" name="username" id="username" />
            </td>
        </tr>
        <tr>
            <td>
                <label for="username"> 密码： </label>
            </td>
            <td>
                <input type="password" name="password" id="password" />
            </td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="登录"/></td>
        </tr>
    </table>
</form>

<?php $GLOBALS['TEMPLATE']['content'] = ob_get_clean();

//display the page
include '../templates/template-page.php';
