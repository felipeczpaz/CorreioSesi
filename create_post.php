<?php
session_start();

require_once('connection.php');

function isUserLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function getUserId(): int
{
    return $_SESSION['user_id'] ?? 0;
}

function getUsername(): string
{
    return $_SESSION['username'] ?? '';
}

function handleFormSubmission(mysqli $mysqli, int $userId): string
{
    $errorString = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!isUserLoggedIn()) {
            header('Location: register.php');
            exit;
        }

        $authorId = $userId;
        $postText = $_POST["postText"];
        $isAnonymous = isset($_POST['anonymousCheck']);
        $isAnonymousString = $isAnonymous ? '1' : '0';

        $sql = "INSERT INTO posts (author_id, is_anonymous, post_content) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("iss", $authorId, $isAnonymousString, $postText);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                header("Location: index.php");
            } else {
                $errorString .= "Error creating post";
            }

            $stmt->close();
        } else {
            $errorString .= "Error creating post";
        }

        $mysqli->close();
    }

    return $errorString;
}

$isLoggedIn = isUserLoggedIn();
$userId = getUserId();
$username = getUsername();
$errorString = handleFormSubmission($mysqli, $userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Correio Sesi - Criar Novo Post</title>
    <!-- Bootstrap CSS CDN -->
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
                        <?php echo htmlspecialchars($username); ?>
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

<!-- Main Container -->
<div class="container mt-5 pt-5">
    <div class="row">
        <div class="col-lg-6 col-md-8 col-sm-12 mx-auto">
            <form action="create_post.php" method="post" class="border rounded shadow p-4">
                <h4>Conte sua fofoca</h4>
                <?php if (!empty($errorString)): ?>
                    <div class="mb-3 text-danger">
                        <?php echo htmlspecialchars($errorString); ?>
                    </div>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="postText" class="form-label">Publicar conteúdo:</label>
                    <textarea class="form-control" id="postText" name="postText" rows="3" style="resize: none;" placeholder="Digite sua postagem aqui"></textarea>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="anonymousCheck" name="anonymousCheck">
                    <label class="form-check-label" for="anonymousCheck">Anônimo</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Postar</button>
            </form>
        </div>
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
