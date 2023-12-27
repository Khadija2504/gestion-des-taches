<!DOCTYPE html>
<html lang="en">
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

		<form class="form" method="post" action="">
    <select class="select-project" name="selected_project" onchange="this.form.submit()">
        <option value="">Select a project</option>
        <?php foreach ($userProjects as $project): ?>
            <option value="<?php echo $project['project_id']; ?>"><?php echo $project['project_name']; ?></option>
        <?php endforeach; ?>
    </select>
</form>

<!-- Delete Project form -->
<form method="post" action="">
    <input type="hidden" name="selected_project" value="<?php echo $selectedProjectId; ?>">
    <button type="submit" name="delete_project" class="btn deleteProject btn-danger">Delete Project</button>
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

    
</body>
</html>