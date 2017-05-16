<?php echo $this->fetch('init_calender'); ?>
<?php if($userinfo['role'] == "1"):?>
  <!-- /********* 検索のエリア ***********/-->
  <script type="text/javascript">
        //スクリプト文章
        var searchCtrl = function($scope) {
          $scope.items = [
          <?php foreach ($User as $Users): ?>
          {"name":"<?php echo $Users['User']['name'];?>","id":"<?php echo $Users['User']['id'];?>"},
          <?php endforeach;?>
           ];
        }
  </script>
  <?php if(!isset($select_user['User']['name']))://選択されていなかったら表示?>
  <div id="Users_SeachBox">
   <div ng-controller="searchCtrl">
    <div id="name_search">
      <h3 style="text-align: center;">※ユーザーを選択してください</h3>
      <p><input type="text" ng-model="query.name"></p>
      <div id="search_result">
      <h2>検索結果</h2>
      <ul>
       <li ng-repeat="item in items|filter:query"><?php echo $this->Html->link("{{item.name}}" ,'/users/index/'."{{item.id}}".'/'.$year.'-'.$month);?>
       </li>
      </ul>
      </div>
    </div>
   </div>
  </div>
  <?php endif;?>
<?php endif;?>

  <?php
    $guest = $userinfo['role'] == "1"  ? $select_user['User'] : $userinfo;
    if (is_null($guest)) { $guest = $userinfo; }
  ;?>

<!-- /********* カレンダーエリア ***********/-->
<div id="con_calender">
  <?php if($userinfo['id'])://ユーザーが選択されていたら表示;?>
  <div id="calender">
      <table border="1">
       <tr>
          <th colspan="2">
          <?php //先月に戻る
          $pmonth = date("m",strtotime("-1 month", $first_date));
          $pyear = date("Y",strtotime("-1 month", $first_date));
          echo $this->Html->link('先月','/users/index/'.h($guest['id']).'/'.$pyear.'/'.$pmonth.'/?date='.strtotime("-1 month", $first_date))
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
          echo $this->Html->link('次月','/users/index/'.h($guest['id']).'/'.$fyear.'/'.$fmonth.'/?date='.strtotime("+1 month", $first_date))
          ;?>
          </th>
       </tr>
       <tr>
          <th>日</th>
          <th>月</th>
          <th>火</th>
          <th>水</th>
          <th>木</th>
          <th>金</th>
          <th>土</th>
       </tr>
       <tr>
        <?php
        // カレンダーの最初の空白部分
        for ($i = 0; $i < $week[$first_day]; $i++) {
         print("<td></td>\n");
        }
        for ($day = $first_day; $day <= $last_day; $day++) {
           if ($week[$day] == 0) {
            print("</tr>\n<tr>\n");
            }
            // スケジュールが存在するかどうかチェックする
            $exist_schedule = true;
             // スケジュールが存在したらリンクをつける
           if ($exist_schedule) {
            //閲覧している日付が今日かどうか
            if($day == $da && $month == $mo && $year == $ye){
              print("<td class=\"today\">");
              echo $this->Html->link($day,'/users/index/'.h($guest['id']).'/'.$year.'/'.$month.'/'.$day.'/?date='.$date_timestamp);
            }else{
              print("<td>");
              echo $this->Html->link($day,'/users/index/'.h($guest['id']).'/'.$year.'/'.$month.'/'.$day.'/?date='.$date_timestamp);
            }
           } else {
            print("<td>$day</td>\n");
           }

        }
        // カレンダーの最後の空白部分
        for ($i = $week[$last_day] + 1; $i < 7; $i++) {
         print ("<td></td>\n");
        }
        ?>
       </tr>
      </table>
  </div>

</div>
<!-- /********* 名前とか日付とか出る欄(カレンダーの右隣のBOX) ***********/-->
<div id="result_date">
<dl>
    <h3><?php echo $guest['name']."さんの工数を編集します";?></h3>
    <h2>
       <dt>Today</dt>
       <dd>: <?php echo date("Y/m/d(D)");?></dd>
      <br>
      <?php if($ye == null || $mo == null || $da == null && isset($select_user['User']['name']))://ユーザーが入っていない&年がない場合?>
        <?php echo "編集する日付を選択してください";?>
      <?php  else: ?>
        <dt>Select date</dt>
        <dd>: <?php echo $ye,"/",$mo,"/",$da ;?></dd>
      <?php  endif;?>
      <br>
    </h2>
      <?php $overtime_work = 0;//残業時間?>
       <?php foreach ($emp as $key => $emps): ?>
        <?php $overtime_work = $overtime_work + $emps['Emp']['coretime'];?>
       <?php endforeach;?>
      <h2>
      <dt>残業時間</dt><dd>: <?php if($overtime_work-8 > 0) echo $overtime_work-8; else echo "8時間以上働いていません";?></dd>
      <br>
      <dt>総労働時間</dt><dd>: <?php echo $overtime_work;?></dd>
      </h2>
  <?php endif;?>
