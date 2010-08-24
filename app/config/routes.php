<?php
/* SVN FILE: $Id: routes.php 7945 2008-12-19 02:16:01Z gwoo $ */
/**
 * Short description for file.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @version       $Revision: 7945 $
 * @modifiedby    $LastChangedBy: gwoo $
 * @lastmodified  $Date: 2008-12-18 20:16:01 -0600 (Thu, 18 Dec 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
 
 //先頭で、存在するコントローラについてはその処理を行う
 //
//print $this->here . '<br />';
/*
 if(is_dir(CONTROLLERS)){
     if($dh = opendir(CONTROLLERS)){
         while(($name = readdir($dh)) !== false){
         //print $name;
             if($name == '.' || $name == '..' || $name == 'components') continue;
             $ct = explode("_", $name);
             $action = explode("/",$this->here);
             if(!isset($action['2'])){ $action['2'] = 'index'; }
             if(!isset($action['3'])){ $action['3'] = '0'; }
             
             Router::connect('/'.$ct['0'].'/*', array('controller' => $ct['0'],'action' => $action['2'], $action['3']));
             //print '/'.$ct['0'].'/*';
         }
         closedir($dh);
     }
 }
*/

//各種形式配信用の設定 rss xml json等
Router::parseExtensions();
//Router::parseExtensions();

Router::connect('/pieces/index/*', array('controller' => 'pieces','action' => 'index'));  //テスト用暫定
Router::connect('/pieces/add/*', array('controller' => 'pieces','action' => 'add'));  //テスト用暫定
Router::connect('/pieces/edit/*', array('controller' => 'pieces','action' => 'edit'));  //テスト用暫定
Router::connect('/pieces/delete/*', array('controller' => 'pieces','action' => 'delete'));  //テスト用暫定


/*
Router::connect('/supports/index/*', array('controller' => 'supports','action' => 'index'));  //テスト用暫定
Router::connect('/supports/add/*', array('controller' => 'supports','action' => 'add'));  //テスト用暫定
Router::connect('/supports/edit/*', array('controller' => 'supports','action' => 'edit'));  //テスト用暫定
Router::connect('/supports/delete/*', array('controller' => 'supports','action' => 'delete'));  //テスト用暫定
*/

Router::connect('/supports/increment/:task_id', array('controller' => 'supports','action' => 'increment'),array('task_id' => '[0-9]+'));
Router::connect('/supports/comment/', array('controller' => 'supports','action' => 'comment'));
Router::connect('/supports/nextcomment/', array('controller' => 'supports','action' => 'nextcomment'));

Router::connect('/users/logout', array('controller' => 'users','action' => 'logout'));
Router::connect('/users/login', array('controller' => 'users','action' => 'login'));
Router::connect('/users/add', array('controller' => 'users','action' => 'add'));
Router::connect('/users/settings', array('controller' => 'users','action' => 'settings'));
Router::connect('/users/notifications', array('controller' => 'users','action' => 'notifications'));
Router::connect('/users/twitter', array('controller' => 'users','action' => 'twitter'));
Router::connect('/users/password', array('controller' => 'users','action' => 'password'));
Router::connect('/users/recent_password', array('controller' => 'users','action' => 'recent_password'));
Router::connect('/users/reset_password/:email/:token', array('controller' => 'users','action' => 'reset_password'));
Router::connect('/users/capcha_img/*', array('controller' => 'users','action' => 'capcha_img'));
Router::connect('/users/complete_show_flag/:show_flag', array('controller' => 'users','action' => 'complete_show_flag'),array('show_flag' => '[0-2]{1}') );
Router::connect('/timelines/add/:task_id', array('controller' => 'timelines','action' => 'add'));
Router::connect('/timelines/delete/:timeline_id', array('controller' => 'timelines','action' => 'delete'));


Router::connect('/:url_user/negai/:task_id/*', array('controller' => 'tasks','action' => 'view','page' => 1));
//Router::connect('/:url_user/negai/:task_id/*', array('controller' => 'tasks','action' => 'view'));
Router::connect('/:url_user/mitinori/:timeline_id', array('controller' => 'tasks','action' => 'view'));

Router::connect('/tasks/add/', array('controller' => 'tasks','action' => 'add'));
Router::connect('/tasks/delete/:task_id', array('controller' => 'tasks','action' => 'delete'));
Router::connect('/tasks/completed/:task_id/:complete_flag', array('controller' => 'tasks','action' => 'completed'),array('task_id' => '[0-9]+','complete_flag' => '[0-1]{1}') );



Router::connect('/follows/add/:follow_id', array('controller' => 'follows','action' => 'add'),array('follow_id' => '[0-9]+'));
Router::connect('/follows/delete/:follow_id', array('controller' => 'follows','action' => 'delete'),array('follow_id' => '[0-9]+'));

Router::connect('/following/*', array('controller' => 'follows','action' => 'follow_list','following'=>TRUE,'page'=>1));
Router::connect('/followers/*', array('controller' => 'follows','action' => 'follow_list','followers'=>TRUE,'page'=>1));

Router::connect('/:url_user/following/*', array('controller' => 'follows','action' => 'follow_list','following'=>TRUE,'page'=>1));
Router::connect('/:url_user/followers/*', array('controller' => 'follows','action' => 'follow_list','followers'=>TRUE,'page'=>1));


 
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 */
	//Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
	Router::connect('/', array('controller' => 'tasks', 'action' => 'top'));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
	
	



Router::connect('/timeline/*', array('controller' => 'tasks', 'action' => 'index','user_timeline' => TRUE,'url_user'=>'home','page'=>1));

Router::connect('/:url_user/timeline/*', array('controller' => 'tasks', 'action' => 'index','user_timeline' => TRUE,'page'=>1));


Router::connect('/:url_user/task/:task_id', array('controller' => 'tasks', 'action' => 'view','user_task' => TRUE));


Router::connect('/public_timeline/*', array('controller' => 'tasks', 'action' => 'index','public_timeline' => TRUE,'page'=>1));


Router::connect('/:url_user', array('controller' => 'tasks', 'action' => 'index','page'=>1,'sort'=>'Timeline.modified','direction'=>'desc'));
Router::connect('/:url_user/*', array('controller' => 'tasks', 'action' => 'index',));




	
?>