<?php
// Include your database connection code here (e.g., config.php)
require_once 'config.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in or not a company
    exit();
}

// Initialize variables to store job posting data
$title = $description = $location = '';
$errors = array();

// Check if the job posting form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["post_job"])) {
        $title = cleanInput($_POST["title"]);
        $description = cleanInput($_POST["description"]);
        $location = cleanInput($_POST["location"]);

        // Format the date in the desired format (YYYY-MM-DD)
        $application_deadline = date("Y-m-d", strtotime($_POST["application_deadline"]));

        // Validate job title
        if (empty($title)) {
            $errors['title'] = 'Title is required';
        }

        // Validate job description
        if (empty($description)) {
            $errors['description'] = 'Description is required';
        }

        // Validate job location
        if (empty($location)) {
            $errors['location'] = 'Location is required';
        }

        // If there are no validation errors, proceed with job posting
        if (empty($errors)) {
            // Get the user's company ID from the session
            $company_id = $_SESSION['user_id'];
            echo 'user ID: ' . $company_id;

            // Insert job posting data into the database (replace with your SQL query)
            $sql = "INSERT INTO Jobs (company_id, title, description, location, application_deadline) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("issss", $company_id, $title, $description, $location, $application_deadline);


            if ($stmt->execute()) {
                // Job posting successful; redirect to the company dashboard
                header("Location: company_dashboard.php");
                exit();
            } else {
                // Job posting failed
                $errors['job_posting'] = 'Job posting failed. Please try again later.';
            }


            $stmt->close();
        }
    }
}

// Function to sanitize user input
function cleanInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post New Job</title>
    <!-- Include your CSS stylesheets and JavaScript libraries here -->
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
            <a class="header item" href="#">JobHub</a>
            <a class="item" href="./company_dashboard.php">Home</a>
            <a class="active item" href="./post_job.php">Post New Job</a>
            <a class="item" href="#">Contact</a>
            <!-- Add more menu items as needed -->
            <div class="right menu">
                <div class="item">
                    <a href="./logout.php" class="ui red button">Log out</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Rest of your page content goes here -->
    <div class="ui container">
        <h1>Post New Job</h1>

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

        <p class="error"><?php echo $errors['job_posting']; ?></p>
    </div>

    <!-- Include your HTML/CSS for styling here -->
</body>

</html>