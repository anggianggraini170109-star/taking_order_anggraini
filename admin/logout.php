<?php
session_start();
unset($_SESSION['admin_id'], $_SESSION['admin_nama']);
session_destroy();
header("Location: login.php");