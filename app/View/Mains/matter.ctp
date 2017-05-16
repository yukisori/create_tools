<h3 style="text-align:center;">
  <?php echo $matter_read['Matter']['number'];?>
  <?php echo $matter_read['Matter']['mcompany'];?>
  <?php echo $matter_read['Matter']['mcontent'];?>
</h3>
<br>
      <?php
      // カレンダーの年月をタイムスタンプを使って指定
        if (isset($_GET["date"]) && $_GET["date"] != "") {
         $date_timestamp = $_GET["date"];
        } else {
         $date_timestamp = time();
        }

        $month = date("m", $date_timestamp);
        $year = date("Y", $date_timestamp);
        $first_date = mktime(0, 0, 0, $month, 1, $year);
        $last_date = mktime(0, 0, 0, $month + 1, 0, $year);
        // 最初の日と最後の日の｢日にち」の部分だけ数字で取り出す。
        $first_day = date("j", $first_date);
        $last_day = date("j", $last_date);
        // 全ての日の曜日を得る。
        for($day = $first_day; $day <= $last_day; $day++) {
         $day_timestamp = mktime(0, 0, 0, $month, $day, $year);
         $week[$day] = date("w", $day_timestamp);
      }
      ?>
	 <table border="1">
       <tr>
          <th colspan="2">
          <?php //先月に戻る
          $pmonth = date("m",strtotime("-1 month", $first_date));
          $pyear = date("Y",strtotime("-1 month", $first_date));
          echo $this->Html->link('先月','/mains/matter/'.$matter_read['Matter']['number'].'/'.$pyear.'-'.$pmonth.'/?date='.strtotime("-1 month", $first_date))
          ;?>
          </th>
          <th colspan="3">
          <?php //選択した年と月
          print(date("Y", $date_timestamp) . "年" . date("n", $date_timestamp) . "月")
          ;?>
          </th>

          <th colspan="2">
          <?php //次月へ進む
          $fmonth = date("m",strtotime("+1 month", $first_date));
          $fyear = date("Y",strtotime("+1 month", $first_date));
          echo $this->Html->link('次月','/mains/matter/'.$matter_read['Matter']['number'].'/'.$fyear.'-'.$fmonth.'/?date='.strtotime("+1 month", $first_date))
          ;?>
          </th>
       </tr>
	</table>

<table>
<tr>
	<th>ユーザー名</th>
	<th>割合</th>
  <th>案件労働時間</th>
	<th>総労働時間</th>
</tr>

<?php
  $arr1 = array();
  $arr2 = array();
  foreach ($emp as $key_1) { $arr1[] = $key_1; }
  foreach ($Useranken_total as $key_2) { $arr2[] = $key_2; }
?>

<?php  foreach ($arr1 as $key=>$emps):?>
<tr>
<?php $Useranken_totals = $arr2[$key];?>

<td><?php echo h($emps['User']['name']);?></td>
<td><?php echo round(($emps['User']['anken_sum']/$Useranken_totals['User']['total_sum'])*100,2);?>%</td>
<td><?php echo $emps['User']['anken_sum'];?>時間</td>
<td><?php echo $Useranken_totals['User']['total_sum'];?>時間</td>
</tr>
<?php endforeach;?>

</table>
<br>
<br>
<br>
<br>