</dl>
</div>

<!-- /********* 工数の登録エリア ***********/-->
<?php if(isset($select_user['User']['name']))://ユーザーが選択されていたら表示?>
  <?php if(isset($ye)&&isset($mo)&&isset($da))://日付が選択されてたら表示?>
      <?php if(empty($usecon)):?>
        <p class="matter_empty">案件が登録されていません</p>
      <?php else: ?>

        <?php if (empty($emp)): ?>
          <div id="coretime">
            <br>
            <div class="matter"><h3>案件</h3></div>
            <div class="core_coretime"><h3>工数</h3></div>
                <table>
                  <tr>
                    <th>契約番号</th>
                    <th>契約会社</th>
                    <th>契約内容</th>
                    <th>工数時間</th>
                <?php
                //案件の配列数
                  $count = count($usecon)+1;
                  $j = 1;
                  ?>
                  <?php
                  for ($i=1; $i < $count ; $i++) {
                  echo $this->Form->create('Emp',array('novalidate' => true));
                  echo $this->Form->input('Emp.'.$i.'.user_id', array('type'=>'hidden','value'=>h($guest['id'])));
                  echo $this->Form->input('Emp.'.$i.'.username', array('type'=>'hidden','value'=>h($select_user['User']['name'])));
                  echo $this->Form->input('Emp.'.$i.'.date', array('type'=>'hidden','value'=>$ye.'-'.$mo.'-'.$da));
                   }
                ?>

                  <?php foreach ($usecon as $usecons): ?>
                    <tr>
                      <?php echo
                        $this->Form->input('Emp.'.$j.'.matter_id', array('type'=>'hidden','value'=>h($usecons['Matter']['number'])))
                      ;?>
                      <td><?php echo h($usecons['Matter']['number']);?></td>
                      <td><?php echo h($usecons['Matter']['mcompany']);?></td>
                      <td><?php echo h($usecons['Matter']['mcontent']);?></td>
                      <td><?php
                      echo $this->Form->input('Emp.'.$j.'.coretime',array('label'=>'','value'=>'0'));
                      $j++;
                      ?></td>
                    </tr>
                  <?php endforeach;?>
                </table>
            <?php echo $this->Form->submit('投稿', array(
            'div'=>false,
            'name' => 'emppost'));?>
            <?php echo $this->Form->end();?>
            <br>
            <br>
            <br>
        </div>
      <?php endif;?>
    <?php endif;//振り分けられているかどうかif(empty($usecon))?>

<!-- /********* 工数の追加エリア ***********/-->
          <div id="coretime_edit">
                   <!-- 工数の登録を受けている項目と受けていない項目 -->
                    <?php $new_usercon = array();?>
                    <?php foreach ($usecon as $usecons): ?>
                          <?php $new_usercon[] += $usecons['Matter']['number'];?>
                    <?php endforeach;?>
                    <?php $edit_emp = array();?>
                    <?php foreach ($emp as $emps): ?>
                          <?php $edit_emp[] += $emps['Matter']['number'];?>
                    <?php endforeach ?>
                    <!-- 受けていないものは新規登録を受け付ける -->
                    <?php $new_add = array_diff($new_usercon,$edit_emp);?>
                    <?php foreach ($new_add as $new_adds): ?>
                    <?php endforeach;?>
                    <?php $k = 1;?>
                    <?php if (isset($new_adds) && !count($emp) == 0 )://配列を引いた結果が入っていた場合と案件が入ってなかったら非表示?>
