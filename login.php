<?php
session_start();

function isUserLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function getUsername(): string
{
    return $_SESSION['username'] ?? '';
}

function validateLoginForm(string $username, string $password): string
{
    if (empty($username) || empty($password)) {
        return "Login e senha são obrigatórios.";
    }
    return '';
}

function authenticateUser(mysqli $mysqli, string $username, string $password): string
{
    $stmt = $mysqli->prepare("SELECT user_id, username, email, password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId, $dbUsername, $dbEmail, $dbPassword);
        $stmt->fetch();

        if (password_verify($password, $dbPassword)) {
            session_start();
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $dbUsername;
            $_SESSION['email'] = $dbEmail;

            header('Location: index.php');
            exit();
        } else {
            return "Login ou senha inválidos.";
        }
    } else {
        return "Login ou senha inválidos.";
    }

    $stmt->close();
    return '';
}

$isLoggedIn = isUserLoggedIn();
$username = getUsername();
$errorString = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('connection.php');
    $username = $_POST['username'];
    $password = $_POST['password'];

    $errorString = validateLoginForm($username, $password);

    if (empty($errorString)) {
        $errorString = authenticateUser($mysqli, $username, $password);
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">Correio Sesi</a>
        <?php if ($isLoggedIn): ?>
            <ul class="nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= htmlspecialchars($username); ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="userDropdown">
                        <li>
                            <form method="post" action="logout.php">
                                <button type="submit" class="dropdown-item">Sair</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        <?php else: ?>
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
            <?php if (!empty($errorString)): ?>
                <div class="mb-3 text-danger">
                    <?= htmlspecialchars($errorString); ?>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
