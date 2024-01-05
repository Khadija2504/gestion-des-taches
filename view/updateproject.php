<?php
require_once '../controller/projects.controller.php';

$updateProject = new ProjectController;

$projectIdToUpdate = null;
$projectDetails = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_project'])) {
    $projectIdToUpdate = $_POST['project_id'];
    $projectDetails = $updateProject->getProjectDetails($projectIdToUpdate);

    if (!$projectDetails) {
        echo "Error: Project details not found for ID: $projectIdToUpdate";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_update'])) {
    // Handle the submitted update form
    $updatedProjectId = $_POST['project_id'];
    $updatedProjectName = $_POST['updated_project_name'];
    $updatedDescription = $_POST['updated_description'];

    // Use the updateProjectDetails method from the ProjectController instance
    $updateProject->updateProjectDetails($updatedProjectId, $updatedProjectName, $updatedDescription);

    // Redirect to dashboard or any other page after successful update
    header('location: dashboard.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="./css/style.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
	<title>Gestion des tâches</title>
</head>

<body>
    <header class="masthead">

        <div class="user-settings">

            <a href="logout.php">
                <button class="user-settings-btn btn" aria-label="Create">

                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z" />
                        <path fill-rule="evenodd"
                            d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z" />
                    </svg>
                </button>
            </a>
            <a href="dashboard.php">
                <button class="user-settings-btn btn" aria-label="Create">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house"
                        viewBox="0 0 16 16">
                        <path
                            d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z" />
                    </svg>
                </button>
            </a>
        </div>

        <div class="logo">

            <h1><i class="fab fa-trello logo-icon" aria-hidden="true"></i>Gestion-des-projet</h1>

        </div>
        <div class="input-group">
            <div class="form-outline">


            </div>
            <form method="post" action="search.php" id="form">
                <input type="search" name="word" id="form1" class="form-control" placeholder="Search" />
                <button name="search" type="button" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search"
                        viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                    </svg>
                </button>
            </form>
        </div>
    </header>
    <div class="center">

    <form action="" method="post" class="form-group">
        <label for="updated_project_name">Updated Project Name:</label><br>
        <input class="form-control" type="text" name="updated_project_name" value="<?php echo isset($projectDetails['project_name']) ? $projectDetails['project_name'] : ''; ?>"><br>

        <label for="updated_description">Updated Description:</label><br>
        <textarea class="form-control" name="updated_description"><?php echo isset($projectDetails['description']) ? $projectDetails['description'] : ''; ?></textarea><br>

        <input class="form-control" type="hidden" name="project_id" value="<?php echo $projectIdToUpdate; ?>">
        <button type="submit" name="submit_update" class="btn btn-primary">Submit Update</button>
    </form>
</div>
    <style>
		#form {
			display: flex;
		}

		.scroll {
			overflow: scroll;
		}


		label {
			color: black;
		}

		input#task {
			border: 1px black;
		}
        .center{
            display: flex;
            flex-direction: column;
            justify-content:space-between;
            align-items: center;
        }

		.board-info-bar {
			display: grid;
			grid-template-columns: auto auto;
			grid-column-gap: 2rem;
			align-items: center;
			padding: 0 0.8rem;
			color: #fff;
			margin-top: 2%;
			margin-bottom: 5%;
		}
		.sep-page{
			display: grid;
			grid-template-columns: auto auto;
		}
		.selected-project-details{
			padding-top: 10%;
			margin: 5%;
		}
		.sidebar{
			height: 100%;
			width: 200px;
			background-color: #0067a3;
			color: #fff;
		}
        .form-group{
			background-color:dodgerblue;
			padding: 50px;
			width: 500px;
            height: 300px;
		}
		body{
			grid-row-gap: 0;
		}
		.board-info-bar .btn{
			background-color: #fff;
			color: #0d6efd;
		}
		.board-info-bar .btn:hover{
			color: #eee;
		}
		
		.select-project{
			width: 30%;
			height: 25px;
			border-radius: 5px;
			border-color: #0d6efd;
			background-color:ghostwhite;
			cursor: pointer;
			margin-right: 10%;
		}
		.select-project:hover{
			background-color:#4c94be;
		}
		.select-project option{
			background-color: cornflowerblue;
			font-size: 15px;
			padding: 5%;
			color: #eee;
		}
		.select-project option:hover{
			color: #4c94be;
			background-color: #eee;
		}
		
		.deleteProject{
			width: 100px;
			margin-left: 4%;
			margin-top: 3%;
		}
		.masthead {
			display: grid;
			grid-template-columns: 2fr 3fr 2fr;
			grid-column-gap: 2rem;
		}
		.lists-container{
			width: auto;
		}

	</style>
</body>

</html>
