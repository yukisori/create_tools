<?php
 echo $this->Form->create('User', array('url' => 'useradd'));
 echo $this->Form->input('name',array('label'=>'ユーザ名'));
 echo $this->Form->input('password',array('label'=>'パスワード'));
 echo $this->Form->input('email',array('label'=>'アドレス','id'=>'Useremail'));
 echo $this->Form->input( 'role', array(
    'type' => 'checkbox',
    'label' => '管理者権限',
));
 echo $this->Form->end('新規ユーザを作成する');

?>
<table>
	<tr>
		<th>ユーザーナンバー</th>
		<th>ユーザーネーム</th>
		<th>削除</th>
	</tr>
<?php foreach ($user_list as $user_lists): ?>
<tr>
<td><?php echo $user_lists['User']['id'];?></td>
<td><?php echo $user_lists['User']['name'];?></td>
<td><?php echo $this->Form->postLink(__('削除'),array('action'=>'userdelete',h($user_lists['User']['id'])), null, __('削除申請を行いますか?'));?></td>
</tr>
<?php endforeach ?>
</table>
