document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('contact-form');
    if (!form) return;

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: { Accept: 'application/json' },
                body: new FormData(form)
            });
            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Your message could not be sent.');
            }

            alert(result.message);
            form.reset();
        } catch (error) {
            alert(error.message);
        }
    });
});