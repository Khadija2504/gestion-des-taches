<?php
session_start();
require '../controller/tasks.Controller.php';

if (!isset($_SESSION['id'])) {
	header('location:login.php');
}

if (isset($_POST['addtodo'])) {
    $task_descr = $_POST['task_descr'];
    $task_end = $_POST['task_end'];
    $project_id = $_POST['project_id'];
    // Create an instance of ADD_task
    $addTaskInstance = new ADD_task();
	if (!taskExists($_SESSION['id'], $project_id, $task_descr)) {
        $addTaskInstance->add_todotask($task_descr, $task_end, $project_id);
    } else{
    // Call the instance method to add the task with the correct project_id
    $addTaskInstance->add_todotask($task_descr, $task_end, $project_id);
	}
}

function taskExists($userId, $projectId, $taskDescription)
{
    $db = Database::connect()->prepare("SELECT COUNT(*) FROM task WHERE user_id=:user AND project_id=:project_id AND task_descr=:task_descr");
    $db->bindParam(':user', $userId);
    $db->bindParam(':project_id', $projectId);
    $db->bindParam(':task_descr', $taskDescription);
    $db->execute();

    return $db->fetchColumn() > 0;
}

if (isset($_POST['adddoing'])) {
    $task_descr = $_POST['task_descr'];
    $task_end = $_POST['task_end'];
    $project_id = $_POST['project_id'];

    // Create an instance of ADD_task
    $addTaskInstance = new ADD_task();

    // Call the instance method to add the task with the correct project_id
    $addTaskInstance->add_doingtask($task_descr, $task_end, $project_id);
}

if (isset($_POST['adddone'])) {
    $task_descr = $_POST['task_descr'];
    $task_end = $_POST['task_end'];
    $project_id = $_POST['project_id'];

    // Create an instance of ADD_task
    $addTaskInstance = new ADD_task();

    // Call the instance method to add the task with the correct project_id
    $addTaskInstance->add_donetask();
}
function getTodoTasksForProject($userId, $projectId)
{
    $db = Database::connect()->prepare("SELECT * FROM task WHERE statut='todo' AND user_id=:user AND project_id=:project_id ORDER BY task_end DESC");
    $db->bindParam(':user', $userId);
    $db->bindParam(':project_id', $projectId);
    $db->execute();
    return $db->fetchAll();
}

function getDoingTasksForProject($userId, $projectId)
{
    $db = Database::connect()->prepare("SELECT * FROM task WHERE statut='doing' AND user_id=:user AND project_id=:project_id ORDER BY task_end DESC");
    $db->bindParam(':user', $userId);
    $db->bindParam(':project_id', $projectId);
    $db->execute();
    return $db->fetchAll();
}

function getDoneTasksForProject($userId, $projectId)
{
    $db = Database::connect()->prepare("SELECT * FROM task WHERE statut='done' AND user_id=:user AND project_id=:project_id ORDER BY task_end DESC");
    $db->bindParam(':user', $userId);
    $db->bindParam(':project_id', $projectId);
    $db->execute();
    return $db->fetchAll();
}

$selectedProjectId = isset($_POST['selected_project']) ? $_POST['selected_project'] : (isset($_SESSION['project_id']) ? $_SESSION['project_id'] : null);

// Get tasks based on selected project
$todotasks = getTodoTasksForProject($_SESSION['id'], $selectedProjectId);
$doingtasks = getDoingTasksForProject($_SESSION['id'], $selectedProjectId);
$donetasks = getDoneTasksForProject($_SESSION['id'], $selectedProjectId);

