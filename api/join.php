<?php

/*
* get bills
*/

require_once 'tokenCheck.php';
require_once 'sqlCheck.php';
$key = inject_check($_POST['key']);
$uid = inject_check($_POST['uid']);
$percent = inject_check($_POST['percent']);

if ($key == '') {
    $arr = array('code' => '1', 'msg' => 'Incomplete parameters');
    echo json_encode($arr);
    die();
}

require_once 'connect.php';
require_once 'mail.php';
$sql = 'SELECT * FROM `bill` WHERE `key`=\''.$key.'\'';
$result = $db->query($sql);
$price = 0;
if (count($result) > 0 && $result->fetchArray(SQLITE3_NUM) !== false) {
    $result = $db->query($sql);
    $row = $result->fetchArray();
    if ($row['type'] == 1) {
        $price = number_format(($row['price'] / $row['total']), 2);
        $per = number_format((100 / $row['total']), 2);
        $sql = 'INSERT INTO `splitter` (`uid`,`bid`,`price`,`percent`) VALUES ('.$uid.', '.$row['bid'].', \''.$price.'\', \''.$per.'\')';
    } else {
        $price = number_format(($row['price'] * $percent / 100), 2);
        $sql = 'INSERT INTO `splitter` (`uid`,`bid`,`price`,`percent`) VALUES ('.$uid.', '.$row['bid'].', \''.$price.'\', \''.$percent.'\')';
    }
    if ($db->exec($sql) === true) {
        $notice = 'Join in a new bill.Your price is '.$price.'.';
        $sql = 'INSERT INTO `notice` (`uid`,`content`,`time`) VALUES ('.$uid.', \''.$notice.'\',\''.time().'\')';
        $db->exec($sql);
        $sql = 'SELECT `email`,`notice` FROM `user` WHERE `uid`='.$uid;
        $result3 = $db->query($sql);
        $row3 = $result3->fetchArray();
        if ($row3['notice'] == 1) {
            sendVerifyMail($row3['email'], $notice);
        }
        $array = array('id' => $row['bid']);
        $arr = array('code' => '0', 'msg' => 'join success', 'data' => $array);
        echo json_encode($arr);
    } else {
        $arr = array('code' => '1', 'msg' => 'join fail', 'data' => $sql);
        echo json_encode($arr);
    }
} else {
    $arr = array('code' => '1', 'msg' => 'key not exist');
    echo json_encode($arr);
}

$db->close();
