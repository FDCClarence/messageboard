<?php
App::uses('AppController', 'Controller');

class MessagesController extends AppController {
    public function index() {
        $messages = $this->Message->find('all');
        $this->set('messages', $messages);
    }

    public function view($id) {
        // Code to view a message
    }

    public function add() {
        // Code to create a new message
    }

    public function sendMessage(){  
        $this->autoRender = false;
        if($this->request->is('post')){
            $this->loadModel('Message_thread');
            $this->Message_thread->create();

            $messageThreadData = array(
                'user_id_1' => $this->request->data['sender_id'],
                'user_id_2' => $this->request->data['receiver_id'],
                'created' => date('Y-m-d H:i:s'), // or use CakePHP's time() function
                'is_deleted' => 0, // Assuming is_deleted should be set to 0 for a new message thread
            );
           
            if ($this->Message_thread->save($messageThreadData)) {
                // Get the ID of the newly created message thread
                $newMessageThreadId = $this->Message_thread->id;
                // Prepare a success response
                $messageData = array(
                    'message_thread_id' => $newMessageThreadId,
                    'sender_id' => $this->request->data['sender_id'],
                    'receiver_id' => $this->request->data['receiver_id'],
                    'message_content' => $this->request->data['message_content'],
                    'updated' => date('Y-m-d H:i:s')
                );

                $this->loadModel('Message');
                if($this->Message->save($messageData)){
                    $newMessageId = $this->Message->id;
                    $response = array(
                        'status' => 'success',
                        'message' => 'Successfully created message thread',
                    );
                }else{
                    $response = array(
                        'status' => 'error',
                        'message' => 'Failed to create message.',
                    );
                }
                
            } else {
                // Prepare an error response
                $response = array(
                    'status' => 'error',
                    'message' => 'Failed to create message thread',
                );
            }
            
            // Set the response type to JSON
            $this->response->type('json');
    
            // Encode the response data as JSON and send it
            $this->response->body(json_encode($response));
            
        }   
    }

    // app/Controller/MessagesController.php
    public function getMessageThreads() {
        $this->autoRender = false;
    
        $this->loadModel('Message_thread');
    
        $userID = $_SESSION['userData']['user_id']; // Assuming $_SESSION['userData']['user_id'] contains the user's ID

        $results = $this->Message_thread->query("
            SELECT MessageThread.message_thread_id, LatestMessage.*, Sender.*, Receiver.*
            FROM message_thread AS MessageThread
            LEFT JOIN (
                SELECT message_thread_id, MAX(created) AS latest_created
                FROM messages
                GROUP BY message_thread_id
            ) AS LatestMessages
            ON MessageThread.message_thread_id = LatestMessages.message_thread_id
            LEFT JOIN messages AS LatestMessage
            ON LatestMessages.message_thread_id = LatestMessage.message_thread_id
            AND LatestMessages.latest_created = LatestMessage.created
            LEFT JOIN users AS Sender
            ON LatestMessage.sender_id = Sender.user_id
            LEFT JOIN users AS Receiver
            ON LatestMessage.receiver_id = Receiver.user_id
            WHERE MessageThread.user_id_1 = $userID OR MessageThread.user_id_2 = $userID
        ");
    
        // Set the response type to JSON
        $this->response->type('json');
        // Encode the response data as JSON and send it
        $this->response->body(json_encode($results));
    }
    

}

?>