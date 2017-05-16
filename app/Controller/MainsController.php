<?php

class MainsController extends AppController{

	var $uses = array('Matter','User','Usercontroll','Emp');//モデルの読み込み

	public $paginate = array(
            'Matter' => array(
                'order' => array('id' => 'asc'),  //データを降順に並べる
				'sort'	=> 'id',
				'limit' => 10, //1ページ表示できるデータ数の設定
            )
        );
    public function isAuthorized($user) {
    	if (isset($user['role']) && $user['role'] == 1) {
            return true;
        }else{
			$this->redirect(array('controller'=>'users','action'=>'index'));
        }
    }

	public function index(){
		$this->set("title_for_layout","ユーザー選択");//タイトルの設定
		$this->layout = "users";//usersレイアウトの読み込み
	}


    public function logout(){
    	if($this->Auth->logout()){
			$this->Session->setFlash('ログアウトしました');
			$this->redirect(array('controller'=>'mains','action'=>'login'));
    	}else{
    		$this->Session->setFlash('ログアウトできませんでした');
    	}

 	}
	public function login(){
		$this->layout = "top";//usersレイアウトの読み込み
		 //POST送信なら
		 if($this->request->is('post')) {
		 	//ログインOKなら
			  if($this->Auth->login()) {
			  	//Auth指定のログインページへ移動
			  	return $this->redirect(array('controller'=>'mains','action'=>'index'));
			  } else { //ログインNGなら
			  	$this->Session->setFlash(__('ユーザ名かパスワードが違うか本登録されていません'), 'default', array(), 'auth');
			  }
		 }
	}

	//ユーザー追加（認証が不要なページ）
	public function useradd(){
		$this->set('user_list',$this->User->find('all'));
		 	//POST送信なら
		 if($this->request->is('post')) {
			 //パスワードのハッシュ値変換
			 $this->request->data['User']['password'] = AuthComponent::password($this->request->data['User']['password']);
			 //作ったときの時間
			 $this->request->data['User']['created'] = date('Y-m-d H:i:s');
 			 //ユーザーの作成
			 $this->User->create();
			 //リクエストデータを保存できたら
			 if ($this->User->save($this->request->data)) {
			 	 // ユーザアクティベート(本登録)用URLの作成
	             $url =
	                DS . strtolower($this->name) .          // コントローラ
	                DS . 'activate' .                       // アクション
	                DS . $this->User->id .                  // ユーザID
	                DS . $this->User->getActivationHash();  // ハッシュ値
	             $url = Router::url($url, true);  // ドメイン(+サブディレクトリ)を付与
	             $this->Session->write('hash_session', $this->User->getActivationHash());
	             //  メール送信
			 	 $Email_address = $this->request->data['User']['email'];
				 $Email_content_value1 = $this->request->data['User']['name'];
				 $Email_content_value2 = $url;
				 $this->User->email($Email_address,$Email_content_value1,$Email_content_value2);
				 $this->Session->setFlash(__('仮登録が完了しました。メールをご確認ください'));
				 $this->redirect(array('action' => 'useradd'));
			 } else { //保存できなかったら
			 	$this->Session->setFlash(__('登録できませんでした。やり直して下さい'));
			 }
		 }
	}

	public function activate($user_id = null, $in_hash = null){//叩いたURLが正しいかどうか
	    // UserモデルにIDをセット
	    $this->User->id = $user_id;
	    $hash = $this->Session->read('hash_session');//セッションを格納
	    // 本登録に有効なURL
	    if ($this->User->exists() && $in_hash == $this->User->getActivationHash() && $in_hash == $hash) {
	    	//ユーザーが存在している　または　urlとハッシュが正しいか　または　セッションとハッシュが正しいか
	        $this->User->saveField('state',1);//ログインの判定を1にする
			$auth = $this->User->find('first',array('conditions' => array('User.id'=> $user_id)));
			$this->Session->delete('hash_session');//セッションの削除
			$this->Session->setFlash("本登録完了!");
			$this->redirect(array('controller' =>'mains','action'=>'login'));
	    }else{
	    // 本登録に無効なURL
	        $this->Session->setFlash( '無効なURLです');
	    }
	}


	public function add(){//案件を追加する
		$this->set("title_for_layout","案件登録");//タイトルの設定
		if($this->request->is('post')){
			if($this->Matter->save($this->request->data)){
				$this->Session->setFlash(_("登録しました。"));
				$this->redirect(array('controller' =>'mains','action'=>'view'));
			}else{
				$this->Session->setFlash(_("登録できません"));
			}
		}
	}

