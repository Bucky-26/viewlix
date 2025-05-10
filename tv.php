<?php include 'includes/header.php'; ?>

<style>
  body { background: #181c24; color: #fff; font-family: 'Inter', Arial, sans-serif; }
  .tv-card {
    background: rgba(60, 65, 100, 0.85);
    border-radius: 22px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    transition: transform 0.18s, box-shadow 0.18s;
    border: none;
    height: 100%;
  }
  .tv-card:hover {
    transform: translateY(-8px) scale(1.04);
    box-shadow: 0 8px 32px rgba(108,99,255,0.18);
  }
  .tv-card img {
    border-radius: 22px 22px 0 0;
    height: 260px;
    object-fit: cover;
    width: 100%;
  }
  .tv-card .card-title {
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
</style>

<div class="container mt-5">
  <h2 class="mb-4">📺 Featured TV Shows</h2>
  <div id="tvGrid" class="row g-4">
    <!-- TV shows will be loaded dynamically -->
  </div>
</div>

<script src="/assets/js/api.js"></script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
    try {
        const data = await API.getPopular('tv');
        if (!data || !data.results) throw new Error('No data available');

        const container = document.getElementById('tvGrid');
        container.innerHTML = '';

        data.results.forEach(show => {
            const title = show.name || 'Untitled';
            const poster = show.poster_path 
                ? `https://image.tmdb.org/t/p/w300${show.poster_path}`
                : 'assets/image/no-poster.jpeg';
            
            container.innerHTML += `
                <div class="col-md-3">
                    <div class="card tv-card">
                        <a href="tv_overview.php?id=${show.id}">
                            <img src="${poster}" class="card-img-top" alt="${title}">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title text-center">${title}</h5>
                        </div>
                    </div>
                </div>
            `;
        });
    } catch (error) {
        console.error('Error loading TV shows:', error);
        document.getElementById('tvGrid').innerHTML = 
            '<div class="col-12"><p>Error loading TV shows. Please try again later.</p></div>';
    }
});
</script>

<?php include 'includes/footer.php'; ?>
