<?php

/*
* register
*/

require_once 'sqlCheck.php';

$name = inject_check($_POST['name']);
$email = inject_check($_POST['email']);
$password = inject_check($_POST['password']);

if ($name == '' || $email == '' || $password == '') {
    $arr = array('code' => '1', 'msg' => 'Incomplete parameters');
    echo json_encode($arr);
    die();
}
$password = md5($password);

require_once 'connect.php';

$sql = 'SELECT * FROM `user` WHERE `email`=\''.$email.'\'';
$result = $db->query($sql);
if (count($result) > 0 && $result->fetchArray(SQLITE3_NUM) !== false) {
    $arr = array('code' => '1', 'msg' => 'email exist');
    echo json_encode($arr);
} else {
    $sql = 'INSERT INTO `user` (`name`,`email`,`password`) VALUES (\''.$name.'\',\''.$email.'\',\''.$password.'\')';
    if ($db->exec($sql) === true) {
        $arr = array('code' => '0', 'msg' => 'register success');
        echo json_encode($arr);
    } else {
        $arr = array('code' => '1', 'msg' => 'register fail', 'debug' => $db->lastErrorMsg());
        echo json_encode($arr);
    }
}

$db->close();
