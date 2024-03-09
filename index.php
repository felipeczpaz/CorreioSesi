<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bootstrap Form Example</title>
  <!-- Bootstrap CSS CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
  <div class="container">
    <a class="navbar-brand" href="#">Your Logo</a>
  </div>
</nav>

<!-- Form Container -->
<div class="container mt-5">
  <form>
    <div class="mb-3">
      <label for="postText" class="form-label">Post Content:</label>
      <textarea class="form-control" id="postText" rows="3" style="resize: none;" placeholder="Type your post here"></textarea>
    </div>
    <div class="mb-3 form-check">
      <input type="checkbox" class="form-check-input" id="anonymousCheck">
      <label class="form-check-label" for="anonymousCheck">Anonymous</label>
    </div>
    <button type="submit" class="btn btn-primary">Post</button>
  </form>
</div>

<!-- Footer -->
<footer class="mt-4 text-center">
  <div class="container">
    <p>&copy; 2024 Your Website Name</p>
  </div>
</footer>

<!-- Bootstrap JS and Popper.js CDN (Required for Bootstrap components) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
