<?php

require $_SERVER['DOCUMENT_ROOT'] . '/../includes/init.php';

loggedin();

if (isset($_GET['project_id']) && isset($_GET['file_id']) && isset($_GET['CSRFtoken'])) {
    files_delete($_SESSION['id'], $_GET['project_id'], $_GET['file_id'], $_GET['CSRFtoken']);
} elseif (isset($_GET['project_id']) && isset($_GET['CSRFtoken'])) {
    projects_delete($_SESSION['id'], $_GET['project_id'], $_GET['CSRFtoken']);
}

page_header('Home');

?>

<?php if (!isset($_GET['project_id'])) { ?>
<div class="row">
    <?php projects_list($_SESSION['id']); ?>
</div>
<?php } else { ?>
<div class="row">
    <?php projects_info($_SESSION['id'], $_GET['project_id']); ?>
</div>
<?php } ?>

<?= page_footer() ?>
