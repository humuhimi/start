<?php
namespace Myapp;

// ini_set('display_errors',1);

class Calender{
  // public $ini;
 public $prev;
  public $next;
  public $yearMonth;
  private $_thisMonth;
  // よく使う奴はprivateにしておく
  function __construct(){

    try {
    if(!isset($_GET['t'])||!preg_match('/\A\d{4}-\d{2}\z/',$_GET['t'])){
      throw new \Exception();
    }
      $this->_thisMonth = new \Datetime($_GET['t']);

    }catch (\Exception $e) {
      $this->_thisMonth = new \Datetime('first day of this month');
    }
    $this->prev =$this->_createPrevLink();
    $this->next =$this->_createNextLink();
    $this->yearMonth=$this->_thisMonth->format('Y-m');
    // var_dump($this->yearMonth);
    // echo __LINE__;
    // echo "<br>";
    // +2 Month担っているので注意
  }
  private function _createPrevLink(){

   $dt= clone $this->_thisMonth;
  return  $dt->modify(' -1 month')->format('Y-m');
  }
  private function _createNextLink(){
    $dt= clone $this->_thisMonth;
    return $dt->modify(' +1 month')->format('Y-m');
    // とりあえず独立してると考えて+1にしておくで
  }
  public function show(){
  $tail=$this->_getTail();
  $body=$this->_getBody();
  $head=$this->_getHead();
  $html='<tr>'.$tail.$body.$head.'</tr>';
  echo $html;
  }

  //_getTailmethod
private function _getTail(){
  $tail='';
  $lastDayOfPreMonth=new \DateTime('last day of '.$this->yearMonth.' -1 month');
  while($lastDayOfPreMonth->format('w')<6){
  $tail .=sprintf('<td class="gray">%d</td>',$lastDayOfPreMonth->format('d'));
  $lastDayOfPreMonth->sub(new \DateInterval('P1D'));
  }
    return $tail;
}

//_getBodymethod
private function _getBody(){
  $body='';
  $period = new \DatePeriod(
    new \DateTime('first day of '.$this->yearMonth),
    new \DateInterval('P1D'),
    new \DateTime('first day of '.$this->yearMonth.'+1month')
  );
  $today=new \Datetime('today');
  foreach ($period as $day) {
    if($day->format('w')==0){$body .=sprintf('<tr>');}
    $todayClass=($today->format('Y-m-d')==$day->format('Y-m-d'))?'today':'';
     $body .= sprintf('<td class="youbi_%d %s">%d</td>',$day->format('w'),$todayClass, $day->format('d'));
  }
  return $body;
}

// _getHeadmetod
private function _getHead(){
$head='';
$firstDayOfNextMonth=new \DateTime("first day of ".$this->yearMonth.'+1month');
// var_dump($firstDayOfNextMonth);
while($firstDayOfNextMonth->format('w')>0){
  $head.=sprintf('<td class="gray">%d</td>',$firstDayOfNextMonth->format('d'));
  $firstDayOfNextMonth->add(new \DateInterval('P1D'));
  // var_dump($firstDayOfNextMonth->format('w'));
}
return $head;
}
}
// ?>
