USE mealverse_db;

INSERT INTO marketplace_items (name, category, description, price, image, stock)
SELECT 'Ceylon Cinnamon Gift Box', 'ingredients', 'A fragrant collection of premium Ceylon cinnamon quills.', 18.50, 'css/images/marketplace/spices.jpg', 40
WHERE NOT EXISTS (SELECT 1 FROM marketplace_items WHERE name = 'Ceylon Cinnamon Gift Box');

INSERT INTO marketplace_items (name, category, description, price, image, stock)
SELECT 'Professional Chef Knife', 'tools', 'A balanced stainless-steel knife for everyday prep.', 45.00, 'css/images/marketplace/apron.jpg', 25
WHERE NOT EXISTS (SELECT 1 FROM marketplace_items WHERE name = 'Professional Chef Knife');

INSERT INTO marketplace_items (name, category, description, price, image, stock)
SELECT 'Cast Iron Skillet', 'tools', 'A durable skillet for searing, baking, and slow cooking.', 49.99, 'css/images/marketplace/spices.jpg', 18
WHERE NOT EXISTS (SELECT 1 FROM marketplace_items WHERE name = 'Cast Iron Skillet');

INSERT INTO marketplace_items (name, category, description, price, image, stock)
SELECT 'MealVerse Masterclass Cookbook', 'cookbooks', 'A practical cookbook of chef-tested recipes and techniques.', 29.99, 'css/images/marketplace/book.jpg', 35
WHERE NOT EXISTS (SELECT 1 FROM marketplace_items WHERE name = 'MealVerse Masterclass Cookbook');

INSERT INTO marketplace_items (name, category, description, price, image, stock)
SELECT 'Organic Olive Oil', 'ingredients', 'Cold-pressed olive oil for dressings, marinades, and cooking.', 22.00, 'css/images/marketplace/book.jpg', 50
WHERE NOT EXISTS (SELECT 1 FROM marketplace_items WHERE name = 'Organic Olive Oil');
