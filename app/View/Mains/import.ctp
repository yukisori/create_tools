	<div id="submenu">
	<ul>
	<li><?php echo $this->Html->link('紐付けマニュアル',array('controller'=>'mains','action'=>'relation_manual'));?></li>
	</ul>
	</div>

<div id="import_content">
	<div id="import_anken">
	<h3>案件</h3>
		<?php
		    echo $this->Form->create('Matter',array('type'=>'file','novalidate' => true));
		    echo $this->Form->input('Mattercsv',array('label'=>'','type'=>'file'));
		    echo $this->Form->submit('投稿', array(
		            'div'=>false,
		            'name' => 'matter_import'));?>

	</div>
	<br>
	<br>
	<div id="import_usercontroll">
		<h3>ユーザー別案件情報</h3>
		<?php echo $this->Form->create('Usercontroll',array('type'=>'file','novalidate' => true)); ?>
			<?php
			for ($i = 2012; $i <= 2025; $i++){
				 $year[] = $i;
				 $years = array_combine($year,$year);
			}
			  echo $this->Form->input('year',array(
			  							'type'=>'select',
			                'label'=>'年の選択',
			  							'options'=>$years
										));	
			;?>
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

		<?php echo $this->Form->input('Usercontrollcsv',array('label'=>'','type'=>'file'));?>
		<?php echo $this->Form->submit('投稿', array(
		            'div'=>false,
		            'name' => 'usecon_import'));?>
	</div>
</div>
