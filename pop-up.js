document.addEventListener("DOMContentLoaded", () => {
    // Modal elements for Delete
    const deleteBookModal = document.getElementById("deleteBookPopup");
    const deleteCloseBtn = document.querySelector(".delete-close");
    const deleteBookForm = document.getElementById("deleteBookForm");

    // Modal elements for Edit
    const editBookModal = document.getElementById("editBookPopup"); // Ensure this is the correct modal ID
    const editCloseBtn = document.querySelector(".edit-close");
    const editBookForm = document.getElementById("editBookForm");

    // Check if the modal elements exist
    if (editBookModal && editCloseBtn && editBookForm) {
        // Attach event listener to the edit button dynamically
        document.querySelectorAll(".editBookBtn").forEach((editButton) => {
            editButton.addEventListener("click", function () {
                const bookId = editButton.getAttribute('data-book-id');
                openEditPopup(bookId);  // Open modal and load book data
            });
        });

        // Close the edit modal
        editCloseBtn.addEventListener("click", function () {
            editBookModal.style.display = "none";
        });
    } else {
        console.error("Edit modal elements not found!");
    }

    // Function to open the Edit Book pop-up and populate the fields
    window.openEditPopup = function (bookId) {
        if (!bookId || isNaN(bookId)) {
            alert("Invalid Book ID");
            return;
        }

        // Make AJAX request to get book details
        fetch(`getBookDetails.php?id=${bookId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const book = data.data;

                    // Make sure the form elements exist
                    const editBookId = document.getElementById("editBookId");
                    const editBookName = document.getElementById("editBookName");
                    const editAuthor = document.getElementById("editAuthor");
                    const editBookNumber = document.getElementById("editBookNumber");
                    const editPublishYear = document.getElementById("editPublishYear");
                    const editGenre = document.getElementById("editGenre");
                    const editDescription = document.getElementById("editDescription");
                    const imagePreview = document.getElementById("imagePreview");

                    if (editBookId && editBookName && editAuthor && editBookNumber && editPublishYear && editGenre && editDescription && imagePreview) {
                        // Populate form inputs with book details
                        editBookId.value = book.id;
                        editBookName.value = book.bookname;
                        editAuthor.value = book.author;
                        editBookNumber.value = book.bookNumber;
                        editPublishYear.value = book.publishYear;
                        editGenre.value = book.genre;
                        editDescription.value = book.description;
                        imagePreview.src = `images/${book.image}`;  // Set image preview

                        // Show the edit modal
                        editBookModal.style.display = "flex";
                    } else {
                        console.error("Edit book form elements not found.");
                    }
                } else {
                    console.error("Error fetching book details: ", data.message);
                    alert("Error fetching book details: " + data.message);
                }
            })
            .catch((err) => {
                console.error("Error fetching book details:", err);
                alert("An error occurred while fetching book details.");
            });
    };

    // Handle Edit Form Submission
    if (editBookForm) {
        editBookForm.addEventListener("submit", (event) => {
            event.preventDefault(); // Prevent default form submission

            const formData = new FormData(editBookForm);

            fetch("editbook.php", {
                method: "POST",
                body: formData,
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }

                // Log the response to see if it's valid JSON
                return response.text();  // Get the response as text (not JSON yet)
            })
            .then(text => {
                // Log the response text
                console.log("Response Text:", text);

                // Try parsing the response as JSON
                let data;
                try {
                    data = JSON.parse(text);
                } catch (err) {
                    console.error("Error parsing JSON:", err);
                    alert("Failed to parse server response.");
                    return;
                }

                // Handle the parsed JSON data
                if (data.success) {
                    alert("Book updated successfully.");
                    location.reload(); // Refresh the page
                } else {
                    alert("Error updating book: " + data.message);
                }
            })
            .catch(error => {
                console.error("Error submitting edit form:", error);
                alert("An error occurred while updating the book.");
            });
        });
    }

    // Handle Delete Form Submission
    if (deleteBookForm) {
        deleteBookForm.addEventListener("submit", (event) => {
            event.preventDefault();
            const formData = new FormData(deleteBookForm);

            fetch("deletebook.php", {
                method: "POST",
                body: formData,
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        alert("Book deleted successfully.");
                        location.reload(); // Refresh the page
                    } else {
                        alert("Error deleting book: " + data.message);
                    }
                })
                .catch((error) => {
                    console.error("Error submitting delete form:", error);
                    alert("An error occurred while deleting the book.");
                });
        });
    }

    // Image Preview for Edit Book Modal
    const uploadButton = document.getElementById("uploadButton");
    const imageInput = document.getElementById("editBookImage");
    const imagePreview = document.getElementById("imagePreview");

    // Trigger file input click when upload button is clicked
    uploadButton.addEventListener("click", () => {
        imageInput.click(); // Simulate click on file input
    });

    // When the file is selected, update the image preview
    imageInput.addEventListener("change", () => {
        const file = imageInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = () => {
                imagePreview.src = reader.result; // Update the preview with the selected image
            };
            reader.readAsDataURL(file);
        }
    });

    // Search Functionality: Handling Search Results
    document.getElementById("searchInput").addEventListener("input", searchFunction);

    function searchFunction() {
        const searchTerm = document.getElementById("searchInput").value;
        const searchResults = document.getElementById("searchResults");
        
        if (searchTerm.length > 0) {
            fetchSearchResults(searchTerm);
        } else {
            // Clear results if search is empty
            searchResults.innerHTML = '';
            searchResults.style.display = 'none';
        }
    }

    function fetchSearchResults(searchTerm) {
        fetch('search.php?query=' + encodeURIComponent(searchTerm))
            .then(response => response.json())
            .then(data => {
                const searchResults = document.getElementById("searchResults");
                searchResults.innerHTML = ''; // Clear previous results

                if (data.success) {
                    const ul = document.createElement('ul');
                    if (data.type === 'books') {
                        data.results.forEach(book => {
                            const li = document.createElement('li');
                            // Create clickable link to book page
                            const bookLink = document.createElement('a');
                            bookLink.href = `book${book.id}.php`;  // Link to the book page
                            bookLink.target = '_blank';  // Open link in a new tab
                            bookLink.textContent = `${book.bookname} by ${book.author}`;
                            li.appendChild(bookLink);
                            ul.appendChild(li);
                        });
                    } else if (data.type === 'users') {
                        data.results.forEach(user => {
                            const li = document.createElement('li');
                            li.textContent = `${user.username} (${user.email})`;
                            ul.appendChild(li);
                        });
                    }
                    searchResults.appendChild(ul);
                    searchResults.style.display = 'block'; // Show dropdown
                } else {
                    searchResults.innerHTML = '<ul><li>No results found</li></ul>';
                    searchResults.style.display = 'block'; // Show dropdown
                }
            })
            .catch(error => {
                console.error('Error fetching search results:', error);
            });
    }
    
});
