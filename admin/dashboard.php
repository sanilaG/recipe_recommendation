<?php
    // Include the database connection and authentication scripts
    include('../includes/db.php');
    
    
    // Fetch total number of users
    $user_query = "SELECT COUNT(*) as total_users FROM users";
    $user_result = $conn->query($user_query);
    $total_users = $user_result->fetch_assoc()['total_users'];
    
    // Fetch total number of recipes
    $recipe_query = "SELECT COUNT(*) as total_recipes FROM recipes";
    $recipe_result = $conn->query($recipe_query);
    $total_recipes = $recipe_result->fetch_assoc()['total_recipes'];
    
    // Fetch the most popular recipe (based on interactions)
    $popular_recipe_query = "SELECT r.name, COUNT(i.recipe_id) AS recommendation_count
                             FROM interactions i
                             JOIN recipes r ON i.recipe_id = r.id
                             GROUP BY i.recipe_id
                             ORDER BY recommendation_count DESC
                             LIMIT 1";
    $popular_recipe_result = $conn->query($popular_recipe_query);
    $popular_recipe = $popular_recipe_result->fetch_assoc()['name'] ?? 'No Data';

    // Fetch number of recommendations made
    $recommendation_query = "SELECT COUNT(*) as total_recommendations FROM interactions";
    $recommendation_result = $conn->query($recommendation_query);
    $total_recommendations = $recommendation_result->fetch_assoc()['total_recommendations'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Recipe Recommendation System</title>
    <link rel="stylesheet" href="admin-style.css">
</head>
<body>

<header>
    <nav>
        <h1>Admin Dashboard</h1>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="manage-users.php">Manage Users</a></li>
            <li><a href="manage-recipes.php">Manage Recipes</a></li>
            <li><a href="manage-recommendations.php">Manage Recommendations</a></li>
            <li><a href="analytics.php">Analytics</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<section class="content">
    <h2>Welcome to Admin Dashboard</h2>

    <div class="dashboard-cards">
        <div class="card">
            <h3>Total Users</h3>
            <p><?php echo $total_users; ?></p>
        </div>

        <div class="card">
            <h3>Total Recipes</h3>
            <p><?php echo $total_recipes; ?></p>
        </div>

        <div class="card">
            <h3>Most Popular Recipe</h3>
            <p><?php echo $popular_recipe; ?></p>
        </div>

        <div class="card">
            <h3>Total Recommendations</h3>
            <p><?php echo $total_recommendations; ?></p>
        </div>
    </div>
</section>

</body>
</html>
