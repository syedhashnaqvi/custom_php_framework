<?php
    $errors = \Core\Sessions::errors("errors");
    $error_msg = \Core\Sessions::messages("error");
    echo $error_msg;
?>

<form action="" method="POST">
    <label for="">Email
        <input type="text" name="email" value="<?php old("email"); ?>"><br>
        <?php _print($errors,"email"); ?>
    </label><br><br>
    <label for="">Password
        <input type="text" name="password"><br>
        <?php _print($errors,"password"); ?>
    </label><br><br>
    <input type="submit" value="LOGIN">
</form>