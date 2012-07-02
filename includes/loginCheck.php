<?php
function loginCheck() {
    if (!(isset($_SESSION['login']) && $_SESSION['login'] != '')) {
        header ("Location: login.php");
    }
}
?>
