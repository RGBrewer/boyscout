<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'functions.php';
$helper = new Helper();

if (!isset($_GET['headless'])){
    $helper::require_auth();
}

$project = $_GET['project'];
$requestedSuite = isset($_GET['suite']) ? $_GET['suite'] : null;

if (!$project) {
    die('Missing project.');
}

$suite = $helper::runSuite($project, $requestedSuite);

if (isset($_GET['headless'])) {
    $helper::alert($project, $suite);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.0"></script>

</head>
<body>


<div id="content" class="container">
    
    <div>
        <h1 v-if="hasFailedTest">Suite(s) Failed!</h1>
        <h1 v-else>Suites Passed!</h1>
    </div>
    <div v-for="suite in failedSuites">
        <p>Failed Suite: {{ suite.title }}</p>
        <table class="table" v-for="scenario in suite.scenarios">
            <th>Scenario: {{ scenario.title }}</th>
            <tr v-for="log in scenario.log">
                <td >
                    {{ log.type }} - {{ log.message }}
                </td>
            </tr>
        </table>
    </div>
    <div v-for="suite in succeededSuites">
        <p>Success Suite: {{ suite.title }}</p>
        <table class="table" v-for="scenario in suite.scenarios">
            <th>Scenario: {{ scenario.title }}</th>
            <tr v-for="log in scenario.log">
                <td >
                    {{ log.message }}
                </td>
            </tr>
        </table>
    </div>
</div>
<script>
var app = new Vue({
    el: '#content',
    data: {
        flagpole: <?php echo json_encode($suite); ?>,
        hasFailedTest: false,
        succeededSuites: [],
        failedSuites: []
    },
    created: function() {
        for (var i = this.flagpole.suites.length - 1; i >= 0; i--) {
            var suite = this.flagpole.suites[i];
            var suiteHasErrors = false;
            // Loop through each scenario
            for (var s = 0; s<suite.scenarios.length; s++) {
                var scenario = suite.scenarios[s];
                // Loop through each log
                for (var l = 0; l<scenario.log.length; l++) {
                    var log = scenario.log[l];
                    // Failed log
                    if (log.type == "fail") {
                        suiteHasErrors = true;
                    }
                }
            }
            // Bucket into succeeds/fails
            if (suiteHasErrors) {
                this.hasFailedTest = true;
                this.failedSuites.push(suite);
            } else {
                this.succeededSuites.push(suite);
            }
        }
    },
    methods: {}
});
</script>

    
</body>
</html>