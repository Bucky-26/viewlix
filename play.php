<?php include 'includes/header.php'; ?>

<div class="container mt-5">
  <!-- Back to Home Button at Top Left -->
  <div class="row mb-2">
    <div class="col-12">
      <a href="index.php" class="btn btn-outline-secondary">← Back to Home</a>
    </div>
  </div>

  <!-- Video Player Section -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="ratio ratio-16x9 shadow">
        <iframe id="playerFrame" allowfullscreen loading="lazy"></iframe>
      </div>
    </div>
  </div>
  
  <!-- Movie Details Section -->
  <div class="row align-items-center g-0" style="display: flex; margin-top: 0; margin-bottom: 0;">
    <div class="col-lg-3 d-flex justify-content-center" style="padding-right: 4px;">
      <img id="posterImage" class="img-fluid rounded shadow" alt="Poster" style="max-width: 140px; margin-right: 0;">
    </div>
    <div class="col-lg-9 d-flex flex-column justify-content-start" style="padding-left: 4px;">
      <h2 id="title" class="fw-bold mb-2" style="margin-top: 0;"></h2>
      <p id="description" class="mb-2" style="margin-bottom: 0.5rem;"></p>
    </div>
  </div>

  <!-- Suggested Movies Section -->
  <div class="row mt-4">
    <div class="col-12">
      <h3 class="fw-bold">Suggested Movies</h3>
      <div id="suggestedContent" class="row">
        <!-- Content will be loaded dynamically -->
      </div>
    </div>
  </div>
</div>

<script src="/assets/js/api.js"></script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');
    const type = urlParams.get('type') || 'movie';

    if (!id) {
        alert('Invalid content ID');
        return;
    }

    try {
        // Load content details
        const content = type === 'movie' ? await API.getMovie(id) : await API.getTV(id);
        if (!content) throw new Error('Content not found');

        // Update player
        document.getElementById('playerFrame').src = `https://vidsrc.xyz/embed/${type}?tmdb=${id}`;

        // Update content details
        document.getElementById('posterImage').src = content.poster_path 
            ? `https://image.tmdb.org/t/p/w500${content.poster_path}`
            : 'assets/image/no-poster.jpeg';
        document.getElementById('posterImage').alt = content.title || content.name;

        document.getElementById('title').textContent = `${content.title || content.name} ${
            content.release_date ? `(${content.release_date.split('-')[0]})` : ''
        }`;
        document.getElementById('description').textContent = content.overview || 'No description available.';

        // Load similar content
        const similar = await API.getSimilar(id, type);
        if (similar && similar.results) {
            const suggestedContainer = document.getElementById('suggestedContent');
            similar.results.slice(0, 4).forEach(item => {
                const poster = item.poster_path 
                    ? `https://image.tmdb.org/t/p/w500${item.poster_path}`
                    : 'assets/image/no-poster.jpeg';
                const title = item.title || item.name;
                const year = item.release_date ? item.release_date.split('-')[0] : '';
                
                suggestedContainer.innerHTML += `
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            <img src="${poster}" class="card-img-top" alt="${title}" style="height: 250px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title">${title} ${year ? `(${year})` : ''}</h5>
                                <a href="?id=${item.id}&type=${type}" class="btn btn-outline-primary btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                `;
            });
        }
    } catch (error) {
        console.error('Error loading content:', error);
        alert('Error loading content. Please try again later.');
    }
});
</script>

<?php include 'includes/footer.php'; ?>
