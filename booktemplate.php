<?php
$role = $_SESSION['role'] ?? 'reader'; // Assume user session holds role info
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="book{{ID}}.css">
    <title>{{NAME}}</title>
</head>
<body>
    <div class="book">
        <img src="../images/{{IMAGE}}" alt="Book Image">
        <h1>{{NAME}}</h1>
        <h2>{{AUTHOR}}</h2>
        <p>{{DESCRIPTION}}</p>
        <p>Published Year: {{YEAR}}</p>
        <p>Genres: {{GENRES}}</p>
        <?php if ($role === 'admin'): ?>
            <button>Edit</button>
            <button>Delete</button>
        <?php else: ?>
            <button>Request to Borrow</button>
            <button>Read</button>
        <?php endif; ?>
    </div>
</body>
</html>
