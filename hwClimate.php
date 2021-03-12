<?php
/*
*@$climate composer套件
*@var $maskList 讀取出來的csv檔儲存為array
*@var $array 儲存轉換過searchMed的array
*@var $file 開啟衛福部maskdata.csv檔案
*
*/

require_once __DIR__ . "/vendor/autoload.php";
$climate = new \League\CLImate\CLImate();
$array = [];
$maskList =  [];
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

/*
*搜尋輸入地區的地點
*
*＠var $array 儲存符合條件之資料
*
*@return array
*/
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

/**usort以array[4]做大小排列*/
function medSort($a, $b)
{
    return $a[4] < $b[4];
}

/*
*刪除多餘的行，留下需要的檔案
*
*@ return array
*/
function deleteRow($array)
{
    for ($i = 0; $i < count($array); $i++) {
        $res[$i][0] = $array[$i][1];
        $res[$i][1] = $array[$i][2];
        $res[$i][2] = $array[$i][4];
    }
    return $res;
}
