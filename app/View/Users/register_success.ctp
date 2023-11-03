<h2>SUCCESSFULLY REGISTERED</h2>
<?php echo $this->Session->flash('success'); // For CakePHP 2.x ?>

<?php
echo $this->Html->link('Go to Homepage', '/users/index', array('class' => 'button'));
?>
