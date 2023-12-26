<?php
// Inside 'projects.Controller.php'
require_once '../database/db_connection.php';

class ProjectController
{

    public static function addProject($userId, $projectName)
    {
        $db = Database::connect()->prepare("INSERT INTO project (user_id, project_name) VALUES (:user, :name)");
        $db->bindParam(':user', $userId);
        $db->bindParam(':name', $projectName);
        $db->execute();
        // No need to fetch results after an INSERT operation
    }

    public static function getUserProjects($userId)
    {
        $db = Database::connect()->prepare("SELECT * FROM projects WHERE user_id = :user");
        $db->bindParam(':user', $userId);
        $db->execute();
        return $db->fetchAll();
    }

    public static function getProjectById($projectId)
    {
        $db = Database::connect()->prepare("SELECT * FROM projects WHERE project_id = :projectId");
        $db->bindParam(':projectId', $projectId);
        $db->execute();
        return $db->fetch();
    }

    public static function updateProject($projectId, $projectName, $projectDescription)
    {
        $db = Database::connect()->prepare("UPDATE projects SET project_name = :name, project_description = :description WHERE project_id = :projectId");
        $db->bindParam(':name', $projectName);
        $db->bindParam(':description', $projectDescription);
        $db->bindParam(':projectId', $projectId);

        return $db->execute();
    }

    public static function deleteProject($projectId)
    {
        $db = Database::connect()->prepare("DELETE FROM projects WHERE project_id = :projectId");
        $db->bindParam(':projectId', $projectId);
        return $db->execute();
    }
}
