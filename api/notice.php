<?php

/*
* get bills
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
$sql = 'SELECT * FROM `notice` WHERE `uid`='.$uid;
$result = $db->query($sql);
if (count($result) > 0 && $result->fetchArray(SQLITE3_NUM) !== false) {
    $data = array();
    $result = $db->query($sql);
    while ($row = $result->fetchArray()) {
        $time = date('Y-m-d H:i:s', $row['time']);
        $array = array('content' => $row['content'], 'time' => $time);
        array_push($data, $array);
    }
    $arr = array('code' => '0', 'msg' => 'success', 'data' => $data);
    echo json_encode($arr);
} else {
    $arr = array('code' => '1', 'msg' => 'no notice', 'data' => array());
    echo json_encode($arr);
}

$db->close();
