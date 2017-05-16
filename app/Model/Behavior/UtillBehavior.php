<?php 

class UtillBehavior extends ModelBehavior {

    public function email($N,$e,$v1,$v2){	
    	$email = new CakeEmail( 'gmail');                        // インスタンス化
		$email->from( array('sender@domain.com' => 'Sender'));  // 送信元
		$email->to($e);                      // 送信先
		$email->subject('メールタイトル');// メールタイトル
		$email->emailFormat('html');                            // フォーマット
		$email->template('tmp');                           // テンプレートファイル
		$email->viewVars(compact('v1', 'v2'));             // テンプレートに渡す変数
		$email->send();
    }

}
;?>
