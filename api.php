<?php

    define("PATH_TO_ROOT_FOLDER", "../"); // The relative path to the folder to track
    define("DEBUG", FALSE);

    if(DEBUG) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        echo '<pre>';
    } else
        header('Content-Type: application/json');
    
    function getExtension($file) {
        $extension = array();
        preg_match('/[.].*/m',$file, $extension);
        if(sizeof($extension) > 0) // If $file is a folder, the preg_match will not put anything in the array $extension
            return $extension[0];
        return "";
    }

    function getClassIcon($file) {
        $icons = [
            "archive" => "fas fa-file-archive",
            "code" => "fas fa-file-code",
            "php" => "fab fa-php",
            "javascript" => "fab fa-js-square",
            "html" => "fab fa-html5",
            "css" => "fab fa-css3-alt",
            "file" => "fas fa-file",
            "folder" => "fas fa-folder"
        ];
        $extension = getExtension($file);
        switch($extension) {
            case ".rar":
            case ".tar.gz":
            case ".zip":
                return $icons["archive"];
            case ".php":
                return $icons["php"];
            case ".js":
                return $icons["javascript"];
            case ".html":
                return $icons["html"];
            case ".css":
                return $icons["css"];
            case "":
                return $icons["folder"];
            default:
                return $icons["file"];
        }
    }

    function getTypeFile($file) {
        if(getExtension($file) != "")
            return "file";
        else
            return "folder";
    }

    function getExcludedFiles() {
        
        $otherFilesToExclude = ["exclude.txt","index.php","index2.php","style.css"];

        $filesToExclude = str_replace("\n", "", file(PATH_TO_ROOT_FOLDER . "exclude.txt"));
        foreach($otherFilesToExclude as $file) {
            $filesToExclude[] = $file;
        }
        return $filesToExclude;
    }

    function removePathInFileName($file, $path) {
        return str_replace($path . "/", "", $file);
    }

    function getFilesFromDirectory($nameDirectory) {
        $files = str_replace(PATH_TO_ROOT_FOLDER, "", array_filter(glob(PATH_TO_ROOT_FOLDER . $nameDirectory . '/*')));
        $notExcludedFiles = [];
        $excludedFiles = getExcludedFiles();
        foreach($files as $file) {
            $file = removePathInFileName($file, $nameDirectory);
            if(!in_array($file, $excludedFiles))
                $notExcludedFiles[] = $file;
        }
        return $notExcludedFiles;
    }

    function fileInExcludeList($nameFile, $foldersToExclude) {
        return in_array($nameFile, $foldersToExclude);
    }

    function getFormatedContentFromDirectory($nameDirectory) {
        $files = getFilesFromDirectory($nameDirectory);
        $formatedContent = [];
        foreach($files as $file) {
            //$file = removePathInFileName($file, $nameDirectory);
            $formatedContent[] = [
                "name" => $file,
                "type" => getTypeFile($file),
                "extension" => getExtension($file),
                "classIcon" => getClassIcon($file)
            ];
        }
        return $formatedContent;
    }

    if(isset($_GET)) {

        if(isset($_GET['query'])) {

            $query = $_GET['query'];

            if(isset($_GET['directory'])) {

                $directory = $_GET['directory'];

                if($query == "getFilesFromDirectory") {
                    if(DEBUG)
                        var_dump(getFilesFromDirectory($_GET['directory']));
                    else
                        echo(json_encode(getFilesFromDirectory($_GET['directory'])));
                }
                    
                else if($query == "getFormatedContentFromDirectory") {
                    if(DEBUG)
                        var_dump(getFormatedContentFromDirectory($_GET['directory']));
                    else
                    echo(json_encode(getFormatedContentFromDirectory($_GET['directory'])));
                }
            } else if($query == "getDiskSpace") {
                if(DEBUG)
                    var_dump(getDiskSpace());
                else
                echo(json_encode(getDiskSpace()));
            }
        }
    }
    
    if(DEBUG)
        echo '</pre>';

?>