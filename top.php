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
  <title>Search sweets</title>
</head>
<body>
  <nav class="navbar navbar-expand-lg sticky-top navbar-light bg-light">
    <div class="container-fluid">
      <a href="#" class="navbar-brand">FSS</a>
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

  <div class="container">
    <img src="./images/title.png" id="title-img" alt="タイトル">
    <p class="top-comment">　これは、みんなの至高のスイーツを地図上にマッピングし、検索できるアプリです。探すのはもちろん、位置情報付き画像を用いて自分の「推し」スイーツを投稿することもできます。スイーツの種類でも、特定の商品名でも、とりあえず検索してみてください。</p>
    <form action="./search_final.php" method="post">
      <div class="input-group input-group-lg">
        <span class="input-group-text" id="inputGroup-sizing-lg">キーワード</span>
        <input type="text" name="item_name" class="input_keyword" aria-describedby="inputGroup-sizing-lg" placeholder="至高のスイーツを探しに行こう！">
        <input type="submit" class="btn btn-lg btn-info" value="探す！" />
      </div>
    </form>

    <iframe src="https://docs.google.com/presentation/d/e/2PACX-1vQqflq9ncM6NS_Bb984QW8yyuAJLFcI9TppMpZvhZCrxRge9-vNCZw-fbplWgx6LA/embed?start=false&loop=false&delayms=3000" frameborder="0" width="640" height="389" allowfullscreen="true" mozallowfullscreen="true" webkitallowfullscreen="true" class="howtoSlide"></iframe>
  </div>

  <footer>
    &copy; 2021 Helianthus annuus
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