// Store the selected project in the session
if (!empty($selectedProjectId)) {
    $_SESSION['project_id'] = $selectedProjectId;
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

		</div>
		<!-- </div> -->
		<div class="logo">

			<h1><i class="fab fa-trello logo-icon" aria-hidden="true"></i>Gestion-des-projet</h1>

		</div>

		<form  method="post" action="">
				<select name="selected_project" onchange="this.form.submit()">
					<option value="">Select a project</option>
					<?php foreach ($userProjects as $project): ?>
						<option value="<?php echo $project['project_id']; ?>"><?php echo $project['project_name']; ?></option>
					<?php endforeach; ?>
				</select>
			</form>
			
		
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
	</header>
	
	<section class="board-info-bar">
		<form method="post" >

			<div class="board-controls">

				<!-- Button trigger modal -->
				<a href="addtask.php">
					<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
						+ Add Multiple
					</button>
				</a>
			</div>
		</form>
	</section>

	<section class="board-info-bar">
		<form method="post">
			<div class="board-controls">
				<a href="addproject.php"> <!-- Add this line for the new button -->
					<button type="button" class="btn btn-primary">
						+ Add Project
					</button>
				</a>
				<!-- Your existing code for other controls -->
			</div>
		</form>

	</section>

	
	<!-- Lists container -->
	<section class="lists-container d-flex flex-row ">
		<?php    if (isset($_POST['selected_project'])) {
		$_SESSION['project_id'] = $_POST['selected_project'];
		$selectedProjectId = $_SESSION['project_id'];
        $projectTasks = getProjectTasks($selectedProjectId);
?>

		<div class="list">
			<?php $j = 0;
			foreach ($todotasks as $todo): {
					$j++;
				}
			endforeach; ?>
			<h3 class="list-title"> A Faire (
				<?php echo $j; ?>)
			</h3>
			<div class="scroll">
				<?php
				foreach ($todotasks as $todo) {
					?>
					<input type="hidden" name="task_id" value="<?php echo $todo['task_id'] ?>">

					<ul class="list-items">

						<li>
							<?php echo $todo['task_descr'] ?>
						</li>
						<li>
							<?php echo $todo['task_end'] ?>
						</li>
						<div class="">
							<form action="" method="post">
								<a style="color:powderblue;" href="updatetask.php?id=<?php echo $todo['task_id'] ?>">
									<button type="button" class="btn btn-primary ">update</button></a>

								<input type="hidden" name="task_id" value="<?php echo $todo['task_id'] ?>">
								<button type="submit" name="delete" class="btn btn-danger">delete</button>
							</form>
						</div>

					</ul>
					<?php
				}
				?>
			</div>
			<form action="" method="post" id="todoform" class="d-none">
				<input type="hidden" name="project_id" value="<?php echo $selectedProjectId; ?>">

				<br><input type="text" name="task_descr" placeholder="enter description" class="form-control">
				<br><input type="date" name="task_end" class="form-control">
				<br><button name="addtodo" class="btn btn-primary">Add Task</button>
				<button type="button" class="btn btn-danger" onclick="addTasktodo()">Cancel</button>
			</form>

			<button type="button" id="todobtn" class="add_field_button btn btn-primary btn-md" onclick="addTasktodo() "><span
					class="glyphicon glyphicon-plus" aria-hidden="true">+ Add New</span></button>
		</div>


		<div class="list">
			<?php $j = 0;
			foreach ($doingtasks as $doing): {
					$j++;
				}
			endforeach; ?>
			<h3 class="list-title">En Cours(
				<?php echo $j; ?>)
			</h3>
			<div class="scroll">
				<?php
				foreach ($doingtasks as $doing) {
					?>

					<input type="hidden" name="task_id" value="<?php echo $doing['task_id'] ?>">

					<ul class="list-items" id="item">
						<li>
							<?php echo $doing['task_descr'] ?>
						</li>
						<li>
							<?php echo $doing['task_end'] ?>
						</li>
						<div class="">
							<form action="" method="post">
								<a style="color:powderblue;" href="updatetask.php?id=<?php echo $doing['task_id'] ?>">
									<button type="button" class="btn btn-primary ">update</button></a>

								<input type="hidden" name="task_id" value="<?php echo $doing['task_id'] ?>">
								<button type="submit" name="delete" class="btn btn-danger">delete</button>
							</form>
						</div>

					</ul>
					<?php
				}
				?>
			</div>
			<form action="" method="post" id="doingform" class="d-none">
				<input type="hidden" name="project_id" value="<?php echo $selectedProjectId; ?>">

				<br><input type="text" name="task_descr" placeholder="enter description" class="form-control">
				<br><input type="date" name="task_end" class="form-control">
				<br><button name="adddoing" class="btn btn-primary">Add Task</button>
				<button type="button" class="btn btn-danger" onclick="addTaskdoing()">Cancel</button>
			</form>

			<button type="button" id="doingbtn" class="add_field_button btn btn-primary btn-md" onclick="addTaskdoing() "><span
					class="glyphicon glyphicon-plus" aria-hidden="true">+ Add New</span></button>
		</div>

		<div class="list">
			<?php $j = 0;
			foreach ($donetasks as $done): {
					$j++;
				}
			endforeach; ?>
			<h3 class="list-title">Terminé (
				<?php echo $j; ?>)
			</h3>
			<div class="scroll">
				<?php
				foreach ($donetasks as $done) {
					?>

					<ul class="list-items" id="itemc">
						<li>
							<?php echo $done['task_descr'] ?>
						</li>
						<li>
							<?php echo $done['task_end'] ?>
						</li>
						<div class="">
							<form action="" method="post">
								<a style="color:powderblue;" href="updatetask.php?id=<?php echo $done['task_id'] ?>">
									<button type="button" class="btn btn-primary ">update</button></a>

								<input type="hidden" name="task_id" value="<?php echo $done['task_id'] ?>">
								<button type="submit" name="delete" class="btn btn-danger">delete</button>
							</form>
						</div>
					</ul>
					<?php
				}
				?>
			</div>

			<form action="" method="post" id="doneform" class="d-none">
				<input type="hidden" name="project_id" value="<?php echo $selectedProjectId; ?>">

				<br><input type="text" name="task_descr" placeholder="enter description" class="form-control">
				<br><input type="date" name="task_end" class="form-control">
				<br><button name="adddone" class="btn btn-primary">Add Task</button>
				<button type="button" class="btn btn-danger" onclick="addTaskdone()">Cancel</button>
			</form>

			<button type="button" id="donebtn" class="add_field_button btn btn-primary btn-md" onclick="addTaskdone() "><span
					class="glyphicon glyphicon-plus" aria-hidden="true">+ Add New</span></button>
		</div>
		</div>
		<?php
		}
		?>
	</section>
	
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
			grid-template-columns: auto;
			grid-column-gap: 2rem;
			align-items: center;
			padding: 0 0.8rem;
			color: #fff;
		}
	</style>
	<script>

		function addTasktodo() {
			var btn = document.getElementById("todobtn");
			var form = document.getElementById("todoform");

			btn.classList.toggle("d-none");
			form.classList.toggle("d-none");

		}
		function addTaskdoing() {
			var btn1 = document.getElementById("doingbtn");
			var form1 = document.getElementById("doingform");

			btn1.classList.toggle("d-none");
			form1.classList.toggle("d-none");

		}
		function addTaskdone() {
			var btn2 = document.getElementById("donebtn");
			var form2 = document.getElementById("doneform");

			btn2.classList.toggle("d-none");
			form2.classList.toggle("d-none");

		}

		function addTaskb() {
			var list = document.querySelector('#item');
			var newTaskb = document.createElement('li');
			newTaskb.innerHTML = "<input>";
			list.appendChild(newTaskb);
		}
		function addTaskc() {
			var list = document.querySelector('#itemc');
			var newTaskc = document.createElement('li');
			newTaskc.innerHTML = "<input>";
			list.appendChild(newTaskc);
		}

	</script>

	<script src="../view/js/script.js"></script>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
		crossorigin="anonymous"></script>
</body>

</html>