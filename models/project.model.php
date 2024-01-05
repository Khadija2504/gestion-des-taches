<?php
require_once '../database/db_connection.php';

class Project
{
    static public function updateProjectDetails($data_update)
    {
        $db = Database::connect()->prepare("UPDATE project SET project_name = :project_name, description = :description WHERE project_id = :id");

        $db->bindParam(':project_name', $data_update['project_name']);
        $db->bindParam(':description', $data_update['description']);
        $db->bindParam(':id', $data_update['project_id']);

        $db->execute();
    }

    static public function getProjectDetails($projectId)
    {
        $db = Database::connect()->prepare("SELECT * FROM project WHERE project_id = :projectId");
        $db->bindParam(':projectId', $projectId);
        $db->execute();
        $project = $db->fetch(PDO::FETCH_ASSOC);
        $db = NULL;

        return $project;
    }
}
?>
