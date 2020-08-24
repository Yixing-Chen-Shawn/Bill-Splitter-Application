<?php

/*
* add bill
*/
require_once 'tokenCheck.php';
require_once 'sqlCheck.php';
$uid = inject_check($_POST['uid']);
$content = inject_check($_POST['content']);
$total = inject_check($_POST['total']);
$number = inject_check($_POST['number']);
$type = inject_check($_POST['type']);

if ($content == '' || $total == '' || $number == '' || $type == '') {
    $arr = array('code' => '1', 'msg' => 'Incomplete parameters');
    echo json_encode($arr);
    die();
}

require_once 'connect.php';
require_once 'mail.php';
date_default_timezone_set('prc');
$stringtime = date('YmdHis', time());
$sql = 'INSERT INTO `bill` (`key`,`content`,`price`,`total`,`type`) VALUES (\''.$stringtime.'\', \''.$content.'\',\''.$total.'\',\''.$number.'\',\''.$type.'\')';
if ($db->exec($sql) === true) {
    $notice = 'Add new bill success. Bill key is '.$stringtime.'.';
    $sql = 'INSERT INTO `notice` (`uid`,`content`,`time`) VALUES ('.$uid.', \''.$notice.'\',\''.time().'\')';
    $db->exec($sql);
    $sql = 'SELECT `email`,`notice` FROM `user` WHERE `uid`='.$uid;
    $result = $db->query($sql);
    $row = $result->fetchArray();
    if ($row['notice'] == 1) {
        sendVerifyMail($row['email'], $notice);
    }
    $arr = array('code' => '0', 'msg' => 'create success', 'key' => $stringtime);
    echo json_encode($arr);
} else {
    $arr = array('code' => '1', 'msg' => 'create fail', 'debug' => $db->lastErrorMsg());
    echo json_encode($arr);
}

$db->close();
