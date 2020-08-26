<?php

class Helper {

    const pathToProjects = '/var/www/html/projects/';

    function __constructor() {}

    // Require Basic Auth
    static function require_auth() {
        $AUTH_USER = 'flocasts';
        $AUTH_PASS = 'florocks!!!';
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
        $is_not_authenticated = (
            !$has_supplied_credentials ||
            $_SERVER['PHP_AUTH_USER'] != $AUTH_USER ||
            $_SERVER['PHP_AUTH_PW']   != $AUTH_PASS
        );

        if ($is_not_authenticated) {
            header('HTTP/1.1 401 Authorization Required');
            header('WWW-Authenticate: Basic realm="Access denied"');
            exit;
        }
    }


    // List our projects
    static function getProjects() {
        $folders = scandir(self::pathToProjects);
        $resp = [];
        for ($i=0; $i < count($folders); $i++) { 
            $folder = $folders[$i];
            if ($folder !== "." && $folder !== "..") {
                $resp[] = self::getConfig(self::pathToProjects . $folder);
            }
        }
        return $resp;
    }


    // Load and Decode a flagpole json file
    static function getConfig($pathToConfig) {
        return json_decode(file_get_contents($pathToConfig . "/flagpole.json"), true);
    }

    // Run a suite!
    static function runSuite($project, $suite = null) {
        $dir = self::pathToProjects . $project;

        $command = 'cd ' . $dir . ' && ' . 'flagpole run';
        if ($suite) {
            $command .= ' -s ' . $suite;
        } else {
            $command .= ' --all';
        }
        $command .= ' -o json -h';

        $json = trim(shell_exec($command));
        // Strip out the header text
        $index = strpos($json, "{");
        $json = substr($json, $index);
        $data = json_decode($json, true);

        // TO-DO -- this can only take one suite at a time...
        static::logSuiteResults($project, $suite, $data);
        return $data;
    }

    private static function logSuiteResults($project, $suite, $results) {
        $logDir = self::pathToProjects . $project . "/logs/";
        $logFile = $suite.".log";
        $data = json_encode([
            'lastUpdated' => strtotime('now'),
            'results' => json_encode($results)
        ]);
        @mkdir($logDir);
        @file_put_contents($logDir . $logFile, $data);
    }

    public static function getLog($project, $suite) {
        $logDir = self::pathToProjects . $project . "/logs/";
        $logFile = $suite.".log";

        $path = $logDir . $logFile;
        $json = is_file($path) ? file_get_contents($path) : null;
        if (!$json) { return []; }
        return json_decode($json, true);
    }

    public static function getLastUpdated($project, $suite) {
        $log = self::getLog($project, $suite);
        return isset($log['lastUpdated']) ? $log['lastUpdated'] : 0;
    }

    public static function alert($project, $suite) {
        $url = 'https://hooks.slack.com/services/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';

        $flagpole = $suite;
        $errors = [];
        $reportBlock = "";
        foreach ($flagpole['suites'] as $suite) {
            $suiteHasErrors = false;
            $reportBlock .= "====== SUITE FAILED ====== \n";
            $reportBlock .= "Suite: " . $suite['title'] . "\n";
            foreach ($suite['scenarios'] as $scenario) {
                $reportBlock .= "Scenario: " . $scenario['title'] . "\n";
                foreach ($scenario['log'] as $log) {
                    if ($log['type'] === 'fail') {
                        $suiteHasErrors = true;
                        $reportBlock .= "Fail: " . $log['message'] . "\n";
                    }
                }
            }
            if ($suiteHasErrors) {
                array_push($errors, $reportBlock);
            }
            $reportBlock = "";
        }
        if ($errors) {
            $report = "<!here> \n======  BOY SCOUT!  ====== \n";
            foreach ($errors as $block) {
                $report .= $block . "\n\n\n";
            }
             
        } else {
            $chance = 1;
            if (rand(0,200) <= $chance) { // 0.5%
                $report = "Boy Scout checking in. All is well.";
            } else {
                die();
            }
        }
        $data = ['text' => $report];

        //API Url
        //Initiate cURL.
        $ch = curl_init($url);
         
        //Tell cURL that we want to send a POST request.
        curl_setopt($ch, CURLOPT_POST, 1);
         
        //Attach our encoded JSON string to the POST fields.
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
         
        //Set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
         
        //Execute the request
        $result = curl_exec($ch);
    }

}
