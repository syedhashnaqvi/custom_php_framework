<h1>User Page</h1>
<?php
$errors = \Core\Sessions::messages("errors");
?>
<form action="/user/<?= $user_id; ?>" method="post">
    <label for="">
        User Name<br>
        <input type="text" name="username"  value=<?php old("username"); ?>><br>
        <?php _print($errors,"username"); ?>
    </label><br><br>
    
    <label>
        Email<br>
        <input type="text" name="email"><br>
        <?php _print($errors,"email"); ?>
    </label><br><br>
   
    <input type="password" name="password">
    <input type="submit" value="Save">
</form>