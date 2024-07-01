<?php
session_start();

require_once('connection.php');

function isUserLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function getUsername(): string
{
    return $_SESSION['username'] ?? '';
}

function getPosts(mysqli $mysqli): array
{
    $sql = "SELECT * FROM posts";
    $result = $mysqli->query($sql);

    if ($result) {
        $posts = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
        return $posts;
    }

    return [];
}

function getUsernameById(mysqli $mysqli, int $userId): string
{
    if (empty($userId)) {
        return "Unknown User";
    }

    $sql = "SELECT username FROM users WHERE user_id = ?";
    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $row['username'];
        }

        $stmt->close();
    }

    return "Unknown User";
}

$isLoggedIn = isUserLoggedIn();
$username = getUsername();
$posts = getPosts($mysqli);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Correio Sesi - Página Inicial</title>
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

            <?php if (!$isLoggedIn): ?>
            <div class="mt-3 text-center">Faça login para postar</div>
            <div class="text-center mb-4">
                <a href="register.php" class="btn btn-primary mt-2">Registre-se</a>
                <a href="login.php" class="btn btn-outline-primary mt-2">Login</a>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-6 col-md-8 col-sm-12">
            <?php foreach ($posts as $post): ?>
                <div class="card mb-4 shadow">
                    <div class="card-body">
                        <?php
                        $authorName = getUsernameById($mysqli, $post['author_id']);
                        $cardTitle = $post['is_anonymous'] ? 'Anônimo' : '@' . $authorName;
                        ?>
                        <h5 class="card-title"><?= htmlspecialchars($cardTitle) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($post['post_content']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<footer class="mt-4 text-center text-muted">
    <div class="container">
        <p>Uma produção Felipe Cezar Paz e Bruno Santos.</p>
        <p>&copy; <script>document.write(new Date().getFullYear());</script> Correio Sesi</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
$mysqli->close();
?>
