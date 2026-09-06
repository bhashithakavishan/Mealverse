document.addEventListener('DOMContentLoaded', () => {
    const list = document.getElementById('cart-items');
    const total = document.getElementById('cart-total');
    const count = document.getElementById('cart-count');
    const empty = document.getElementById('cart-empty');
    const summary = document.getElementById('cart-summary');

    async function loadCart() {
        const cart = await fetch('cart_api.php').then((response) => response.json());
        list.innerHTML = cart.items.map((item) => `<div class="d-flex align-items-center gap-3 py-3 border-bottom flex-wrap">
            <img src="${item.image}" alt="${item.name}" class="rounded-3" style="width: 90px; height: 90px; object-fit: cover;">
            <div class="flex-grow-1"><h6 class="fw-bold mb-1">${item.name}</h6><p class="text-muted small mb-0">$${Number(item.price).toFixed(2)} each</p></div>
            <div class="d-flex align-items-center gap-2 border rounded-pill px-2 py-1"><button class="btn btn-sm border-0 p-1" data-change="-1" data-id="${item.id}"><i class="fas fa-minus"></i></button><span class="fw-bold px-2">${item.quantity}</span><button class="btn btn-sm border-0 p-1" data-change="1" data-id="${item.id}"><i class="fas fa-plus"></i></button></div>
            <div class="text-end" style="min-width: 80px;"><span class="fw-bold text-primary">$${Number(item.line_total).toFixed(2)}</span></div>
            <button class="btn text-danger p-1" data-remove="${item.id}"><i class="far fa-trash-can"></i></button>
        </div>`).join('');
        total.textContent = `$${Number(cart.total).toFixed(2)}`;
        count.textContent = `${cart.count} Items`;
        empty.classList.toggle('d-none', cart.items.length > 0);
        summary.classList.toggle('d-none', cart.items.length === 0);

        list.querySelectorAll('[data-change]').forEach((button) => button.addEventListener('click', () => changeQuantity(button.dataset.id, Number(button.dataset.change))));
        list.querySelectorAll('[data-remove]').forEach((button) => button.addEventListener('click', () => updateCart('remove', button.dataset.remove)));
    }

    async function updateCart(action, itemId, quantity) {
        const body = new URLSearchParams({ action, item_id: itemId || '', quantity: String(quantity || 1) });
        await fetch('cart_api.php', { method: 'POST', body });
        loadCart();
    }

    async function changeQuantity(itemId, delta) {
        const cart = await fetch(`cart_api.php?item_id=${itemId}`).then((response) => response.json());
        const item = cart.items.find((entry) => String(entry.id) === String(itemId));
        if (item) updateCart('update', itemId, Math.max(1, item.quantity + delta));
    }

    document.getElementById('clear-cart')?.addEventListener('click', () => updateCart('clear'));
    document.getElementById('checkout-form')?.addEventListener('submit', async (event) => {
        event.preventDefault();
        const result = await fetch('checkout.php', { method: 'POST', body: new FormData(event.target) }).then((response) => response.json());
        if (result.error) return alert(result.error);
        alert(`Order #${result.order_id} confirmed. Thank you for shopping with MealVerse!`);
        event.target.reset();
        loadCart();
    });
    loadCart();
});
