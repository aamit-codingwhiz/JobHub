<?php
// Include your database connection code here (e.g., config.php)
require_once 'config.php';

// Initialize variables to store user login data
$email = $password = '';
$errors = array();

// Check if the login form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize user input
    $email = cleanInput($_POST["email"]);
    $password = $_POST["password"];

    // Validate email
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }

    // Validate password
    if (empty($password)) {
        $errors['password'] = 'Password is required';
    }

    // If there are no validation errors, attempt login
    if (empty($errors)) {
        // Fetch user data from the database based on the entered email
        $sql = "SELECT user_id, email, password FROM Users WHERE email=?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Verify password and user existence
        if ($user && password_verify($password, $user['password'])) {
            // Login successful; set user session and redirect to the user's dashboard
            session_start();
            $_SESSION['user_id'] = $user['user_id'];
            header("Location: company_dashboard.php"); // Redirect to the dashboard or profile page
            exit();
        } else {
            // Invalid login credentials
            $errors['login'] = 'Invalid email or password';
        }

        $stmt->close();
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
    <title>User Login</title>
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
            <a class="header item" href="./">JobHub</a>
            <a class="item" href="./">Home</a>
            <a class="item" href="#">Contact</a>

            <div class="right menu">
                <div class="item">
                    <div class="ui buttons">
                        <a href="./registration.php" class="ui positive button">Register</a>
                        <div class="or"></div>
                        <a href="./login.php" class="ui active button">login</a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="ui container">
        <!-- <h1>User Login</h1> -->

        <!-- $_SERVER["PHP_SELF"] is a super global variable that returns the filename of the currently executing script -->
        <form method="POST" class="ui form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="field">
                <!-- <label for="email">Email:</label> -->
                <input type="email" name="email" id="email" value="<?php echo $email; ?>" placeholder="Your Email">
                <span class="error"><?php echo $errors['email']; ?></span>
            </div>

            <div class="field">
                <!-- <label for="password">Password:</label> -->
                <input type="password" name="password" id="password" placeholder="Your Password">
                <span class="error"><?php echo $errors['password']; ?></span>
            </div><br>

            <div class="field">
                <input type="submit" class="ui primary right floated button" value="Login">
            </div>
        </form>

        <p class="error"><?php echo $errors['login']; ?></p>
    </div>

    <!-- Include your HTML/CSS for styling here -->
</body>

</html>