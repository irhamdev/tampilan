<?php 

include ('db_connect.php');
$sub = $_POST ['sub'];
$kata = $_POST ['kata'];

$start = microtime(true);

$hasilterjemahan = array ();

function multiexplode ($delimiters,$data) {
    $MakeReady = str_replace($delimiters, $delimiters[0], $data);
    $Return    = explode($delimiters[0], $MakeReady);
    return  $Return;
}

$kalimat = multiexplode(array(" ",", ","()",","), $kata);

$getDatahigh = "SELECT * FROM `dayakkata` WHERE dayakkata.id_dayakmaster = $sub";
$data = mysqli_query($link, $getDatahigh);

$getDataAll = array ();
foreach ($data as $key => $value) {
    $idbindo = $value['id_dayakkata'];
    $getDataAll[] = $idbindo; //all data 
}

$datakey = "SELECT * FROM `dayakkata` WHERE dayakkata.id_dayakmaster = $sub";
$datakunci = mysqli_query($link, $datakey);
$datakunciall = array ();

foreach ($datakunci as $key2 => $value) {
    $datakunciall[] = $value;
}

// print("<pre>".print_r($datakunciall,true)."</pre>");


// fungsi Pencarian_Interpolasi 
function Pencarian_Interpolasi ($getDataAll, $key) {
    $low = 0;
    $high = count($getDataAll) - 1;
    $pos;
    while($low <= $high && $key >= $getDataAll[$low] && $key <= $getDataAll[$high]) {
        $rise = $high - $low;
        $run = $getDataAll[$high] - $getDataAll[$low];
        $x = $key - $getDataAll[$low];
        $pos = $low + ($rise / $run ) * $x ;

        if ($getDataAll[$pos] == $key) {
            return $pos+1;

        } elseif ($key < $getDataAll[$pos]) {
            $high = $pos -1;
            
        } elseif ($key > $getDataAll[$pos]) {
            $low = $pos +1;
            
        } 
    }
    return -1;
}
//end

foreach ($kalimat as $kata) {
    $query = "SELECT * FROM `dayakkata` INNER JOIN bindo ON dayakkata.id_bindo = bindo.id_bindo INNER JOIN dayakmaster ON dayakkata.id_dayakmaster = dayakmaster.id_dayakmaster WHERE `bindo`.`teks_indo` = '$kata' AND `dayakkata`.`id_dayakmaster` = $sub";
    $st = mysqli_query($link, $query);
    if($st->num_rows == 0) {
        $kata = '<span style="color : red">'.$kata.'</span>';
        $hasilterjemahan[] = array('kata' => $kata, 'teks_dayak' => $kata, 'suaradayak' => 'Tidak Ada Suara');
    } else {
        while($row = mysqli_fetch_array($st)) {
            $key = $row['id_dayakkata'];
        }        
        $position = Pencarian_Interpolasi($getDataAll, $key);
        $dataposisi = $datakunciall[$position -1 ];
   
        $nilaiposisi = $dataposisi['id_dayakkata'];
        $queryHasil = "SELECT * FROM `dayakkata` INNER JOIN bindo ON dayakkata.id_bindo = bindo.id_bindo INNER JOIN dayakmaster ON dayakkata.id_dayakmaster = dayakmaster.id_dayakmaster WHERE `dayakkata`.`id_dayakkata` = '$nilaiposisi'";
        $sthasil = mysqli_query($link, $queryHasil);
        $arrayterjemahan = array();
        while($rowhasil = mysqli_fetch_array($sthasil)) {
            $teksdayak = $rowhasil['teks_dayak']; // get Key Kata
            $suaradayak = $rowhasil['suara_dayak']; // get Key Kata
            $hasilterjemahan[] = array('kata' => $kata,'teks_dayak' => $teksdayak, 'suaradayak' => $suaradayak);
        }
    }
     
}


// print("<pre>".print_r($nilaiposisi,true)."</pre>");

$gabungan = implode(' ', array_map(function ($entry) {
    return $entry['teks_dayak'];
  }, $hasilterjemahan));


$suara = $hasilterjemahan[0]['suaradayak'];
  
$titlesuara = array();
foreach ($hasilterjemahan as $key => $item) {
    
    $titlesuara[] = $item['suaradayak'];

}

$end = microtime(true);
$cari = abs($start-$end);
$waktu = number_format ($cari,5,",",".");

$kalimatterjemahan = array('kata' => $gabungan,'suara' => $titlesuara, 'waktu' => $waktu);
$data = json_encode($kalimatterjemahan);
echo $data;

?>