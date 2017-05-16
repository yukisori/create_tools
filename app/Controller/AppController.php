<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	 public $uses = array('User');
	 public $actsAs = array('Utill');
	 //使用コンポーネントの登録
	 public $components = array(
	 'Security' => array('validatePost' => false),
	 'Session',
		 'Auth' => array(
			 //ログイン後の移動先
			 'loginRedirect' => array('controller' => 'mains', 'action' => 'index'),
			 //ログアウ後の移動先
			 'logoutRedirect' => array('controller' => 'mains', 'action' => 'login'),
			 //ログインページのパス
			 'loginAction' => array('controller' => 'mains', 'action' => 'login'),
			 //未ログイン時のメッセージ
			 'authError' => 'あなたのお名前とパスワードを入力して下さい。',
			 'authorize' => array('Controller'),
			 'authenticate' => array(
            	'Form' => array(
            		'userModel' => 'User',
            		'fields' => array('username' => 'name'),
                	'scope' => array('state' => 1),//フラグが１じゃなくてはログインが許可されない
            		),
        		),
		 	)
	 );

	public function beforeFilter(){
		 //親クラスのbeforeFilterの読み込み
		 parent::beforeFilter();
		 $this->Security->validatePost = false; //改竄対応
		 $this->Security->csrfCheck = false;    //CSRF対応
		 $this->set('userinfo',$this->Auth->user());
		 //認証不要のページの指定
		 $this->Auth->allow('login','logout','useradd','activate');
	 }

}
