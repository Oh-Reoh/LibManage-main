document.addEventListener("DOMContentLoaded", () => {
    // Modal elements for Delete
    const deleteBookModal = document.getElementById("deleteBookModal");
    const deleteCloseBtn = document.querySelector(".delete-close");
    const deleteBookForm = document.getElementById("deleteBookForm");

    // Modal elements for Edit
    const editBookModal = document.getElementById("editBookModal");
    const editCloseBtn = document.querySelector(".edit-close");
    const editBookForm = document.getElementById("editBookForm");

    // Attach Delete Listeners
    document.querySelectorAll(".deleteBookBtn").forEach((button) => {
        button.addEventListener("click", () => {
            const bookId = button.dataset.bookId;
            const bookTitle = button.dataset.bookTitle;

            if (!bookId || !bookTitle) {
                alert("Error: Missing book details.");
                return;
            }

            document.getElementById("deleteBookId").value = bookId;
            document.getElementById("deleteBookTitle").textContent = bookTitle;

            deleteBookModal.style.display = "flex"; // Show modal
        });
    });

    // Attach Edit Listeners
    document.querySelectorAll(".editBookBtn").forEach((button) => {
        button.addEventListener("click", () => {
            const bookId = button.dataset.bookId;

            fetch(`getBookDetails.php?id=${bookId}`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        const book = data.data;
                        document.getElementById("editBookname").value = book.bookname;
                        document.getElementById("editAuthor").value = book.author;
                        document.getElementById("editBookNumber").value = book.bookNumber;
                        document.getElementById("editPublishYear").value = book.publishYear;
                        document.getElementById("editGenre").value = book.genre;
                        document.getElementById("editDescription").value = book.description;

                        editBookModal.style.display = "flex"; // Show modal
                    } else {
                        alert("Error fetching book details: " + data.message);
                    }
                })
                .catch((error) => {
                    console.error("Error fetching book details:", error);
                    alert("An error occurred while fetching book details.");
                });
        });
    });

    // Close Delete Modal
    deleteCloseBtn.addEventListener("click", () => {
        deleteBookModal.style.display = "none";
    });

    // Close Edit Modal
    editCloseBtn.addEventListener("click", () => {
        editBookModal.style.display = "none";
    });

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
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    alert("Book deleted successfully.");
                    location.reload();
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
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    alert("Book updated successfully.");
                    location.reload();
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
