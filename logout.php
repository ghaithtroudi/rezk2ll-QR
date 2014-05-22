<?php


# no debugging
error_reporting(0);
session_start();

# let's destroy thid session , see you later

$_SESSION['log'] = NULL;
session_destroy(); // boom
?>
<script>
// and let's get back to home
location.replace('index.php');
</script>