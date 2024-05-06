<?php
include 'includes/init.php';
echo 'Main page <br>';
if ( ! is_user_login()){
echo '<a href="login.php">Login</a>';
}else{
    echo 'your are login';
}
?>