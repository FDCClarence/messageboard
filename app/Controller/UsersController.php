<?php

App::uses('AppController', 'Controller');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');


class UsersController extends AppController {

    public $components = array('Session');


    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add', 'logout','register_success', 'users');
    }

    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $user_id = $this->Auth->user('user_id');
                $this->User->id = $user_id;
                $this->User->saveField('last_login_time', date('Y-m-d H:i:s'));
                $user = $this->User->find('first', array(
                    'conditions' => array('User.user_id' => $user_id),
                    'fields' => array('User.user_id', 
                    'User.name', 
                    'User.email', 
                    'User.birthdate', 
                    'User.gender', 
                    'User.hobby', 
                    'User.created', 
                    'User.updated', 
                    'User.last_login_time',
                    'User.img_url')
                ));
                $this->Session->write('userData', $user['User']);
                $this->redirect($this->Auth->redirectUrl('index'));
            } else {
                $this->Flash->error(__('Invalid username or password, try again'));
            }
        }
    }
    
    public function logout() {
        $this->Session->destroy();
        return $this->redirect($this->Auth->logout());
    }

    public function index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    public function view($id = null) {
        $this->set('user', $this->User->find('first', array(
            'conditions' => array('User.user_id' => $id),
            'fields' => array(
                'User.user_id',
                'User.name',
                'User.email',
                'User.birthdate',
                'User.gender',
                'User.hobby',
                'User.created',
                'User.updated',
                'User.last_login_time'
            )
        )));
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->User->create();
            $data = $this->request->data;
            $data['User']['last_login_time'] = date('Y-m-d H:i:s');
            if ($this->User->save($data)) {
                $user_id = $this->User->id; 
                $user = $this->User->find('first', array(
                    'conditions' => array('User.user_id' => $user_id),
                    'fields' => array(
                        'User.user_id', 'User.name', 'User.email', 'User.birthdate',
                        'User.gender', 'User.hobby', 'User.created', 'User.updated',
                        'User.last_login_time', 'User.img_url'
                    )
                ));
                $this->Auth->login($user['User']);
                $this->Session->write('userData', $user['User']);
                $this->Flash->success(__('The user has been saved'));
                return $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'register_success',
                    $user_id
                ));
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
    }

    public function register_success($user_id) {
        //only used to redirect
    }

    public function edit($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Flash->success(__('The user has been saved'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Flash->error(
                __('The user could not be saved. Please, try again.')
            );
        } else {
            $this->request->data = $this->User->findById($id);
            unset($this->request->data['User']['password']);
        }
    }

    public function delete($id = null) {
        $this->request->allowMethod('post');
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->User->delete()) {
            $this->Flash->success(__('User deleted'));
            return $this->redirect(array('action' => 'index'));
        }
        $this->Flash->error(__('User was not deleted'));
        return $this->redirect(array('action' => 'index'));
    }

    public function checkEmailUnique() {
        $this->autoRender = false; // Disable rendering a view
        if ($this->request->is('ajax')) {
            $email = $this->request->data['email'];

            //check db for email
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.email' => $email,
                    'User.is_deleted' => 0
                )
            ));
            //unique email = true
            $isUnique = (empty($user)) ? true : false; 
            // Send a JSON response
            $this->response->body(json_encode(['unique' => $isUnique]));
            $this->response->type('json');
        }
    }

    public function updateEmail(){
        $this->autoRender = false; 
    
        if ($this->request->is('ajax')) {
            $newEmail = $this->request->data['email'];
            $oldEmail = $this->request->data['oldEmail'];
            $originalPasswordValidationRules = $this->User->validator()->getField('password');
            $this->User->validator()->remove('password'); 
    
            $conditions = array('User.email' => $oldEmail, 'is_deleted' => false);
            $fields = array('email' => "'" . $newEmail . "'");
            if ($this->User->updateAll($fields, $conditions)) {
                $this->Session->write('userData.email', $newEmail);
                $response = array('success' => true, 'newEmail' => $newEmail);
            } else {
                $response = array('success' => false, 'message' => 'Error updating email');
            }
    
            $this->User->validator()->add('password', $originalPasswordValidationRules); 
            $this->response->body(json_encode($response));
            $this->response->type('json');
        }
    }

    
    public function passwordCheck() {
        $this->autoRender = false; 
        if ($this->request->is('ajax')) {
            $id = $this->request->data['user_id'];
            $password = $this->request->data['password'];
    
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.user_id' => $id,
                    'User.is_deleted' => false
                )
            ));
    
            $isValid = false;
            if ($user) {
                $hasher = new BlowfishPasswordHasher();
                $isValid = $hasher->check($password, $user['User']['password']);
            }
    
            $this->response->body(json_encode(['valid' => $isValid]));
            $this->response->type('json');
        }
    }
    
    public function passwordChange() { 
        $this->autoRender = false;

        $hasher = new BlowfishPasswordHasher();
        $password = $hasher->hash($this->request->data['password']);
        $user_id = $this->request->data['user_id'];

        $conditions = array(
            'User.user_id' => $user_id,
            'User.is_deleted' => false
        );
        $data = array('User.password' => "'" . $password . "'");
        $this->User->updateAll($data, $conditions);

        if ($this->User->getAffectedRows() > 0) {
            // Password updated successfully
            $this->response->body(json_encode(['success' => true]));
        } else {
            // Password update failed
            $this->response->body(json_encode(['success' => false, 'message' => 'Password update failed']));
        }
        $this->response->type('json');
    }

    public function saveImage() {
        $this->autoRender = false;
        // Check if the request contains files
        if ($this->request->form['image']) {
            $file = $this->request->form['image'];
    
            // Handle the file upload
            if ($file['error'] === UPLOAD_ERR_OK) {
                $uploadDir = WWW_ROOT . 'img' . DS . 'profile-pictures' . DS;
                $filename = uniqid() . '_' . $file['name'];
                $targetPath = $uploadDir . $filename;
    
                if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                    // Image uploaded and saved successfully
                    $imageURL = '/img/profile-pictures/' . $filename;
                    $user_id = $this->Auth->user('user_id');
                    $this->User->id = $user_id;
                    $this->User->saveField('img_url', $imageURL);
                    $_SESSION['userData']['img_url'] = $imageURL;
                    echo 'success';
                } else {
                    echo 'failed to move image';
                }
            } else {
                echo 'failed to upload image';
            }
        } else {
            echo 'no image';
        }
    }

    public function updateUser() {
        $this->autoRender = false;
    
        if ($this->request->is('post')) {
            // Retrieve user data from the POST request
            $userData = $this->request->data;
    
            // Create conditions for the update
            $conditions = array('User.user_id' => $userData['user_id']);
    
            // Fields to update with proper escaping
            $fields = array(
                'name' => "'" . $userData['name'] . "'",
                'birthdate' => "'" . $userData['birthdate'] . "'",
                'gender' => "'" . $userData['gender'] . "'",
                'hobby' => "'" . $userData['hobby'] . "'",
            );
    
            // Update the user data
            if ($this->User->updateAll($fields, $conditions)) {
                // Update successful
                $this->Session->write('userData.name', $userData['name']);
                $this->Session->write('userData.birthdate', $userData['birthdate']);
                $this->Session->write('userData.gender', $userData['gender']);
                $this->Session->write('userData.hobby', $userData['hobby']);
                echo json_encode(array('status' => 'success'));
            } else {
                // Update failed
                echo json_encode(array('status' => 'error'));
            }
        }
    }
    
    public function getUsers() {
        $this->autoRender = false;
    
        if ($this->request->is('ajax')) {
            $term = $this->request->query('term');
            // $conditions = [
            //     'is_deleted' => 0,
            //     'AND' => [
            //         ['email LIKE' => "%$term%"],
            //         ['name LIKE' => "%$term%"],
            //     ]
            // ];
            $conditions = [
                'is_deleted' => 0,
                'user_id <>' => $_SESSION['userData']['user_id'],
                'AND' => [
                    ['email LIKE' => "%$term%"],
                    ['name LIKE' => "%$term%"]
                ]
            ];
            $fields = ['user_id AS id', 'name AS text', 'img_url'];
            $users = $this->User->find('all', [
                'conditions' => $conditions,
                'fields' => $fields
            ]);
    
            $response = [];
            foreach ($users as $user) {
                $response[] = [
                    'id' => $user['User']['id'],
                    'text' => $user['User']['text'],
                    'img_url' => $user['User']['img_url']
                ];
            }
            $this->response->type('json');
            echo json_encode(['users' => $response]);
            
        }
    }
    
    
    
    
}
