<?php

include 'database.php';

/* Block access if no one is logged in */
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

?>