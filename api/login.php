<?php

/*
* login
*/

require_once 'sqlCheck.php';
$email = inject_check($_POST['email']);
$password = inject_check($_POST['password']);

if ($email == '' || $password == '') {
    $arr = array('code' => '1', 'msg' => 'Incomplete parameters');
    echo json_encode($arr);
    die();
}
$password = md5($password);

require_once 'connect.php';

$sql = 'SELECT * FROM `user` WHERE `email`=\''.$email.'\'';
$result = $db->query($sql);
if (count($result) > 0 && $result->fetchArray(SQLITE3_NUM) !== false) {
    $result = $db->query($sql);
    while ($row = $result->fetchArray()) {
        if ($password == $row['password']) {
            $str = md5(uniqid(md5(microtime(true)), true));
            $str = sha1($str);
            $sql = 'DELETE FROM `token` WHERE `uid`='.$row['uid'];
            $db->exec($sql);
            $sql = 'INSERT INTO `token` (`uid`, `token`, `time`) VALUES (\''.$row['uid'].'\' ,\''.$str.'\', \''.time().'\')';
            if ($db->exec($sql) === true) {
                $arr = array('code' => '0', 'msg' => 'login success', 'uid' => $row['uid'], 'token' => $str);
                echo json_encode($arr);
            } else {
                $arr = array('code' => '1', 'msg' => 'login fail', 'sql' => $sql);
                echo json_encode($arr);
            }
        } else {
            $arr = array('code' => '1', 'msg' => 'password error');
            echo json_encode($arr);
        }
    }
} else {
    $arr = array('code' => '1', 'msg' => 'email not exist');
    echo json_encode($arr);
}

$db->close();
