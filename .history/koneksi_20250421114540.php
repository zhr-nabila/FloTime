<?php
define("LOCALHOST", "localhost");
define("USERNAME", "root");
define("PASSWORD", "");
define("SELECTED_DB", "todolist");
  $conn = new mysqli(LOCALHOST, USERNAME, PASSWORD, SELECTED_DB);
  echo !$conn ? "DB FAILED" : "DB SUCCESSFULLY CONNECTED";