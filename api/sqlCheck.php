<?php

/*
* sql check
*/

function inject_check($Sql_Str)
{
    $check = preg_match('/select|insert|update|delete|\'|\\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/i', $Sql_Str);
    if ($check) {
        $arr = array('code' => '500', 'msg' => 'Attack warning!');
        echo json_encode($arr);
        die();
    } else {
        return $Sql_Str;
    }
}
