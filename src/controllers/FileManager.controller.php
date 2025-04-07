<?php

declare(strict_types=1);

class FileManagerController {
    private $userId;
    private $uploadPath;
    private $middleware;
    private $maxFileSizeUpload;
    private $fileManagerModel;
    private $settings;

    public function __construct(string $path, FileManagerModel $fileManagerModel, 
    Middleware $middleware, int $maxFileSizeUpload) {
        $this->uploadPath = rtrim($path, '/') . '/';
        $this->maxFileSizeUpload = $maxFileSizeUpload;
        $this->fileManagerModel = $fileManagerModel;
        $this->userId = $_SESSION["CURR_SESSION"]["CURRENT_USER_UUID"] ?? null;
        $this->middleware = $middleware;
        $this->settings = Config::loadConfig();
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0777, true);
        }
    }

    public function isEmptyTmpNames (string $fileName, string $fileTmpName,  
    bool $alertMsg = true): bool {
        if (empty($fileName) || empty($fileTmpName)) {
            if ($alertMsg) $this->middleware->alert("inval_file_inpt");
            return false;
        }
        return true;
    }

    public function checkFileStorageDir (string $docType, string $userId): string {
        $uploadPaths = $this->settings["APP_FILE_UPLOAD_PATHS"][$docType] ?? "";
        $settings =  $uploadPaths . $userId;
        return $this->uploadPath . "{$settings}/";
    }

    public function checkFolderExist ($folder) {
        if (!is_dir($folder)) mkdir($folder, 0777, true);
    }

    public function moveUploadFile(string $fileTmpName, string $fileName, string $fileType, int $fileSize, string $docType, string $userId, string $extExtension = "", bool $alertMsg = true): mixed {
        $fileId = $this->middleware->getUniqueId();
        $identifiedFileDir = $this->checkFileStorageDir($docType, $userId);
        $extra = $extExtension != "" ? "{$extExtension}/" : "";
        $filePathFolder = $identifiedFileDir . $extra;
        $fullFilePath = $identifiedFileDir . $extra . $fileName;
        $this->checkFolderExist($filePathFolder);
        $this->isFileMaxSizeReached($fileSize);
        $this->isEmptyTmpNames($fileName, $fileTmpName);
        $this->fileManagerModel->isFileNameAlreadyExist($fileName);
        $this->fileManagerModel->isFileAlreadyExist($fullFilePath);
        $fileUploadExec = move_uploaded_file($fileTmpName, $fullFilePath);

        $this->fileManagerModel->isFileTypeAllowed($fileType);
        $fileStoration = $this->fileManagerModel->storeFileMetadata($fileId, $fileName, $fullFilePath, $fileType, $fileSize, $userId, $docType, false);

        if ($alertMsg) {
            $fileUploadExec && $fileStoration ? $this->middleware->alert("successful_file_upload")
            : $this->middleware->alert("failed_file_upload");
        }

        return [$fileUploadExec, $fileStoration, $fileId];
    }

    public function isFileMaxSizeReached (int $size, bool $alertMsg = true): bool {
        $comparison = $size >= $this->maxFileSizeUpload;
        if ($comparison && $alertMsg) {
            $this->middleware->alert("file_size_upl_limit_reached");
        }
        return $comparison;
    }

    public function uploadFile(array $file, string $docType = "other", ?string $extExtension = "", bool $alertMsg = true): array {
        header("Content-Type: application/json");
        $userId = $this->userId ?? null;
        $fileTmpName = $file["tmp_name"] ?? null;
        $fileType = $file["type"] ?? null;
        $fileSize = $file["size"] ?? null;
        $fileBaseName = basename($file['name']);
        [$filePathExec, $fileStoration, $fileId] = $this->moveUploadFile($fileTmpName, 
        $fileBaseName, $fileType, $fileSize, $docType, $userId, $extExtension, false);
        return [$filePathExec, $fileStoration, $fileId];
    }

    public function deleteFile($filename) {
        $filePath = $this->uploadPath . $filename;
        if (file_exists($filePath)) {
            unlink($filePath);
            return "File deleted successfully.";
        } else {
            return "File not found.";
        }
    }

    public function renameFile($oldName, $newName) {
        $oldPath = $this->uploadPath . $oldName;
        $newPath = $this->uploadPath . $newName;
        
        if (file_exists($oldPath)) {
            if (!file_exists($newPath)) {
                rename($oldPath, $newPath);
                return "File renamed successfully.";
            } else {
                return "A file with the new name already exists.";
            }
        } else {
            return "File not found.";
        }
    }

    public function listFiles() {
        return array_diff(scandir($this->uploadPath), ['.', '..']);
    }

    public function getFileMetadata($filename) {
        $filePath = $this->uploadPath . $filename;
        if (file_exists($filePath)) {
            return [
                'name' => $filename,
                'size' => filesize($filePath) . ' bytes',
                'type' => mime_content_type($filePath),
                'last_modified' => date("F d Y H:i:s", filemtime($filePath))
            ];
        } else {
            return "File not found.";
        }
    }

    public function getFile($accountId) {
        return $this->fileManagerModel->getfile($accountId);
    }

    public function downloadFile($fileStoredName) {
        $fullFilePath = $this->fileManagerModel->getFilePath($fileStoredName, false)["file_path"];
        $filterFile = str_replace("/\/", "/", $fullFilePath);
        if (file_exists($filterFile)) {
            ob_clean();
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filterFile) . '"');
            header('Expires: 0');
            header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filterFile));
            flush();
            readfile($filterFile);
            return true;
        } else {
            http_response_code(404);
            return "File not found.";
        }
    }

    function bytesToMB(int $bytes, int $precision = 2): float {
        return round($bytes / (1024 * 1024), $precision);
    }
}