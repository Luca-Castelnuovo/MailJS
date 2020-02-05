<?php

require $_SERVER['DOCUMENT_ROOT'] . '/../includes/init.php';

if (isset($_GET['authenticate'])) {
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['state'] = $provider->getState();
    header('Location: '.$authUrl);
}

if (isset($_GET['code'])) {
    if(empty($_GET['state']) || ($_GET['state'] !== $_SESSION['state']))  {
        redirect('/?reset', $error);
    }

    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    try {
        $user = $provider->getResourceOwner($token);
        login($provider->getResourceOwner($token));
    } catch (Exception $error) {
        redirect('/?reset', $error);
    }
}

if (isset($_GET['logout'])) {
    alert_set('You are logged out.');
    reset_session();
}

if (isset($_GET['reset'])) {
    reset_session();
}

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    redirect('/home');
}

?>
<!DOCTYPE html>
<html>

<head>
    <!-- Config -->
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="manifest" href="/manifest.json"></link>
    <title>Login || MailJS</title>

    <!-- SEO -->
    <link href="https://mail.lucacastelnuovo.nl" rel="canonical">
    <meta content="Backend for PWA mail apps" name="description">

    <!-- Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
</head>

<body>
<div class="row">
    <div class="col s12 m8 offset-m2 l4 offset-l4">
        <div class="card">
            <div class="card-action blue accent-4 white-text">
                <h3>TestingPlatform</h3>
            </div>
            <div class="card-content">
                <div class="row center">
                    <a class="waves-effect waves-light btn-large blue accent-4" href="?authenticate">
                        Login with GitHub
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <?= alert_display() ?>
</body>

</html>
