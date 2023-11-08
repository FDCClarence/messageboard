<?php
App::uses('AppController', 'Controller');

class MessagesController extends AppController
{
    public function index()
    {
        $messages = $this->Message->find('all');
        $this->set('messages', $messages);
    }

    public function view()
    {
        $this->loadModel('Message_thread');
        $messageThreadId = $this->request->query['messageThreadId'];
        $messages = $this->Message_thread->query("
                                                    SELECT mt.*, m.*,DATE_FORMAT(m.created, '%M %e, %Y %h:%i %p') AS formatted_created_date, sender.*, receiver.*
                                                    FROM message_thread AS mt 
                                                    JOIN messages m 
                                                    ON m.message_thread_id = mt.message_thread_id
                                                    JOIN users AS sender
                                                    ON sender.user_id = m.sender_id
                                                    JOIN users AS receiver
                                                    ON receiver.user_id = m.receiver_id
                                                    WHERE mt.message_thread_id = $messageThreadId
                                                    AND mt.is_deleted = false 
                                                    AND m.is_deleted = false 
                                                    ORDER BY m.message_id DESC
                                                    LIMIT 5 
                                                    OFFSET 0
                                                ");
        $message_thread = $this->Message_thread->query("
                                                        SELECT mt.*, user_1.*, user_2.* 
                                                        FROM message_thread AS mt
                                                        JOIN users AS user_1
                                                        ON mt.user_id_1 = user_1.user_id
                                                        JOIN users AS user_2
                                                        ON mt.user_id_2 = user_2.user_id 
                                                        WHERE mt.message_thread_id = $messageThreadId 
                                                        AND mt.is_deleted = false
                                                        AND user_1.is_deleted = false
                                                        AND user_2.is_deleted = false

                                                    ");
        $this->set('message_thread', $message_thread);
        $this->set('messages', $messages);
    }

    public function add()
    {
        // Code to create a new message
    }

    public function sendMessage()
    {
        $this->autoRender = false;

        if ($this->request->is('post')) {
            $this->loadModel('Message_thread');
            $this->loadModel('Message');

            // Define the sender and receiver IDs
            $senderId = $this->request->data['sender_id'];
            $receiverId = $this->request->data['receiver_id'];

            // Check if an existing message thread exists between the two users (regardless of order)
            $existingThread = $this->Message_thread->find('first', [
                'conditions' => [
                    'OR' => [
                        [
                            'user_id_1' => $senderId,
                            'user_id_2' => $receiverId,
                        ],
                        [
                            'user_id_1' => $receiverId,
                            'user_id_2' => $senderId,
                        ],
                    ],
                    'is_deleted' => 0, // Ensure the thread is not deleted
                ],
            ]);

            if ($existingThread) {
                // An existing message thread was found, append the message to it
                $messageData = [
                    'message_thread_id' => $existingThread['Message_thread']['message_thread_id'],
                    'sender_id' => $senderId,
                    'receiver_id' => $receiverId,
                    'message_content' => $this->request->data['message_content'],
                    'created' => date('Y-m-d H:i:s'),
                    'is_deleted' => 0, // Assuming is_deleted should be set to 0
                ];

                if ($this->Message->save($messageData)) {
                    $newMessageId = $this->Message->getLastInsertID(); // Get the newly inserted message_id
                    $response = [
                        'status' => 'success',
                        'message' => 'Message added to the existing thread',
                        'message_id' => $newMessageId,
                    ];
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'Failed to save the message to the existing thread',
                    ];
                }
            } else {
                // No existing message thread found, create a new thread and save the message
                $messageThreadData = [
                    'user_id_1' => $senderId,
                    'user_id_2' => $receiverId,
                    'created' => date('Y-m-d H:i:s'),
                    'is_deleted' => 0,
                ];

                if ($this->Message_thread->save($messageThreadData)) {

                    $newMessageThreadId = $this->Message_thread->id;

                    $messageData = [
                        'message_thread_id' => $newMessageThreadId,
                        'sender_id' => $senderId,
                        'receiver_id' => $receiverId,
                        'message_content' => $this->request->data['message_content'],
                        'created' => date('Y-m-d H:i:s'),
                        'is_deleted' => 0,
                    ];

                    if ($this->Message->save($messageData)) {
                        $newMessageId = $this->Message->getLastInsertID(); // Get the newly inserted message_id
                        $response = [
                            'status' => 'success',
                            'message' => 'New message thread and message created',
                            'message_id' => $newMessageId,
                        ];
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'Failed to save the message to the new thread',
                        ];
                    }
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'Failed to create a new message thread',
                    ];
                }
            }

            // Set the response type to JSON
            $this->response->type('json');
            // Encode the response data as JSON and send it
            $this->response->body(json_encode($response));
        }
    }

    public function getMessageThreads()
    {
        $this->autoRender = false;
        $this->loadModel('Message_thread');
        $userID = $_SESSION['userData']['user_id'];
        $thread_limit = 5;
        $list_count = 0;
        $results = $this->Message_thread->query("
                                                    SELECT MessageThread.message_thread_id, LatestMessage.*, Sender.*, Receiver.*
                                                    FROM message_thread AS MessageThread
                                                    LEFT JOIN (
                                                        SELECT message_thread_id, MAX(created) AS latest_created
                                                        FROM messages
                                                        WHERE messages.is_deleted = false
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
                                                    GROUP BY MessageThread.message_thread_id
                                                    HAVING LatestMessage.message_id IS NOT NULL
                                                    LIMIT $thread_limit
                                                    OFFSET $list_count;
                                                ");
        // Set the response type to JSON
        $this->response->type('json');
        // Encode the response data as JSON and send it
        $this->response->body(json_encode($results));
    }

    public function getMessages()
    {
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $this->loadModel('Message_thread');

            $results = $this->Message_thread->query("
                SELECT mt.*, m.*, sender.*, receiver.*
                FROM message_thread AS mt 
                JOIN messages m 
                ON m.message_thread_id = mt.message_thread_id
                JOIN users AS sender
                ON sender.user_id = m.sender_id
                JOIN users AS receiver
                ON receiver.user_id = m.receiver_id
                WHERE mt.message_thread_id = 1
                AND mt.is_deleted = false 
                AND m.is_deleted = false 
                LIMIT 5 
                OFFSET 0
            ");

            // Set the response type to JSON
            $this->response->type('json');
            // Encode the response data as JSON and send it
            $this->response->body(json_encode($results));
            $this->response->send();
        }
    }

    public function deleteMessage()
    {
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $this->loadModel('Message_thread');
            $message_id = $this->request->data['message_id'];
            $result = $this->Message_thread->query("
                                                    UPDATE messages
                                                    SET is_deleted = 1
                                                    WHERE message_id = $message_id AND is_deleted = 0;
                                                    ");

            echo "success";
        } else {
            echo "error";
        }
    }

    public function deleteMessageThread()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $this->loadModel('Message_thread');
            $messageThreadId = $this->request->data['message_thread_id'];

            $messagesResult = $this->Message_thread->query(
                "UPDATE messages SET is_deleted = 1 WHERE message_thread_id = $messageThreadId"
            );

            $messageThreadResult = $this->Message_thread->query(
                "UPDATE message_thread SET is_deleted = 1 WHERE message_thread_id = $messageThreadId"
            );

            if ($messagesResult && $messageThreadResult) {
                $response = [
                    'status' => 'success',
                    'message' => 'Message thread and associated messages deleted successfully',
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Failed to delete message thread and associated messages',
                ];
            }

            $this->response->type('json');
            $this->response->body(json_encode($response));
        }
    }

    public function searchMessage()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $this->loadModel('Message_thread');
            $messageThreadId = $this->request->data['message_thread_id'];
            $search = $this->request->data['search'];
            $result = $this->Message_thread->query("
                                                SELECT
                                                m.*,
                                                u1.name AS sender_name,
                                                DATE_FORMAT(m.created, '%M %e, %Y %l:%i%p') AS formatted_created_date
                                            FROM
                                                messages m
                                            JOIN
                                                users u1 ON m.sender_id = u1.user_id
                                            WHERE
                                                m.message_content LIKE '%$search%'
                                                AND m.message_thread_id = $messageThreadId
                                                AND m.is_deleted = false
            ");
            $this->response->body(json_encode($result));
            $this->response->type('json');
        } else {
            echo "Invalid Request";
        }
    }

    public function showMoreMessages()
{
    $this->autoRender = false;
    if ($this->request->is('ajax')) {
        $this->loadModel('Message_thread');
        $messageThreadId = $this->request->data['message_thread_id'];
        $bubbleNum = $this->request->data['numberOfBubbles'];
        $messageLimit = 10;

        // Count the total number of messages in the thread
        $totalMessages = $this->Message_thread->find('count', array(
            'conditions' => array(
                'message_thread_id' => $messageThreadId,
                'is_deleted' => false
            )
        ));

        // Query for messages with a "moreMessages" flag
        $messages = $this->Message_thread->query("
            SELECT mt.*, m.*, DATE_FORMAT(m.created, '%M %e, %Y %h:%i %p') AS formatted_created_date, sender.*, receiver.*,
            CASE WHEN $totalMessages > $bubbleNum + $messageLimit THEN 1 ELSE 0 END AS moreMessages
            FROM message_thread AS mt 
            JOIN messages m 
            ON m.message_thread_id = mt.message_thread_id
            JOIN users AS sender
            ON sender.user_id = m.sender_id
            JOIN users AS receiver
            ON receiver.user_id = m.receiver_id
            WHERE mt.message_thread_id = $messageThreadId
            AND mt.is_deleted = false 
            AND m.is_deleted = false 
            ORDER BY m.message_id DESC
            LIMIT $messageLimit 
            OFFSET $bubbleNum
        ");

        $this->response->body(json_encode($messages));
        $this->response->type('json');
    } else {
        echo "Invalid Request";
    }
}


}
