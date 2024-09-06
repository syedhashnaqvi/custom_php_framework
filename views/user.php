<h1>User Page</h1>
<p><?php echo $details; ?></p>

<form action="/user/<?= $user_id; ?>" method="post">
    <input type="text" name="username">
    <input type="email" name="email">
    <input type="password" name="password">
    <input type="submit" value="Save">
</form>