<?php include 'includes/header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">Search Results</h2>
    <div id="searchResults" class="row g-4">
        <!-- Results will be loaded dynamically -->
    </div>
</div>

<script src="/assets/js/api.js"></script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const query = urlParams.get('query');

    if (!query) {
        document.getElementById('searchResults').innerHTML = '<div class="col-12"><p>Please enter a search term.</p></div>';
        return;
    }

    try {
        const results = await API.search(query);
        const container = document.getElementById('searchResults');

        if (!results || !results.results || results.results.length === 0) {
            container.innerHTML = '<div class="col-12"><p>No results found.</p></div>';
            return;
        }

        results.results.forEach(item => {
            if (!item.title && !item.name) return;

            const title = item.title || item.name;
            const poster = item.poster_path 
                ? `https://image.tmdb.org/t/p/w300${item.poster_path}`
                : 'assets/image/no-poster.jpeg';
            const type = item.media_type;
            const link = type === 'movie' 
                ? `movie_overview.php?id=${item.id}`
                : `play.php?id=${item.id}&type=tv`;

            container.innerHTML += `
                <div class="col-md-3">
                    <div class="card h-100 shadow-sm">
                        <a href="${link}">
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
        console.error('Error searching:', error);
        document.getElementById('searchResults').innerHTML = 
            '<div class="col-12"><p>Error performing search. Please try again later.</p></div>';
    }
});
</script>

<?php include 'includes/footer.php'; ?>
