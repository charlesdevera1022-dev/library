<?php
include 'connection.php';

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
} elseif ($_SESSION['role'] == 'admin') {
    header("Location: admin/books.php");
} else {
    header("Location: books.php");
}
exit;
?>
