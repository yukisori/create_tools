<?php 

App::uses('AppModel','Model');

class User extends AppModel{


public $hasMany = array('Emp','Usercontroll');

     public $actsAs = array('Utill');

	 var $validate = array(
        'name' => array(
                array(
                'rule' => 'notEmpty',
                    'required' => true,
                    'message' => '内容を入力してください'
                ),
                array(
                    'rule' => 'isUnique',
                    'message' => 'このユーザー名は既に登録されています'
                ),
      	),
        'email' => array(
                array(
                    'rule' => 'isUnique',
                    'message' => 'このユーザー名は既に登録されています'
                ),
        ),
      	'password' => array(
                array(
                'rule' => 'notEmpty',
                    'required' => true,
                    'message' => '内容を入力してください'
                ),
      	)
    );

	public function getActivationHash() {
    	// ユーザIDの有無確認
        if (!isset($this->id)) {
            return false;
        }
        // 更新日時をハッシュ化
        return Security::hash( $this->field('created'), 'md5', true);
    }

}
 ?>