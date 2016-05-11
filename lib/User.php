<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/10/2016
 * Time: 9:08 PM
 */

namespace box;


class User
{
    private $uid;       //user id
    private $fields;    //other user fields

    //initial user object
    public function __construct()
    {
        $this-> uid = null;
        $this-> fields = array('username'=>'','password'=>'', 'emailAddr'=>'','isActive'=>'');
    }

    //override method to retrieve properties
    public function __get($field)
    {
        if($field == 'userId')
        {
            return $this-> uid;
        }
        else
        {
            return $this->fields[$field];
        }
    }

    //override methid to set properties
    public function __set($field, $value)
    {
        if(array_key_exists($field, $this->fields))
        {
            $this->fields[$field] = $value;
        }
    }

    //return username is valid
    public static function validateUsername($username)
    {
        return preg_match('/^[A-Z0-9]{2,20}$/i', $username);
    }

    //return email is valid
    public static function validateEmailAddr($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    //return user object by userid
    public static function getById($user_id)
    {
        $user = new User();
        $query = sprintf('SELECT USERNAME, PASSWORD, EMAIL_ADDR, IS_ACTIVE'.'FROM %sUSER WHERE USER_ID=%D', DB_TBL_PREFIX, $user_id);
        $result = mysqli_query($query,$GLOBALS['DB']);
        if(mysqli_num_rows($result))
        {
            $row = mysqli_fetch_assoc($result);
            $user-> username = $row['USERNAME'];
            $user-> password = $row['PASSWORD'];
            $user-> emailAddr = $row['EMAIL_ADDR'];
            $user-> isActive = $row['IS_ACTIVE'];
            $user-> uid = $user_id;
        }
        mysqli_free_result($result);
        return $user;
    }

    //return user object by username
    public static function getByUsername($username)
    {
        $user = new User();
        $query = sprintf('SELECT USERNAME, PASSWORD, EMAIL_ADDR, IS_ACTIVE'.'FROM %sUSER WHERE USERNAME="%s"', DB_TBL_PREFIX, mysqli_real_escape_string($username));
        $result = mysqli_query($query,$GLOBALS['DB']);
        if(mysqli_num_rows($result))
        {
            $row = mysqli_fetch_assoc($result);
            $user-> username = $row['USERNAME'];
            $user-> password = $row['PASSWORD'];
            $user-> emailAddr = $row['EMAIL_ADDR'];
            $user-> isActive = $row['IS_ACTIVE'];
            $user-> uid = $username;
        }
        mysqli_free_result($result);
        return $user;
    }

    public function save()
    {
        if($this-> uid)
        {
            $query  = sprintf('UPDATE %sUSER SET USERNAME = "&s", PASSWORD="%s", EMAIL_ADDR="%s". IS_ACTIVE=%d WHERE USER_ID=%d', DB_TBL_PREFIX,
                mysqli_real_escape_string($this-> username, $GLOBALS['DB']),
                mysqli_real_escape_string($this-> password, $GLOBALS['DB']),
                mysqli_real_escape_string($this-> emailAddr, $GLOBALS['DB']),
                $this-> isActive, $this-> userId);
            return mysqli_query($query, $GLOBALS['DB']);
        }
        else
        {
            $query = sprintf('INSERT INTO %sUSER (USERNAME, PASSWORD, EMAIL_ADDR, IS_ACTIVE) VALUES ("%s","%s","%s",%d)',DB_TBL_PREFIX,
                mysqli_real_escape_string($this-> username, $GLOBALS['DB']),
                mysqli_real_escape_string($this-> password, $GLOBALS['DB']),
                mysqli_real_escape_string($this-> emailAddr, $GLOBALS['DB']),
                $this-> isActive);
            if(mysqli_query($query, $GLOBALS['DB']))
            {
                $this-> uid = mysqli_insert_id($GLOBALS['DB']);
                return true;
            }
            else
            {
                return false;
            }

        }
    }

    //set record inactive and return active token
    public function setInactive($token)
    {
        $this-> isActive = fales;
        $this-> save();
        $token = random_text(5);
        $query = sprintf('INSERT INTO %sPENDING (USER_ID, TOKEN) VALUES (%d, "%s")', DB_TBL_PREFIX, $this-> uid, $token);
        return (mysqli_query($query, $GLOBALS['DB'])) ? $token : false;
    }

    //clear pending status and set active status
    public function setActive($token)
    {
        $query = sprintf('SELECT TOKEN FROM %sPENDING WHERE USER_ID = %d AND TOKEN = "%s"', DB_TBL_PREFIX,
            $this-> uid, mysqli_real_escape_string($token, $GLOBALS['DB']));
        $result = mysqli_query($query, $GLOBALS['DB']);
        if(!mysqli_num_rows($result))
        {
            mysqli_free_result($result);
            return false;
        }
        else
        {
            mysqli_free_result($result);
            $query = sprintf('DELETE FROM %sPENDING WHERE USER_ID = %d AND TOKEN = "%s"', DB_TBL_PREFIX,
                $this-> uid, mysqli_real_escape_string($token, $GLOBALS['DB']));
            if(!mysqli_query($query, $GLOBALS['DB']))
            {
                return false;
            }
            else
            {
                $this-> isActive = true;
                return $this->save();
            }
        }
    }
}