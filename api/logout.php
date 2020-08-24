<?php

/*
* lougout
*/

require_once 'sqlCheck.php';
$uid = inject_check($_POST['uid']);

if ($uid == '') {
    $arr = array('code' => '1', 'msg' => 'Incomplete parameters');
    echo json_encode($arr);
    die();
}

require_once 'connect.php';
$sql = 'DELETE FROM `token` WHERE `uid`='.$uid;
if ($db->exec($sql) === true) {
    $arr = array('code' => '0', 'msg' => 'logout success');
    echo json_encode($arr);
} else {
    $arr = array('code' => '1', 'msg' => 'logout fail', 'data' => $sql);
    echo json_encode($arr);
}

$db->close();
