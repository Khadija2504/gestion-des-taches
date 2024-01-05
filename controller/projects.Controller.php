<?php
require_once '../models/project.model.php';

class ProjectController
{
    public static function addProject($userId, $projectName, $description)
    {
        $db = Database::connect()->prepare("INSERT INTO project (user_id, project_name, description) VALUES (:user, :name, :description)");
        $db->bindParam(':user', $userId);
        $db->bindParam(':name', $projectName);
        $db->bindParam(':description', $description);
        $db->execute();
        // No need to fetch results after an INSERT operation
    }
    public function getProjectDetails($projectId)
    {
        return Project::getProjectDetails($projectId);
    }

    public function updateProjectDetails($projectId, $updatedProjectName, $updatedDescription)
    {
        $data_update = array(
            'project_name' => $updatedProjectName,
            'description' => $updatedDescription,
            'project_id' => $projectId,
        );
        Project::updateProjectDetails($data_update);
    }
    public static function getUserProjects($userId)
    {
        $db = Database::connect()->prepare("SELECT * FROM project WHERE user_id = :user");
        $db->bindParam(':user', $userId);
        $db->execute();
        return $db->fetchAll();
    }

    public static function getProjectById($projectId)
    {
        $db = Database::connect()->prepare("SELECT * FROM project WHERE project_id = :projectId");
        $db->bindParam(':projectId', $projectId);
        $db->execute();
        return $db->fetch();
    }

    public static function getTaskCountForProject($projectId)
    {
        $db = Database::connect()->prepare("SELECT COUNT(*) FROM task WHERE project_id = :projectId");
        $db->bindParam(':projectId', $projectId);
        $db->execute();

        return $db->fetchColumn();
    }

    public static function getCompletedTaskCountForProject($projectId)
    {
        $db = Database::connect()->prepare("SELECT COUNT(*) FROM task WHERE project_id = :projectId AND statut = 'done'");
        $db->bindParam(':projectId', $projectId);
        $db->execute();

        return $db->fetchColumn();
    }


    public static function getOverdueTaskCountForProject($projectId)
    {
        $currentDate = date('Y-m-d');
        $db = Database::connect()->prepare("SELECT COUNT(*) FROM task WHERE project_id = :projectId AND task_end < :currentDate AND statut != 'done'");
        $db->bindParam(':projectId', $projectId);
        $db->bindParam(':currentDate', $currentDate);
        $db->execute();

        return $db->fetchColumn();
    }

    public static function updateProject($projectId, $projectName, $description)
    {
        $db = Database::connect()->prepare("UPDATE project SET project_name = :name, description = :description WHERE project_id = :projectId");
        $db->bindParam(':name', $projectName);
        $db->bindParam(':description', $description);
        $db->bindParam(':projectId', $projectId);

        return $db->execute();
    }

    public static function deleteProject($projectId)
    {
        $db = Database::connect()->prepare("DELETE FROM project WHERE project_id = :projectId");
        $db->bindParam(':projectId', $projectId);
        return $db->execute();
    }
}
