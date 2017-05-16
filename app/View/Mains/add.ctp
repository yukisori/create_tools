	<br>
	<br>
<?php
	echo $this->Form->create('Matter',array('url' =>'/Mains/add','novalidate' => true));
	echo $this->Form->input('number',array('type' => 'text','label'=>'案件No.'));
	echo $this->Form->input('mcontent',array('label'=>'案件内容'));
	echo $this->Form->input('mcompany',array('label'=>'会社名'));
	echo $this->Form->end('案件を追加する');

 ;?>
