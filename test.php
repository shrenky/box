<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>check OK</title>
</head>
<body>
<form name="check_ok" action="check_ok.html" method="post" target="_blank">
    <table>
        <tr>
            <td><label>Name:</label></td>
            <td><input name="product" type="text" value="纯净水" size="12"></td>
            <td><textarea name="a" cols="20" rows="3" wrap="soft">我是一个粉刷匠</textarea></td>
            <td><input type="submit" value="提交"></td>
        </tr>
    </table>
</form>
<?php
    $p = $_POST["a"];
    echo $p
?>

</body>
</html>