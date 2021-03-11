<?php

require_once __DIR__ . "/vendor/autoload.php";
$climate = new \League\CLImate\CLImate();
$array = [];
$res = [];
$maskList = [];
$file =  fopen("https://data.nhi.gov.tw/resource/mask/maskdata.csv", 'r');

while ($data = fgetcsv($file)) {
    $maskList[] = $data;
}
fclose($file);
if($argv[1] == null) {
    echo "請重新執行!";
}
else {
    $array = searchMed($maskList, $argv[1]);
} 
if($array != null) {
    usort($array, "medSort");
    $array = deleteRow($array);
    $climate->table($array);
}
else {
    echo "請重新執行!";
}


function searchMed($maskList, $where)
{
    $array = [];
    foreach ($maskList as $arr) {
        if(strstr($arr[2], (string)$where) !== false) {
            $array[] = $arr;
        }
        else {
            continue;
        }
    }
    return $array;
}

function medSort($a, $b)
{
    return $a[4] < $b[4];
}

function deleteRow($array)
{
    for ($i = 0; $i < count($array); $i++) {
        $res[$i][0] = $array[$i][1];
        $res[$i][1] = $array[$i][2];
        $res[$i][2] = $array[$i][4];
    }
    return $res;
}
