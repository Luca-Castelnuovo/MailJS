<?php

// Validate data is set
function is_empty($var, $type ='Unknown', $redirectTo = '/')
{
    if (empty($var)) {
        redirect($redirectTo, "{$type} is empty.");
    }
}


// Clean data
function clean_data($data)
{
    $conn = sql_connect();
    $data = $conn->escape_string($data);
    sql_disconnect($conn);

    $data = trim($data);
    $data = htmlspecialchars($data);
    $data = stripslashes($data);

    return $data;
}


// Check data
function check_data($data, $isEmpty = true, $isEmptyType = 'Unknown', $clean = true, $redirectTo = null)
{
    if ($isEmpty) {
        is_empty($data, $isEmptyType, $redirectTo);
    }

    if ($clean) {
        return clean_data($data);
    } else {
        return $data;
    }
}


// Generate random string
function gen($length)
{
    $length = $length / 2;
    return bin2hex(random_bytes($length));
}


// Set CSRF
function csrf_gen()
{
    if (isset($_SESSION['CSRFtoken'])) {
        return $_SESSION['CSRFtoken'];
    } else {
        $_SESSION['CSRFtoken'] = gen(32);
        return $_SESSION['CSRFtoken'];
    }
}


// Validate CSRF
function csrf_val($CSRFtoken, $redirect = '/')
{
    if (!isset($_SESSION['CSRFtoken'])) {
        redirect($redirect, 'CSRF Error');
    }

    if (!(hash_equals($_SESSION['CSRFtoken'], $CSRFtoken))) {
        redirect($redirect, 'CSRF Error');
    } else {
        unset($_SESSION['CSRFtoken']);
    }
}


// Validate CAPTCHA
function captcha_val($resonse, $redirect = '/')
{
    $url = "https://www.google.com/recaptcha/api/siteverify?secret={$GLOBALS['config']->recaptcha->secret_key}&response={$resonse}";
    $response = json_decode(file_get_contents($url));

    if (!$response->success) {
        redirect($redirect, 'Please try again.');
    }
}
