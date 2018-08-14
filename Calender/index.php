<?php
require "Calender.php";
// ini_set('display_errors',1);
// error_reporting(E_ALL);


function h($s){
  $t=htmlspecialchars($s,ENT_QUOTES,'UTF-8');
  return $t;
}
$cal=new \Myapp\Calender;
// $calはmyapp\calenderclass
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Calendar</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <table>
    <thead>
      <tr>
        <th><a href="/Calender/?t=<?php echo h($cal->prev)?>">&laquo;</a></th>
        <!-- 2018-07にすればいい  -->
        <th colspan="5"><?php echo h($cal->yearMonth);?></th>
        <!--最後にyearmonth をaug型に変換する  -->
        <th><a href="/Calender/?t=<?php echo h($cal->next)?>">&raquo;</a></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Sun</td>
        <td>Mon</td>
        <td>Tue</td>
        <td>Wed</td>
        <td>Thu</td>
        <td>Fri</td>
        <td>Sat</td>
      </tr>
    <?php $cal->show(); ?>


     </tbody>
     <tfoot>
       <tr>
         <th colspan="7"><a href="?t=2018-08">Today</a></th>
       </tr>
     </tfoot>
   </table>
 </body>
 </html>
