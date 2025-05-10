<?php
include 'includes/header.php';
include 'api/tmdb.php';

// Fetch data
$upcoming = tmdbRequest("movie/upcoming");
$upcoming = $upcoming['results'] ?? [];
$top_movies = tmdbRequest("movie/top_rated");
$top_movies = $top_movies['results'] ?? [];
$top_tv = tmdbRequest("tv/top_rated");
$top_tv = $top_tv['results'] ?? [];
?>
<style>
body { background: #181c24; color: #fff; font-family: 'Inter', Arial, sans-serif; }
.custom-navbar {
  background: linear-gradient(90deg, #3a3f51 0%, #23263a 100%);
  box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}
.custom-navbar .navbar-brand img { height: 48px; }
.hero-unique {
  background: linear-gradient(120deg, #3a3f51 60%, #23263a 100%);
  border-radius: 0 0 32px 32px;
  padding: 60px 0 40px 0;
  text-align: center;
  margin-bottom: 2rem;
}
.hero-unique h1 { font-size: 2.8rem; font-weight: 700; margin-bottom: 0.5rem; }
.hero-unique p { font-size: 1.2rem; color: #bfc9d9; }
.hero-unique .search-bar {
  max-width: 500px;
  margin: 2rem auto 0 auto;
  display: flex;
  gap: 0.5rem;
}
.hero-unique input[type=text] {
  border-radius: 8px;
  border: none;
  padding: 0.8rem 1rem;
  width: 100%;
  font-size: 1.1rem;
}
.hero-unique button {
  border-radius: 8px;
  background: #6c63ff;
  color: #fff;
  border: none;
  padding: 0.8rem 1.5rem;
  font-size: 1.1rem;
  font-weight: 600;
  transition: background 0.2s;
}
.hero-unique button:hover { background: #554ee0; }
.section-title { font-size: 1.5rem; font-weight: 600; margin: 2.5rem 0 1rem 0; }
.scroll-row {
  display: flex;
  overflow-x: auto;
  gap: 1.2rem;
  padding-bottom: 1rem;
  scrollbar-width: thin;
  scrollbar-color: #444 #23263a;
}
.movie-card-unique {
  background: rgba(60, 65, 100, 0.85);
  border-radius: 22px;
  min-width: 180px;
  max-width: 180px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.12);
  transition: transform 0.18s, box-shadow 0.18s;
  border: none;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-bottom: 10px;
}
.movie-card-unique:hover {
  transform: translateY(-8px) scale(1.04);
  box-shadow: 0 8px 32px rgba(108,99,255,0.18);
}
.movie-card-unique img {
  border-radius: 22px 22px 0 0;
  height: 260px;
  object-fit: cover;
  width: 100%;
}
.movie-card-unique .card-title {
  color: #fff;
  font-size: 1.05rem;
  font-weight: 700;
  margin: 0.7rem 0 0.2rem 0;
  text-align: center;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  width: 95%;
  min-height: 2.4em;
}
.scroll-row::-webkit-scrollbar {
  height: 6px;
  background: #23263a;
}
.scroll-row::-webkit-scrollbar-thumb {
  background: #444;
  border-radius: 8px;
}
.scroll-row::-webkit-scrollbar-track {
  background: #23263a;
}
.hero-carousel {
  width: 100%;
  min-height: 420px;
  margin-bottom: 2.5rem;
}
.hero-slide-bg {
  position: absolute;
  top: 0; left: 0; width: 100%; height: 100%;
  background-size: cover;
  background-position: center;
  z-index: 1;
  border-radius: 24px;
}
.hero-slide-overlay {
  position: absolute;
  top: 0; left: 0; width: 100%; height: 100%;
  background: linear-gradient(90deg, rgba(24,28,36,0.92) 40%, rgba(24,28,36,0.3) 100%);
  z-index: 2;
  border-radius: 24px;
}
.hero-slide-content {
  position: relative;
  z-index: 3;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 3rem 3rem 3rem 4rem;
  max-width: 60%;
}
@media (max-width: 900px) {
  .hero-slide-content { max-width: 100%; padding: 2rem; }
}
.hero-slide-title {
  font-size: 2.2rem;
  font-weight: 700;
  margin-bottom: 0.7rem;
}
.hero-slide-release {
  display: inline-block;
  background: #23263a;
  color: #fff;
  border-radius: 6px;
  font-size: 1rem;
  padding: 0.2rem 0.8rem;
  margin-bottom: 1rem;
}
.hero-slide-overview {
  font-size: 1.1rem;
  color: #e0e0e0;
  margin-bottom: 1.5rem;
}
.hero-slide-btn {
  background: #6c63ff;
  color: #fff;
  border: none;
  border-radius: 6px;
  font-size: 0.85rem;
  font-weight: 600;
  padding: 0.4rem 0.8rem;
  transition: background 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 0.3rem;
  text-decoration: none;
  width: 80px;
}
.hero-slide-btn:hover { 
  background: #554ee0;
  color: #fff;
}
.hero-slide-btn i { 
  font-size: 1rem;
}
.carousel-indicators [data-bs-target] { background-color: #fff; }
.hero-next-btn {
  background: none;
  border: none;
  color: #fff;
  font-size: 2.2rem;
  position: absolute;
  right: 2rem;
  bottom: 2rem;
  z-index: 4;
  cursor: pointer;
  transition: color 0.2s;
}
.hero-next-btn:hover { color: #6c63ff; }
.carousel-control-next, .carousel-control-prev {
  width: 60px;
  height: 60px;
  top: 50%;
  transform: translateY(-50%);
  opacity: 1;
  z-index: 5;
  cursor: pointer;
  pointer-events: auto;
}
.carousel-control-next-icon, .carousel-control-prev-icon {
  background-size: 60% 60%;
  width: 2.5rem;
  height: 2.5rem;
  filter: drop-shadow(0 2px 8px #000a);
  pointer-events: auto;
}
.carousel-control-next {
  right: 2rem;
}
.carousel-control-prev {
  left: 2rem;
}
</style>

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<div class="hero-unique">
  <h1>Welcome to Easy Movie</h1>
  <p>Discover, search, and watch the best movies and TV shows. Enjoy a modern, unique experience!</p>
  <form action="search.php" method="GET" class="search-bar">
    <input type="text" name="query" placeholder="Search for movies or TV shows..." required>
    <button type="submit">Search</button>
  </form>
</div>

<div class="container">
  <div class="section-title"><i class="bi bi-film"></i> Upcoming Movies</div>
  <div id="upcomingCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-inner">
      <?php foreach (array_slice($upcoming, 0, 8) as $i => $movie):
        $backdrop = $movie['backdrop_path'] ? "https://image.tmdb.org/t/p/original" . $movie['backdrop_path'] : ($movie['poster_path'] ? "https://image.tmdb.org/t/p/w500" . $movie['poster_path'] : "assets/image/no-poster.jpeg");
        $title = $movie['title'] ?? 'Untitled';
        $release = $movie['release_date'] ?? '';
        $overview = $movie['overview'] ?? '';
        $id = $movie['id'];
      ?>  
      <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>" style="min-height:420px;">
        <div class="hero-slide-bg" style="background-image:url('<?= $backdrop ?>');"></div>
        <div class="hero-slide-overlay"></div>
        <div class="hero-slide-content">
          <div class="hero-slide-title"><?= htmlspecialchars($title) ?></div>
          <div class="hero-slide-release" style="background:none; padding-left:0;">Release: <?= $release ?></div>
          <div class="hero-slide-overview"><?= htmlspecialchars($overview) ?></div>
          <a href="movie_overview.php?id=<?= $id ?>" class="hero-slide-btn btn-sm">View <i class="bi bi-chevron-right"></i></a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="carousel-indicators">
      <?php for ($i = 0; $i < min(8, count($upcoming)); $i++): ?>
        <button type="button" data-bs-target="#upcomingCarousel" data-bs-slide-to="<?= $i ?>" class="<?= $i === 0 ? 'active' : '' ?>" aria-current="<?= $i === 0 ? 'true' : 'false' ?>" aria-label="Slide <?= $i+1 ?>"></button>
      <?php endfor; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#upcomingCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#upcomingCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>

  <div class="section-title">⭐ Top Rated Movies</div>
  <div class="scroll-row">
    <?php foreach (array_slice($top_movies, 0, 14) as $movie):
      $poster = (isset($movie['poster_path']) && $movie['poster_path']) ? "https://image.tmdb.org/t/p/w300" . $movie['poster_path'] : "assets/image/no-poster.jpeg";
      $title = $movie['title'] ?? 'Untitled';
      $id = $movie['id'];
    ?>
      <div class="card movie-card-unique">
        <a href="movie_overview.php?id=<?= $id ?>">
          <img src="<?= $poster ?>" class="card-img-top" alt="<?= htmlspecialchars($title) ?>">
        </a>
        <div class="card-body p-2">
          <div class="card-title"><?= htmlspecialchars($title) ?></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="section-title">📺 Top Rated TV Shows</div>
  <div class="scroll-row">
    <?php foreach (array_slice($top_tv, 0, 14) as $tv):
      $poster = (isset($tv['poster_path']) && $tv['poster_path']) ? "https://image.tmdb.org/t/p/w300" . $tv['poster_path'] : "assets/image/no-poster.jpeg";
      $title = $tv['name'] ?? 'Untitled';
      $id = $tv['id'];
    ?>
      <div class="card movie-card-unique">
        <a href="tv_overview.php?id=<?= $id ?>">
          <img src="<?= $poster ?>" class="card-img-top" alt="<?= htmlspecialchars($title) ?>">
        </a>
        <div class="card-body p-2">
          <div class="card-title"><?= htmlspecialchars($title) ?></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php include 'includes/footer.php'; ?>