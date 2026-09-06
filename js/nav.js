document.addEventListener('DOMContentLoaded', () => {
  const navPlaceholder = document.getElementById('navbar-placeholder');
  if (!navPlaceholder) return;

  const currentPage = window.location.pathname.split('/').pop() || 'index.php';

  fetch('get_user.php')
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data => {
      renderNavbar(data.loggedIn, data.name);
    })
    .catch(error => {
      
      renderNavbar(false, null);
    });

  function renderNavbar(isLoggedIn, userName) {
    let authButtons = '';
    let mobileAuthButtons = '';

    if (isLoggedIn) {
      authButtons = `
        <a class="text-white" href="cart.php"><i class="fa-solid fa-cart-shopping"></i> Cart</a>
        <span class="text-white fw-bold"><i class="fa-solid fa-user me-1 text-primary"></i> Welcome, ${userName}</span>
        <a href="auth/logout.php" class="btn btn-outline-light rounded-pill px-3 py-1 btn-sm">Logout</a>
      `;
      mobileAuthButtons = `
        <a class="btn btn-outline-light rounded-pill" href="cart.php"><i class="fa-solid fa-cart-shopping me-1"></i> Cart</a>
        <span class="text-white fw-bold my-2">Welcome, ${userName}</span>
        <a href="auth/logout.php" class="btn btn-danger rounded-pill">Logout</a>
      `;
    } else {
      authButtons = `
        <a class="text-white" href="cart.php"><i class="fa-solid fa-cart-shopping"></i> Cart</a>
        <a class="text-white" href="signin.php">Sign In</a>
        <a class="btn btn-primary rounded-pill px-4 py-2" href="signin.php">Get Started</a>
      `;
      mobileAuthButtons = `
        <a class="btn btn-outline-light rounded-pill" href="signin.php">Sign In</a>
        <a class="btn btn-primary rounded-pill" href="signin.php">Get Started</a>
      `;
    }

    navPlaceholder.innerHTML = `
      <nav class="navbar navbar-expand-lg">
        <div class="container">
          <a href="index.php" class="navbar-brand">
            <img src="images/logowhite.png" alt="Mealverse Logo" class="logo">
          </a>

          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav gap-lg-4">
              <li class="nav-item"><a class="nav-link ${currentPage === 'index.php' ? 'active' : ''}" href="index.php">Home</a></li>
              <li class="nav-item"><a class="nav-link ${currentPage === 'recipes.php' ? 'active' : ''}" href="recipes.php">Recipes</a></li>
              <li class="nav-item"><a class="nav-link ${currentPage === 'marketplace.php' ? 'active' : ''}" href="marketplace.php">Marketplace</a></li>
              <li class="nav-item"><a class="nav-link ${currentPage === 'chefs.php' ? 'active' : ''}" href="chefs.php">Chefs</a></li>
              <li class="nav-item"><a class="nav-link ${currentPage === 'contact.php' ? 'active' : ''}" href="contact.php">Contact</a></li>
            </ul>
            
            <div class="mobile-nav-actions d-lg-none mt-3 d-flex flex-column gap-2">
              ${mobileAuthButtons}
            </div>
          </div>

          <div class="nav-buttons d-none d-lg-flex align-items-center gap-3">
            ${authButtons}
          </div>
        </div>
      </nav>
    `;
  }
});
