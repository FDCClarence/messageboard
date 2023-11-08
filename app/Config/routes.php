<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
 
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */

 //user routes
Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'index'));
Router::connect('/users/view/*', array('controller' => 'users', 'action' => 'view'));
Router::connect('/users/checkEmailUnique', array('controller' => 'users', 'action' => 'checkEmailUnique'));
Router::connect('/users/updateEmail', array('controller' => 'users', 'action' => 'updateEmail'));
Router::connect('/users/passwordCheck', array('controller' => 'users', 'action' => 'passwordCheck'));
Router::connect('/users/passwordChange', array('controller' => 'users', 'action' => 'passwordChange'));
Router::connect('/users/saveImage', array('controller' => 'users', 'action' => 'saveImage'));
Router::connect('/users/updateUser', array('controller' => 'users', 'action' => 'updateUser'));
Router::connect('/users/getUsers', array('controller' => 'users', 'action' => 'getUsers'));


//message routes
Router::connect('/messages', array('controller' => 'messages', 'action' => 'index'));
Router::connect('/sendMessage', array('controller' => 'messages', 'action' => 'sendMessage'));
Router::connect('/addMessage', array('controller' => 'messages', 'action' => 'sendMessage'));
Router::connect('/searchMessage', array('controller' => 'messages', 'action' => 'searchMessage'));
Router::connect('/showMoreMessages', array('controller' => 'messages', 'action' => 'showMoreMessages'));

Router::connect('/deleteMessage', array('controller' => 'messages', 'action' => 'deleteMessage'));
Router::connect('/getMessageThreads', array('controller' => 'messages', 'action' => 'getMessageThreads'));
Router::connect('/messages/getMessages', array('controller' => 'messages', 'action' => 'getMessages'));

/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
