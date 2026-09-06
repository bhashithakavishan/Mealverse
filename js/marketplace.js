document.addEventListener('DOMContentLoaded', async () => {
    const grid = document.querySelector('[data-marketplace-grid]');
    if (!grid) return;
    try {
        const response = await fetch('get_marketplace.php');
        if (!response.ok) throw new Error('Marketplace request failed');
        const items = await response.json();
        grid.innerHTML = items.length ? items.map(productCard).join('') : '<p class="text-muted py-5">No marketplace items are available.</p>';
        setupFilters();
        setupSearch();
        document.querySelectorAll('[data-add-to-cart]').forEach((button) => {
            button.addEventListener('click', async () => {
                button.disabled = true;
                try {
                    const body = new URLSearchParams({ action: 'add', item_id: button.dataset.addToCart, quantity: '1' });
                    const response = await fetch('cart_api.php', { method: 'POST', body });
                    const result = await response.json();
                    if (!response.ok || result.error) throw new Error(result.error || 'Unable to add this item.');
                    button.innerHTML = '<i class="fa-solid fa-check me-2"></i>Added';
                    setTimeout(() => { button.disabled = false; button.innerHTML = '<i class="fa-solid fa-cart-plus me-2"></i>Add to Cart'; }, 1200);
                } catch (error) {
                    button.disabled = false;
                    alert(error.message);
                }
            });
        });
    } catch (error) {
        grid.innerHTML = '<p class="text-danger py-5">Marketplace items could not be loaded.</p>';
    }
});

function productCard(item) {
    return `<div class="col-lg-3 col-md-6 product-item" data-category="${escapeHtml(item.category)}" data-search="${escapeHtml(`${item.name} ${item.category} ${item.description}`)}">
        <div class="recipe-card h-100 d-flex flex-column">
            <div class="recipe-image"><img src="${escapeHtml(item.image)}" alt="${escapeHtml(item.name)}"><span class="recipe-badge">${escapeHtml(item.category)}</span></div>
            <div class="recipe-info flex-grow-1 d-flex flex-column">
                <h3>${escapeHtml(item.name)}</h3><p class="recipe-author mb-2">${escapeHtml(item.description)}</p>
                <div class="d-flex justify-content-between align-items-center mb-3 mt-auto"><h4 class="text-primary fw-bold mb-0">$${Number(item.price).toFixed(2)}</h4></div>
                <button class="btn btn-primary w-100 rounded-pill fw-semibold" data-add-to-cart="${item.id}"><i class="fa-solid fa-cart-plus me-2"></i>Add to Cart</button>
            </div>
        </div>
    </div>`;
}

function setupFilters() {
    document.querySelectorAll('.filter-btn').forEach((button) => button.addEventListener('click', () => {
        document.querySelectorAll('.filter-btn').forEach((item) => item.classList.remove('active', 'btn-primary'));
        button.classList.add('active', 'btn-primary');
        const filter = button.dataset.filter;
        document.querySelectorAll('.product-item').forEach((item) => { item.style.display = filter === 'all' || item.dataset.category === filter ? '' : 'none'; });
    }));
}

function setupSearch() {
    const input = document.querySelector('.search-input');
    const search = () => document.querySelectorAll('.product-item').forEach((item) => { item.style.display = item.dataset.search.toLowerCase().includes(input.value.toLowerCase()) ? '' : 'none'; });
    input?.addEventListener('input', search);
    document.querySelector('.search-btn')?.addEventListener('click', search);
}

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>'"]/g, (character) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;' }[character]));
}
