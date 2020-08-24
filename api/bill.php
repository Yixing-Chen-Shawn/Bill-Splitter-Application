<?php

/*
* get bills
*/

require_once 'tokenCheck.php';
require_once 'sqlCheck.php';
$id = inject_check($_POST['id']);

if ($id == '') {
    $arr = array('code' => '1', 'msg' => 'Incomplete parameters');
    echo json_encode($arr);
    die();
}

require_once 'connect.php';
$sql = 'SELECT `percent` FROM `splitter` WHERE `status`=1 AND `bid`='.$id;
$total = 0;
$result = $db->query($sql);
if (count($result) > 0 && $result->fetchArray(SQLITE3_NUM) !== false) {
    $result = $db->query($sql);
    while ($row = $result->fetchArray()) {
        $total += $row['percent'];
    }
    if ($total == 100) {
        $sql = 'UPDATE `bill` SET `status`=0 WHERE `bid`='.$id;
        $db->exec($sql);
    }
}
$sql = 'SELECT * FROM `bill` WHERE `bid`='.$id;
$result = $db->query($sql);
if (count($result) > 0 && $result->fetchArray(SQLITE3_NUM) !== false) {
    $result = $db->query($sql);
    $sql = 'SELECT * FROM `splitter`,`user` WHERE `splitter`.`uid`=`user`.`uid` AND `bid`='.$id;
    $result2 = $db->query($sql);
    $member = array();
    if (count($result2) > 0 && $result2->fetchArray(SQLITE3_NUM) !== false) {
        $result2 = $db->query($sql);
        while ($row2 = $result2->fetchArray()) {
            $array = array('name' => $row2['name'], 'price' => $row2['price'], 'percent' => $row2['percent'], 'status' => $row2['status']);
            array_push($member, $array);
        }
    }
    $row = $result->fetchArray();
    $data = array('id' => $row['bid'], 'key' => $row['key'], 'content' => $row['content'], 'price' => $row['price'], 'status' => $row['status'], 'member' => $member);
    $arr = array('code' => '0', 'msg' => 'success', 'data' => $data);
    echo json_encode($arr);
} else {
    $arr = array('code' => '1', 'msg' => 'id not exist', 'data' => array());
    echo json_encode($arr);
}

$db->close();
