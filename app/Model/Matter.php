<?php 

App::uses('AppModel','Model');

class Matter extends AppModel{
public $hasMany = array('Emp','Usercontroll');
public $primaryKey = 'number';

// public $uniqueKey = 'number';
public $validate = array(

    'Mattercsv' => array(
        'upload-file' => array( 
            'rule' => array( 'uploadError'),
            'message' => array( 'アップロードできません')
        ),
        'extension' => array(
            'rule' => array( 'extension', array( 
                'csv')  // 拡張子を配列で定義
            ),
            'message' => array( '拡張子が違います')
        ),
        'size' => array(
            'maxFileSize' => array( 
                'rule' => array( 'fileSize', '<=', '10MB'),  // 10M以下
                'message' => array( 'ファイルサイズが大きすぎます')
            ),
            'minFileSize' => array( 
                'rule' => array( 'fileSize', '>',  0),    // 0バイトより大
                'message' => array( 'ファイルサイズが小さすぎます')
            ),
        ),
    ),
  'number' => 
    array(
        'rule1' => array(
            'rule' => 'notEmpty',
            'message' => '内容を入力してください',
            ),
        'rule2' => array(
            'rule' => 'myIsUnique',
            'message' => 'そのIDは既に使用されています。'
        ),
        "rule3" =>  array(
              'rule' => 'alphaNumeric',
              'message' => 'ユーザ名は半角英数字のみ使用できます。'
            ),
        'rule4' => array(
                'rule'    => 'numeric',
                'message' => '数字で入力してください',
        ),
    ),
    'mcompany' =>
    array(
        'rule1' =>  array(
             'rule' => 'notEmpty',
             'message' => '内容を入力してください',
        ),
        'rule2' => array(
             'rule' => 'uniqueConfirm',
             'message' => '会社名と案件名は異なる名前にしてください。'
        ),
    ),
    'mcontent' =>
     array(
        'rule' => 'notEmpty',
        'message' => '内容を入力してください',
     ),

);


    public function MatterloadCSV($filename){
            $this->begin();
            try{
                //$this->deleteAll('1=1',false); //すべて上書きの場合
                $csvData = file($filename,FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
                $delete_index = array('Matter.number=1');
                $this->deleteAll($delete_index,false);//被ってたら被っていたところを削除して更新

                foreach($csvData as $line){
                    $record = split(',',$line);
                    $record[0] = mb_convert_encoding($record[0], "UTF-8", "auto");//utf-8にエンコード
                    $record[1] = mb_convert_encoding($record[1], "UTF-8", "auto");
                    $record[2] = mb_convert_encoding($record[2], "UTF-8", "auto");
                    $record[3] = mb_convert_encoding($record[3], "UTF-8", "auto");
                    if($record[3] == 1){
                    //フラグが1の場合のみ配列に格納
                        $data = array(
                            'number' => $record[0],
                            'mcontent' => $record[1],
                            'mcompany' => $record[2],
                            'flag' => $record[3],
                        );
                    }else{
                        continue;
                    }
                      $this->create($data);
                      $this->save();
                }

                $this->commit();
            }catch(Exception $e){
                $this->rollback();
            }
    }


public function myIsUnique($check){

    $results = $this->find('all', array(
        'conditions' => array(
            'number' => $check['number'],
        ),
    ));
    var_dump($check);
    if(sizeof($results) == 0){
        return true;
    }else{
        return false;
    }
}

public function  uniqueConfirm($check){

    if($this->data['Matter']['mcompany'] !== $this->data['Matter']['mcontent']){
        return true;
    }else{
        return false;
    }

}



}

 ?>
