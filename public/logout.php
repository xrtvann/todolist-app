<?php

session_start();
session_unset();

setcookie('id', '', time() - 3600);
setcookie('key', '', time() - 3600);

session_destroy();

header('Location: signin.php');