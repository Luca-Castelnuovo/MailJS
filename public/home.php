<?php

require $_SERVER['DOCUMENT_ROOT'] . '/../includes/init.php';

loggedin();

if (isset($_GET['CSRFtoken']) && isset($_GET['project_id'])) {
    projects_delete($_SESSION['id'], $_GET['project_id'], $_GET['CSRFtoken']);
}

page_header('Home');

?>

<div class="row">
    <?php 
        if (isset($_GET['project_id'])) {
            projects_info($_SESSION['id'], $_GET['project_id']);
        } else {
            projects_list($_SESSION['id']);
        }
    ?>
</div>

<?= page_footer() ?>
