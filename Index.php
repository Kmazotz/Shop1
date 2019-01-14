<?php 

    require "Vendor/autoload.php";

    define('ViewPath', dirname(dirname(__FILE__)) . "\App\Components\\");

    header('location: Public/Index.html');
?>
