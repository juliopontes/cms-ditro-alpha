Hello this is a view from com_users component.

<h3>Users</h3>
<ul>
<?php foreach ($this->users as $user): ?>
	<li><?php echo $user->username; ?></li>
<?php endforeach; ?>
</ul>