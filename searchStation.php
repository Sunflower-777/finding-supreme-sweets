<?php

  // 座標を渡すとそこに近い駅情報を返す
  function nearSearch(){
    $near_stationSearchURL = 'http://express.heartrails.com/api/json?method=getStations';
    $loc_y = 35.63124288;
    $loc_x = 139.7865704;

    $query_url = $near_stationSearchURL."&x=".$loc_x."&y=".$loc_y;
    echo $query_url;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $query_url);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    $retult = curl_exec($ch);
    curl_close($ch);

    echo $result;
  }
  
  // 駅名を入れると，同じ名前の駅情報をすべて返す
  function stationSearch($query_stationName){
    $stationSearchURL = "http://express.heartrails.com/api/json?method=getStations";
    $stationName = $query_stationName;
    $stationName = mb_convert_encoding($stationName, "UTF-8", "auto");

    $query_url = $stationSearchURL."&name=". urlencode($stationName);

    // ここでセッションを開始し取り出してくる
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $query_url);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    $response = curl_exec($ch); //ここで取り出している
    
    if ($result === false){
      echo curl_error($ch);
    }

    $resultJson = json_decode($response);
    
    $stations = $resultJson->response->station;

    curl_close($ch); //これ忘れるとメモリを占有したままになってしまう
    return $stations;
  }

  $stations = stationSearch("国際展示場");

   //取り出した駅の情報を使いやすいように分割したい
  $latlons = array();
  $lons = array();
  foreach($stations as $station){
    $latlons[] = array($station->y, $station->x);
  }



?>