<?php
    $errors = \Core\Sessions::errors("errors");
    $success_msg = \Core\Sessions::messages("success");
    $error_msg = \Core\Sessions::messages("error");

    echo $success_msg;
    echo $error_msg;
?>

<form action="" method="POST">
    <label for="">User name
        <input type="text" name="username" value="<?php old("username"); ?>"><br>
        <?php _print($errors,"username"); ?>
    </label><br><br>
    <label for="">Email
        <input type="text" name="email" value="<?php old("email"); ?>"><br>
        <?php _print($errors,"email"); ?>
    </label><br><br>
    <label for="">Password
        <input type="text" name="password">
    </label><br><br>
    <label for="">Confirm Password
        <input type="text" name="confrim">
    </label><br><br>
    <input type="submit" value="REGISTER">
</form>