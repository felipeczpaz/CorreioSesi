<?php
// Start the session (if not already started)
session_start();

// Assume some logic to check if the user is logged in
$is_logged_in = isset($_SESSION['user_id']);
$username = $is_logged_in ? $_SESSION['username'] : '';

// Initialize the error message string
$errorString = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
// Include the MySQLi connection file
require_once('connection.php');

    // Retrieve user input from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate username and password
    if (empty($username) || empty($password)) {
        $errorString = "Username and password are required.";
    } else {
        // Prepare and execute the SQL statement to retrieve user data
        $stmt = $mysqli->prepare("SELECT user_id, username, email, password_hash FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // User found, fetch the password
            $stmt->bind_result($userid, $dbUsername, $dbEmail, $dbPassword);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $dbPassword)) {
                // Password is correct, start a session
                session_start();

                // Set session variables
                $_SESSION['user_id'] = $userid;
                $_SESSION['username'] = $dbUsername;
                $_SESSION['email'] = $dbEmail;

                // Redirect to a success page or perform other actions
                header('Location: index.php');
                exit();
            } else {
                // Password is incorrect
                $errorString = "Invalid username or password.";
            }
        } else {
            // User not found
            $errorString = "Invalid username or password.";
        }

        // Close the statement
        $stmt->close();
    }

$mysqli->close();

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login no Correio Sesi</title>
  <!-- Bootstrap CSS CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">Correio Sesi</a>
    
    <?php if ($is_logged_in): ?>
              <!-- User Dropdown if logged in -->
              <ul class="nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo $username; ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="userDropdown">
                        <!-- <li><a class="dropdown-item" href="#">Profile</a></li> -->
                        <li>
                            <!-- Logout Form -->
                            <form method="post" action="logout.php">
                                <button type="submit" class="dropdown-item">Sair</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        <?php else: ?>
            <!-- Login and Register Buttons -->
            <div class="d-flex">
                <a href="login.php" class="btn btn-outline-light me-2">Login</a>
                <a href="register.php" class="btn btn-light">Registre-se</a>
            </div>
        <?php endif; ?>
  </div>
</nav>

<!-- Main Container for Login Form -->
<div class="container mt-5 pt-5">
  <div class="col-lg-6 col-md-8 col-sm-12 mx-auto">
    <form action="login.php" method="post" class="border rounded shadow p-4">
      <h4>Login</h4>
      
         <!-- PHP Conditional to display error message -->
         <?php if (!empty($errorString)): ?>
        <div class="mb-3 text-danger">
          <?php echo $errorString; ?>
        </div>
      <?php endif; ?>

      <div class="mb-3">
        <label for="username" class="form-label">Nome de usuário ou email (Teams):</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Digite seu nome de usuário ou e-mail" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Senha:</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
  </div>
</div>

<!-- Footer with Bootstrap text color classes -->
<footer class="mt-4 text-center text-muted">
  <div class="container">
    <p>Uma produção Felipe Cezar Paz e Bruno Santos.</p>
    <p>&copy; <script>document.write(new Date().getFullYear());</script> Correio Sesi</p>
  </div>
</footer>

<!-- Bootstrap JS and Popper.js CDN (Required for Bootstrap components) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
