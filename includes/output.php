<?php

//Redirect user
function redirect($to, $alert = null)
{
    if (!empty($alert)) {
        alert_set($alert);
    }

    header('location: ' . $to);
    exit;
}


// Set message
function alert_set($alert)
{
    $_SESSION['alert'] = $alert;
}


// Read message
function alert_display()
{
    if (isset($_SESSION['alert']) && !empty($_SESSION['alert'])) {
        echo "<script>M.toast({html: \"{$_SESSION['alert']}\"});</script>";
        unset($_SESSION['alert']);
    }
}
