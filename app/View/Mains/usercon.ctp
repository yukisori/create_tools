
<?php echo $this->fetch('init_calender'); ?>

<script type="text/javascript">
var searchCtrl = function($scope) {
	$scope.items = [
	<?php foreach ($User as $Users): ?>
	{"name":"<?php echo $Users['User']['name'];?>","id":"<?php echo $Users['User']['id'];?>"},
	<?php endforeach;?>
	 ];
}
</script>
  <div id="submenu">
  <ul>
  <li><?php echo $this->Html->link('ユーザー登録',array('controller'=>'mains','action'=>'useradd'));?></li>
  </ul>
  </div>
<!-- 検索ロジック -->
<div ng-controller="searchCtrl">
  <div id="name_search">
  	<p><input type="text" ng-model="query.name"></p>
	<div id="search_result">
	  <h2>検索結果</h2>
	  <ul>
	   <li ng-repeat="item in items|filter:query"><?php echo $this->Html->link("{{item.name}}" ,'/mains/usercon/'."{{item.id}}".'/'.$year.'-'.$month);?>
	   </li>
	  </ul>
  	</div>
  </div>
</div>


	<?php $total_time = 0;?>
	<?php foreach ($total as $totals): ?>
		<?php $total_time += $totals['User']['total_sum'] ;?>
	<?php endforeach ?>
	<p class="member_state"><?php if(isset($use['User']['id'])) echo $use['User']['name']."さんの仕事状況".'<br>'."総労働時間　".$total_time."　時間";?></p>

	 <table border="1">
       <tr>
          <th colspan="2">
          <?php //先月に戻る
          $pmonth = date("m",strtotime("-1 month", $first_date));
          $pyear = date("Y",strtotime("-1 month", $first_date));

          echo $this->Html->link('先月','/mains/usercon/'.$use['User']['id'].'/'.$pyear.'-'.$pmonth.'/?date='.strtotime("-1 month", $first_date))
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
          echo $this->Html->link('次月','/mains/usercon/'.$use['User']['id'].'/'.$fyear.'-'.$fmonth.'/?date='.strtotime("+1 month", $first_date))
          ;?>
          </th>
       </tr>
	</table>
	<br><br>

	<table>
		   <tr>
	       	<td>案件番号</td>
	       	<td>案件内容</td>
	       	<td>担当社名</td>
	       	<td>総労働時間</td>
          <td>労働割合</td>
	       </tr>
	       <?php foreach($anken_total as $anken_totals): ?>
	       <tr>
	       	<td><?php echo $anken_totals['Matter']['number'];?></td>
	       	<td><?php echo $anken_totals['Matter']['mcontent'];?></td>
	       	<td><?php echo $anken_totals['Matter']['mcompany'];?></td>
	       	<td><?php echo $anken_totals['Matter']['anken_sum'];?>時間</td>
          <td><?php echo round(($anken_totals['Matter']['anken_sum']/$total_time)*100); ?>%</td>
	       </tr>
	  		<?php endforeach;?>
	</table>

<script>
$(function() {
    $.jqplot(
        'circle_anken',
        [
            [
             <?php foreach($anken_total as $anken_totals): ?>
                [ '<?php echo $anken_totals['Matter']['mcontent'];?>',<?php echo $anken_totals['Matter']['anken_sum'];?> ],
              <?php endforeach;?>
            ]
        ],
        {
            seriesDefaults: {
                renderer: jQuery . jqplot . PieRenderer,
                rendererOptions: {
                    padding: 5,
                    showDataLabels: true
                }
            },
            legend: {
                show: true,
                location: 's',
                rendererOptions: {
                    numberColumns: 3
                },
            }
        }
    );
} );
</script>
<?php if($total_time > 0):?>
<div id="circle_anken" style="height: 400px; width: 400px; margin-left:auto; margin-right:auto;"></div>
<?php endif;?>