<br>
<br>
<br>
                      <h3>工数の追加登録</h3>
                      <table>
                          <tr>
                            <th>契約番号</th>
                            <th>契約会社</th>
                            <th>契約内容</th>
                            <th>工数</th>
                          </tr>
                       <?php foreach ($usecon as $usecons): ?>
                        <?php foreach ($new_add as $new_adds): ?>
                              <?php if($new_adds == $usecons['Matter']['number']): ?>
                              <tr>
                                <?php
                                  echo $this->Form->create('Emp',array('novalidate' => true));
                                  echo $this->Form->input('Emp.'.$k.'.user_id', array('type'=>'hidden','value'=>h($guest['id'])));
                                  echo $this->Form->input('Emp.'.$k.'.username', array('type'=>'hidden','value'=>h($select_user['User']['name'])));
                                  echo $this->Form->input('Emp.'.$k.'.date', array('type'=>'hidden','value'=>$ye.'-'.$mo.'-'.$da));
                                 ?>
                                <?php echo
                                  $this->Form->input('Emp.'.$k.'.matter_id', array('type'=>'hidden','value'=>h($usecons['Matter']['number'])))
                                ;?>
                                <td><?php echo h($usecons['Matter']['number']);?></td>
                                <td><?php echo h($usecons['Matter']['mcompany']);?></td>
                                <td><?php echo h($usecons['Matter']['mcontent']);?></td>
                                <td><?php
                                      echo $this->Form->input('Emp.'.$k.'.coretime',array('label'=>'','value'=>'0'));
                                      $k++;?>
                                </td>
                              </tr>
                              <?php continue;?>
                              <?php endif;?>
                        <?php endforeach;?>
                      <?php endforeach;?>
                      </table>
                          <?php echo $this->Form->submit('投稿', array(
                          'div'=>false,
                          'name' => 'emppost'));?>
                        <?php echo $this->Form->end();?>
<br>
<br>
<br>
<br>
                    <?php endif;?>
<!-- /********* 工数の編集、削除を選択するエリア ***********/-->
            <?php if(!isset($edit['Emp']['id']))://パラメータにempidがセットされているかどうか?>
              <?php if (!empty($emp)): ?>
                <h3>工数の編集</h3>
                <table>
                      <tr>
                        <th>契約番号</th>
                        <th>契約会社</th>
                        <th>契約内容</th>
                        <th>工数</th>
                        <th>編集</th>
                        <th>削除</th>
                      </tr>

                      <?php foreach ($emp as $emps): ?>
                      <tr>
                        <td><?php if(isset($emps['Matter']['number'])) echo h($emps['Matter']['number']);?></td>
                        <td><?php if(isset($emps['Matter']['mcompany'])) echo h($emps['Matter']['mcompany']);?></td>
                        <td><?php if(isset($emps['Matter']['mcontent'])) echo h($emps['Matter']['mcontent']);?></td>
                        <td><?php echo $emps['Emp']['coretime'];?></td>
                        <td><?php echo $this->Html->link('編集',array('controller'=>'users','action'=>'index',h($guest['id']),$ye,$mo,$da,h($emps['Emp']['id']),'?date='.$date_timestamp));?></td>
                        <td><?php echo $this->Form->postLink(__('削除'),array('controller'=>'users','action'=>'delete',h($emps['Emp']['id'])), null, __('削除申請を行いますか?'));?></td>
                      </tr>
                      <?php endforeach;?>
                </table>

              <?php endif;?>
            <?php else:?>
<!-- /********* 工数の編集を行うエリア ***********/-->
              <table>
                 <?php
                  echo $this->Form->create('Emp');
                  echo $this->Form->input('Emp.user_id', array('type'=>'hidden','value'=>h($guest['id'])));
                  echo $this->Form->input('Emp.username', array('type'=>'hidden','value'=>h($select_user['User']['name'])));
                  echo $this->Form->input('Emp.date', array('type'=>'hidden','value'=>$ye.'-'.$mo.'-'.$da));
                 ?>
                    <tr>
                      <th>契約番号</th>
                      <th>契約会社</th>
                      <th>契約内容</th>
                      <th>工数</th>
                    </tr>
                        <tr>
                          <?php echo
                          $this->Form->input('Emp.matter_id', array('type'=>'hidden','value'=>h($edit['Matter']['number'])))
                          ;?>
                          <td><?php echo h($edit['Matter']['number']);?></td>
                          <td><?php echo h($edit['Matter']['mcompany']);?></td>
                          <td><?php echo h($edit['Matter']['mcontent']);?></td>
                          <td><?php
                          echo $this->Form->input('Emp.coretime');
                          ?></td>
                        </tr>
              </table>
             <?php echo $this->Form->end('更新');?>
              <br>
            <?php endif;?>
          </div>

    <?php endif;//日付が選択されているかどうかのendif?>
  <?php endif;//ユーザーが選択されているかどうかのendif?>

