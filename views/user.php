<h1>User Page</h1>
<?php
$errors = \Core\Sessions::messages("errors");
?>
<form action="/user/<?= $user_id; ?>" method="post">
    <label for="">
        User Name<br>
        <input type="text" name="username" placeholder="username" value=<?php old("username"); ?>><br>
        <?php _print($errors,"username"); ?>
    </label><br><br>
    
    <label>
        Email<br>
        <input type="text" name="email" placeholder="email" value=<?php old('email') ?>><br>
        <?php _print($errors,"email"); ?>
    </label><br><br>
   
    <input type="password" name="password" placeholder="password" value=<?php old('password') ?>>
    <input type="submit" value="Save">
</form>


<table border="1">
    <tr>
        <th>ID</th>
        <th>name</th>
        <th>Email</th>
    </tr>
    <?php foreach($users as $user):?>
        <tr>
            <td><?php __($user->id);?></td>
            <td><?php __($user->username);?></td>
            <td><?php __($user->email);?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php links(); ?>