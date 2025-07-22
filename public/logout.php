<?php

require_once '../utility/databaseUtility.php';

clearSecureSession();

setcookie('id', '', time() - 3600);
setcookie('key', '', time() - 3600);

header('Location: signin.php');