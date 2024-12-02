document.addEventListener("DOMContentLoaded", () => {
    // Function to handle the search input event
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

    // Fetch search results from the server
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
                            const bookLink = document.createElement('a');
                            bookLink.href = `book${book.id}.php`; // Redirect to the book page
                            bookLink.textContent = `${book.bookname} by ${book.author}`; // Display book name and author
                            li.appendChild(bookLink); // Append the link to the list item
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

    // Add event listener to the search input field
    const searchInput = document.getElementById("searchInput");
    if (searchInput) {
        searchInput.addEventListener("input", searchFunction);
    }
});
