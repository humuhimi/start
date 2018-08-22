<?php

session_start();





ini_set('display_errors',1);
error_reporting(E_ALL);

define("MAX_FILE_SIZE",1*1024*1024);
define("THUMBNAIL_WIDTH",400);
define("IMAGES_DIR",__DIR__.'/images');
// 普通にここで定義してるやん
// 絶対パスの定義
define("THUMBNAIL_DIR",__DIR__.'/thumbs');
// var_dump(IMAGES_DIR);
// var_dump(ITHUMBNAIL_DIR);
// GD(画像処理)のファンクションを用意する→
// imagecreatetruecolorを別のファイルからrequireしている
if(!function_exists('imagecreatetruecolor')){

  echo "GD not installed!";
  exit;
}

function h($s){
 return  htmlspecialchars($s,ENT_QUOTES,'UTF-8');
}

require "ImageUploader.php";
// TODO:  thrown in /Applications/MAMP/htdocs/image_Uploader/index.php on line 33
$uploader=new Myapp\ImageUploader();
// $uploaderにはクラスを入れるだけ。
// インスタンス化させて使う
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $uploader->uploader();
}
list($success,$result)=$uploader->getResults();
$images=$uploader->getImages();
// TODO: getimges modify

 ?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
  <meta charset="utf-8" >
  <title>画像アップロード</title>
  <style type="text/css">
  body{
    text-align:center;
    font-family:Arial,sans-serif;
  }
  ul{
    list-style:none;
    margin: 0;
    padding: 0;
  }
  li{
    margin-bottom:5px;
  }
  input[type=file]{
    /*ここを透明にしつつ,ボタンを広げる*/
    position:absolute;
    top:0;
    left:20px;
    width:341px;
    height:50px;
    cursor:pointer;
    opacity:0;
  }
  input[type=submit]{
    opacity:0;
  }
  .btn{
    position:relative;
    display:inline-block;
    width:300px;
    padding:7px;
    border-radious:5px;
    margin:10px auto 20px;
    color:#fff;
    box-shadow: 0 4px  #0088cc;
    background:#00aaff;
  }
  /*TODO:fix hover*/
  .btn:hover{
    opacity: 0.8;
  }

  </style>
</head>
<body>
<p>ここに画像をアップロードしてください</p>
<div class="btn">
  upload!
<form action="" method="POST" enctype="multipart/form-data">
  <label for="image">
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo h(MAX_FILE_SIZE); ?>">
  <input type="file" id="image" name="image" accept="image/*" >
  </label>
  <label id="submit">
  <input type="submit" name="submit" id="submit" value="アップロード" >
<!--TODO:fix no submit  -->
  </label>
</form>
</div>
<?php if(isset($sucess)) : ?>
  <div class="msg sucess"><?php echo h($success); ?></div>
<?php endif; ?>

<?php if(isset($error)) : ?>
  <div class="msg error"><?php echo h($error); ?></div>
<?php endif; ?>

<ul>
  <!-- TODO:    Invalid argument supplied for foreach() in /Applications/MAMP/htdocs/image_Uploader/index.php on line 70 -->
  <?php foreach ($images as $image) :?>
    <li>
      <a href="<?php echo h(basename(IMAGES_DIR)).'/'.basename($image); ?>">
            <!--とりあえずIMAGES_DIRよりだけを取り出している  -->
        <img src="<?php echo h($image); ?>">
      </a>
    </li>
  <?php endforeach; ?>
</ul>
<!--TODO:bug  -->
<script src="https://ajax.googleapis.com/ajax/libs/dojo/1.13.0/dojo/dojo.js"></script>
<script>
$(function(){
  $('.msg'),fadeOut(3000);
  // 1000で1秒　つまりはミリ秒
});
</script>
</body>
</html>
