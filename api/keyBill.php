<?php

/*
* get bills
*/

require_once 'tokenCheck.php';
require_once 'sqlCheck.php';
$key = inject_check($_POST['key']);

if ($key == '') {
    $arr = array('code' => '1', 'msg' => 'Incomplete parameters');
    echo json_encode($arr);
    die();
}

require_once 'connect.php';
$sql = 'SELECT * FROM `bill` WHERE `key`=\''.$key.'\'';
$result = $db->query($sql);
if (count($result) > 0 && $result->fetchArray(SQLITE3_NUM) !== false) {
    $result = $db->query($sql);
    $row = $result->fetchArray();
    $sql = 'SELECT * FROM `splitter` WHERE `bid`='.$row['bid'];
    $result2 = $db->query($sql);
    $remain = 100;
    if (count($result2) > 0 && $result2->fetchArray(SQLITE3_NUM) !== false) {
        $result2 = $db->query($sql);
        while ($row2 = $result2->fetchArray()) {
            $remain -= $row2['percent'];
        }
    }
    $array = array('type' => $row['type'], 'remain' => $remain);
    $arr = array('code' => '0', 'msg' => 'success', 'data' => $array);
    echo json_encode($arr);
} else {
    $arr = array('code' => '1', 'msg' => 'key not exist', 'data' => $sql);
    echo json_encode($arr);
}

$db->close();
