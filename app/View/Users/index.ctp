<!-- <div class="row">
    <div class="col-md-1  offset-md-10">
        <?php
        $userData = CakeSession::read('userData');
        if (isset($userData['user_id'])) {
            echo $this->Html->link(
                'Profile',
                array(
                    'controller' => 'users',
                    'action' => 'view',
                ),
                array('class' => 'btn btn-primary ml-auto')
            );
        } else {
            echo 'User data is missing or incomplete.';
        }
        ?>
    </div>
    <div class="col-md-1">
        <?php
        echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout'), array('class' => 'btn btn-secondary'));
        ?>
    </div>
</div> -->


<div class="row">
        <div class="mt-5">
            <h1>Welcome to the Message Board, <?php echo $_SESSION['userData']['name'] ?>!</h1>
        </div>
</div>






