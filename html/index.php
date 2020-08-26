<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'functions.php';

$helper = new Helper();

$helper::require_auth();
$projects = $helper::getProjects();

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
</head>
<body>

    <div class="container">
            
        <?php foreach ($projects as $data) { 
            $project = $data['project']['name'];
        ?>
            <h1><?php echo $project ?></h1>
            <a href="build.php?project=<?php echo $project ?>" target="_blank">Build TypeScript</a>

            <table class="table">
                <tr>
                    <th>Suite</th>
                    <th>Last Run</th>
                    <th>Log</th>
                    <th>Action</th>
                </tr>

                <?php foreach ($data['suites'] as $suite) { ?>
                    <tr>
                        <th><?php echo $suite['name'] ?></th>
                        <th><?php echo date("M-d H:i:s", $helper::getLastUpdated($project, $suite['name'])); ?> GMT</th>
                        <th><a href="log.php?project=<?php echo $project; ?>&suite=<?php echo $suite['name']; ?>">View Log</a></th>
                        <th><a href="run.php?project=<?php echo $project; ?>&suite=<?php echo $suite['name']; ?>">Run it</a></th>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>

    </div>
    
</body>
</html>

