<?php include 'indexheader.php'; ?>
<?php
// Database configuration
$servername = "localhost"; // Your server name
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "recipe_recommendation"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to select all recipes
$sql = "SELECT recipe_id, title, calories, categories, ingredients, ratings, photo_url, total_mins FROM recipes"; // Added total_mins to the SQL query
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Recommendation System</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        .recipes-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px; /* Space between cards */
        }

        .recipe-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 15px;
            width: calc(33.333% - 20px); /* 3 cards per row, adjust margin */
            text-align: center;
        }

        .recipe-image {
            width:20px;
            height:200px;
            border-radius: 5px;
        }

        .star-rating {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .star {
            color: gold;
            font-size: 20px; /* Adjust size as needed */
            margin-right: 2px; /* Space between stars */
        }

        .star.empty {
            color: lightgray; /* Color for empty stars */
        }
    </style>
</head>
<body>
    <h2>All recipes</h2>
    <div class="recipes-container">
        <?php
        // Check if there are results
        if ($result->num_rows > 0) {
            // Output data for each row
            while ($row = $result->fetch_assoc()) {
                echo '<div class="recipe-card">';
                echo '<img src="' . htmlspecialchars($row["photo_url"]) . '" alt="' . htmlspecialchars($row["title"]) . '" class="recipe-image">';
                echo '<h2 class="recipe-title">' . htmlspecialchars($row["title"]) . '</h2>';
                
                // Display star ratings
                $rating = round($row["ratings"]); // Assuming ratings is out of 5
                echo '<div class="star-rating">';
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $rating) {
                        echo '<span class="star">★</span>'; // Filled star
                    } else {
                        echo '<span class="star empty">☆</span>'; // Empty star
                    }
                }
                echo ' (' . htmlspecialchars($row["ratings"]) . ' ratings)';
                echo '</div>'; // End star rating
                echo '</div>'; // End recipe card
            }
        } else {
            echo "<p>No recipes found.</p>";
        }

        // Close the connection
        $conn->close();
        ?>
    </div>
</body>
</html>
