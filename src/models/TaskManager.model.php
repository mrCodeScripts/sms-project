<?php

declare(strict_types=1);

class TaskManagerModel extends Database {
    protected $middleware;

    public function __construct(Middleware $middleware)
    {
        $this->middleware = $middleware;     
    }

    public function isAnyColumnEmpty (array $data, bool $alert = true): bool {
        $error = false;
        foreach ($data as $d) {
            if ($d === null) {
                $error = true;
                break;
            }
        }

        if ($error && $alert) {
            $this->middleware->alert("incomplete_data");
        }

        return $error;
    }

    public function isTaskNameAlreadyUsed (string $taskName, string $profid, bool $alert = true): bool {
        $query = "SELECT task_name FROM task WHERE task_name = task_name  AND task_teacher_profid = :profid;";
        $data = ["task_name" => $taskName, "task_teacher_profid" => $profid];
        $exec = $this->setBindedExecution($query, $data)->fetchAll();

        if (!empty($exec) && $alert) {
            $this->middleware->alert("task_name_already_used");
        }

        return !empty($exec);
    }

    public function isTaskPriorityExist (string $taskPriority, bool $alert = true): bool {
        $query = "SELECT task_priority_id FROM task_priority WHERE task_priority_id = :task_priority_id;";
        $data = ["task_priority_id" => $taskPriority];
        $exec = $this->setBindedExecution($query, $data)->fetchAll();

        if (empty($exec) && $alert) {
            $this->middleware->alert("task_priority_noexist");
        }
        return !empty($exec);
    }

    public function isTaskPriorityStatusExist (string $taskStatus, bool $alert = true): bool {
        $query = "SELECT task_status_id FROM task_status WHERE task_status_id = :task_status_id;";
        $data = ["task_status_id" => $taskStatus];
        $exec = $this->setBindedExecution($query, $data)->fetchAll();

        if (empty($exec) && $alert) {
            $this->middleware->alert("task_status_noexist");
        }

        return !empty($exec);
    }

    public function createNewTask (array $data, bool $alert = true): bool {
        $query = "INSERT INTO task_student_compliance (
        task_id, 
        task_name, 
        task_deadline_on, 
        task_priority, 
        task_status, 
        task_teacher_profid,
        task_notes,
        class_id
        ) VALUES (
        :task_id, 
        :task_name, 
        :task_deadline_on, 
        :task_priority, 
        :task_status, 
        :task_teacher_profid,
        :task_notes,
        :class_id
        );";
        $exec = $this->setBindedExecution($query, $data);
        if ($alert) {
            $exec ? $this->middleware->alert("successful_task_creation")
            : $this->middleware->alert("failed_task_creation");
        }
        return $exec;
    }

    public function createNewTaskWithSubject (array $data, bool $alert = true): bool {
        $query = "INSERT INTO task_student_compliance (
        task_id, 
        task_name, 
        task_deadline_on, 
        task_priority, 
        task_status, 
        task_teacher_profid,
        task_notes,
        task_assignment_compliance,
        class_id
        ) VALUES (
        :task_id, 
        :task_name, 
        :task_deadline_on, 
        :task_priority, 
        :task_status, 
        :task_teacher_profid,
        :task_assignment_compliance
        :task_notes,
        :class_id
        );";
        $exec = $this->setBindedExecution($query, $data);
        if ($alert) {
            $exec ? $this->middleware->alert("successful_task_creation")
            : $this->middleware->alert("failed_task_creation");
        }
        return $exec;
    }

    public function isTaskExist (string $taskId, string $profid, bool $alert = true): bool {
        $query = "SELECT * FROM task WHERE task_id = :task_id AND task_teacher_profid = :profid;";
        $data = ["task_id" => $taskId, "profid" => $profid];
        $exec = $this->setBindedExecution($query, $data)->fetchAll();
        
        if (empty($exec) && $alert) {
            $this->middleware->alert("task_noexist");
        }

        return !empty($exec);
    }

    public function isUserHasTask (string $profid, bool $alert = true): bool {
        $query = "SELECT * FROM task WHERE task_teacher_profid = :profid;";
        $data = ["profid" => $profid];
        $exec = $this->setBindedExecution($query, $data)->fetchAll();

        if (empty($exec) && $alert) {
            $this->middleware->alert("user_hasno_task");
        }

        return !empty($exec);
    }
}