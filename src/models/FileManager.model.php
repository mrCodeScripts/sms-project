<?php

declare(strict_types=1);

class FileManagerModel extends Database {
    private $middleware;

    public function __construct($middleware)
    {
        $this->middleware = $middleware; 
    }

    public function isFileNameAlreadyExist(string $fileName, bool $alertMsg = true): bool {
        $query = "SELECT file_name FROM uploaded_files WHERE file_name = :file_name;";
        $exec = $this->setBindedExecution($query, ["file_name" => $fileName]);

        if (empty($exec) && $alertMsg) {
            $this->middleware->alert("file_name_already_exist");
        }

        return !empty($exec);
    }

    public function isFileAlreadyExist (string $filePath, bool $alertMsg = true): bool {
        $query = "SELECT * FROM uploaded_files WHERE file_path = :file_path;";
        $exec = $this->setBindedExecution($query, ["file_path" => $filePath])->fetchAll();

        if (!empty($exec) && $alertMsg) {
            $this->middleware->alert("FILE_PATH_ALREXIST");
        }

        return !empty($exec);
    }

    public function getFileTypeId (string $fileTypeMime, bool $alertMsg = true): string {
        $query = "SELECT file_type_id FROM file_types WHERE file_mime_type = :mime;";
        $exec = $this->setBindedExecution($query, ["mime" => $fileTypeMime])->fetchAll();
        
        if (empty($exec) && $alertMsg) {
            $this->middleware->alert("file_mime_type_noexist");
        }

        return $exec[0]["file_type_id"];
    }

    public function storeFileMetadata (string $fileId, string $fileName, string $filePath, string $fileType, int $fileSize, string $fileOwnderUUID, string $docType, bool $alertMsg = true): bool {
        $query = "INSERT INTO uploaded_files (
        file_id, 
        file_name, 
        file_stored_name, 
        file_path, 
        file_type, 
        file_size, 
        file_uploaded_by, 
        file_doc_type
        ) VALUES (
        :file_id, 
        :file_name,
        :file_stored_name, 
        :file_path, 
        :file_type, 
        :file_size, 
        :file_uploaded_by, 
        :file_doc_type);";

        $data = [
            "file_id" => $fileId,
            "file_name" => $fileName,
            "file_stored_name" => $this->middleware->getUniqueId(10),
            "file_path" => $filePath,
            "file_type" => $this->getFileTypeId($fileType),
            "file_size" => intval($fileSize),
            "file_uploaded_by" => $fileOwnderUUID,
            "file_doc_type" => $docType
        ];

        $exec = $this->setBindedExecution($query, [
            "file_id" => $fileId,
            "file_name" => $fileName,
            "file_stored_name" => $this->middleware->getUniqueId(10),
            "file_path" => $filePath,
            "file_type" => $this->getFileTypeId($fileType),
            "file_size" => intval($fileSize),
            "file_uploaded_by" => $fileOwnderUUID,
            "file_doc_type" => $docType
        ]);


        if ($alertMsg) {
            $exec ? $this->middleware->alert("SUCC_FILE_UPL")
            : $this->middleware->alert("FAIL_FILE_UPL");
        }

        return !empty($exec);
    }

    public function getFile ($id) {
        return $this->setBindedExecution("SELECT filePath FROM uploaded_files WHERE file_uplaoded_by = :id;", ["id" => $id]);
    }
    
    public function isFileTypeAllowed (string $fileType, bool $alert = true): bool {
        $query = "SELECT file_type_id FROM file_types 
        WHERE file_mime_type = :filetype AND file_type_allowed = 1;";
        $exec = $this->setBindedExecution($query, ["filetype" => $fileType])->fetchAll();

        if (empty($exec) && $alert) {
            $this->middleware->alert("FILETYPE_NOTALLOWED"); 
        }

        return !empty($exec);
    }

    public function getFilePath (string $fileStoredName, bool $alert = true) {
        $query = "SELECT file_path FROM uploaded_files WHERE file_stored_name = :name;";
        $exec = $this->setBindedExecution($query, ["name" => $fileStoredName])->fetchAll();
        if (empty($exec) && $alert) {
            $this->middleware->alert("FILE_NOT_FOUND");
        }
        return $exec[0];
    }
}
