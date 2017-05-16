
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
	<style type="text/css">
	div#authMessage {
	  font-size: 6px;
	}
	div#flashMessage{
	  font-size: 6px;
	}
	label {
	  font-size: 90%;
	  margin-left: 15px;
	  text-align: left;
	}
	</style>
</head>
<body id="top_image">
	<div id="header"class="selfclear">
			<div id="navi">
				<ul>
					<li><?php echo $this->Html->link('Top',array('controller'=>'mains','action'=>'index'));?></li>
				</ul>
			</div>
	</div>
<?php echo $this->Html->image('pen.gif',array('class' => 'index'));?>
	<div id="container">

		<div id="content">

			<?php //echo $this->Session->flash(); ?>
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