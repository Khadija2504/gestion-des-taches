<?php
session_start();
require '../controller/projects.Controller.php';

if (!isset($_SESSION['id'])) {
    header('location:login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addproject'])) {
    $userId = $_SESSION['id'];
    $projectName = $_POST['project_name'];
    // Call the addProject function
    if (ProjectController::addProject($userId, $projectName)) {
        // Project added successfully, you can redirect or perform any other action
        header('location: dashboard.php'); // Redirect to the projects page or any other page
        exit;
    } else {
        // Handle the case where the project couldn't be added
        header('location: dashboard.php');
    }
}
?>

<!-- Your HTML code for the add project page -->
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
<section class="add-project-form">
       <h2>Add Project</h2>
       <form method="post" action="">
           <div class="mb-3">
               <label for="project_name" class="form-label">Project Name</label>
               <input type="text" name="project_name" class="form-control" required>
           </div>
           <button type="submit" name="addproject" class="btn btn-primary">Add Project</button>
       </form>
   </section>
</body>
</html>
