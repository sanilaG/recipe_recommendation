<?php include 'header.php'; ?>
<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['Email']); // Changed to 'Email' to match the form field name
    $password = $_POST['Password'];

    // Server-side validation
    $errors = [];

    if (empty($username) || empty($password)) {
        $errors[] = "Email and Password are required.";
    }

    if (empty($errors)) {
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: ../pages/recipes.php");
                exit;
            } else {
                $errors[] = "Incorrect password!";
            }
        } else {
            $errors[] = "User not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login page</title>
    <link rel="stylesheet" href="../css/style.css">
    <script>
        function validateLoginForm() {
            var username = document.getElementsByName('Email')[0].value;
            var password = document.getElementsByName('Password')[0].value;

            if (username.trim() === "" || password.trim() === "") {
                alert("Email and Password are required.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <div class="center">
        <form action="../php/login.php" method="POST" class="form" onsubmit="return validateLoginForm()">
            <h2>Login</h2>
            
            <input type="text" placeholder="Enter email address" name="Email" class="box">
            <input type="password" placeholder="Enter password" name="Password" class="box">
            <input type="submit" value="login" id="submit"><br>
            <a href="#">Forget password?</a>
            
            <div class="sign">
                Create new account&nbsp;&nbsp;<a href="Register.php">sign up</a>
            </div>
        </form>

        <div class="side">
            <img src="../images/login.jpg" alt="Blood donation">
        </div>

        <!-- Show server-side validation errors -->
        <?php if (!empty($errors)) : ?>
            <div>
                <?php foreach ($errors as $error) : ?>
                    <p style="color: red;"><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php include 'footer.php'; ?>
