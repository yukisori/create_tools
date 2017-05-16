
<?php echo $this->Form->create('User',array('url' =>'/Mains/user_list','novalidate' => true));?>
<?php echo $this->Form->input('name');?>
<?php echo $this->Form->submit('登録します');?>
<table>
	<tr>
		<th>UserNo.</th>
		<th>Username</th>
		<th>delete</th>
	</tr>
<?php foreach ($user_list as $user_lists): ?>
<tr>
<td><?php echo $user_lists['User']['id'];?></td>
<td><?php echo $user_lists['User']['name'];?></td>
<td><?php echo $this->Form->postLink(__('削除'),array('action'=>'userdelete',h($user_lists['User']['id'])), null, __('削除申請を行いますか?'));?></td>
</tr>
<?php endforeach ?>
</table>
