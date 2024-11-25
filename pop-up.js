document.addEventListener("DOMContentLoaded", () => {
    // Select modal elements
    const editBookModal = document.getElementById("editBookModal");
    const deleteBookModal = document.getElementById("deleteBookModal");
    const editBookBtn = document.getElementById("editBookBtn");
    const deleteBookBtn = document.getElementById("deleteBookBtn");
    const editCloseBtn = document.querySelector(".edit-close");
    const deleteCloseBtn = document.querySelector(".delete-close");

    // Function to open a modal
    const openModal = (modal) => {
        modal.style.display = "block";
    };

    // Function to close a modal
    const closeModal = (modal) => {
        modal.style.display = "none";
    };

    // Function to close a modal when clicking outside it
    const clickOutsideModal = (event, modal) => {
        if (event.target === modal) {
            closeModal(modal);
        }
    };

    // Open Edit Modal
    editBookBtn.addEventListener("click", () => {
        openModal(editBookModal);

        // Pre-fill the edit form with current book details
        document.getElementById("editBookname").value = "{{NAME}}"; // Replace with the book name dynamically
        // Add more fields as needed (author, genre, etc.)
    });

    // Open Delete Modal
    deleteBookBtn.addEventListener("click", () => {
        openModal(deleteBookModal);
    });

    // Close Edit Modal
    editCloseBtn.addEventListener("click", () => {
        closeModal(editBookModal);
    });

    // Close Delete Modal
    deleteCloseBtn.addEventListener("click", () => {
        closeModal(deleteBookModal);
    });

    // Close modals when clicking outside
    window.addEventListener("click", (event) => {
        clickOutsideModal(event, editBookModal);
        clickOutsideModal(event, deleteBookModal);
    });
});
