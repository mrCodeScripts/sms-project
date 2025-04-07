<?php 

declare(strict_types=1);

class TaskManagerController {
    protected $taskManagerModel;    
    protected $middleware;

    public function __construct(TaskManagerModel $taskManagerModel, Middleware $middleware)
    {
        $this->taskManagerModel = $taskManagerModel;
        $this->middleware = $middleware;
    }

    public function addTask (string $profid) {
        $data = [
            "task_id" => $this->middleware->getUniqueId(10),
            "task_name" => $_POST["task-name"] ?? null,
            "task_deadline_on" => $_POST["task-deadline"] ?? null,
            "task_priority" => $_POST["task-priority-lvl"] ?? null,
            "task_status" => "NOT_STARTED" ?? null,
            "task_teacher_profid" => $profid ?? null,
            "task_notes" => $_POST["task-description"] ?? null,
            "class_id" => $_POST["class-id"] ?? null,
        ];

        $this->taskManagerModel->isAnyColumnEmpty($data);
        $this->taskManagerModel->isTaskPriorityExist($data["task_priority"]);
        $this->taskManagerModel->isTaskPriorityStatusExist($data["task_status"]);
        $this->taskManagerModel->createNewTask($data);
    }

    public function addTaskWithSubject (
        string $taskName,
        string $taskDeadline,
        string $taskPriority,
        string $taskStatus,
        string $profid, 
        string $notes,
        string $assigID,
        string $classId,
    ) {
        $data = [
            "task_id" => $this->middleware->getUniqueId(10) ?? null,
            "task_name" => $taskName ?? null,
            "task_deadline_on" => $taskDeadline ?? null,
            "task_priority" => $taskPriority ?? null,
            "task_status" => $taskStatus ?? null,
            "task_teacher_profid" => $profid ?? null,
            "task_assignment_compliance" => $assigID ?? null,
            "task_notes" => $notes,
            "class_id" => $classId,
        ];

        $this->taskManagerModel->isAnyColumnEmpty($data);
        $this->taskManagerModel->createNewTaskWithSubject($data);
    }

    public function removeTask (string $profid) {
        $data = [
            "task_id" => $_POST["task-id"] ?? null,
            "task_teacher_profid" => $profid,
        ];

        $this->taskManagerModel->isAnyColumnEmpty($data);        
        $this->taskManagerModel->isTaskExist($data["task_id"], $profid);
        $this->taskManagerModel->isUserHasTask($data["user_id"]);
        // $this->taskManagerModel->removeTask($data);
    }
}