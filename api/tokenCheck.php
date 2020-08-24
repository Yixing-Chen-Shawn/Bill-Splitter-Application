<?php

/*
* token check
*/

require_once 'sqlCheck.php';
$uid = inject_check($_POST['uid']);
$token = inject_check($_POST['token']);

if ($uid == '' || $token == '') {
    $arr = array('code' => '1', 'msg' => 'Incomplete parameters');
    echo json_encode($arr);
    die();
}

require_once 'connect.php';
$sql = 'SELECT `token` FROM `token` WHERE `uid`='.$uid;
$result = $db->query($sql);
if (count($result) > 0 && $result->fetchArray(SQLITE3_NUM) !== false) {
    $result = $db->query($sql);
    $row = $result->fetchArray();
    if (time() - $row['time'] > 604800) {
        if ($token === $row['token']) {
        } else {
            $arr = array('code' => '1', 'msg' => 'login at other place');
            echo json_encode($arr);
            die();
        }
    } else {
        $arr = array('code' => '1', 'msg' => 'token overtime');
        echo json_encode($arr);
        die();
    }
} else {
    $arr = array('code' => '1', 'msg' => 'no token found');
    echo json_encode($arr);
    die();
}
