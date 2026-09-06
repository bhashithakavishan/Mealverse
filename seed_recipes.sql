USE mealverse_db;

INSERT INTO recipes (title, chef, cuisine, category, image, ingredients, instructions)
SELECT 'Coconut Chicken Curry', 'Tharusha Lakshitha', 'Sri Lankan', 'Main Course', 'css/images/featured-recipe.jpg',
       '500g chicken\n1 onion (sliced)\n200ml coconut milk\n2 tbsp curry powder\nSalt and pepper',
       '1. Season the chicken with curry powder, salt, and pepper.\n2. Saute the onion until soft.\n3. Add the chicken and cook until browned.\n4. Pour in the coconut milk and simmer until tender.'
WHERE NOT EXISTS (SELECT 1 FROM recipes WHERE title = 'Coconut Chicken Curry');

INSERT INTO recipes (title, chef, cuisine, category, image, ingredients, instructions)
SELECT 'Creamy Garlic Pasta', 'Pavithra Wijesooriya', 'Italian', 'Main Course', 'css/images/recipe card.jpg',
       '250g pasta\n3 cloves garlic (minced)\n200ml cooking cream\n50g parmesan\nFresh parsley',
       '1. Cook the pasta until tender and reserve some pasta water.\n2. Saute garlic in olive oil.\n3. Add cream and parmesan.\n4. Toss with pasta and garnish with parsley.'
WHERE NOT EXISTS (SELECT 1 FROM recipes WHERE title = 'Creamy Garlic Pasta');

INSERT INTO recipes (title, chef, cuisine, category, image, ingredients, instructions)
SELECT 'Spicy Vegetable Kottu', 'Thimira Nimsara', 'Sri Lankan', 'Main Course', 'css/images/trending/trending1.jpg',
       '4 godamba roti (chopped)\n1 carrot\n1 leek\n2 eggs\n2 tbsp kottu sauce',
       '1. Stir-fry the vegetables until crisp.\n2. Add the eggs and scramble.\n3. Add chopped roti and kottu sauce.\n4. Chop and mix everything over high heat.'
WHERE NOT EXISTS (SELECT 1 FROM recipes WHERE title = 'Spicy Vegetable Kottu');

INSERT INTO recipes (title, chef, cuisine, category, image, ingredients, instructions)
SELECT 'Mango Chia Pudding', 'Chamidu Sandamal', 'Asian', 'Dessert', 'css/images/featured-recipe.jpg',
       '1 ripe mango\n250ml coconut milk\n4 tbsp chia seeds\n1 tbsp honey\nMint leaves',
       '1. Blend half of the mango with coconut milk and honey.\n2. Stir in the chia seeds.\n3. Refrigerate overnight.\n4. Top with diced mango and mint.'
WHERE NOT EXISTS (SELECT 1 FROM recipes WHERE title = 'Mango Chia Pudding');

INSERT INTO recipes (title, chef, cuisine, category, image, ingredients, instructions)
SELECT 'Roasted Tomato Soup', 'Bhashitha Dharmarathna', 'French', 'Soups', 'css/images/recipe card.jpg',
       '6 ripe tomatoes\n1 onion\n4 cloves garlic\n500ml vegetable stock\nFresh basil',
       '1. Roast tomatoes, onion, and garlic until caramelized.\n2. Add the roasted vegetables to warm stock.\n3. Blend until smooth.\n4. Season and finish with fresh basil.'
WHERE NOT EXISTS (SELECT 1 FROM recipes WHERE title = 'Roasted Tomato Soup');

INSERT INTO recipes (title, chef, cuisine, category, image, ingredients, instructions)
SELECT 'Passion Fruit Cooler', 'Isuru Kumara', 'Asian', 'Beverages', 'css/images/trending/trending1.jpg',
       '4 passion fruits\n500ml chilled water\n2 tbsp lime juice\n2 tbsp sugar\nIce cubes',
       '1. Scoop the passion fruit pulp into a jug.\n2. Add water, lime juice, and sugar.\n3. Stir until the sugar dissolves.\n4. Serve over ice.'
WHERE NOT EXISTS (SELECT 1 FROM recipes WHERE title = 'Passion Fruit Cooler');