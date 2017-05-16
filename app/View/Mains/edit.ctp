<br>
<?php
	echo $this->Form->create('Matter',array('novalidate' => true));
	echo $this->Form->input('mcontent',array('label'=>'案件内容'));
	echo $this->Form->input('mcompany',array('label'=>'会社名'));
	echo $this->Form->end('案件を追加する');
 ;?>
