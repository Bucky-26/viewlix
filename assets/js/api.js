const API = {
    async getMovie(id) {
        try {
            const response = await fetch(`/api/movie/${id}?type=movie`);
            if (!response.ok) throw new Error('Movie not found');
            return await response.json();
        } catch (error) {
            console.error('Error fetching movie:', error);
            return null;
        }
    },

    async getTV(id) {
        try {
            const response = await fetch(`/api/tv/${id}`);
            if (!response.ok) throw new Error('TV show not found');
            return await response.json();
        } catch (error) {
            console.error('Error fetching TV show:', error);
            return null;
        }
    },

    async getSimilar(id, type) {
        try {
            const response = await fetch(`/api/similar/${id}?type=${type}`);
            if (!response.ok) throw new Error('Similar content not found');
            return await response.json();
        } catch (error) {
            console.error('Error fetching similar content:', error);
            return null;
        }
    },

    async getPopular(type, page = 1) {
        try {
            const response = await fetch(`/api/popular/${type}?page=${page}`);
            if (!response.ok) throw new Error('Popular content not found');
            return await response.json();
        } catch (error) {
            console.error('Error fetching popular content:', error);
            return null;
        }
    },

    async search(query) {
        try {
            const response = await fetch(`/api/search?query=${encodeURIComponent(query)}`);
            if (!response.ok) throw new Error('Search failed');
            return await response.json();
        } catch (error) {
            console.error('Error searching:', error);
            return null;
        }
    },

    async getCredits(id, type) {
        try {
            const response = await fetch(`/api/credits/${id}?type=${type}`);
            if (!response.ok) throw new Error('Credits not found');
            return await response.json();
        } catch (error) {
            console.error('Error fetching credits:', error);
            return null;
        }
    }
}; 