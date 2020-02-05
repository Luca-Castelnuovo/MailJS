<?php

require $_SERVER['DOCUMENT_ROOT'] . '/../includes/init.php';

loggedin();

$project_id = check_data($_GET['project_id'], true, 'Project ID', true, '/home');
$existing_project = sql_select('projects', 'id', "owner_id='{$_SESSION['id']}'  AND id='{$project_id}'", false);
if ($existing_project->num_rows != 1) {
    redirect('/project', 'Project doen\'t exist');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_val($_POST['CSRFtoken'], '/project');

    $project = sql_select('projects', 'name', "owner_id='{$_SESSION['id']}'  AND id='{$project_id}'", true);

    $file_name = check_data($_POST['name'], true, 'File name', true, '/files/add');

    $file_name = strtolower($file_name);
    $file_name = str_replace(' ', '_', $file_name);
    $file_type = substr(strrchr($file_name,'.'),1);

    if (!in_array(substr(strrchr($file_name,'.'),1), $GLOBALS['config']->allowed_extensions)) {
        redirect('/files/add?project_id=' . $project_id, 'Incorrect file type.');
    }

    $existing_file = sql_select('files', 'id', "owner_id='{$_SESSION['id']}' AND project_id='{$project_id}' AND name='{$file_name}'", false);
    if ($existing_file->num_rows > 0) {
        redirect('/files/add?project_id=' . $project_id, 'You already have a file with this name.');
    }

    sql_insert('files', [
        'owner_id' => $_SESSION['id'],
        'project_id' => $project_id,
        'name' => $file_name
    ]);

    $file = fopen("../users/{$_SESSION['id']}/{$project['name']}/{$file_name}", "w");
    fclose($file);

    redirect('/home?project_id=' . $project_id, 'File created');
}

page_header('Create File');

?>

<style>
    .input-field input:focus + label {
        color: #2962FF !important;
    }

    .input-field input:focus {
        border-bottom: 1px solid #2962FF !important;
        box-shadow: 0 1px 0 0 #2962FF !important;
    }

    [type="radio"]:checked+span:after, [type="radio"].with-gap:checked+span:after {
        background-color: #2962FF !important;
        border: 2px solid #2962FF;
    }
</style>
<div class="row">
    <h4>Create File</h4>
    <form method="post" action="?project_id=<?= $project_id ?>">
        <div class="row">
            <div class="input-field col s12">
                <label for="name">File Name (.html, .css, .js, .json)</label> <input id="name" name="name" type="text">
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <input type="hidden" name="CSRFtoken" value="<?= csrf_gen() ?>"/>
                <button class="col s12 btn waves-effect blue accent-4" type="submit">Create File</button>
            </div>
        </div>
    </form>
</div>

<?= page_footer(); ?>
