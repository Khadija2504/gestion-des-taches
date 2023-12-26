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

        task::addtodotask($data);

        $result = task::addtodotask($data);
    }
    public function add_doingtask()
    {

        $data = array(
            'task_descr' => $_POST['task_descr'],
            'task_end' => $_POST['task_end'],

        );

        $result = task::adddoingtask($data);

    }
    public function add_donetask()
    {


        $data = array(
            'task_descr' => $_POST['task_descr'],
            'task_end' => $_POST['task_end'],

        );

        $result = task::adddonetask($data);

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