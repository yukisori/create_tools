
<!DOCTYPE html>
<html ng-app>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('default');
		echo $this->Html->css('style');
		echo $this->Html->script( 'jquery.min.js');
		echo $this->Html->script( 'script.js');
		echo $this->Html->script( 'angular.min.js');
		echo $this->Html->script( 'footerFixed.js');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
<body>
	<div id="header"class="selfclear">
			<div id="navi">
				<ul>
					<li><?php echo $this->Html->link('Top',array('controller'=>'mains','action'=>'index'));?></li>
					<li><?php echo $this->Html->link('logout',array('controller'=>'mains','action'=>'logout'));?></li>
				</ul>
			</div>
	</div>
	<div id="tools">
		<div id="toolname">
			<p>
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