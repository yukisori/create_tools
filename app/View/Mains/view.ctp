<?php
  $month = date("m", time());
  $year = date("Y", time());
;?>
	<br>
	<br>
	<div id="submenu">
	<ul>
	<li><?php echo $this->Html->link('案件登録ページへ',array('controller'=>'mains','action'=>'add'));?></li>
	<li><?php echo $this->Html->link('CSV取込',array('controller'=>'mains','action'=>'import'));?></li></li>
	</ul>
	</div>
	<br>
	<br>
<?php if(!empty($matters)):?>
<table>
	<th><?php echo $this->Paginator->sort('Matter.number','案件番号');?></th>
	<th class="th_number">契約内容</th>
	<th class="th_company">会社名</th>

	<!-- <th>削除</th> -->
<?php foreach ($matters as $matter):?>
	<tr>
		<td><?php echo h($matter['Matter']['number']);?></td>
		<td><?php echo $this->Html->link(h($matter['Matter']['mcontent']),'/mains/matter/'.$matter['Matter']['number'].'/'.$year.'-'.$month);?></td>
		<td><?php echo h($matter['Matter']['mcompany']);?></td>
		<!-- <td><?php echo $this->Html->link('編集','/mains/edit/'.$matter['Matter']['number']);?></td>  -->
		<!-- <td><?php //echo $this->Form->postLink(__('削除'),array('action'=>'delete',h($matter['Matter']['number'])), null, __('削除申請を行いますか?'));?></td> -->
	</tr>
<?php endforeach;?>
</table>
<?php else:?>
	<p class="matter_empty">案件が登録されていません</p>
<?php endif;?>

<?php if($this->Paginator->numbers()): ?>
<div id="pagination_contents">
<?php
echo $this->Paginator->prev('< 　', array(), null, array('class' => 'prev disabled'));
echo $this->Paginator->numbers(array('separator' => ' | '));
echo $this->Paginator->next('　 >', array(), null, array('class' => 'next disabled'));
;?>
</div>
<?php endif; ?>