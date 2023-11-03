<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

?>
<!DOCTYPE html>
<html>

<head>
	<!-- Include Bootstrap CSS -->


	<!-- Include Bootstrap JavaScript (Optional) -->

	<?php echo $this->Html->charset(); ?>
	<?php

	echo $this->Html->css('cake.generic');

	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
	?>


	<title>
		<?php echo "Message Board Application" ?>:
	</title>
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="<?php echo $this->webroot; ?>vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
	<link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<!-- Include jQuery and Select2 -->
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

	<!-- font awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">



</head>

<body style="background-color: white;">
	<div id="container" class="width : 100%;">

		<?php //start login and register check
		if ($this->request->params['action'] != 'login' && $this->request->params['action'] != 'add') {
		?>
			<div id="header" style="padding : 0">
				<nav class="navbar navbar-expand-lg navbar-primary bg-primary" style="width: 100%; padding : 1em">
					<a class="navbar-brand" href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'index')); ?>">Message Board</a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse justify-content-between" id="navbarNav">
						<ul class="navbar-nav">
							<li class="nav-item active">
								<?php
								echo $this->Html->link('Home', array('controller' => 'users', 'action' => 'index'), array('class' => 'nav-link'));
								?>
							</li>
							<li class="nav-item active">
								<?php
								echo $this->Html->link('Profile', array('controller' => 'users', 'action' => 'view'), array('class' => 'nav-link'));
								?>
							</li>
							<li class="nav-item">
								<?php
								echo $this->Html->link('Messages', array('controller' => 'messages', 'action' => 'index'), array('class' => 'nav-link'));
								?>
							</li>
						</ul>
						<ul class="navbar-nav">
							<li class="nav-item">
								<?php
								echo $this->Html->link('Log Out', array('controller' => 'users', 'action' => 'logout'), array('class' => 'nav-link'));
								?>
							</li>
						</ul>
					</div>
				</nav>

			</div>
		<?php //end login and register check
		}
		?>

		<div id="content">
			<?php echo $this->Flash->render(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
	</div>
</body>

</html>