	public function view($id=null){
		 $this->set('matters',$this->paginate());//一覧をページネートで取得
		 $this->set("title_for_layout","案件一覧");
	}

	public function edit($id = null){
		$this->set("title_for_layout","案件編集");
		$this->Matter->id = $id;
		$this->set('matter',$this->Matter->read());
			if($this->request->is('get')){
				$this->request->data = $this->Matter->read();//指定された番号の中身を渡す
			}else{
				if($this->Matter->save($this->request->data))
				{
					$this->Session->setFlash("編集しました");
					$this->redirect(array('controller' =>'mains','action'=>'view'));
				}else{
					$this->Session->setFlash('編集を失敗しました');
				}
			}
	}

	public function delete($id=null){
		$this->Matter->id= $id;
			if ($this->Matter->delete()) {
				$this->Session->setFlash("削除しました");
				$this->redirect(array('controller' =>'mains','action'=>'view'));
			}else{
				$this->Session->setFlash("削除失敗");
			}
	}

	public function userdelete($id=null){
		$this->User->id = $id;
			if ($this->User->delete()) {
				$this->Session->setFlash("削除しました");
				$this->redirect(array('controller' =>'mains','action'=>'useradd'));
			}else{
				$this->Session->setFlash("削除失敗");
			}
	}

	public function usercon($id=null,$date=null){
		$this->set("title_for_layout","ユーザー別案件管理");
		$this->User->id = $id;//User_idがパラメータ
		$this->set('use',$this->User->read());//id 確認用
		$this->set('User',$this->User->find('all'));//ユーザー全県表示用
		if(!empty($this->request->params['named']['date'])) $date = $this->params['named']['date'];//urlの日付を取得
		$this->set('date',$date);//現在選択されている日付パラメーターの
		//選択月にユーザーが何人いるのか調べて次の検索に渡すための条件
		$usecon = $this->Usercontroll->find('all',array(
	        'conditions' => array(
	        	'Usercontroll.user_id'=>$id,
	   			'Usercontroll.date like'=>"{$date}%",
	    	)));
		$this->set('usecon',$usecon);
		foreach ($usecon as $usecons) {
			$matterid[]= $usecons['Matter']['number'];//選択月にユーザーが何人いるのか調べる
		}
		$this->Matter->virtualFields['anken_sum'] = 0;//anken_sumをMatter配列内に入れる
		$anken_total = $this->Emp->find('all',array(
	        'conditions' => array(
	        	'Emp.user_id'=>$id,
	        	'Emp.matter_id'=>$matterid,
	   			'Emp.date like'=>"{$date}%",
	    	),
	    	'fields' => array(
	    		'DISTINCT Matter.number,Matter.mcontent,Matter.mcompany,SUM(Emp.coretime) as Matter__anken_sum'
	    	),//案件の合計を出力する
	    	'group' => array('matter_id',//matter_idでまとめる
	    	),
	    	));
		$this->set('anken_total',$anken_total);
		$this->set('usercontroll',$this->Usercontroll->find('all'));

		$this->User->virtualFields['total_sum'] = 0;
		$this->set('total',$this->Emp->find('all',array(
			'conditions'=> array(
				'Emp.user_id'=>$id,
				'Emp.date like'=>"{$date}%",
				),
			'fields' => array(
				'DISTINCT User.name,SUM(Emp.coretime) as User__total_sum'
				),
			'group' => array(
				'User.id',
				),
			)));
	}



	public function import(){
		$this->set("title_for_layout","CSV");
		$this->User->unbindModel(array('hasMany'=>array('Emp','Usercontroll')), false);//紐付けやめる
        $user_id_list = $this->User->find('all');//全部取り出す
        $this->set('user_id_list',$user_id_list);//UserCSVにユーザー全件を送るため

        if($this->request->is('post')){
            if(isset($this->request->data['matter_import'])){
              $up_file = $this->data['Matter']['Mattercsv']['tmp_name'];
              $fileName = 'matter.csv';
              $this->Matter->set($this->request->data);
				if ($this->Matter->validates( array( 'fieldList' => array( 'Mattercsv')))){
		            if(is_uploaded_file($up_file)){
		                move_uploaded_file($up_file, $fileName);
		                $this->Matter->MatterloadCSV($fileName);
		                $this->Session->setFlash('アップロードしました');
		                $this->redirect(array('controller'=>'mains','action'=>'view'));
		            }
		        }
	        }else if(isset($this->request->data['usecon_import'])){
	            	$up_file2 = $this->data['Usercontroll']['Usercontrollcsv']['tmp_name'];
                	$fileName = 'usercontroll.csv';
                	$month = $this->request->data['Usercontroll']['month'];
                	$year = $this->request->data['Usercontroll']['year'];
                	$this->Usercontroll->set($this->request->data);
				if ($this->Usercontroll->validates( array( 'fieldList' => array('Usercontrollcsv')))) {
		        	if(is_uploaded_file($up_file2)){
		                move_uploaded_file($up_file2, $fileName);
		                $this->Usercontroll->UserControllloadCSV($fileName,$year,$month,$user_id_list);
		                $this->Session->setFlash('アップロードしました');
		                $this->redirect(array('controller'=>'mains','action'=>'usercon'));
		            }
		        }
	        }else{
	            $this->Session->setFlash('アップロード失敗');
	            $this->redirect(array('action'=>'index'));
	        }
        }
    }

