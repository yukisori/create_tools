<?php

App::uses('Model', 'Model');

class Usercontroll extends Model {

    public $belongsTo = array('Matter','User');
    public $validate = array(

        'Usercontrollcsv' => array(

            // ルール：uploadError => errorを検証 (2.2 以降)
            'upload-file' => array(
                'rule' => array( 'uploadError'),
                'message' => array( 'アップロードできません')
            ),
            // ルール：extension => pathinfoを使用して拡張子を検証
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
      'matter_id' => array(
        'rule'     => array('multiple', array('min' => 1)),
        'required' => true,
        'message'  => '案件を１つ以上選んでください',

      ),
      'user_id' => array(
        'rule'     => array('multiple', array('min' => 1)),
        'required' => true,
        'message'  => 'ユーザーを選択してください',
      ),
      'year' => array(
        'rule' => 'notempty',
        'message' => '入力してください',
      ),
        'month' => array(
        'rule' => 'notEmpty',
        'message' => '入力してください',
      ),

    );

    public function UserControllloadCSV($filename,$year,$month,$user_id_list){
            $this->begin();
            try{
                $delete_index = array('date' => $year.'-'.$month.'-'.'00');//日付がかぶってたら削除して更新
                $this->deleteAll($delete_index,false);
                $csvData = file($filename,FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
                $csvcount = count($csvData);

                foreach ($user_id_list as $user_id_lists) {
                 $other_anken = array(
                    'user_id' => $user_id_lists['User']['id'],
                    'matter_id' => '99999',
                    'date'=>$year.'-'.$month.'-'.'00',
                    );
                    $this->create($other_anken);
                    $this->save();
                }

                foreach($csvData as $line){
                    $record = split(',',$line);
                    $record[0] = mb_convert_encoding($record[0], "UTF-8", "auto");
                    $record[1] = mb_convert_encoding($record[1], "UTF-8", "auto");
                    $data = array(
                        'user_id' => $record[0],
                        'matter_id' => $record[1],
                        'date'=>$year.'-'.$month.'-'.'00',
                    );
                    $this->create($data);
                    $this->save();
                }

                $this->commit();
            }catch(Exception $e){
                $this->rollback();
            }
    }

    public function UserControllManual($user_id,$matter_id,$date){
        $this->begin();
        try {
            foreach ($matter_id as $matter_ids) {
                $relation_data = array(
                    'user_id' => $user_id,
                    'matter_id' => $matter_ids,
                    'date'=>$date,
                );
                  $this->create($relation_data);
                  $this->save();
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
        }
        return true;
    }

}

?>