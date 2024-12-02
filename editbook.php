<?php
session_start();
header('Content-Type: application/json');  // Ensure the response is in JSON format

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "libmanagedb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Initialize variables
$bookId = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch book ID from POST request (form submission)
    $bookId = intval($_POST['bookId']);
} elseif (isset($_GET['bookId'])) {
    // Fetch book ID from GET request (initial page load)
    $bookId = intval($_GET['bookId']);
}

$bookData = [];
// Fetch the book details if bookId is provided
if ($bookId > 0) {
    $stmt = $conn->prepare("SELECT * FROM tbl_bookinfo WHERE id = ?");
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookData = $result->fetch_assoc();
    $stmt->close();
}

// Process form submission for editing the book
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookId = intval($_POST['bookId']);
    $bookname = htmlspecialchars($_POST['bookname']);
    $author = htmlspecialchars($_POST['author']);
    $bookNumber = htmlspecialchars($_POST['bookNumber']);
    $publishYear = intval($_POST['publishYear']);
    $genre = htmlspecialchars($_POST['genre']);
    $description = htmlspecialchars($_POST['description']);
    $image = $bookData['image']; // Default to current image

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "images/";  // The directory to store the uploaded image
        $imageName = basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . $imageName;
    
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            // Successfully moved the file, use the new image path
            $image = $imageName;
        }
    }
    

    // Update the book record in the database
    $stmt = $conn->prepare("UPDATE tbl_bookinfo SET bookname = ?, author = ?, bookNumber = ?, publishYear = ?, genre = ?, description = ?, image = ? WHERE id = ?");
    $stmt->bind_param("sssisssi", $bookname, $author, $bookNumber, $publishYear, $genre, $description, $image, $bookId);

    if ($stmt->execute()) {
        // After updating the database, regenerate the book file
        regenerateBookFile($bookId, $bookname, $author, $bookNumber, $publishYear, $genre, $description, $image);
        
        // Respond with a JSON success message
        echo json_encode(["success" => true, "message" => "Book updated successfully."]);
    } else {
        // Respond with an error message if the update failed
        echo json_encode(["success" => false, "message" => "Error updating book: " . $stmt->error]);
    }

    $stmt->close();
    exit();  // Ensure no further output is sent (this will stop HTML output from being sent)
}

$conn->close();

