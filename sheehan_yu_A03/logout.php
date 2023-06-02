<?php
session_start();

if (isset($_SESSION['student_ID'])) {
    unset($_SESSION['student_ID']);
    unset($_SESSION['account_type']);
    session_destroy();
}

header("Location: index.php");
exit;
?>
