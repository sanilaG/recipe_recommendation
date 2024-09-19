<?php
include 'database.php';

function searchRecipesByIngredients($query) {
    global $db;
    // Normalize the query to lower case and split by commas to get individual ingredients
    $queryIngredients = array_map('trim', explode(',', strtolower($query)));

    // Fetch all recipes from the database
    $stmt = $db->query("SELECT * FROM recipes");
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $matchingRecipes = [];
    
    foreach ($recipes as $recipe) {
        // Normalize recipe ingredients to lower case and split by commas
        $recipeIngredients = array_map('trim', explode(',', strtolower($recipe['ingredients'])));
        
        // Check for intersection between query ingredients and recipe ingredients
        if (count(array_intersect($queryIngredients, $recipeIngredients)) > 0) {
            $matchingRecipes[] = $recipe;
        }
    }

    return $matchingRecipes;
}

// Get the search query from user input
$searchQuery = $_GET['query'] ?? '';
header('Content-Type: application/json');
echo json_encode(searchRecipesByIngredients($searchQuery));
?>
