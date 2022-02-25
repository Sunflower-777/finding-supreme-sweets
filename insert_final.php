<!DOCTYPE html PUBLIC "-//W3C/DTD XHTML 1.0 Transitional//JA" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="./static/style.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
   <!-- <link rel="stylesheet" href="./static/style.css"> -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
   <!-- Make sure you put this AFTER Leaflet's CSS -->
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>
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
            <a class="nav-link" href="https://muds.gdl.jp/~s1922006/top.php">Top</a>
          </li>
          <li class="nav-item">
            <a href="https://muds.gdl.jp/~s1922006/insert_final.php" class="nav-link active"  aria-current="page" >スイーツ登録</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>


  <div class="container">
    <p class="title">スイーツ情報登録</p>

    <form class="formList" action="./insert_final.php" method="post" enctype="multipart/form-data">
      <div class="input-group mb-3">
        <span class="input-group-text">商品名</span>
        <input type="text" name="item_name" placeholder="商品名" maxlength=13>
      </div>
      <div class="input-group mb-3">
        <span class="input-group-text">位置情報付き商品画像</span>
        <input type="file" name="item_photo" class="form-control" placeholder="位置情報付き画像">
      </div>
      <div class="input-group mb-3">
        <span class="input-group-text">撮影地の緯度経度</span>
        <input type="text" name="item_latlon" id="place_latlon" class="form-control" placeholder="下の地図にピンを打つか 緯度,経度  の形で入力（画像に位置情報がない場合）">
      </div>
      <div class="input-group mb-3">
        <span class="input-group-text">カテゴリ</span>
        <input type="text" name="item_category" placeholder="チョコ、ケーキ、など" maxlength=7>
      </div>
      <div class="input-group mb-3">
        <span class="input-group-text">コメント（任意）</span>
        <input type="text" name="item_comment" placeholder="感想を書いてね（任意）" value="" maxlength=102>
      </div>
      <div class="input-group mb-3">
        <span class="input-group-text">購入店舗名</span>
        <input type="text" name="item_shop" placeholder="店舗名" maxlength=30>
      </div>
      <div class="input-group mb-3">
        <span class="input-group-text">ホームページURL（任意）</span>
        <input type="text" name="item_HP" placeholder="URL（任意）" value="" maxlength=100>
      </div>

      <input type="submit" class="btn btn-lg btn-info" value="登録" />
    </form>

    <?php
      $flag = 0;
      $success = 0;
      if (isset($_POST['item_name']) && isset($_FILES['item_photo']) && isset($_POST['item_category']) && isset($_POST['item_shop'])){
        $item_name = $_POST['item_name'];
        $item_photo = $_FILES['item_photo'];
        $item_latlon = $_POST['item_latlon'];
        $item_category = $_POST['item_category'];
        $item_comment = $_POST['item_comment'];
        $item_shop = $_POST['item_shop'];
        $item_HP = $_POST['item_HP'];
        $flag = 1;

        // $imgType = mime_content_type($item_photo);
        // $reciveFileType = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif');
        // if (in_array($imgType,$reciveFileType) == False){
        //   $flag = 0;
        //   echo "<p class=\"message success\">画像はpngかjpeg, jpg, gifのみ入力いただけます</p>";
        // }
      }
      else{
        echo "<p class=\"message success\">登録する情報を入力してください</p>";
      }

      // 60進数から10進数に変換
      function convert_10_to_60($ref, $gps)
      {
        $data = floatval( $gps[0] ) + (floatval($gps[1]) / 60) + (floatval($gps[2]) / 3600);
        return ($ref=='S'||$ref=='W') ? ($data * -1):$data;
      }
      // [例:986/100]という文字列を[986÷100=9.86]というように数値に変換する関数
      function convert_float($str)
      {
        $val = explode('/', $str);
        return (isset($val[1])) ? $val[0]/$val[1]: $str;
      }
      // 入力した分数を少数に変換
      function convert_gps($str_gps)
      {
        $gps = array();
        for ($i = 0; $i < 3; $i++){
          array_push($gps, convert_float($str_gps[$i]));
        }
        return $gps;
      }

      // echo $flag;
      if ($flag == 1){
        // 画像を保存する
        $save = './images/'.basename($_FILES['item_photo']['name']);
        move_uploaded_file($_FILES['item_photo']['tmp_name'], $save);
        $exif = @exif_read_data($save);
        if($exif['GPSLatitude']){
          $lats = convert_gps($exif['GPSLatitude']);
          $lat = convert_10_to_60($exif['GPSLatitudeRef'], $lats);
          $lons = convert_gps($exif['GPSLongitude']);
          $lon = convert_10_to_60($exif['GPSLongitudeRef'], $lons);
          $date = strtotime($exif['DateTimeOriginal']);
          $date = date('Y-m-d', $date);

          // その他データをDBに保存する
          require('DBACCESS.php');
          $connect = "host=" . $host . " dbname=" . $dbname . " user=" . $user . " password=" . $password;
          $dbconn = pg_connect($connect) or die('Could not connect: '.pg_last_error());
          // DBに保存
          $insert_shop = "INSERT INTO shop(name, hp) VALUES($1, $2);";
          $res1 = pg_query_params($dbconn, $insert_shop, array($item_shop, $item_HP)) or die('Could not connect: ' . pg_last_error());
          $query_id = "SELECT id FROM shop WHERE name=$1;";
          $shop_id = pg_query_params($dbconn, $query_id, array($item_shop)) or die('Could not connect: ' . pg_last_error());

          while ($line = pg_fetch_array($shop_id, null, PGSQL_NUM)){
            $insert_sweets = "INSERT INTO sweets(name, photo, category, comment, lat, lon, location, date, shop_id) VALUES($1, '$save', $2, $3, $lat, $lon, '($lat, $lon)', $4, $line[0]);";
            $res2 = pg_query_params($dbconn, $insert_sweets, array($item_name, $item_category, $item_comment, $date)) or die('Could not connect: ' . pg_last_error());
          }
        }
        elseif(!$item_latlon==""){
          // echo "ok";
          $latlon = explode(",", $item_latlon);
          $lat = $latlon[0];
          $lon = $latlon[1];
          $date = strtotime($exif['DateTimeOriginal']);
          $date = date('Y-m-d', $date);

          // その他データをDBに保存する
          require('DBACCESS.php');
          $connect = "host=" . $host . " dbname=" . $dbname . " user=" . $user . " password=" . $password;
          $dbconn = pg_connect($connect) or die('Could not connect: '.pg_last_error());
          // DBに保存
          $insert_shop = "INSERT INTO shop(name, hp) VALUES($1, $2);";
          $res1 = pg_query_params($dbconn, $insert_shop, array($item_shop, $item_HP)) or die('Could not connect: ' . pg_last_error());
          $query_id = "SELECT id FROM shop WHERE name=$1;";
          $shop_id = pg_query_params($dbconn, $query_id, array($item_shop)) or die('Could not connect: ' . pg_last_error());

          while ($line = pg_fetch_array($shop_id, null, PGSQL_NUM)){
            $insert_sweets = "INSERT INTO sweets(name, photo, category, comment, lat, lon, location, date, shop_id) VALUES($1, '$save', $2, $3, $lat, $lon, '($lat, $lon)', $4, $line[0]);";
            $res2 = pg_query_params($dbconn, $insert_sweets, array($item_name, $item_category, $item_comment, $date)) or die('Could not connect: ' . pg_last_error());
            $success = 1;
          }
        }
        else{
          echo "<p class=\"message error\">位置情報がついていないようです…マップにピンをさして緯度経度を入力してください。</p>";
        }
      }
      if($success==1){
        echo "<p class=\"message success\">登録できました！</p>";
      }

    ?>

    <!-- <div id="map" style="height: 775px;"></div> -->
    <div id="map"></div>
    
  </div>
  
  <footer>
    &copy; 2021 Helianthus annuus
  </footer>

  <script type="text/javascript">
    var map = L.map('map', {
      <?php
        //echo "center: [$clat, $clon],";
        echo "center: [35.63124288, 139.7865704],";
      ?>
      zoom: 15,
    });
    var tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy;<a href="http://osm.org/copyright">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
    });
    tileLayer.addTo(map);
    <?php
      echo "var Layer = L.marker([35.63124288, 139.7865704]);";
      echo "Layer.addTo(map);";
    ?>

    // マップをクリックしてピンを刺すところ
    map.on('click', function(e){
      Layer.remove();
      Layer = L.marker(e.latlng);
      Layer.addTo(map);
      document.getElementById("place_latlon").value = String(e.latlng.lat) + ',' + String(e.latlng.lng);
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
