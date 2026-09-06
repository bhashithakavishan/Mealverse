document.addEventListener('DOMContentLoaded', () => {

    const footerPlaceholder = document.getElementById('footer-placeholder');

    if (!footerPlaceholder) return;

    footerPlaceholder.innerHTML = `
    
    <section class="newsletter-section">
    <div class="container">

        <div class="newsletter-box">

            <div class="newsletter-icon">
                <i class="fa-regular fa-envelope"></i>
            </div>

            <h2>Get Weekly Recipe Inspiration</h2>

            <p>
                Join 36 thousand food lovers. Get curated recipes, chef tips
                and exclusive deals delivered to your inbox every Thursday.
            </p>

            <form class="newsletter-form" action="newsletter.php" method="post">

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Enter your email address"
                    required>

                <button class="btn btn-dark">
                    Subscribe
                </button>

            </form>

            <small>No spam. Unsubscribe anytime. 100% free.</small>

        </div>

    </div>
</section>


<footer class="footer">

    <div class="container footer-content">

        <a href="index.php">
            <img src="images/logowhite.png" class="footer-logo" alt="MealVerse">
        </a>

        <ul class="footer-nav">
            <li><a href="index.php">Home</a></li>
            <li><a href="recipes.php">Recipes</a></li>
            <li><a href="marketplace.php">Marketplace</a></li>
            <li><a href="chefs.php">Chefs</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>

        <a href="signin.php" class="btn btn-primary rounded-pill px-4">
            Get Started
        </a>

    </div>

    <p class="footer-copy">
        ©2026 MealVerse. All Rights Reserved.
    </p>

</footer>

    `;

    footerPlaceholder.querySelector('.newsletter-form').addEventListener('submit', async (event) => {
        event.preventDefault();
        const response = await fetch(event.currentTarget.action, {
            method: 'POST',
            headers: { Accept: 'application/json' },
            body: new FormData(event.currentTarget)
        });
        const result = await response.json();
        alert(result.message);
        if (result.success) event.currentTarget.reset();
    });
});