	public function matter($id=null,$date=null){
		$this->set("title_for_layout","案件別ユーザー");
		$this->Matter->number = $id;
		// if(!$this->Matter->exists($id)){//レコードが存在した場合の処理
 	// 		throw new NotFoundException();
		// }
		if(!empty($this->request->params['named']['date'])) $date = $this->params['named']['date'];
		if(!empty($this->request->params['named']['year'])) $year_count = $this->params['named']['year'];
		$this->set('date',$date);
		$this->set('matter_read',$this->Matter->read(null , $id));//readにid(number)を設定syry
		//ユーザーの個別案件時間
		$this->User->virtualFields['anken_sum'] = 0;//Userの中にsumを入れる
		$empcondision = $this->Emp->find('all',array(
	        'conditions' => array(
	   			// 'Emp.date like'=>"{$date}%",
	   			'Emp.matter_id'=>$id,
	    	),
	    	'fields' => array(
	    		'DISTINCT User.id,User.name,SUM(Emp.coretime) as User__anken_sum'
	    		),
	    	'group' => array('username',
	    		),
	    	));
		$this->set('emp',$empcondision);
		foreach ($empcondision as $empcondisions) {
			//ユーザーが何人いるのか調べる
			$userid[]= $empcondisions['User']['id'];
		}
		$this->User->virtualFields['total_sum'] = 0;//User配列の中にSUM表示を入れる
		//ユーザーの総労働時間を調べる
		$this->set('Useranken_total',$this->Emp->find('all',array(
	        'conditions' => array(
	   			'Emp.date like'=>"{$date}%",
	   			'Emp.user_id'=>$userid,//表示されているユーザーだけ
	    	),
	    	'fields' => array(
	    		'DISTINCT User.name,SUM(Emp.coretime) as User__total_sum'
	    		),
	    	'group' => array('username',
	    		),
	    )));


	}

	public function relation_manual(){

		$this->set("title_for_layout","ユーザー情報の紐付け");
		$this->User->unbindModel(array('hasMany'=>array('Emp','Usercontroll')), false);
		$this->Matter->unbindModel(array('hasMany'=>array('Emp','Usercontroll')), false);
		$User_count = $this->User->find('list',array('fields' => Array('User.id', 'User.name'),));
		$Matter_count = $this->Matter->find('list',array('fields' => Array('Matter.number','Matter.mcontent','Matter.mcompany'),));
		$this->set('User',$User_count);//ユーザー全県表示用
		$this->set('Matter',$Matter_count);//ユーザー全県表示用
		if($this->request->is('post')){
			if(isset($this->request->data['usecon_add'])){
				$month = $this->request->data['Usercontroll']['month'];
                $year = $this->request->data['Usercontroll']['year'];
                $user_id = $this->request->data['Usercontroll']['user_id'];
                $matter_id = $this->request->data['Usercontroll']['matter_id'];
                $date = $year.'-'.$month.'-'.'00';
                $this->Usercontroll->set($this->request->data);
                if($this->Usercontroll->validates(array('fieldList'=>array('matter_id','user_id')))){

	                if($this->Usercontroll->UserControllManual($user_id,$matter_id,$date)){
		              $this->Session->setFlash('アップロードしました');
	              	  $this->redirect(array('controller'=>'mains','action'=>'relation_manual'));
			     	}else{
		              $this->Session->setFlash('アップロードできませんでした');
	              	  $this->redirect(array('controller'=>'mains','action'=>'relation_manual'));
			     	}

			    }

			}
		}
	}

	public function relation_manual_comfilm(){
		//確認画面現在保留中
	}

	public function user_list(){
		$this->set('user_list',$this->User->find('all'));
		if($this->request->is('post')){
			if($this->User->save($this->request->data)){
				$this->Session->setFlash('登録しました');
				$this->redirect(array('controller'=>'mains','action'=>'user_list'));
			}else{
				$this->Session->setFlash('登録できませんでした');
			}
		}
	}

}
;?>