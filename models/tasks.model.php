<?php
require_once '../database/db_connection.php';


class task
{

    static public function addtask($data)
    {
        $db = Database::connect()->prepare("INSERT INTO task( task_descr, task_end , statut,user_id , project_id )
                                        VALUES( :task_descr, :task_end , :statut ,:user , :project_id )");
        $db->bindParam(':task_descr', $data['task_descr']);
        $db->bindParam(':user', $_SESSION['id']);
        $db->bindParam(':task_end', $data['task_end']);
        $db->bindParam(':statut', $data['statut']);
        $db->bindParam(':project_id', $data['project_id']);
        $db->execute();
    }

    static public function addtodotask($data)
    {
        $db = Database::connect()->prepare("INSERT INTO task (task_descr, task_end, statut, user_id, project_id)
                                            VALUES (:task_descr, :task_end, 'todo', :user, :project_id)");
        $db->bindParam(':task_descr', $data['task_descr']);
        $db->bindParam(':user', $_SESSION['id']);
        $db->bindParam(':task_end', $data['task_end']);
        $db->bindParam(':project_id', $_SESSION['project_id']);
        $db->execute();
    }

    static public function adddoingtask($data)
    {
        $db = Database::connect()->prepare("INSERT INTO task (task_descr, task_end, statut, user_id, project_id)
                                            VALUES (:task_descr, :task_end, 'doing', :user, :project_id)");

        $db->bindParam(':task_descr', $data['task_descr']);
        $db->bindParam(':task_end', $data['task_end']);
        $db->bindParam(':user', $_SESSION['id']);
        $db->bindParam(':project_id', $_SESSION['project_id']);
        $db->execute();
    }

    static public function adddonetask($data)
    {
        $db = Database::connect()->prepare("INSERT INTO task (task_descr, task_end, statut, user_id, project_id)
                                            VALUES (:task_descr, :task_end, 'done', :user, :project_id)");

        $db->bindParam(':task_descr', $data['task_descr']);
        $db->bindParam(':task_end', $data['task_end']);
        $db->bindParam(':user', $_SESSION['id']);
        $db->bindParam(':project_id', $_SESSION['project_id']);
        $db->execute();
    }

    static public function gettasks()
    {
        $db = Database::connect()->prepare("SELECT * FROM task where statut='todo' and user_id=:user ORDER BY task_end DESC");
        $db->bindParam(':user', $_SESSION['id']);
        $db->execute();
        $tasks = $db->fetchAll();
        $db = NULL;

        return $tasks;
    }
    static public function gettodotasks()
    {
        $db = Database::connect()->prepare("SELECT * FROM task where statut='todo' and user_id=:user ORDER BY task_end DESC");
        $db->bindParam(':user', $_SESSION['id']);
        $db->execute();
        $todotasks = $db->fetchAll();
        $db = NULL;

        return $todotasks;
    }
    static public function getdoingtasks()
    {
        $db = Database::connect()->prepare("SELECT * FROM task where statut='doing' and user_id=:user ORDER BY task_end DESC");
        $db->bindParam(':user', $_SESSION['id']);
        $db->execute();
        $doingtasks = $db->fetchAll();
        $db = NULL;

        return $doingtasks;
    }
    static public function getdonetasks()
    {
        $db = Database::connect()->prepare("SELECT * FROM task where statut='done' and user_id=:user ORDER BY task_end DESC");
        $db->bindParam(':user', $_SESSION['id']);
        $db->execute();
        $donetasks = $db->fetchAll();
        $db = NULL;

        return $donetasks;
    }
    static public function getOnetask($task_id)
    {
        $db = Database::connect()->prepare("SELECT * FROM task WHERE task_id = :task_id ");

        $db->bindParam(':task_id', $task_id['task_id']);

        $db->execute();
        $tasks = $db->fetchAll();
        $db = NULL;


        return $tasks;
    }

    static public function getsearch($search)
    {

        $db = Database::connect()->prepare("SELECT * FROM `task` WHERE task_descr like :task_descr or task_descr like :task_descr1 or task_descr like :task_descr2 and user_id=:user ;");

        $db->bindParam(':task_descr', $search['task_descr']);
        $db->bindParam(':task_descr1', $search['task_descr1']);
        $db->bindParam(':task_descr2', $search['task_descr2']);
        $db->bindParam(':user', $_SESSION['id']);
        $db->execute();
        $tasks = $db->fetchAll();
        $db = NULL;

        return $tasks;
    }
    static public function update_task($data_update)
    {
        $db = Database::connect()->prepare("UPDATE task SET task_descr = :task_descr, task_end = :task_end , statut = :statut
                                            WHERE task_id = :task_id");

        $db->bindParam(':task_id', $data_update['task_id']);
        $db->bindParam(':task_descr', $data_update['task_descr']);
        $db->bindParam(':task_end', $data_update['task_end']);
        $db->bindParam(':statut', $data_update['statut']);

        $db->execute();
    }

    static public function delete_task($data)
    {
        $db = Database::connect()->prepare("DELETE FROM task WHERE task_id = :task_id and user_id=:user");
        $db->bindParam(':task_id', $data['task_id']);
        $db->bindParam(':user', $_SESSION['id']);
        $db->execute();
    }
    static public function search_task($data_search)
    {
        $db = Database::connect()->prepare("SELECT * FROM `task` WHERE task_descr like '%['task_descr']%' or task_descr like '%['task_descr']'or task_descr like '['task_descr']%'; and ,user_id=:user ");
        $db->bindParam(':task_descr', $data_search['task_descr']);
        $db->bindParam(':user', $_SESSION['id']);
        $db->execute();
    }


}