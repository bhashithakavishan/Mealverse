document.addEventListener('DOMContentLoaded', async () => {
    const id = new URLSearchParams(window.location.search).get('id');
    if (!id) {
        showRecipeMessage('Choose a recipe from the recipe page to view its details.');
        return;
    }

    try {
        const response = await fetch(`get_recipes.php?id=${encodeURIComponent(id)}`);
        if (!response.ok) throw new Error('Recipe not found');
        renderRecipe(await response.json());
    } catch (error) {
        showRecipeMessage('This recipe could not be found.');
    }
});

function renderRecipe(recipe) {
    document.title = `${recipe.title} - MealVerse`;
    document.getElementById('recipe-image').src = recipe.image || 'css/images/recipe card.jpg';
    document.getElementById('recipe-image').alt = recipe.title;
    document.getElementById('recipe-category').textContent = recipe.category;
    document.getElementById('recipe-title').textContent = recipe.title;
    document.getElementById('recipe-chef').textContent = `by ${recipe.chef}`;
    document.getElementById('recipe-date').textContent = new Date(recipe.created_at).toLocaleDateString();
    document.getElementById('recipe-description').textContent = `${recipe.title}, shared by ${recipe.chef}.`;
    document.getElementById('recipe-chef-name').textContent = recipe.chef;

    document.getElementById('recipe-badges').innerHTML = `<span class="recipe-badge-pill">${escapeHtml(recipe.category)}</span><span class="recipe-badge-pill">${escapeHtml(recipe.cuisine)}</span>`;
    document.getElementById('ingredients-list').innerHTML = lines(recipe.ingredients).map((item) => `<div class="col-md-6 d-flex align-items-center gap-2 text-dark"><span class="bullet-dot"></span>${escapeHtml(item)}</div>`).join('');
    document.getElementById('instructions-list').innerHTML = lines(recipe.instructions).map((step, index) => `<div class="d-flex gap-3"><div class="step-badge">${index + 1}</div><p class="text-muted mb-0">${escapeHtml(step.replace(/^\d+\.\s*/, ''))}</p></div>`).join('');
}

function lines(value) {
    return String(value || '').split(/\r?\n/).map((line) => line.trim()).filter(Boolean);
}

function showRecipeMessage(text) {
    const target = document.getElementById('recipe-title');
    if (target) target.textContent = text;
}

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>'"]/g, (character) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;'
    }[character]));
}