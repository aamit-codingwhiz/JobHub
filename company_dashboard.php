<?php
// Include your database connection code here (e.g., config.php)
require_once 'config.php';

session_start();

// Check if the user is logged in and has the 'company' role
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in or not a company
    exit();
}

// Fetch company-specific data (replace with your SQL query)
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM CompanyProfiles WHERE user_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$company = $stmt->get_result()->fetch_assoc();

// Fetch job listings associated with the company (replace with your SQL query)
$sql = "SELECT * FROM Jobs WHERE company_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $company['company_id']);
$stmt->execute();
$jobListings = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/semantic.min.js" integrity="sha512-Xo0Jh8MsOn72LGV8kU5LsclG7SUzJsWGhXbWcYs2MAmChkQzwiW/yTQwdJ8w6UA9C6EVG18GHb/TrYpYCjyAQw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/semantic.css" integrity="sha512-6PtWSF1JdejluD9SoAmj/idJKF+dJoa2u9UMldygOhgT4M0dmXTiNUx1TwqNiEg4eIjOb4bZRQ19cOP7p8msYA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script>
        $(document).ready(function() {
            $("#toggle-button").click(function() {
                $('.ui.modal').modal('show');
            });
        });
    </script>
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
            <a class="header item" href="#">JobHub</a>
            <a class="active item" href="#">Home</a>
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
        <div class="ui items">
            <div class="item">
                <div class="ui tiny image">
                    <img src="blk1_4i9y_230714.jpg" alt="logo">
                </div>
                <div class="middle aligned content">
                    <a class="header">
                        <h1><?php echo $company['company_name']; ?></h1>
                    </a>
                </div>
            </div>
        </div>

        <div class="ui segment">
            <h2>Job Listings</h2>
            <table class="ui celled striped table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>Deadline</th>
                        <th>...</th>
                        <!-- Add more job-related headers as needed -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $jobListings->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row["title"] . '</td>';
                        echo '<td>' . $row["description"] . '</td>';
                        echo '<td>' . $row["location"] . '</td>';
                        // Display additional job-related data here
                        echo '<td>' . $row["application_deadline"] . '</td>';
                        // echo '<td><button class="toggle-button">show applicants</button></td>';
                        echo '<td><a href="./show_applicants.php?job_id=' . $row["job_id"] . '" class="ui button">show applicants</a>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
            <div class="ui right floated">
                <a class="ui primary right floated button" href="./post_job.php">Post New Job</a>
                <!-- <button class="ui primary right floated button" id="toggle-button">Post New Job</button> -->
            </div>
        </div>

        <div class="ui modal">
            <div class="header">Header</div>
            <div class="content">
                <form method="POST" class="ui form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div>
                        <label for="title">Title:</label>
                        <input type="text" name="title" id="title" value="<?php echo $title; ?>">
                        <span class="error"><?php echo $errors['title']; ?></span>
                    </div>
                    <div>
                        <label for="description">Description:</label>
                        <textarea name="description" id="description"><?php echo $description; ?></textarea>
                        <span class="error"><?php echo $errors['description']; ?></span>
                    </div>
                    <div>
                        <label for="location">Location:</label>
                        <input type="text" name="location" id="location" value="<?php echo $location; ?>">
                        <span class="error"><?php echo $errors['location']; ?></span>
                    </div>
                    <div>
                        <label for="application_deadline">Application Deadline:</label>
                        <input type="date" name="application_deadline" id="application_deadline" value="<?php echo $application_deadline; ?>">
                        <span class="error"><?php echo $errors['application_deadline']; ?></span>
                    </div><br>

                    <div>
                        <input type="submit" class="ui right floated primary button" name="post_job" value="Post Job">
                    </div>
                </form>
            </div>

        </div>

    </div>
</body>

</html>