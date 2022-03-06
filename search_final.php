<!DOCTYPE html PUBLIC "-//W3C/DTD XHTML 1.0 Transitional//JA" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="./static/style.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
   <!-- Make sure you put this AFTER Leaflet's CSS -->
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>
   <!-- plugin -->
   <script src="leaflet.sprite-gh-pages/dist/leaflet.sprite.js"></script>
  <title>Search sweets</title>
</head>
<body>
  <nav class="navbar navbar-expand-lg sticky-top navbar-light bg-light">
    <div class="container-fluid">
      <a href="https://muds.gdl.jp/~s1922006/top.php" class="navbar-brand">FSS</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="https://muds.gdl.jp/~s1922006/top.php">Top</a>
          </li>
          <li class="nav-item">
            <a href="https://muds.gdl.jp/~s1922006/insert_final.php" class="nav-link">スイーツ登録</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container search">
    <form action="./search_final.php" method="post">
      <div class="input-group mb-3">
        <span class="input-group-text">商品名/駅名</span>
        <input type="text" name="item_name" placeholder="スイーツ名/〇〇駅">
        <input type="submit" class="btn btn-lg btn-info" value="調べる！" />
      </div>
    </form>
  

    <?php
      if (isset($_POST['item_name'])){
        $item_name = $_POST['item_name'];
      }
      if (isset($item_name)){
        // DBに接続する
        require('DBACCESS.php');
        $connect = "host=" . $host . " dbname=" . $dbname . " user=" . $user . " password=" . $password;
        $dbconn = pg_connect($connect) or die('Could not connect: '.pg_last_error());

        // PGSQL_NUMオプションを指定すると、データをidで検索できる
        // PGSQL_ASSOCは列名
        // デフォルトだとidも列名も使えるように2通りで返してくれるので
        // 全て出そうとすると2通り分出力し重複して出てくる
      }
    ?>
    <!-- 地図 -->
    <div id=resultsum></div>
    <div class="mapAndInfoWrapper">
      <div id='map'></div>
      <div id='info'></div>
    </div>
  </div>
  
  <!-- 地図の設定 -->
  <script type="text/javascript">
    // 地図中心の初期位置をここで設定
    var map = L.map('map', {
      center: [35.63124288, 139.7865704],
      zoom: 15,
    });

    var tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a><br /><a href="http://express.heartrails.com/">HeartRails Express</a>',
        });
    tileLayer.addTo(map);
    <?php
      if(mb_substr($item_name, -1, 1) == "駅"){
        require('searchStation.php');
        $stations = stationSearch(rtrim($item_name, "駅"));
        //取り出した駅の情報を使いやすいように分割したい
        $latlons = array();
        $stationNames = array();
        foreach($stations as $station){
          $latlons[] = array($station->y, $station->x);
          $stationNames[] = $station->name;
        }
        
        echo "var station = L.marker([".$latlons[0][0].", ".$latlons[0][1]."], {icon: L.spriteIcon('red')}).addTo(map);\n";
          echo "station.bindPopup('<h3>$stationNames[0]</h3>').openPopup();\n";
          $query = "SELECT nearshop.id, nearshop.sname, nearshop.photo, nearshop.comment, nearshop.lat, nearshop.lon, nearshop.pname, nearshop.HP, nearshop.distance 
          FROM 
          ( SELECT 
            sweets.id AS id, sweets.name AS sname, sweets.photo AS photo, sweets.comment AS comment, sweets.lat AS lat, sweets.lon AS lon, shop.name AS pname, shop.HP AS HP,
            ST_Distance(st_setSRID(st_makepoint($1, $2), 4326), st_setSRID(st_makepoint(lon, lat), 4326))*100 AS distance
            FROM sweets JOIN shop ON sweets.shop_id=shop.id
            ) AS nearshop
          WHERE nearshop.distance < 1;";
          // 検索
        $result = pg_query_params($dbconn, $query, array($latlons[0][1], $latlons[0][0])) or die('Could not connect: ' . pg_last_error());
        echo $result[0];

      }else{
        $query = "SELECT sweets.id, sweets.name, sweets.photo, sweets.comment, sweets.lat, sweets.lon, shop.name, shop.HP FROM sweets, shop WHERE ((position($1 in sweets.name)>0 OR position($2 in sweets.category)>0) AND sweets.shop_id=shop.id);";
        // 検索
        $result = pg_query_params($dbconn, $query, array($item_name, $item_name)) or die('Could not connect: ' . pg_last_error());
      }

      echo "var c = 0;";
      echo "var datas = [];";
      echo "var shopLocation = [];";
      // ピンを表示する
      while ($line = pg_fetch_array($result, null, PGSQL_NUM)){
        echo "var m" . $line[0] . "= L.marker([$line[4], $line[5]]).addTo(map);\n";
        echo "shopLocation.push([$line[4], $line[5]]);";
        echo "m" . $line[0]	. ".bindPopup(\"<div class=\\\"popup\\\"><h1 class=\\\"pop-title\\\">" . $line[1] . "</h1><br /><img class=\\\"mini-img\\\" src=\\\"". $line[2] ."\\\"></div>\").openPopup();\n";
        echo "c += 1 ;\n";
        echo "var info" . $line[0] . "= \"<i id=\\\"back\\\" class=\\\"bi bi-caret-left-fill\\\"></i><i id=\\\"next\\\" class=\\\"bi bi-caret-right-fill\\\"></i><h1 class=\\\"item-title\\\">" . $line[1] . "</h1><img class=\\\"info-img\\\" src=\\\"". $line[2] ."\\\"><h2 class=\\\"content-title\\\">comment</h2><p class=\\\"comment\\\">".$line[3]."</p><h2 class=\\\"content-title\\\">shop</h2><p class=\\\"shop-name\\\">".$line[6]."</p><a class=\\\"web\\\" href=\\\"".$line[7]."\\\">詳しくはwebで→</a>\";\n";
        echo "var i = info" . $line[0] . ";\n";
        echo "datas.push(i);\n";
        echo "var Information = document.getElementById(\"info\");\n";
        echo "Information.innerHTML = info" . $line[0] . ";\n";
      }

    ?>

    var cursor = c - 1;

    // マップの初期中心座標をここで再設定する
    jumpMapCenter(shopLocation[cursor]);

    var resultsum = "<p>"+c+"件見つかりました！</p>";
    var ResultSum = document.getElementById('resultsum');
    ResultSum.innerHTML = resultsum;
    
    // 地図の中心を移動させる関数
    function jumpMapCenter(latlng){
      var point = L.latLng(latlng[0], latlng[1]);
      map.setView(point);
    }
    
    function backMenu(){
      if(cursor == 0 ){
        cursor = c - 1;
        Information.innerHTML = datas[cursor];
      }else{
        cursor -= 1;
        Information.innerHTML = datas[cursor];
      }
      jumpMapCenter(shopLocation[cursor]);
    };

    function nextMenu(){
      if(cursor == c-1 ){
        cursor = 0;
        Information.innerHTML = datas[cursor];
      }else{
        cursor += 1;
        Information.innerHTML = datas[cursor];
      }
      jumpMapCenter(shopLocation[cursor]); 
    };

    Information.onclick = function(){
      document.getElementById('back').removeEventListener('click', backMenu);
      document.getElementById('next').removeEventListener('click', nextMenu);
      document.getElementById('back').addEventListener('click', backMenu);
      document.getElementById("next").addEventListener('click', nextMenu);
    };

  </script>

  <footer>
    &copy; 2021 Helianthus annuus
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

  <!-- footerの高さを自動調整するスクリプト -->
  <script type='text/javascript'>
    var navbarHeight = document.getElementsByClassName('navbar')[0].offsetHeight;
    var containerHeight = document.getElementsByClassName('container')[0].offsetHeight;
    var bodyHeight = document.getElementsByTagName('body')[0].offsetHeight;
    var footerElement = document.getElementsByTagName('footer');
    
    footerElement[0].style.height = (bodyHeight - (navbarHeight + containerHeight))+'px';
    if (footerElement[0].offsetHeight <= 69){
      footerElement[0].style.height = 68 + 'px';
    }
  </script>
</body>
</html>
