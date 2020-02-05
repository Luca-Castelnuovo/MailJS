<?php

require $_SERVER['DOCUMENT_ROOT'] . '/../includes/init.php';

loggedin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_val($_POST['CSRFtoken'], '/project');

    $project_name = check_data($_POST['name'], true, 'Project name', true, '/project');

    $project_name = strtolower($project_name);
    $project_name = str_replace(' ', '_', $project_name);

    $existing_project = sql_select('projects', 'id', "owner_id='{$user_id}'  AND name='{$project_name}'", false);
    if ($existing_project->num_rows > 0) {
        redirect('/project', 'You already have a project with this name.');
    }

    sql_insert('projects', [
        'owner_id' => $_SESSION['id'],
        'name' => $project_name
    ]);

    mkdir("users/{$_SESSION['id']}/{$project_name}", 0770);

    redirect('/home', 'Project created');
}

page_header('Create Project');

?>

<style>
    .input-field input:focus + label {
        color: #2962FF !important;
    }

    .input-field input:focus {
        border-bottom: 1px solid #2962FF !important;
        box-shadow: 0 1px 0 0 #2962FF !important;
    }
</style>
<div class="row">
    <h4>Create Project</h4>
    <form method="post">
        <div class="row">
            <div class="input-field col s12">
                <label for="name">Project Name</label>
                <input type="text" id="name" name="name" required/>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <input type="hidden" name="CSRFtoken" value="<?= csrf_gen() ?>"/>
                <button class="col s12 btn waves-effect blue accent-4" type="submit">Create Project</button>
            </div>
        </div>
    </form>
</div>

<?= page_footer(); ?>
