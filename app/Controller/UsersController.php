<?php

class UsersController extends AppController {

	var $uses = array('Matter','User','Emp','Usercontroll');


    public function isAuthorized($user) {
        if (isset($user['role'])) {
            return true;
        }else{
			$this->Session->setFlash('不正なユーザーです。再登録をし直してください');
			$this->redirect(array('controller'=>'mains','action'=>'login'));
        }
    }

	public function index($id=null,$year=null,$month=null,$day=null,$edit_id=null){

		$this->set("title_for_layout","工数入力");
		$this->layout="users";
		$this->User->id = $id;
		$this->Emp->id = $edit_id;//変換するときはここは消す
		if(!empty($this->request->params['named']['year'])) $year = $this->params['named']['year'];
		if(!empty($this->request->params['named']['month'])) $month = $this->params['named']['month'];
		if(!empty($this->request->params['named']['day'])) $year = $this->params['named']['day'];
		$this->set('ye',$year);
		$this->set('mo',$month);
		$this->set('da',$day);
		$yearMonthDay = $year.'-'.$month.'-'.$day;

		$this->set('emp',$this->Emp->find('all',array(
        'conditions' => array(
        	'Emp.date'=>$yearMonthDay,
   			'Emp.user_id'=>$id,
    	))));

		$yearMonth = $year.'-'.$month;
  		//データセット
		$this->set('select_user', $this->User->read());
		$this->set('user',$this->User->find('all'));
		$this->set('matter',$this->Matter->find('all'));
		$this->set('edit',$this->Emp->read());
		$this->set('usecon',$this->Usercontroll->find('all',array(
	        'conditions' => array(
	        	'Usercontroll.user_id'=>$id,
	   			'Usercontroll.date like'=>"{$yearMonth}%",
	    	))));

		if($this->request->is('post')){
			if(isset($this->params['data']['emppost'])){
				if($this->Emp->saveAll($this->request->data['Emp'])){
					$this->Session->setFlash(_("登録完了です"));
					$this->redirect($this->referer());
				}else{
					$this->Session->setFlash(_("登録できません"));
				}
			}else{
				if(empty($this->request->data)) {
					$this->render();
				} else {
					$key = $this->request->data['User']['name'];
					$search =  $this->User->find('all',array(
						"conditions" => array(
							'User.name LIKE' => "%".$key."%",
							),
						));
					$this->set('username',$search);
				}
			}
		}
		if($this->request->is('get')){
	    	    $this->request->data=$this->Emp->read();
    		}else{
    			if(!$this->request->is('post')){
		        	if($this->Emp->save($this->request->data)){
		       	    	$this->Session->setFlash('更新完了');
		       	    	$this->redirect($this->redirect(array('controller' =>'users','action'=>'index',$id,$year,$month,$day)));
		        	} else {
	            		$this->Session->setFlash('更新失敗');
	        		}
        	}
    	}
	}
	public function delete($id=null){
		$this->Emp->id= $id;
			if ($this->Emp->delete()) {
				$this->Session->setFlash("削除しました");
				$this->redirect($this->referer());
			}else{
				$this->Session->setFlash("削除失敗");
			}
	}

}
;?>