document.addEventListener('DOMContentLoaded', async () => {
    const grids = document.querySelectorAll('[data-recipe-grid]');
    if (!grids.length) return;

    try {
        const response = await fetch('get_recipes.php');
        if (!response.ok) throw new Error('Recipe request failed');
        const recipes = await response.json();

        grids.forEach((grid) => {
            const visibleRecipes = grid.dataset.recipeGrid === 'featured' ? recipes.slice(0, 6) : recipes;
            grid.innerHTML = '';
            visibleRecipes.forEach((recipe) => grid.insertAdjacentHTML('beforeend', recipeCard(recipe)));
            if (!visibleRecipes.length) {
                grid.innerHTML = '<p class="text-muted py-5">No recipes have been added yet.</p>';
            }
        });

        document.querySelectorAll('.recipe-grid-2').forEach((grid) => {
            grid.style.display = 'none';
        });

        const countText = document.getElementById('recipe-count-text');
        if (countText) countText.textContent = `Showing ${recipes.length} recipes`;
        setupRecipeFilters();
        setupRecipeSearch();
    } catch (error) {
        grids.forEach((grid) => {
            grid.innerHTML = '<p class="text-danger py-5">Recipes could not be loaded. Check that MySQL is running.</p>';
        });
    }
});

function recipeCard(recipe) {
    const category = recipe.category.toLowerCase().replaceAll(' ', '-');
    const image = recipe.image || 'images/recipe card.jpg';
    return `
        <article class="recipe-card" data-category="${escapeHtml(category)}" data-search="${escapeHtml(`${recipe.title} ${recipe.chef} ${recipe.cuisine} ${recipe.ingredients}`)}">
            <div class="recipe-image">
                <img src="${escapeHtml(image)}" alt="${escapeHtml(recipe.title)}">
                <span class="recipe-badge">${escapeHtml(recipe.cuisine)}</span>
            </div>
            <div class="recipe-info">
                <h3>${escapeHtml(recipe.title)}</h3>
                <p class="recipe-author">by ${escapeHtml(recipe.chef)}</p>
                <div class="recipe-meta mb-3">
                    <span><i class="fas fa-tag"></i> ${escapeHtml(recipe.category)}</span>
                </div>
                <div class="d-flex justify-content-end">
                    <a href="viewrecipe.php?id=${encodeURIComponent(recipe.id)}" class="btn btn-primary btn-sm rounded-pill px-3">View Recipe</a>
                </div>
            </div>
        </article>`;
}

function setupRecipeFilters() {
    const cards = [...document.querySelectorAll('[data-recipe-grid] .recipe-card')];
    document.querySelectorAll('.filter-btn').forEach((button) => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.filter-btn').forEach((item) => item.classList.remove('active', 'btn-primary'));
            button.classList.add('active', 'btn-primary');
            const filter = button.dataset.filter;
            let visible = 0;
            cards.forEach((card) => {
                const show = filter === 'all' || card.dataset.category === filter || (filter === 'appetizers' && card.dataset.category === 'appetizer');
                card.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            const countText = document.getElementById('recipe-count-text');
            if (countText) countText.textContent = `Showing ${visible} recipes`;
        });
    });
}

function setupRecipeSearch() {
    const input = document.querySelector('.search-input');
    const button = document.querySelector('.search-btn');
    if (!input) return;
    const search = () => {
        const value = input.value.trim().toLowerCase();
        document.querySelectorAll('[data-recipe-grid] .recipe-card').forEach((card) => {
            card.style.display = !value || card.dataset.search.toLowerCase().includes(value) ? '' : 'none';
        });
    };
    input.addEventListener('input', search);
    if (button) button.addEventListener('click', search);
}

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>'"]/g, (character) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;'
    }[character]));
}