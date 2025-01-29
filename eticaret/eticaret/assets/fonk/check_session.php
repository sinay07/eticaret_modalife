<?php
session_start();

if (isset($_SESSION['sepet']) && !empty($_SESSION['sepet'])) {
    echo "login-register.php";
} else {
    echo "checkout.php";
}
?>
