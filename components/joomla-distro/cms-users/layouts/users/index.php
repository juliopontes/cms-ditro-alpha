Hello this is a view from com_users component.

<?php foreach ($this->users as $user): ?>
	<?php echo $user->username; ?>
	<br />
<?php endforeach; ?>