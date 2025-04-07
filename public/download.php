<?php

include_once __DIR__ . "/../src/bootstrap.php";

$fileManagerController->downloadFile($_GET["path"]);