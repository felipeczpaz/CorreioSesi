<?php
// Start the session (if not already started)
session_start();

require_once('connection.php');

// Assume some logic to check if the user is logged in
$is_logged_in = isset($_SESSION['user_id']);
$username = $is_logged_in ? $_SESSION['username'] : '';

// SQL query to retrieve all posts
$sql = "SELECT * FROM posts";

$posts = []; // Initialize posts as an empty array

// Execute the query
$result = $mysqli->query($sql);

// Check if the query was successful
if ($result) {
  // Fetch all rows as an associative array
  $posts = $result->fetch_all(MYSQLI_ASSOC);

  // Free the result set
  $result->free();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Correio Sesi - Página Inicial</title>
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

<!-- Main Container -->
<div class="container mt-5 pt-5">
  <div class="row">
    <!-- Form Container -->
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

      <!-- Text below the form -->
      <div class="mt-3 text-center">Faça login para postar</div>

<!-- Register and Login Buttons -->
<div class="text-center mb-4">
  <a href="register.php" class="btn btn-primary mt-2">Registre-se</a>
  <a href="login.php" class="btn btn-outline-primary mt-2">Login</a>
</div>
    </div>
    
    <!-- User Posts Container -->
    <div class="col-lg-6 col-md-8 col-sm-12">
    <?php foreach ($posts as $post) : ?>
        <div class="card mb-4 shadow">
            <div class="card-body">
            <?php
                    $author_name = getUsernameById($mysqli, $post['author_id']);
                    $card_title = $post['is_anonymous'] ? 'Anônimo' : '@' . $author_name;
                    ?>
                    <h5 class="card-title"><?= $card_title ?></h5>
                <p class="card-text"><?= htmlspecialchars($post['post_content']) ?></p>
            </div>
        </div>
    <?php endforeach; ?>
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


<?php

// Close the database connection
$mysqli->close();

// Function to get username by user_id
function getUsernameById($mysqli, $user_id)
{
  if (empty($user_id)) {
    return "Unknown User";
}
    $sql = "SELECT username FROM users WHERE user_id = $user_id";
    $result = $mysqli->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        return $row['username'];
    }

    return "Unknown User";
}

?>