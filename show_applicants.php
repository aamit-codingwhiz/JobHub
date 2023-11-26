<?php
// Include your database connection code here (e.g., config.php)
require_once 'config.php';

session_start();

// Check if the user is logged in and has the 'company' role
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in or not a company
    exit();
}

if (!isset($_GET['job_id'])) {
    header("Location: company_dashboard.php");
    exit();
}

$job_id = $_GET['job_id'];
$sql = "SELECT * FROM Applications WHERE job_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$applicants = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/semantic.min.js" integrity="sha512-Xo0Jh8MsOn72LGV8kU5LsclG7SUzJsWGhXbWcYs2MAmChkQzwiW/yTQwdJ8w6UA9C6EVG18GHb/TrYpYCjyAQw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/semantic.css" integrity="sha512-6PtWSF1JdejluD9SoAmj/idJKF+dJoa2u9UMldygOhgT4M0dmXTiNUx1TwqNiEg4eIjOb4bZRQ19cOP7p8msYA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>
    <!-- Navbar -->
    <div class="ui custom-navbar menu">
        <div class="ui container">
            <a class="header item" href="#">JobHub</a>
            <a class="item" href="./company_dashboard.php">Home</a>
            <a class="item" href="#">Contact</a>
            <div class="right menu">
                <div class="item">
                    <a href="./logout.php" class="ui red button">logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Rest of your page content goes here -->
    <div class="ui container">
        <table class="ui celled striped table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Resume</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $applicants->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row["Name"] . '</td>';
                    echo '<td>' . $row["Email"] . '</td>';
                    echo '<td><a href="' . $row["resume_path"] . '" class="ui button">click to see resume</a>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>