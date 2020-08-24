<?php

/*
* email notice
*/

require_once 'tokenCheck.php';
require_once 'sqlCheck.php';
$uid = inject_check($_POST['uid']);
$email = inject_check($_POST['email']);

if ($uid == '' || $email == '') {
    $arr = array('code' => '1', 'msg' => 'Incomplete parameters');
    echo json_encode($arr);
    die();
}

require_once 'connect.php';
require_once 'mail.php';
$sql = 'UPDATE `user` SET `notice`='.$email.' WHERE `uid`='.$uid;
if ($db->exec($sql) === true) {
    $notice = 'Change email notice success.';
    $sql = 'INSERT INTO `notice` (`uid`,`content`,`time`) VALUES ('.$uid.', \''.$notice.'\',\''.time().'\')';
    $db->exec($sql);
    $sql = 'SELECT `email`,`notice` FROM `user` WHERE `uid`='.$uid;
    $result = $db->query($sql);
    $row = $result->fetchArray();
    if ($row['notice'] == 1) {
        sendVerifyMail($row['email'], $notice);
    }
    $arr = array('code' => '0', 'msg' => 'change success');
    echo json_encode($arr);
} else {
    $arr = array('code' => '1', 'msg' => 'change fail', 'data' => $sql);
    echo json_encode($arr);
}

$db->close();
