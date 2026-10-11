<?php

ob_start();                                 
session_start();                            
date_default_timezone_set('Asia/Manila');
mysqli_report(MYSQLI_REPORT_OFF);            


$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "library";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error() . ". Check connection.php and make sure you imported database/schema.sql.");
}
mysqli_set_charset($conn, "utf8mb4");

define("LOAN_DAYS", 7);    
define("MAX_LOANS", 3);   


function run_query($conn, $sql, $types = "", $params = array()) {
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        die("Query error: " . mysqli_error($conn));
    }
    if ($types != "") {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    if (!mysqli_stmt_execute($stmt)) {
        die("Query error: " . mysqli_stmt_error($stmt));
    }
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    if ($result === false) {
        return true;       
    }
    return $result;      
}


function e($text) {
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}


function set_message($type, $text) {
    $_SESSION['message_type'] = $type;    
    $_SESSION['message']      = $text;
}


function first_letter($text) {
    return function_exists('mb_substr') ? mb_strtoupper(mb_substr($text, 0, 1)) : strtoupper(substr($text, 0, 1));
}


function loan_badge($loan) {
    if ($loan['returned_on'] !== null) {
        return '<span class="badge bg-secondary">Returned</span>';
    }
    if ($loan['due_on'] < date('Y-m-d')) {
        return '<span class="badge bg-danger">Overdue</span>';
    }
    return '<span class="badge bg-primary">Borrowed</span>';
}
?>
