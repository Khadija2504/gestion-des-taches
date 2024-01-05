<?php
// Include necessary files and start the session
session_start();
require '../controller/projects.controller.php';
require '../controller/tasks.Controller.php';

// Check if a project is selected
if (!isset($_POST['project_id'])) {
    // Redirect to the main page or handle accordingly
    header('location: dashboard.php');
    exit();
}
$statics = new ProjectController();

$selectedProjectId = $_POST['project_id'];
$selectedProjectDetails = $statics->getProjectDetails($selectedProjectId);
// Check if the project details are fetched successfully
if (!$selectedProjectDetails) {
    // Handle the case where project details are not found
    echo "Project details not found.";
    exit();
}

$addTaskInstance = new ADD_task();

// Extract project details
$projectName = $selectedProjectDetails['project_name'];
$projectDescription = $selectedProjectDetails['description'];

// Your code to fetch and display project statistics goes here
$taskCount = $statics->getTaskCountForProject($selectedProjectId);
$completedTaskCount = $statics->getCompletedTaskCountForProject($selectedProjectId);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle 'addtodo' form submission
    if (isset($_POST['addtodo'])) {
        $task_descr = $_POST['task_descr'];
        $task_end = $_POST['task_end'];
        $project_id = $_POST['project_id'];
        
        // Check if the task already exists
        if (!$addTaskInstance->taskExists($_SESSION['id'], $project_id, $task_descr)) {
            // Create an instance of ADD_task
            $addTaskInstance = new ADD_task();
            $addTaskInstance->add_todotask($task_descr, $task_end, $project_id);

            // Redirect to prevent form resubmission
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            // Task already exists, handle accordingly
            // For example, redirecting to the same page with an error message
            $_SESSION['error_message'] = 'Task already exists.';
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}

$selectedProjectId = isset($_POST['selected_project']) ? $_POST['selected_project'] : (isset($_SESSION['project_id']) ? $_SESSION['project_id'] : null);

// Get tasks based on selected project
$todotasks = $addTaskInstance->getTodoTasksForProject($_SESSION['id'], $selectedProjectId);
$doingtasks = $addTaskInstance->getDoingTasksForProject($_SESSION['id'], $selectedProjectId);
$donetasks = $addTaskInstance->getDoneTasksForProject($_SESSION['id'], $selectedProjectId);

// Store the selected project in the session
if (!empty($selectedProjectId)) {
    $_SESSION['project_id'] = $selectedProjectId;
}
if (!empty($selectedProjectId)) {
    $selectedProjectDetails = $statics->getProjectDetails($selectedProjectId);

    // Check if the project details are fetched successfully
    if ($selectedProjectDetails) {
        $projectName = $selectedProjectDetails['project_name'];
        $projectDescription = $selectedProjectDetails['description'];
    } else {
        // Handle the case where project details are not found
        $projectName = "Project Not Found";
        $projectDescription = "Project details could not be retrieved.";
    }
}

if (isset($_POST['delete_project']) && !empty($selectedProjectId)) {
    $projectIdToDelete = $selectedProjectId;

    // Create an instance of ADD_task
    $addTaskInstance = new ADD_task();

    // Call the instance method to delete the project and its tasks
    $addTaskInstance->deleteProject($projectIdToDelete);

    // Redirect to the same page or any other page as needed
    header("Location: " . $_SERVER['PHP_SELF']);
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
	
	<!-- Masthead -->
		
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

		<form method="post" action="">
			<select class="select-project" name="selected_project" onchange="this.form.submit()">
				<option value="">Select a project</option>
				<?php foreach ($userProjects as $project): ?>
					<option value="<?php echo $project['project_id']; ?>"><?php echo $project['project_name']; ?></option>
				<?php endforeach; ?>
			</select>
		</form>

		<form class="search" method="post" action="search.php" id="form">
			<input type="search" name="word" id="form1" class="form-control" placeholder="Search" />
			<button name="search" type="button" class="btn btn-primary" style="background-color: #4c94be">
				<i class="fas fa-search"></i>
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search"
					viewBox="0 0 16 16">
					<path
						d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
				</svg>
			</button>
		</form>
		
	</header>
	<div class="sep-page">
		<section class="sidebar">
			<div class="forms">
                <!-- Delete Project form -->
                <form class="form" method="post" action="">
                    <input type="hidden" name="selected_project" value="<?php echo $selectedProjectId; ?>">
                    <button type="submit" name="delete_project" class="btns btn deleteProject btn-danger">Delete Project</button>
                </form>

                <form action="updateproject.php" method="post">
                    <input type="hidden" name="project_id" value="<?php echo $selectedProjectId; ?>">
                    <button type="submit" name="update_project" class="btns btn btn-primary">Update Project</button>
                </form>

                <form action="projectstats.php" method="post">
                    <input type="hidden" name="project_id" value="<?php echo $selectedProjectId; ?>">
                    <button type="submit" class="btns btn btn-info">Project Statistics</button>
                </form>
            </div>
		
			<?php if (!empty($selectedProjectId)): ?>
				<div class="selected-project-details">
					<h2>Selected Project Details</h2>
					<p><strong>Project Name:</strong> <?php echo $projectName; ?></p>
					<p><strong>Project Description:</strong> <?php echo $projectDescription; ?></p>
				</div>
			<?php endif; ?>
			<?php
			// Function to get details of a specific project
			function getProjectDetails($projectId)
			{
				$db = Database::connect()->prepare("SELECT * FROM project WHERE project_id = :projectId");
				$db->bindParam(':projectId', $projectId);
				$db->execute();

				return $db->fetch(PDO::FETCH_ASSOC);
			}
			?>
		</section>

		<!-- Lists container -->
		<section class="lists-container d-flex flex-row ">
            <section class="board-info-bar">
                <!-- Display project details -->
                <div>
                    <h2>Project Statistics for <?php echo $projectName; ?></h2><br>
                    <p class="p"><strong>Project Description:</strong> <?php echo $projectDescription; ?></p><br>
                </div>
                <!-- Basic statistics -->
                <div>
                    <p class="p">Total Tasks: <?php echo $taskCount; ?></p>
                    <p class="p">Completed Tasks: <?php echo $completedTaskCount; ?></p>
                    <p class="p">Remaining Tasks: <?php echo $taskCount - $completedTaskCount; ?></p>
                </div>
            </section>
            <div>
                <canvas id="taskChart" width="400" height="200"></canvas>
            </div>
		</section>
	</div>
	
	<?php
// Fonction pour obtenir les tâches d'un projet spécifique
function getProjectTasks($projectId)
{
    $statement = Database::connect()->prepare("SELECT * FROM task WHERE project_id = :projectId");
    $statement->bindParam(':projectId', $projectId);
    $statement->execute();

    return $statement->fetchAll();
}
?>
		
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

		.board-info-bar {
			display: grid;
			grid-template-rows: auto auto;
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
        .forms{
			display: flex;
			flex-direction: column;
			margin: 20px;
			justify-content: space-between;
		}
		.forms form{
			margin-bottom: 10px;
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
        h2 {
            color: #007bff;
        }

        .p {
            margin-bottom: 10px;
            font-size: larger;
            color: black;
        }

        .div {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .lists-container{
            width: auto;
        }
        .btns{
            background-color: #0067a3;
            color: #eee;
        }

	</style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
        // Get the canvas element
        var ctx = document.getElementById('taskChart').getContext('2d');

        // Create a bar chart
        var taskChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Tasks', 'Completed Tasks', 'Remaining Tasks'],
                datasets: [{
                    label: 'Task Statistics',
                    data: [<?php echo $taskCount; ?>, <?php echo $completedTaskCount; ?>, <?php echo $taskCount - $completedTaskCount; ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 205, 86, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 205, 86, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
    </script>

</body>

</html>