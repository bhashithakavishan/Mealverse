document.addEventListener('DOMContentLoaded', () => {
  const navPlaceholder = document.getElementById('navbar-placeholder');
  if (!navPlaceholder) return;

  const currentPage = window.location.pathname.split('/').pop() || 'index.html';

  // Check if user is logged in via get_user.php
  fetch('get_user.php')
    .then(response => response.json())
    .then(data => {
      let authButtons = '';
      let mobileAuthButtons = '';

      if (data.loggedIn) {
        // HTML to display when user IS logged in
        authButtons = `
          <span class="text-white fw-bold"><i class="fa-solid fa-user me-1 text-primary"></i> Welcome, ${data.name}</span>
          <a href="logout.php" class="btn btn-outline-light rounded-pill px-3 py-1 btn-sm">Logout</a>
        `;
        mobileAuthButtons = `
          <span class="text-white fw-bold my-2">Welcome, ${data.name}</span>
          <a href="logout.php" class="btn btn-danger rounded-pill">Logout</a>
        `;
      } else {
        // HTML to display when user is NOT logged in
        authButtons = `
          <a class="text-white" href="cart.html"><i class="fa-solid fa-cart-shopping"></i> Cart</a>
          <a class="text-white" href="signin.html">Sign In</a>
          <a class="btn btn-primary rounded-pill px-4 py-2" href="signin.html">Get Started</a>
        `;
        mobileAuthButtons = `
          <a class="btn btn-outline-light rounded-pill" href="signin.html">Sign In</a>
          <a class="btn btn-primary rounded-pill" href="signin.html">Get Started</a>
        `;
      }

      // Render the complete Navbar
      navPlaceholder.innerHTML = `
        <nav class="navbar navbar-expand-lg">
          <div class="container">
            <a href="index.html" class="navbar-brand">
              <img src="css/images/logowhite.png" alt="Mealverse Logo" class="logo">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
              <ul class="navbar-nav gap-lg-4">
                <li class="nav-item"><a class="nav-link ${currentPage === 'index.html' ? 'active' : ''}" href="index.html">Home</a></li>
                <li class="nav-item"><a class="nav-link ${currentPage === 'recipes.html' ? 'active' : ''}" href="recipes.html">Recipes</a></li>
                <li class="nav-item"><a class="nav-link ${currentPage === 'marketplace.html' ? 'active' : ''}" href="marketplace.html">Marketplace</a></li>
                <li class="nav-item"><a class="nav-link ${currentPage === 'chefs.html' ? 'active' : ''}" href="chefs.html">Chefs</a></li>
                <li class="nav-item"><a class="nav-link ${currentPage === 'contact.html' ? 'active' : ''}" href="contact.html">Contact</a></li>
              </ul>
              
              <!-- Mobile View Buttons -->
              <div class="mobile-nav-actions d-lg-none">
                ${mobileAuthButtons}
              </div>
            </div>

            <!-- Desktop View Buttons -->
            <div class="nav-buttons d-none d-lg-flex align-items-center gap-3">
              ${authButtons}
            </div>
          </div>
        </nav>
      `;
    })
    .catch(error => console.error('Error fetching user status:', error));
});