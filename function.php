<?php
// Get hybrid recommendations
function getHybridRecommendations($userId, $currentRecipeId, $pdo) {
    // Content-based recommendations
    $contentBasedRecommendations = getContentBasedRecommendations($currentRecipeId, $userId, $pdo);
    
    // Collaborative filtering recommendations
    $collaborativeRecommendations = getCollaborativeRecommendations($userId, $currentRecipeId, $pdo);
    
    // Combine the recommendations
    $combinedRecommendations = array_merge($contentBasedRecommendations, $collaborativeRecommendations);
    
    // Remove duplicates
    return array_unique($combinedRecommendations);
}

// Content-based recommendation logic
function getContentBasedRecommendations($recipeId, $userId, $pdo) {
    // Get recipe features
    $recipeFeatures = getRecipeFeatures($recipeId, $pdo);
    
    // Check if the recipe features are empty or not
    if (empty($recipeFeatures['ingredients'])) {
        return []; // Return an empty array if no features found
    }

    // Exclude recipes with a rating of 2 or lower by the current user and exclude the current recipe
    $stmt = $pdo->prepare("
        SELECT id 
        FROM recipes 
        WHERE category_id = (SELECT category_id FROM recipes WHERE id = :recipeId) 
        AND id != :recipeId 
        AND id NOT IN (
            SELECT recipe_id 
            FROM rating 
            WHERE user_id = :userId 
            AND rating <= 2
        )
    ");
    $stmt->bindParam(':recipeId', $recipeId);
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Fetch recipe features (like ingredients)
function getRecipeFeatures($recipeId, $pdo) {
    $stmt = $pdo->prepare("SELECT ingredients FROM recipes WHERE id = :recipeId");
    $stmt->bindParam(':recipeId', $recipeId);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Check if result is null
    if (!$result) {
        return ['ingredients' => '']; // Return an empty string if no features found
    }
    
    return $result;
}

// Collaborative filtering logic
function getCollaborativeRecommendations($userId, $currentRecipeId, $pdo) {
    // Exclude recipes rated 2 or lower by the current user and exclude the current recipe
    $stmt = $pdo->prepare("
        SELECT r.id 
        FROM rating rt2 
        JOIN rating rt ON rt2.recipe_id = rt.recipe_id
        JOIN recipes r ON rt2.recipe_id = r.id
        WHERE rt.user_id != :userId 
        AND rt.user_id IN (
            SELECT user_id 
            FROM rating 
            WHERE recipe_id IN (
                SELECT recipe_id 
                FROM rating 
                WHERE user_id = :userId
            )
        )
        AND r.id != :currentRecipeId
        AND r.id NOT IN (
            SELECT recipe_id 
            FROM rating 
            WHERE user_id = :userId 
            AND rating <= 2
        )
        GROUP BY r.id
    ");
    
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':currentRecipeId', $currentRecipeId);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Fetch all recipes (optional, for displaying recommendations)
function getAllRecipes($pdo) {
    $stmt = $pdo->query("SELECT * FROM recipes");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
