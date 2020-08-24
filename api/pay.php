<?php

/*
* pay
*/

require_once 'tokenCheck.php';
require_once 'sqlCheck.php';
$uid = inject_check($_POST['uid']);

if ($uid == '') {
    $arr = array('code' => '1', 'msg' => 'Incomplete parameters');
    echo json_encode($arr);
    die();
}

require_once 'connect.php';
require_once 'mail.php';
$sql = 'UPDATE `splitter` SET `status`=1 WHERE `uid`='.$uid;
if ($db->exec($sql) === true) {
    $notice = 'You pay all your bill.';
    $sql = 'INSERT INTO `notice` (`uid`,`content`,`time`) VALUES ('.$uid.', \''.$notice.'\',\''.time().'\')';
    $db->exec($sql);
    $sql = 'SELECT `email`,`notice` FROM `user` WHERE `uid`='.$uid;
    $result = $db->query($sql);
    $row = $result->fetchArray();
    if ($row['notice'] == 1) {
        sendVerifyMail($row['email'], $notice);
    }
    $arr = array('code' => '0', 'msg' => 'pay success');
    echo json_encode($arr);
} else {
    $arr = array('code' => '1', 'msg' => 'pay fail', 'data' => $sql);
    echo json_encode($arr);
}

$db->close();
