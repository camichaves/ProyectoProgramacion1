<?php
session_start();
session_destroy();
header("location: http://localhost/proyProg1/login.php");