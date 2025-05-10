<?php
include 'includes/header.php';
include 'api/tmdb.php';

$movie_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$movie_id) {
    echo '<div class="container mt-4"><div class="alert alert-danger">Invalid movie ID.</div></div>';
    include 'includes/footer.php';
    exit;
}

$movie = tmdbRequest("movie/{$movie_id}");
if ($movie === null) {
    echo '<div class="container mt-4"><div class="alert alert-danger">Unable to fetch movie details. Please try again later.</div></div>';
    include 'includes/footer.php';
    exit;
}

$credits = tmdbRequest("movie/{$movie_id}/credits");
$directors = [];
$writers = [];
if ($credits && isset($credits['crew'])) {
    foreach ($credits['crew'] as $crew) {
        if ($crew['job'] === 'Director') $directors[] = $crew['name'];
        if (in_array($crew['job'], ['Writer', 'Screenplay'])) $writers[] = $crew['name'];
    }
}

$poster = $movie['poster_path'] ? "https://image.tmdb.org/t/p/w500" . $movie['poster_path'] : "assets/img/no-image.jpg";
$backdrop = $movie['backdrop_path'] ? "https://image.tmdb.org/t/p/original" . $movie['backdrop_path'] : $poster;
$title = $movie['title'] ?? 'Untitled';
$tagline = $movie['tagline'] ?? '';
$overview = $movie['overview'] ?? '';
$release_date = $movie['release_date'] ?? '';
$runtime = $movie['runtime'] ? $movie['runtime'] . ' min' : '';
$genres = isset($movie['genres']) ? array_map(function($g){return $g['name'];}, $movie['genres']) : [];
$play_url = "play.php?id={$movie_id}&type=movie";
?>

<style>
.tmdb-backdrop {
  background: linear-gradient(rgba(20,20,20,0.85), rgba(20,20,20,0.85)), url('<?= $backdrop ?>') center/cover no-repeat;
  color: #fff;
  padding: 40px 0 30px 0;
  margin-bottom: 30px;
  border-radius: 0 0 24px 24px;
}
.tmdb-poster {
  max-width: 320px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.5);
  border-radius: 12px;
}
.tmdb-info h2 {
  font-size: 2.5rem;
  font-weight: bold;
}
.tmdb-badges .badge {
  font-size: 1rem;
  margin-right: 6px;
}
.tmdb-crew {
  font-size: 1rem;
  color: #eee;
}
</style>

<div class="tmdb-backdrop">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-3 text-center mb-3 mb-md-0">
        <img src="<?= $poster ?>" alt="<?= htmlspecialchars($title) ?>" class="img-fluid tmdb-poster">
      </div>
      <div class="col-md-9 tmdb-info">
        <h2><?= htmlspecialchars($title) ?> <?php if(isset($movie['release_date'])): ?><span class="text-light fs-5">(<?= substr($movie['release_date'], 0, 4) ?>)</span><?php endif; ?></h2>
        <?php if ($tagline): ?>
          <p class="fst-italic text-secondary">"<?= htmlspecialchars($tagline) ?>"</p>
        <?php endif; ?>
        <div class="tmdb-badges mb-2">
          <?php if ($genres): ?>
            <span class="badge bg-primary"><?= implode('</span> <span class="badge bg-primary">', $genres) ?></span>
          <?php endif; ?>
          <?php if ($runtime): ?>
            <span class="badge bg-dark">⏱ <?= $runtime ?></span>
          <?php endif; ?>
          <?php if ($release_date): ?>
            <span class="badge bg-secondary">📅 <?= $release_date ?></span>
          <?php endif; ?>
        </div>
        <h5 class="mt-4">Overview</h5>
        <p class="fs-5"><?= nl2br(htmlspecialchars($overview)) ?></p>
        <div class="tmdb-crew mt-4">
          <?php if ($directors): ?>
            <div><strong>Director:</strong> <?= htmlspecialchars(implode(', ', $directors)) ?></div>
          <?php endif; ?>
          <?php if ($writers): ?>
            <div><strong>Writer:</strong> <?= htmlspecialchars(implode(', ', array_unique($writers))) ?></div>
          <?php endif; ?>
        </div>
        <a href="<?= $play_url ?>" class="btn btn-danger btn-lg mt-4"><i class="bi bi-play-circle"></i> Play Now</a>
      </div>
    </div>
  </div>
</div>

<?php
// Fetch recommendations
$recommend = tmdbRequest("movie/{$movie_id}/recommendations");
if (!empty($recommend['results'])): ?>
<div class="container mt-5">
  <h4>Suggested Movies</h4>
  <div class="row g-3">
    <?php foreach ($recommend['results'] as $rec):
      $rec_title = $rec['title'] ?? $rec['name'] ?? 'Untitled';
      $rec_poster = (isset($rec['poster_path']) && $rec['poster_path']) ? "https://image.tmdb.org/t/p/w300" . $rec['poster_path'] : "assets/image/no-poster.jpeg";
      $rec_id = $rec['id'];
    ?>
    <div class="col-6 col-md-3 col-lg-2">
      <div class="card h-100 shadow-sm">
        <a href="movie_overview.php?id=<?= $rec_id ?>">
          <img src="<?= $rec_poster ?>" class="card-img-top" alt="<?= htmlspecialchars($rec_title) ?>">
        </a>
        <div class="card-body p-2">
          <p class="card-title mb-0 text-center small fw-bold text-dark"><?= htmlspecialchars($rec_title) ?></p>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
