<?php

App::uses('AppModel','Model');

class Overtime extends AppModel{
	public $hasMany = array('User','Emp');
}
 ?>