// Function to regenerate the book file
function regenerateBookFile($bookId, $bookname, $author, $bookNumber, $publishYear, $genre, $description, $image) {
    // Open the book file for writing
    $bookFile = fopen("book{$bookId}.php", "w");

    // Generate the PHP content for the book file, updating only the book details
    $content = "<?php\n";
    // Start the session to ensure role is available
    $content .= "session_start();\n";
    // Assuming session is already started and role is defined
    $content .= "\$role = isset(\$_SESSION['role']) ? \$_SESSION['role'] : 'regular';\n";
    $content .= "\$dashboardLink = \$role === 'librarian' ? 'Dashboard(Librarian).php' : 'Dashboard(Reader).php';\n";
    $content .= "\$booklistLink = \$role === 'librarian' ? 'booklist(Librarian).php' : 'booklist(Reader).php';\n";
    $content .= "\$book = [\n";
    $content .= "'id' => '{$bookId}',\n";
    $content .= "'title' => '{$bookname}',\n";
    $content .= "'author' => '{$author}',\n";
    $content .= "'year' => '{$publishYear}',\n";
    $content .= "'genre' => '{$genre}',\n";
    $content .= "'number' => '{$bookNumber}',\n";
    $content .= "'image' => '{$image}',\n";
    $content .= "'description' => '{$description}',\n";
    $content .= "'history' => 'No history available yet.',\n";
    $content .= "'rating' => '4.1',\n";
    $content .= "'stars' => '★★★★☆',\n";
    $content .= "'language' => 'ENGLISH',\n";
    $content .= "];\n";

    // Now insert the complete HTML layout from booktemplate.php, ensuring the modal HTML is included correctly
    $content .= "
    ?>\n
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css?family=Bakbak+One' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css2?family=Baloo+2&display=swap' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap' rel='stylesheet'>
        <link rel='stylesheet' href='book<?php echo htmlspecialchars(\$book['id']); ?>.css'>
        <link rel='stylesheet' href='pop-up_add.css'>
        <title><?php echo htmlspecialchars(\$book['title']); ?></title>
    </head>
    <body>
        <div class='dboard_content'>
            <!-- Sidebar -->
            <section id='sidebar'>
                <a href='<?php echo \$dashboardLink; ?>' class='brand'>
                    <img src='images/logo_ra.png' alt='Logo Icon' class='logo'>
                    <p>Libmanage</p>
                </a>
                <ul class='side-menu'>
                    <li><a href='<?php echo \$dashboardLink; ?>' class='active'>
                    <img src='images/dashboard_icon.png' alt='Dashboard Icon' class='icon'>Dashboard</a>
                </li>
                    <li><a href='<?php echo \$booklistLink; ?>' class='active'>
                    <img src='images/book_icon(big).png' alt='Books Icon' class='icon'>Books</a>
                </li> 
                </ul>
            </section>

            <!-- Main Content -->
            <section id='content'>
                <nav>
                    <i class='bx bx-menu toggle-sidebar'></i>
                    <form action='#'>
                        <div class='form-group'>
                            <input type='text' placeholder='Search books & members'>
                            <i class='bx bx-search icon'></i>
                        </div>
                    </form>

                    <div class='profile'>
                        <img src='https://images.unsplash.com/photo-1517841905240-472988babdf9?ixid=MnwxMjA3fDB8MHxzZWFyY2h8NHx8cGVvcGxlfGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60' alt=''>
                        <ul class='profile-link'>
                            <li><a href='profile.php'><i class='bx bxs-user-circle icon' ></i> Profile</a></li>
                            <li><a href='#'><i class='bx bxs-cog' ></i> Settings</a></li>
                            <li><a href='Mainpage.php'><i class='bx bxs-log-out-circle' ></i> Logout</a></li>
                        </ul>
                    </div>
                </nav>

                <main>
                    <h1 class='title'><a href='<?php echo \$booklistLink; ?>'>Books</a> >> <?php echo htmlspecialchars(\$book['title']); ?></h1>
                    <div class='data'>
                        <div class='left-column'>
                            <div class='content-data book-information'>
                                <div class='book-info-content'>
                                    <div class='book-details'>
                                        <div class='detail-item'>
                                            <span class='label'>Publish Year:</span>
                                            <span class='value'><?php echo htmlspecialchars(\$book['year'] ?? 'N/A'); ?></span>
                                        </div>
                                        <div class='detail-item'>
                                            <span class='label'>Genre:</span>
                                            <span class='value'><?php echo htmlspecialchars(\$book['genre'] ?? 'N/A'); ?></span>
                                        </div>
                                        <div class='detail-item'>
                                            <span class='label'>Book Number:</span>
                                            <span class='value'><?php echo htmlspecialchars(\$book['number'] ?? 'N/A'); ?></span>
                                        </div>
                                    </div>
                                    <div class='book-image'>
                                        <img src='images/<?php echo htmlspecialchars(\$book['image']); ?>' alt='Book Image'>
                                    </div>
                                </div>
                            </div>
                            <div class='content-data book-history'>
                                <div class='head'>
                                    <h3>Book History</h3>
                                </div>
                                <p><?php echo htmlspecialchars(\$book['history'] ?? 'No history available yet.'); ?></p>
                            </div>
                        </div>

                        <div class='right-column'>
                            <div class='content-data book-overview'>
                                <div class='book-overview-content'>
                                    <img src='images/<?php echo htmlspecialchars(\$book['image']); ?>' alt='<?php echo htmlspecialchars(\$book['title']); ?> Cover' class='book-cover'>
                                    <h2 class='book-title'><?php echo htmlspecialchars(\$book['title']); ?></h2>
                                    <p class='book-author'>by <?php echo htmlspecialchars(\$book['author']); ?></p>
                                    <p class='book-description'><?php echo htmlspecialchars(\$book['description']); ?></p>
                                    <div class='book-rating'>
                                        <span><?php echo htmlspecialchars(\$book['rating'] ?? 'N/A'); ?></span>
                                        <div class='stars'><?php echo htmlspecialchars(\$book['stars'] ?? '★★★★☆'); ?></div>
                                        <span class='language'><?php echo htmlspecialchars(\$book['language'] ?? 'ENGLISH'); ?></span>
                                    </div>
                                    <button class='read-button'>View/Read Book</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </section>
        </div>

        <footer>
            <div class='footer-container'>
                <div class='footer-left'>
                    <div class='footer-description'>
                        <img src='logo.png' alt='Libmanage Logo' class='footer-logo'>
                        <p>Where Stories Live: <span class='second-line'>Find And Borrow Your Next Great Read.</span></p>
                    </div>
                </div>
                <div class='footer-center'>
                    <p>&copy; ALPHA ONE 2024</p>
                </div>
                <div class='footer-right'>
                    <p>Follow Us</p>
                    <div class='footer-socials'>
                        <a href='#'><img src='icon-ig.png' alt='Instagram'></a>
                        <a href='#'><img src='icon-fb.png' alt='Facebook'></a>
                        <a href='#'><img src='icon-tw.png' alt='Twitter'></a>
                    </div>
                </div>
            </div>
        </footer>

        <script src='pop-up.js'></script>

        
    </body>
    </html>
    ";

    // Write the content to the book file
    fwrite($bookFile, $content);
    fclose($bookFile);
}
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Dashboard(Librarian).css">
    <title>Edit Book</title>
