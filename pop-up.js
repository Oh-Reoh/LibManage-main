document.addEventListener("DOMContentLoaded", () => {
    // Modal elements for Delete
    const deleteBookModal = document.getElementById("deleteBookPopup");
    const deleteCloseBtn = document.querySelector(".delete-close");
    const deleteBookForm = document.getElementById("deleteBookForm");

    // Modal elements for Edit
    const editBookModal = document.getElementById("editBookPopup");
    const editCloseBtn = document.querySelector(".edit-close"); // Make sure this exists in the HTML
    const editBookForm = document.getElementById("editBookForm");

    // Function to open the Edit Book pop-up and populate the fields
    window.openEditPopup = function(bookId) {
        if (!bookId || isNaN(bookId)) {
            alert("Invalid Book ID");
            return;
        }

        fetch(`getBookDetails.php?id=${bookId}`)
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    const book = data.data;

                    // Ensure modal elements exist before proceeding
                    const editBookId = document.getElementById("editBookId");
                    const editBookname = document.getElementById("editBookName");
                    const editAuthor = document.getElementById("editAuthor");
                    const editBookNumber = document.getElementById("editBookNumber");
                    const editPublishYear = document.getElementById("editPublishYear");
                    const editGenre = document.getElementById("editGenre");
                    const editDescription = document.getElementById("editDescription");

                    if (editBookId && editBookname && editAuthor && editBookNumber && editPublishYear && editGenre && editDescription) {
                        // Populate the form with book details
                        editBookId.value = book.id;
                        editBookname.value = book.bookname;
                        editAuthor.value = book.author;
                        editBookNumber.value = book.bookNumber;
                        editPublishYear.value = book.publishYear;
                        editGenre.value = book.genre;
                        editDescription.value = book.description;

                        // Open the modal
                        editBookModal.style.display = "flex";
                    } else {
                        console.error("Edit book modal elements not found");
                    }
                } else {
                    alert("Error fetching book details: " + data.message);
                }
            })
            .catch((error) => {
                console.error("Error fetching book details:", error);
                alert("An error occurred while fetching book details.");
            });
    };

    // Function to open the Delete Book pop-up
    window.openDeletePopup = function(bookId, bookTitle) {
        if (!bookId || !bookTitle) {
            alert("Error: Missing book details.");
            return;
        }

        document.getElementById("deleteBookId").value = bookId;
        document.getElementById("deleteBookTitle").textContent = `Are you sure you want to delete: ${bookTitle}?`;
        deleteBookModal.style.display = "flex"; // Show modal
    };

    // Attach Delete Listeners
    document.querySelectorAll(".deleteBookBtn").forEach((button) => {
        button.addEventListener("click", () => {
            const bookId = button.getAttribute('data-book-id');
            const bookTitle = button.getAttribute('data-book-title');
            openDeletePopup(bookId, bookTitle);
        });
    });

    // Attach Edit Listeners
    document.querySelectorAll(".editBookBtn").forEach((button) => {
        button.addEventListener("click", () => {
            const bookId = button.getAttribute('data-book-id');
            openEditPopup(bookId);
        });
    });

    // Close Delete Modal
    if (deleteCloseBtn) {
        deleteCloseBtn.addEventListener("click", () => {
            deleteBookModal.style.display = "none";
        });
    }

    // Close Edit Modal
    if (editCloseBtn) {
        editCloseBtn.addEventListener("click", () => {
            editBookModal.style.display = "none";
        });
    }

    // Close modals when clicking outside
    window.addEventListener("click", (event) => {
        if (event.target === deleteBookModal) {
            deleteBookModal.style.display = "none";
        } else if (event.target === editBookModal) {
            editBookModal.style.display = "none";
        }
    });

    // Handle Delete Form Submission
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

    // Handle Edit Form Submission
    editBookForm.addEventListener("submit", (event) => {
        event.preventDefault();
        const formData = new FormData(editBookForm);

        fetch("editbook.php", {
            method: "POST",
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert("Book updated successfully.");
                    location.reload(); // Refresh the page
                } else {
                    alert("Error updating book: " + data.message);
                }
            })
            .catch((error) => {
                console.error("Error submitting edit form:", error);
                alert("An error occurred while updating the book.");
            });
    });
});
