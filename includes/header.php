<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MyMovies</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="/assets/css/bootstrap.css" rel="stylesheet">
  <link href="/assets/css/style.css" rel="stylesheet">
  <script src="/assets/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php"><img src="assets\image\easy movie1.png" width="200" alt=""></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navLinks">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navLinks">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="movie.php">Movies</a></li>
        <li class="nav-item"><a class="nav-link" href="tv.php">TV Shows</a></li>
      </ul>
      <form class="d-flex" action="search.php" method="GET">
        <input class="form-control me-2" type="search" name="query" placeholder="Search movies..." required>
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>

</body>
