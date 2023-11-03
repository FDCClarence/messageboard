<?php 

// app/Model/Message.php
class Message extends AppModel {
    public $name = 'Message';
    public $useTable = 'messages'; // Specify the table name
    public $primaryKey = 'message_id'; 

    // public $validate = array(
    //     'content' => array(
    //         'rule' => 'notBlank',
    //         'message' => 'Content is required'
    //     )
    // );
}


?>