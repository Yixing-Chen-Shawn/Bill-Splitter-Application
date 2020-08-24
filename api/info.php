<?php

/*
* get balance
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
$sql = 'SELECT * FROM `splitter` WHERE `status`=0 AND `uid`='.$uid;
$result = $db->query($sql);
if (count($result) > 0 && $result->fetchArray(SQLITE3_NUM) !== false) {
    $balance = 0;
    $result = $db->query($sql);
    while ($row = $result->fetchArray()) {
        $balance += $row['price'];
    }
    $sql = 'SELECT `notice` FROM `user` WHERE `uid`='.$uid;
    $result2 = $db->query($sql);
    $row2 = $result2->fetchArray();
    $arr = array('code' => '0', 'msg' => 'success', 'balance' => $balance, 'notice' => $row2['notice']);
    echo json_encode($arr);
} else {
    $sql = 'SELECT `notice` FROM `user` WHERE `uid`='.$uid;
    $result2 = $db->query($sql);
    $row2 = $result2->fetchArray();
    $arr = array('code' => '0', 'msg' => 'success', 'balance' => 0, 'notice' => $row2['notice']);
    echo json_encode($arr);
}

$db->close();
