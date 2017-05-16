

<!DOCTYPE html>
<html ng-app>
<head>
	<?php echo $this->Html->charset(); ?>
	
		<title><?php echo $title_for_layout;?></title>
	
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('default');
		echo $this->Html->css('style');
		echo $this->Html->css('jquery.jqplot.min');
		echo $this->Html->script( 'jquery.min.js');
		echo $this->Html->script( 'script.js');
		echo $this->Html->script( 'angular.min.js');
		echo $this->Html->script( 'jquery.jqplot.min.js');
		echo $this->Html->script( 'jqplot.pieRenderer.min.js');
		echo $this->Html->script( 'footerFixed.js');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
  	  
	?>
</head>
<body>

	<div id="header" class="selfclear">
			<div id="kanri_navi">
				<ul>
					<li><?php echo $this->Html->link('Top',array('controller'=>'mains','action'=>'index'));?></li>
					<li><?php echo $this->Html->link('案件管理一覧',array('controller'=>'mains','action'=>'view'));?></li>
					<li><?php echo $this->Html->link('ユーザー別案件管理',array('controller'=>'mains','action'=>'usercon'));?></li>
					<li><?php echo $this->Html->link('logout',array('controller'=>'mains','action'=>'logout'));?></li>
				</ul>
			</div>
	</div>
	<div id="tools">
		<div id="toolname">
<!-- 			<p>
			<?php echo $this->Html->image('kanri.png', array('height'=>'50px','class'=>'vertical'));?>
			作業工程管理Tools</p> -->
			<p class="title"><?php echo $title_for_layout;?></p>
		</div>	
	</div>

	<div id="container">

		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>

	</div>
		<div id="footer">
			<div id="footer_bar">
				<p>Copyright ©  kousu All Rights Reserved.</p>
			</div>
		</div>
</body>
</html>
