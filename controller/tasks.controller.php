<?php

require_once '../models/tasks.model.php';


class ADD_task
{

    public function addtask()
    {
        $nbr = $_POST['task-count'];

        for ($i = 0; $i < $nbr; $i++) {

            ${'data' . $i} = array(
                "task_descr" => htmlspecialchars($_POST['task_descr' . $i]),
                "task_end" => htmlspecialchars($_POST['task_end' . $i]),
                "statut" => htmlspecialchars($_POST['statut' . $i]),
                "project_id" => htmlspecialchars($_POST['project_id' . $i]),
            );
            
            task::addtask(${'data' . $i});

        }
        header('location:dashboard.php');
    }

    public function add_todotask()
{
    $data = array(
        'task_descr' => $_POST['task_descr'],
        'task_end' => $_POST['task_end'],
    );

    $result = task::addtodotask($data);

    // Check the result if needed
    if ($result) {
        // Task added successfully
        header('location:dashboard.php');
    } else {
        // Handle the case where the task addition failed
        // You might want to show an error message or redirect to an error page
        echo "Task addition failed.";
    }
}
public function taskExists($userId, $projectId, $taskDescription)
{
    $db = Database::connect()->prepare("SELECT COUNT(*) FROM task WHERE user_id=:user AND project_id=:project_id AND task_descr=:task_descr");
    $db->bindParam(':user', $userId);
    $db->bindParam(':project_id', $projectId);
    $db->bindParam(':task_descr', $taskDescription);
    $db->execute();

    return $db->fetchColumn() > 0;
}
public function add_doingtask()
{
    $data = array(
        'task_descr' => $_POST['task_descr'],
        'task_end' => $_POST['task_end'],
    );

    $result = task::adddoingtask($data);

    // Check the result if needed
    if ($result) {
        // Task added successfully
        header('location:dashboard.php');
    } else {
        // Handle the case where the task addition failed
        // You might want to show an error message or redirect to an error page
        echo "Task addition failed.";
    }
}

public function add_donetask()
{
    $data = array(
        'task_descr' => $_POST['task_descr'],
        'task_end' => $_POST['task_end'],
    );

    $result = task::adddonetask($data);

    // Check the result if needed
    if ($result) {
        // Task added successfully
        header('location:dashboard.php');
    } else {
        // Handle the case where the task addition failed
        // You might want to show an error message or redirect to an error page
        echo "Task addition failed.";
    }
}


    
    // récupère les informations d'une tâche spécifique à partir de la base de données.

    public function gettasks()
    {
        return task::gettasks();
        // header('location:tours.php');
    }
    public function gettodotasks()
    {
        return task::gettodotasks();
        // header('location:tours.php');
    }
    public function getdoingtasks()
    {
        return task::getdoingtasks();
        // header('location:tours.php');
    }
    public function getdonetasks()
    {
        return task::getdonetasks();
    }
    public function gettask($task_id)
    {

        return task::getOnetask($task_id);

    }

    public function getTodoTasksForProject($userId, $projectId)
    {
        $db = Database::connect()->prepare("SELECT * FROM task WHERE statut='todo' AND user_id=:user AND project_id=:project_id ORDER BY task_end DESC");
        $db->bindParam(':user', $userId);
        $db->bindParam(':project_id', $projectId);
        $db->execute();
        return $db->fetchAll();
    }

    public function getDoingTasksForProject($userId, $projectId)
    {
        $db = Database::connect()->prepare("SELECT * FROM task WHERE statut='doing' AND user_id=:user AND project_id=:project_id ORDER BY task_end DESC");
        $db->bindParam(':user', $userId);
        $db->bindParam(':project_id', $projectId);
        $db->execute();
        return $db->fetchAll();
    }

    public function getDoneTasksForProject($userId, $projectId)
    {
        $db = Database::connect()->prepare("SELECT * FROM task WHERE statut='done' AND user_id=:user AND project_id=:project_id ORDER BY task_end DESC");
        $db->bindParam(':user', $userId);
        $db->bindParam(':project_id', $projectId);
        $db->execute();
        return $db->fetchAll();
    }

    public function delete_task()
    {
        $task_id = array(
            'task_id' => $_POST['task_id']
        );
        task::delete_task($task_id);
        header('location:dashboard.php');
    }
    public function update_task()
    {
        $data_update = array(

            'task_id' => $_POST['task_id'],
            'task_descr' => $_POST['task_descr'],
            'task_end' => $_POST['task_end'],
            'statut' => $_POST['statut'],
            "project_id" => $_POST['project_id'],
        );
        $result = task::update_task($data_update);

        return $result;
    }
    public function search()
    {
        $search = array(
            'task_descr' => '%' . $_POST['word'],
            'task_descr1' => $_POST['word'] . '%',
            'task_descr2' => '%' . $_POST['word'] . '%',

        );

        $result = task::getsearch($search);
        return $result;

    }

    public function getUserProjects($userId)
{
    $statement = Database::connect()->prepare("SELECT * FROM project WHERE user_id = :userId");
    $statement->bindParam(':userId', $userId); // Use the parameter passed to the method
    $statement->execute();

    $projects = $statement->fetchAll();

    return $projects;
}

public function deleteProject($projectId) {
    // Delete tasks associated with the project
    $this->deleteTasksForProject($projectId);

    // Delete the project
    $db = Database::connect()->prepare("DELETE FROM project WHERE project_id = :projectId");
    $db->bindParam(':projectId', $projectId);
    $db->execute();
}

private function deleteTasksForProject($projectId) {
    // Delete tasks associated with the project
    $db = Database::connect()->prepare("DELETE FROM task WHERE project_id = :projectId");
    $db->bindParam(':projectId', $projectId);
    $db->execute();
}

}

$data = new ADD_task();
$tasks = $data->gettasks();
$data = new ADD_task();
$todotasks = $data->gettodotasks();
$data = new ADD_task();
$doingtasks = $data->getdoingtasks();
$data = new ADD_task();
$donetasks = $data->getdonetasks();
$data = new ADD_task();
$userProjects = $data->getUserProjects($_SESSION['id']);


if (isset($_POST['addtodo'])) {
    $task = new ADD_task();
    $task->add_todotask();
    header('location:dashboard.php');

}

if (isset($_POST['adddoing'])) {
    $task = new ADD_task();
    $task->add_doingtask();
    header('location:dashboard.php');

}
if (isset($_POST['adddone'])) {
    $task = new ADD_task();
    $task->add_donetask();
    header('location:dashboard.php');


}
if (isset($_POST['delete'])) {
    $delete = new ADD_task();
    $delete->delete_task();
    header('location:dashboard.php');

}

if (isset($_POST['update_task'])) {
    $update = new ADD_task();
    $update->update_task();
    header('location:updatetask.php');
}