<?php include 'indexheader.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Sharing Platform</title>
    <style>
        /* Add any styles you want here */
    </style>
</head>
<body>
<img src="../images/home.jpg" alt="description" width="1430px" height="700px">

    

    <!-- Search bar -->
   
    <!-- Most Popular Recipes -->
    <h2>Most Popular Recipes</h2>
    <div id="popular-recipes"></div>

    <!-- Add your script here -->
    <script>
        // Fetch popular recipes when the page loads
        fetch('fetch_popular_recipes.php')
            .then(response => response.json())
            .then(data => {
                const recipesDiv = document.getElementById('popular-recipes');
                data.forEach(recipe => {
                    const recipeItem = document.createElement('div');
                    recipeItem.classList.add('recipe-item');
                    recipeItem.innerHTML = `
                        <h3>${recipe.title}</h3>
                        <p>Rating: ${recipe.ratings} (Reviews: ${recipe.reviews})</p>
                    `;
                    recipesDiv.appendChild(recipeItem);
                });
            })
            .catch(error => console.error('Error fetching popular recipes:', error));

        // Add the search functionality
        document.getElementById('search').addEventListener('input', function() {
            const query = this.value;

            // Fetch search results from the backend
            fetch(`search_recipes.php?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    const recipesDiv = document.getElementById('popular-recipes');
                    recipesDiv.innerHTML = ''; // Clear previous results
                    data.forEach(recipe => {
                        const recipeItem = document.createElement('div');
                        recipeItem.classList.add('recipe-item');
                        recipeItem.innerHTML = `
                            <h3>${recipe.title}</h3>
                            <p>Rating: ${recipe.ratings} (Reviews: ${recipe.reviews})</p>
                        `;
                        recipesDiv.appendChild(recipeItem);
                    });
                })
                .catch(error => console.error('Error fetching search results:', error));
        });
    </script>
</body>
</html>

<?php include 'footer.php'; ?>


