<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/12/2016
 * Time: 7:21 AM
 */

//include shared code
include '../lib/common.php';
include '../lib/db.php';
include '../lib/functions.php';
include '../lib/User.php';

//start or continue session so the CAPTCHA text stored in $_SESSION is accessible
session_start();
header('Cache-control: private');

//prepare the registration form's HTML
ob_start();
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_Self]']); ?>>">
    <table>
        <tr>
            <td>
                <label for="username"> 用户名： </label>
            </td>
            <td>
                <input type="text" name="username" id="username" value=" <?php if(isset($_POST['username'])) echo htmlspecialchars($_POST['username]']); ?> " />
            </td>
        </tr>
        <tr>
            <td>
                <label for="password1"> 密码： </label>
            </td>
            <td>
                <input type="password" name="password1" id="password1" value="" />
            </td>
            <td>
                <label for="password2"> 确认密码： </label>
            </td>
            <td>
                <input type="password" name="password2" id="password2" value="" />
            </td>
        </tr>
        <tr>
            <td>
                <label for="email"> 有效邮箱： </label>
            </td>
            <td>
                <inpput type="text" name="email" id="email" value=" <?php if(isset($_POST['email'])) echo htmlspecialchars($_POST['email']);?> " />
            </td>
        </tr>
        <tr>
            <td>
                <label for="captcha"> 验证码： </label>
            </td>
            <td>
                请输入图中验证码：<br/>
                <img src="img/captcha.php?nocache=<?php echo time(); ?>" alt="" /> <br/>
                <input type="text" name="captcha" id="captcha" />
            </td>
        </tr>
    </table>
</form>
<?php
$form = ob_get_clean();

//show the form if this is the first time the page is rendered
if(!isset($_POST['submitted']))
{
    $GLOBALS['TEMPLATE']['content'] = $form;
}

//otherwise process incoming data
else {
    //validate password
    $password1 = (isset($_POST['password1'])) ? $_POST['password1'] : '';
    $password21 = (isset($_POST['password2'])) ? $_POST['password2'] : '';
    $password = ($password1 && $password1 == $password2) ? sha1($password1) : '';

    //validate captcha
    $captcha = (isset($_POST['captcha']) && strtoupper($_POST['captcha']) == $_SESSION['captcha']);

    //save record if valid
    if (User::validateUsername($_POST['username']) && $password && User::validateEmailAddr($_POST['email']) && $captcha) {
        //make sure user not exist
        $user = User::getByUsername($_POST['username']);
        if ($user->userId) {
            $GLOBALS['TEMPLATE']['content'] = '<p> <strong> 用户名已被占用，请换一个用户名。</strong> </p>';
            $GLOBALS['TEMPLATE']['content'] .= $form;
        } else {
            //create an inactive user record
            $user = new User();
            $user->username = $_POST['username'];
            $user->password = $password;
            $user->emailAddr = $_POST['email'];
            $token = $user->setInactive();
            $GLOBALS['TEMPLATE']['content'] = ' <p> <strong>感谢您的注册，请激活账户：</strong> <a href="verify.php?uid='
                . $user->uid . '&token=' . $token . '"> verify.php?uid=' . $user->userId . '&token=' . $token . '</a> </p>';
        }
    } else {
        $GLOBALS['TEMPLATE']['content'] .= '<p><strong>无效数据！请检查您填的信息！</strong></p>';
        $GLOBALS['TEMPLATE']['content'] .= $form;
    }
}
//display the page
include '../templates/template-page.php';
