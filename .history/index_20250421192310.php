<?php
$page = $_GET['page'] ?? 'dashboard';
include 'sidebar.php';

if ($page == 'dashboard') {
    include 'dashboard.php';
} elseif ($page == 'todo') {
    include 'todo.php';
}
?>
