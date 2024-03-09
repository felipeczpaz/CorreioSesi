<?php
// Start the session (if not already started)
session_start();

// Assume some logic to check if the user is logged in
$is_logged_in = isset($_SESSION['user_id']);

$username = $is_logged_in ? $_SESSION['username'] : '';
$email='';

$errorString = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  require_once('connection.php');

  // Retrieve user input from the form
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirmPassword'];

// Perform basic validation
    // Validate username
    if (empty($username)) {
      $errorString .= "Nome de usuário é obrigatório.";
  }

  // Validate email
  if (empty($email)) {
      $errorString .= "O e-mail é obrigatório.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errorString .= "Formato de email inválido.";
  } elseif (substr(strrchr($email, '@'), 1) !== "sesisenaipr.org.br") {
    $errorString .= "Domínio inválido, somente emails @sesisenaipr.org.br são permitidos.";
  }

  // Validate password
  if (empty($password)) {
      $errorString .= "A senha é obrigatória.";
  } elseif (strlen($password) < 6) {
      $errorString .= "A senha deve ter pelo menos 6 caracteres.";
  }

  // Confirm password
  if ($password !== $confirmPassword) {
      $errorString .= "As senhas não coincidem.";
  }

  // If there are no errors, insert user data into the database
  if (empty($errorString)) {
      // Hash the password before storing it in the database
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      // Prepare and execute the SQL statement to insert user data
      $stmt = $mysqli->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $username, $email, $hashedPassword);

      if ($stmt->execute()) {
                    $userid = $stmt->insert_id;

          // Start a session
          session_start();

          // Set session variables
          $_SESSION['user_id'] = $userid;
          $_SESSION['username'] = $username;
          $_SESSION['email'] = $email;

          // Redirect to a success page or perform other actions
          header('Location: index.php');
          exit();
      } else {
        // Registration failed
        $errorString .= "Falha ao registrar usuário.";
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
  <title>Registro no Correio Sesi</title>
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

<!-- Main Container for Registration Form -->
<div class="container mt-5 pt-5">
  <div class="col-lg-6 col-md-8 col-sm-12 mx-auto">
    <form action="register.php" method="post" class="border rounded shadow p-4">
      <h4>Registre-se</h4>

         <!-- PHP Conditional to display error message -->
         <?php if (!empty($errorString)): ?>
        <div class="mb-3 text-danger">
          <?php echo $errorString; ?>
        </div>
      <?php endif; ?>

      <div class="mb-3">
        <label for="username" class="form-label">Nome de usuário:</label>
        <input type="text" class="form-control" id="username" name="username" value="<?php echo $username ?>" placeholder="Digite seu nome de usuário" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Endereço de email:</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo $email ?>" placeholder="Digite seu e-mail (@sesisenaipr.org.br)" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Senha:</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha (mínimo de 6 dígitos)" required>
      </div>
      <div class="mb-3">
        <label for="confirmPassword" class="form-label">Confirme sua senha:</label>
        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirme sua senha" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Registre-se</button>
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
