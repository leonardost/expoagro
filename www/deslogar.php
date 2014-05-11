<?php

    session_start();
    session_destroy();
    // http://stackoverflow.com/questions/9971626/session-variables-do-not-get-destroy-even-using-session-destroy
    $_SESSION = array();

    header('Location: index.php');
    exit;
