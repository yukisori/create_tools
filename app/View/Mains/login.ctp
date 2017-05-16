<div id="top_contents_area">
	<div id="title_area">
	</div>
	<div id="login_form_area">
		<div class="login_form">
		<?php echo $this->Session->flash(); ?>
		<?php echo $this->Session->flash('auth'); ?>
		<?php echo $this->Form->create('User', array('url' => 'login','class'=>'topForm','novalidate' => true)); ?>
		<?php echo $this->Form->input('name', array('label' => 'ID','class'=>'topFormEmail')); ?>
		<?php echo $this->Form->input('password', array('label' => 'PASSWORD','class'=>'topFormPass')); ?>
		<?php echo $this->Form->submit('ログイン',array('class'=>'loginButton')); ?>
		</div>
	</div>
</div>