<?php 

App::uses('AppModel','Model');

class Emp extends AppModel{
public $name = 'Emp';

//public $hasMany = array('Usercontroll');
public $belongsTo = array('Matter','User');

public $validate = array(
  'coretime' =>
  	array(
	  	 'rule1' => array(
		    'rule' => 'notEmpty',
		    'message' => '入力してください',
		),
		'rule2' => array(
		    'rule'    => array( 'range', -1, 24),  // マイナス1より大きく11より小さい
		    'message' => '入力時間が間違っています',
		    //'allowEmpty' => true           // 空白許可
	    ),
    	'rule3' => array(
	        'rule'    => 'numeric',
	        'message' => '数字で入力してください',
	        // 'allowEmpty' => true             // 空白許可
    	),
	),
  );



}
 ?>
