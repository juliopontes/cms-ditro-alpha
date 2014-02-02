Hello this is a view from administrator com_users component.

<h3>Manage Users</h3>
<ul>
<?php foreach ($this->users as $user): ?>
	<li><?php echo $user->username; ?></li>
<?php endforeach; ?>
</ul>