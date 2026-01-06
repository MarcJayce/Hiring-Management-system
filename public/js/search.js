document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const searchResults = document.getElementById("searchResults");

    const staticLinks = [
        { name: "Overview", link: "/dashboard" },
        { name: "Intern Jobs", link: "/vacancies/interns" },
        { name: "Employee Jobs", link: "/vacancies/employees" },
        { name: "Intern Form", link: "/apply/intern" },
        { name: "Employee Form", link: "/apply/employee" },
        { name: "Candidates", link: "/candidates" },
        { name: "Candidates All", link: "/candidates" },
        { name: "Candidates Intern", link: "/candidates?type=interns" },
        { name: "Candidates Employee", link: "/candidates?type=employee" },
        { name: "Calendar", link: "/calendar" },
        { name: "All Interviews", link: "/interviews" },
        { name: "Conduct Interviews", link: "/interviews" },
        { name: "Interview Questions", link: "/interviews/interview-questions" },
        { name: "Settings", link: "/settings" },
        { name: "Users", link: "/users" },
    ];

    searchInput.addEventListener("input", function () {
        const query = searchInput.value.toLowerCase();
        searchResults.innerHTML = "";

        if (query.trim() === "") {
            searchResults.style.display = "none";
            return;
        }

        let matchedStatics = staticLinks.filter(item =>
            item.name.toLowerCase().includes(query)
        );

        // Show matched static links
        matchedStatics.forEach(item => {
            let resultItem = document.createElement("a");
            resultItem.href = item.link;
            resultItem.className = "list-group-item list-group-item-action";
            resultItem.textContent = item.name;
            searchResults.appendChild(resultItem);
        });

        // Fetch dynamic candidate results
        fetch(`/search-candidates?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(candidate => {
                    let resultItem = document.createElement("a");
                    resultItem.href = `/candidates/${candidate.id}`;
                    resultItem.className = "list-group-item list-group-item-action";
                    resultItem.textContent = candidate.full_name;
                    searchResults.appendChild(resultItem);
                });

                searchResults.style.display = "block";
            });

        searchResults.style.display = "block";
    });

    // Enter to redirect first result
    searchInput.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            const firstLink = searchResults.querySelector("a");
            if (firstLink) {
                window.location.href = firstLink.href;
            }
        }
    });

    // Hide results when clicking outside
    document.addEventListener("click", function (event) {
        if (!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
            searchResults.style.display = "none";
        }
    });
});