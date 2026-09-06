CREATE DATABASE IF NOT EXISTS mealverse_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mealverse_db;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS recipes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    chef VARCHAR(100) NOT NULL,
    cuisine VARCHAR(100) NOT NULL,
    category VARCHAR(100) NOT NULL,
    image VARCHAR(500) NOT NULL,
    ingredients TEXT NOT NULL,
    instructions TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS marketplace_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(500) NOT NULL,
    stock INT UNSIGNED NOT NULL DEFAULT 100,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    shipping_address TEXT NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'confirmed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED NOT NULL,
    item_id INT UNSIGNED NOT NULL,
    quantity INT UNSIGNED NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES marketplace_items(id)
);

CREATE TABLE IF NOT EXISTS messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO recipes (title, chef, cuisine, category, image, ingredients, instructions)
SELECT 'Coconut Chicken Curry', 'Tharusha Lakshitha', 'Sri Lankan', 'Main Course', 'images/featured-recipe.jpg', '500g chicken\n1 onion (sliced)\n200ml coconut milk\n2 tbsp curry powder\nSalt and pepper', '1. Season the chicken with curry powder, salt, and pepper.\n2. Saute the onion until soft.\n3. Add the chicken and cook until browned.\n4. Pour in the coconut milk and simmer until tender.'
WHERE NOT EXISTS (SELECT 1 FROM recipes WHERE title = 'Coconut Chicken Curry');

INSERT INTO recipes (title, chef, cuisine, category, image, ingredients, instructions)
SELECT 'Creamy Garlic Pasta', 'Pavithra Wijesooriya', 'Italian', 'Main Course', 'images/recipe card.jpg', '250g pasta\n3 cloves garlic (minced)\n200ml cooking cream\n50g parmesan\nFresh parsley', '1. Cook the pasta until tender and reserve some pasta water.\n2. Saute garlic in olive oil.\n3. Add cream and parmesan.\n4. Toss with pasta and garnish with parsley.'
WHERE NOT EXISTS (SELECT 1 FROM recipes WHERE title = 'Creamy Garlic Pasta');

INSERT INTO recipes (title, chef, cuisine, category, image, ingredients, instructions)
SELECT 'Spicy Vegetable Kottu', 'Thimira Nimsara', 'Sri Lankan', 'Main Course', 'images/trending/trending1.jpg', '4 godamba roti (chopped)\n1 carrot\n1 leek\n2 eggs\n2 tbsp kottu sauce', '1. Stir-fry the vegetables until crisp.\n2. Add the eggs and scramble.\n3. Add chopped roti and kottu sauce.\n4. Chop and mix everything over high heat.'
WHERE NOT EXISTS (SELECT 1 FROM recipes WHERE title = 'Spicy Vegetable Kottu');

INSERT INTO recipes (title, chef, cuisine, category, image, ingredients, instructions)
SELECT 'Mango Chia Pudding', 'Chamidu Sandamal', 'Asian', 'Dessert', 'images/featured-recipe.jpg', '1 ripe mango\n250ml coconut milk\n4 tbsp chia seeds\n1 tbsp honey\nMint leaves', '1. Blend half of the mango with coconut milk and honey.\n2. Stir in the chia seeds.\n3. Refrigerate overnight.\n4. Top with diced mango and mint.'
WHERE NOT EXISTS (SELECT 1 FROM recipes WHERE title = 'Mango Chia Pudding');

INSERT INTO recipes (title, chef, cuisine, category, image, ingredients, instructions)
SELECT 'Roasted Tomato Soup', 'Bhashitha Dharmarathna', 'French', 'Soups', 'images/recipe card.jpg', '6 ripe tomatoes\n1 onion\n4 cloves garlic\n500ml vegetable stock\nFresh basil', '1. Roast tomatoes, onion, and garlic until caramelized.\n2. Add the roasted vegetables to warm stock.\n3. Blend until smooth.\n4. Season and finish with fresh basil.'
WHERE NOT EXISTS (SELECT 1 FROM recipes WHERE title = 'Roasted Tomato Soup');

INSERT INTO recipes (title, chef, cuisine, category, image, ingredients, instructions)
SELECT 'Passion Fruit Cooler', 'Isuru Kumara', 'Asian', 'Beverages', 'images/trending/trending1.jpg', '4 passion fruits\n500ml chilled water\n2 tbsp lime juice\n2 tbsp sugar\nIce cubes', '1. Scoop the passion fruit pulp into a jug.\n2. Add water, lime juice, and sugar.\n3. Stir until the sugar dissolves.\n4. Serve over ice.'
WHERE NOT EXISTS (SELECT 1 FROM recipes WHERE title = 'Passion Fruit Cooler');

INSERT INTO marketplace_items (name, category, description, price, image, stock)
SELECT 'Ceylon Cinnamon Gift Box', 'ingredients', 'A fragrant collection of premium Ceylon cinnamon quills.', 18.50, 'images/marketplace/spices.jpg', 40
WHERE NOT EXISTS (SELECT 1 FROM marketplace_items WHERE name = 'Ceylon Cinnamon Gift Box');

INSERT INTO marketplace_items (name, category, description, price, image, stock)
SELECT 'Professional Chef Knife', 'tools', 'A balanced stainless-steel knife for everyday prep.', 45.00, 'images/marketplace/apron.jpg', 25
WHERE NOT EXISTS (SELECT 1 FROM marketplace_items WHERE name = 'Professional Chef Knife');

INSERT INTO marketplace_items (name, category, description, price, image, stock)
SELECT 'Cast Iron Skillet', 'tools', 'A durable skillet for searing, baking, and slow cooking.', 49.99, 'images/marketplace/spices.jpg', 18
WHERE NOT EXISTS (SELECT 1 FROM marketplace_items WHERE name = 'Cast Iron Skillet');

INSERT INTO marketplace_items (name, category, description, price, image, stock)
SELECT 'MealVerse Masterclass Cookbook', 'cookbooks', 'A practical cookbook of chef-tested recipes and techniques.', 29.99, 'images/marketplace/book.jpg', 35
WHERE NOT EXISTS (SELECT 1 FROM marketplace_items WHERE name = 'MealVerse Masterclass Cookbook');

INSERT INTO marketplace_items (name, category, description, price, image, stock)
SELECT 'Organic Olive Oil', 'ingredients', 'Cold-pressed olive oil for dressings, marinades, and cooking.', 22.00, 'images/marketplace/book.jpg', 50
WHERE NOT EXISTS (SELECT 1 FROM marketplace_items WHERE name = 'Organic Olive Oil');
