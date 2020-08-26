<?php 

// THIS IS CALLED ONCE PER MINUTE BY A CRON JOB

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'functions.php';
$helper = new Helper();

$allProjects = $helper::getProjects();

shuffle($allProjects);

// Pick N random projects to fire this minute

$N = ceil(count($allProjects)/2); // Half of em, rounded up. (3 if 5)

$winners = [];
for ($i=0; $i<$N; $i++) {
	$winners[] = $allProjects[$i];
}

// Currently just runs a random suite from each project.
foreach ($winners as $project) {
	$suites = array_keys($project['suites']);
	shuffle($suites);

	$url = "http://" . $_SERVER['HTTP_HOST'] . "/run.php?headless=true&project=" . $project['project']['name'] . "&suite=" . $suites[0];
	file_get_contents($url);
}