<?php
session_start();

// Clear session and redirect to homepage
session_unset();
session_destroy();
header("Location: index.php");
exit();
?>
