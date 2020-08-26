<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'functions.php';
$helper = new Helper();

$command = 'cd ' . $helper::pathToProjects . $_GET['project'] . ' && flagpole build';
$res = shell_exec($command);

if (strrpos($res, "Done!")) {
	die("Done");
} else {
	die("Something went wrong.");
}
