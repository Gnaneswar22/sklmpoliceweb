// File: admin/logout.php
<?php
session_start();
session_destroy();
header('Location: admin_panel.php');
exit();
?>
