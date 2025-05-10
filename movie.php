<?php include 'includes/header.php'; ?>

<style>
  body { background: #181c24; color: #fff; font-family: 'Inter', Arial, sans-serif; }
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
</style>

<div class="container mt-4">
  <h3 class="mb-4">🎬 Featured Movies</h3>
  <div id="movieGrid" class="row g-3">
    <!-- Movies will be loaded dynamically -->
  </div>
  <nav aria-label="Page navigation">
    <ul id="pagination" class="pagination justify-content-center mt-4">
      <!-- Pagination will be loaded dynamically -->
    </ul>
  </nav>
</div>

<script src="/assets/js/api.js"></script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const page = parseInt(urlParams.get('page')) || 1;
    
    try {
        const data = await API.getPopular('movie', page);
        if (!data || !data.results) throw new Error('No data available');

        const container = document.getElementById('movieGrid');
        container.innerHTML = '';

        data.results.slice(0, 22).forEach(movie => {
            const title = movie.title || 'Untitled';
            const poster = movie.poster_path 
                ? `https://image.tmdb.org/t/p/w300${movie.poster_path}`
                : 'assets/img/no-image.jpg';
            
            container.innerHTML += `
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="card movie-card-unique position-relative">
                        <a href="movie_overview.php?id=${movie.id}" class="text-decoration-none">
                            <img src="${poster}" class="card-img-top rounded shadow-sm" alt="${title}">
                        </a>
                        <div class="card-body p-2">
                            <div class="card-title">${title}</div>
                        </div>
                    </div>
                </div>
            `;
        });

        // Update pagination
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';
        
        if (page > 1) {
            pagination.innerHTML += `
                <li class="page-item">
                    <a class="page-link" href="?page=${page - 1}">Previous</a>
                </li>
            `;
        }

        for (let i = Math.max(1, page - 2); i <= Math.min(data.total_pages, page + 2); i++) {
            pagination.innerHTML += `
                <li class="page-item ${i === page ? 'active' : ''}">
                    <a class="page-link" href="?page=${i}">${i}</a>
                </li>
            `;
        }

        if (page < data.total_pages) {
            pagination.innerHTML += `
                <li class="page-item">
                    <a class="page-link" href="?page=${page + 1}">Next</a>
                </li>
            `;
        }
    } catch (error) {
        console.error('Error loading movies:', error);
        document.getElementById('movieGrid').innerHTML = 
            '<div class="col-12"><p>Error loading movies. Please try again later.</p></div>';
    }
});
</script>

<?php include 'includes/footer.php'; ?>
