

<?php 
$date_timestamp = time(); 
$year = date("Y", $date_timestamp);?>

<?php echo $this->Form->create('Usercontroll',array('type'=>'file','novalidate' => true)); ?>
<?php //echo $this->Form->create("mains",array("action" => "relation_manual_comfilm", "type" => "post")); ?>
<div id="total_contents" class="clearfix">
	<div id="only_box">

		<div id="date_list">
		<h1>年月リスト(単一選択)</h1>
			<?php
			for ($i = $year-10; $i <= $year+10; $i++){
			 $year_count[] = $i;
			 $years_counts = array_combine($year_count,$year_count);
			}
			echo $this->Form->input('year',array(
					'type'=>'select',
					'label'=>'年の選択',
					'options'=>$years_counts
					));	
						;?>
		</div>
		<div id="user_list">
		<h1>ユーザーリスト(単一選択)</h1>
		<?php
		for ($y = 1; $y <= 12; $y++){
		$month[] = $y;
		$months = array_combine($month,$month);
		}
		echo $this->Form->input('month',array(
				'type'=>'select',
				'label'=>'年の選択',
				'options'=>$months
				));	
					;?>
		<?php 
		echo $this->Form->input('user_id', array(
								'label'=>'案件名',
		                         'legend' => false,
		                         'options' => $User,
		                         'type' => 'radio',
		));
		;?>
		</div>
	</div>


	<div id="anken_list">
	<h1>案件リスト(複数選択可)</h1>
		<?php 
		echo $this->Form->input('matter_id', array(
								'label'=>'案件名',
		                         'legend' => false,
		                         'options' => $Matter,
		                         'type' => 'select',
		                         'multiple' => true,
		));
		;?>	
	</div>
	
	<div id="total_submit">
		<?php echo $this->Form->submit('投稿', array(
				            'div'=>false,
				            'name' => 'usecon_add'));?>
	</div>
</div>



 </pre>
