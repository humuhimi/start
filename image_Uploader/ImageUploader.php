<?php
namespace Myapp;



class ImageUploader {

  private $_imageFileName;
  private $_imageType;


// クラスメインメソッド
  public function uploader(){
// アップロード用の関するを作る/エラーチェック/画像タイプチェック/保存/サムネイル作成をする
    try{
      // error check
      $this->_validateUpload();
      // type check
      $ext=$this->_validateImageType();

      var_dump($_FILES);
      // save
      $savePath=$this->_save($ext);
      // データを格納するのではなく、実行するための関数

      // create thumnail


      $this->_createThumbnail($savePath);

$_SESSION['success']='Upload success!';

    }catch(\Exception $e){
      echo $_SESSION['error']=$e->getMessage();
      exit;
    }

// 画像投稿後にindex.phpに戻る
header('Location: http://'.$_SERVER['HTTP_HOST']);
exit();
  }
// __________________________________________________________
// TODO:  Call to undefined method Myapp\ImageUploader::getResults()
public function getResults(){
  $success=null;
  $error=null;
  if(isset($_SESSION['success'])){
    $success=$_SESSION['success'];
    unset($_SESSION['success']);
    // reload毎に毎回nullにしてsessionをしないといけないからunsetする(前の状態に戻る)
  }
  if(isset($_SESSION['error'])){
    $success=$_SESSION['error'];
    unset($_SESSION['error']);
  }
  return [$success,$error];
  // 配列形式の変数をlistに格納する
}

  // TODO:  Undefined variable: Images in /Applications/MAMP/htdocs/image_Uploader/ImageUploader.php on line 60
  // _____________________________________
  // TODO: show thumbnail
public function getImages(){
  $images =[];
  $files=[];
  $imageDir=opendir(IMAGES_DIR);
  while(false !==($file=readdir($imageDir))){
  // $imageDirから一行ずつ読み込んで,$fileに入れて行く　そんでそれがfalseにならない限り回し続ける
    if($file === '.'||$file =='..'){
      // カレントディレクトリやルートディレクトリを飛ばして回す
      continue;
    }
    $files[]=$file;
    //thumnailは imagesdirに存在するのかな
    if(file_exists(THUMBNAIL_DIR.'/'.$file)){
      $images[]=basename(THUMBNAIL_DIR).'/'.$file;
    } else{
      $images[]=basename(IMAGES_DIR).'/'.$file;
    }
  }
  array_multisort($files,SORT_DESC,$images);
  // 逆向き順に$filesに入っているimagesをそr・せよ
  return $images;
}

// -----------------------------------
// errorチェックする
  private function _validateUpload(){
    if (!isset($_FILES['image']) ||!isset($_FILES['image']['error'])) {
      // どちらにしろ error は帰ってくる
      throw new \Exception('upload error!');
    }
    switch ($_FILES['image']['error']) {
     case UPLOAD_ERR_OK:
        // echo "アップロードに成功しました。";
        return true;//別のところで受け取るために返り値を用意する
     break;
      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
        throw new \Exception('file size error!');
      case UPLOAD_ERR_PARTIAL:
      default;
        throw new \Exception('Err:'.$_FILES['image']['error']);
      break;
    }

    // ここのエラーはどこに行くんやろうか
    // なるほど下の方で受け取ることができるんか

//     $FileError=$_FILES['image']['error'];
//     try{
//     if ($FileError!==UPLOAD_ERR_OK) {
//       throw new \Exception("ファイルのアップロードができませんでした");
//     }else if ($FileError==UPLOAD_ERR_NO_TMP_DIR) {
//        throw new \Exception("一時保存のためのファイルがないです");
//     }else {
//       echo "ああああ";
//     }
//   }catch(Exception $e){
//     echo $e->getMessage();
// exit;}
  }
  // -----------------------------------
private function _validateImageType(){
  // TODO:bug
$this->_imageType=exif_imageType($_FILES['image']['tmp_name']);
var_dump($this->_imageType);
switch($this->_imageType){
  case IMAGETYPE_GIF:
     return "gif";
     // ここら辺が$extに入る
	case IMAGETYPE_JPEG:
     return "jpeg";
	case IMAGETYPE_PNG:
     return "png";
  default:
    throw new Exception('file extention error');
}
}
// ------------------------------------------
private function _save($ext){
  $this->_imageFileName = sprintf(
    '%s_%s.$s',time(),
    sha1(uniqid(mt_rand(),true)),
    $ext
  );
  $savePath=IMAGES_DIR.'/'.$this->_imageFileName;
  // IMAGES_DIRってなんぞファイル名か
  // var_dump(IMAGES_DIR);
  $res=move_uploaded_file($_FILES['image']['tmp_name'],$savePath);
  if ($res===false) {
    throw new \Exception ('Could not upload!');
  }
  return $savePath;



  // if (is_uploaded_file($_FILE['image']['tmp'])) {
  //
  // }
}
// ------------------------------------------------
private function _createThumbnail($savePath){
  $imageSize = getimagesize($savePath);
  // savaPathはファイル名
  $width =$imageSize[0];//
  // o,1は
  $height=$imageSize[1];
  if ($width>THUMBNAIL_WIDTH) {
    $this->_createThumbnailMain($savePath,$width,$height);
    // サムネイルを作る
  }
}
 private function _createThumbnailMain($savePath,$width,$height){
   //
   switch($this->_imageType){
     // 画像の種類によって処理が違う
     case IMAGETYPE_GIF;
     $srcImage = imagecreatefromgif($savePath);
     case IMAGETYPE_PNG;
     $srcImage = imagecreatefrompng($savePath);
     case IMAGETYPE_JPEG;
     $srcImage = imagecreatefromjpeg($savePath);
     break;
   }
   // __________________________________________________________
   //thumbheightをheightに合わせてサイズを変更する
   $thumbHeight=round($height * THUMBNAIL_WIDTH/$width);
   $thumbImage = imagecreatetruecolor(THUMBNAIL_WIDTH,$thumbHeight);
   imagecopyresampled($thumbImage,$srcImage,0,0,0,0,THUMBNAIL_WIDTH,$thumbHeight,$width,$height);
   switch($this->_imageType){
     case IMAGETYPE_GIF;
    imagegif($thumbImage,THUMBNAIL_DIR.'/'.$this->_imageFileName);
    // ファイルの出力
     case IMAGETYPE_PNG;
     imagepng($thumbImage,THUMBNAIL_DIR.'/'.$this->_imageFileName);
     case IMAGETYPE_JPEG;
     imagejpeg($thumbImage,THUMBNAIL_DIR.'/'.$this->_imageFileName);
     //
     break;
   }
 }

}


// var_dump($_SERVER['HTTP_HOST']);
// //localhost:8888
// echo "<br>";
// var_dump($_SERVER['PHP_SELF']);
// // /image_Uploader/index.php
// echo "<br>";
// var_dump($_SERVER['SERVER_NAME']);
// // localhost
// echo "<br>";
// var_dump($_SERVER['SCRIPT_NAME']);
// // /image_Uploader/index.php
// echo "<br>";
// var_dump($_SERVER['HTTP_REQUEST']);
// //null
// echo "<br>";
//

?>
