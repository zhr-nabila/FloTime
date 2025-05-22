<?php
$pageTitle = 'Pomodoro';
$activePage = 'pomodoro';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pomodoro</title>
  <link rel="stylesheet" href="css/pomodoro.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="include/sidebar.css">
  <link rel="stylesheet" href="include/topbar.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
   <!-- sidebar -->
    <?php
    $activePage = 'todo';
    include 'includes/sidebar.php';
    ?>

    <div class="main-content">
        <!-- topbar -->
        <?php
        $pageTitle = "Todo List";
        include 'includes/topbar.php';
        ?>
  
  <script src="js/pomodoro.js"></script>
</body>
</html>
