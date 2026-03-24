<?php
session_start();
session_destroy();
header('Location: login.php'); // You may create a simple login page
exit;