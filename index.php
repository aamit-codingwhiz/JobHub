<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobHub</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/semantic.min.js" integrity="sha512-Xo0Jh8MsOn72LGV8kU5LsclG7SUzJsWGhXbWcYs2MAmChkQzwiW/yTQwdJ8w6UA9C6EVG18GHb/TrYpYCjyAQw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/semantic.min.css" integrity="sha512-KXol4x3sVoO+8ZsWPFI/r5KBVB/ssCGB5tsv2nVOKwLg33wTFP3fmnXa47FdSVIshVTgsYk/1734xSk9aFIa4A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            background-color: #f4f4f4;
        }

        .ui.form {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <div class="ui custom-navbar menu">
        <div class="ui container">
            <a class="header item" href="./">JobHub</a>
            <a class="item" href="./">Home</a>
            <a class="item" href="#">Contact</a>

            <div class="right menu">
                <div class="item">
                    <div class="ui buttons">
                        <a href="./registration.php" class="ui positive button">Register</a>
                        <div class="or"></div>
                        <a href="./login.php" class="ui primary button">login</a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="ui container" style='margin: 1em;'>
        <div class="ui segment">
            <h1 class="ui header">Job Listings</h1>
        </div>
        <div class="ui segment">
            <div class="ui cards">
                <?php
                require_once 'config.php';
                require_once 'functions.php';

                $jobListings = getJobListings($db);
                if ($jobListings) {
                    while ($row = $jobListings->fetch_assoc()) {
                        echo '<div class="ui blue card">';
                            echo '<div class="content">';
                                echo '<div class="header">' . $row["title"] . '</div>';
                                echo '<div class="meta">' . $row["company_name"] . '</div>';
                                echo '<div class="description">' . $row["description"] . '</div>';
                            echo '</div>';
                            echo '<div class="extra content">';
                            echo '<a href="apply.php?id=' . $row["job_id"] . '" class="ui bottom attached button">Apply</a>';
                            echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No job listings available.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>