</head>
<body>
    <div class="dboard_content">
        <section id="sidebar">
            <!-- Sidebar content -->
        </section>
        <section id="content">
            <nav>
                <!-- Navigation content -->
            </nav>
            <main>
                <div class="book-registration">
                    <div class="head">
                        <h2>Edit Book</h2>
                        <form action="editbook.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="bookId" value="<?php echo htmlspecialchars($bookId); ?>">
                            <br><br>
                            <label for="image">Upload Book Image</label>
                            <br><br>
                            <div class="image-preview-container">
                                <img id="imagePreview" src="images/<?php echo htmlspecialchars($bookData['image'] ?? 'blankimg.png'); ?>" alt="Book Image Preview" style="max-width: 100px;">
                                <input type="file" id="editBookImage" name="image" accept="image/*" style="display: none;">
                                <button type="button" id="uploadButton">Upload Image</button>
                            </div>
                            <br><br>
                            <label for="bookname">Book Name</label>
                            <br><br>
                            <input type="text" id="bookname" name="bookname" value="<?php echo htmlspecialchars($bookData['bookname'] ?? ''); ?>" required>
                            <br><br>
                            <label for="author">Author</label>
                            <br><br>
                            <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($bookData['author'] ?? ''); ?>" required>
                            <br><br>
                            <label for="bookNumber">Book Number</label>
                            <br><br>
                            <input type="text" id="bookNumber" name="bookNumber" value="<?php echo htmlspecialchars($bookData['bookNumber'] ?? ''); ?>" required>
                            <br><br>
                            <label for="publishYear">Publish Year</label>
                            <br><br>
                            <input type="number" id="publishYear" name="publishYear" value="<?php echo htmlspecialchars($bookData['publishYear'] ?? ''); ?>" required>
                            <br><br>
                            <label for="genre">Genre</label>
                            <br><br>
                            <input type="text" id="genre" name="genre" value="<?php echo htmlspecialchars($bookData['genre'] ?? ''); ?>" required>
                            <br><br>
                            <label for="description">Description</label>
                            <br><br>
                            <textarea id="description" name="description" required><?php echo htmlspecialchars($bookData['description'] ?? ''); ?></textarea>
                            <br><br>
                            <button type="submit" name="submit">Update Book</button>
                        </form>
                    </div>
                </div>
            </main>
        </section>
    </div>

    <!-- Place your JavaScript for handling the image preview and file selection here -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const uploadButton = document.getElementById("uploadButton");  // Upload button
            const imageInput = document.getElementById("editBookImage");  // File input
            const imagePreview = document.getElementById("imagePreview");  // Image preview

            // When the "Upload Image" button is clicked, trigger the file input click
            uploadButton.addEventListener("click", () => {
                imageInput.click();  // Trigger the file input to open
            });

            // When a file is selected, update the image preview
            imageInput.addEventListener("change", () => {
                const file = imageInput.files[0];  // Get the selected file

                if (file) {
                    const reader = new FileReader();

                    // When the file is loaded, update the preview
                    reader.onload = function (e) {
                        imagePreview.src = e.target.result;  // Set the preview to the selected image
                    };

                    reader.readAsDataURL(file);  // Read the file as a data URL
                }
            });
        });
    </script>
</body>
</html>
