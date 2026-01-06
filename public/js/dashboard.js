// Date Range Filter Functionality
document.addEventListener("DOMContentLoaded", function () {
    const predefinedRange = document.getElementById("predefinedRange");
    let loadingTimeout;
    // Initialize dashboard with default date range
    const defaultRange = document.getElementById("predefinedRange").value;
    updateDashboard(defaultRange);

    // Handle date range selection
    predefinedRange.addEventListener("change", function () {
        showLoading();
        updateDashboard(this.value);
    });

    // Add event listener for date range dropdown
    document
        .getElementById("predefinedRange")
        .addEventListener("change", function () {
            const selectedRange = this.value;
            const customDateContainer = document.getElementById(
                "customDateContainer"
            );

            if (selectedRange === "custom") {
                // Show custom date inputs
                customDateContainer.classList.remove("d-none");
            } else {
                // Hide custom date inputs and update dashboard
                customDateContainer.classList.add("d-none");
                showLoading();
                updateDashboard(selectedRange);
            }
        });

    // Add event listener for custom date apply button
    document
        .getElementById("applyCustomDate")
        .addEventListener("click", function () {
            const startDate = document.getElementById("startDate").value;
            const endDate = document.getElementById("endDate").value;

            if (!startDate || !endDate) {
                alert("Please select both start and end dates");
                return;
            }

            if (new Date(startDate) > new Date(endDate)) {
                alert("Start date cannot be after end date");
                return;
            }

            showLoading();
            updateDashboard("custom", startDate, endDate);
        });

    // Show loading indicator
    function showLoading() {
        // Create loading overlay if it doesn't exist
        let loadingOverlay = document.getElementById("loadingOverlay");
        if (!loadingOverlay) {
            loadingOverlay = document.createElement("div");
            loadingOverlay.id = "loadingOverlay";
            loadingOverlay.style.position = "fixed";
            loadingOverlay.style.top = "0";
            loadingOverlay.style.left = "0";
            loadingOverlay.style.width = "100%";
            loadingOverlay.style.height = "100%";
            loadingOverlay.style.backgroundColor = "rgba(255, 255, 255, 0.7)";
            loadingOverlay.style.display = "flex";
            loadingOverlay.style.justifyContent = "center";
            loadingOverlay.style.alignItems = "center";
            loadingOverlay.style.zIndex = "9999";

            const spinner = document.createElement("div");
            spinner.className = "spinner-border text-primary";
            spinner.setAttribute("role", "status");

            const loadingText = document.createElement("span");
            loadingText.className = "ml-2";
            loadingText.textContent = "Loading...";

            const loadingContainer = document.createElement("div");
            loadingContainer.className = "d-flex align-items-center";
            loadingContainer.appendChild(spinner);
            loadingContainer.appendChild(loadingText);

            loadingOverlay.appendChild(loadingContainer);
            document.body.appendChild(loadingOverlay);
        }

        loadingOverlay.style.display = "flex";
    }

    // Hide loading indicator
    function hideLoading() {
        const loadingOverlay = document.getElementById("loadingOverlay");
        if (loadingOverlay) {
            loadingOverlay.style.display = "none";
        }
    }

    // Function to update dashboard data
    function updateDashboard(range, start = null, end = null) {
        const data = {
            range: range,
            start_date: start,
            end_date: end,
        };

        fetch("/dashboard/update-data", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify(data),
        })
            .then((response) => {
                if (!response.ok) {
                    return response.json().then((err) => {
                        throw new Error(
                            err.message || "Network response was not ok"
                        );
                    });
                }
                return response.json();
            })
            .then((data) => {
                if (data.error) {
                    throw new Error(data.error);
                }

                // Update all dashboard statistics and charts for all tabs
                updateStatistics(data);

                // Hide loading indicator
                hideLoading();
                // Update tables with the response data
                if (data.partTimePositions)
                    updatePartTimePositionsTable(data.partTimePositions);
                if (data.fullTimePositions)
                    updateFullTimePositionsTable(data.fullTimePositions);
                if (data.internPositions)
                    updateInternPositionsTable(data.internPositions);

                // Update universities table
                if (data.universities) {
                    updateUniversitiesTable(data.universities);
                } else {
                    console.error("Universities data not found in response");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                hideLoading();
                alert(
                    error.message ||
                        "An error occurred while updating the dashboard. Please try again."
                );
            });
    }

    // Function to update statistics with loading state
    function updateStatistics(data) {
        // Update all dashboard statistics

        // Employees Tab
        const fullTimeSection = document.querySelector(
            "#employees #full-time-section"
        );
        if (fullTimeSection) {
            const fullTimeCountEl = fullTimeSection.querySelector(
                ".display-3:first-child"
            );
            if (fullTimeCountEl)
                fullTimeCountEl.textContent = data.fullTime.count;

            if (data.fullTime.count === 0) {
                fullTimeSection
                    .querySelectorAll("[data-status]")
                    .forEach((el) => (el.textContent = "0"));
            } else {
                const statuses = [
                    "For screening",
                    "Shortlisted",
                    "For Interview",
                    "Scheduled for Interview",
                    "Completed Interview",
                    "Offer Made",
                    "Hired",
                    "Rejected",
                ];

                statuses.forEach((status) => {
                    const statusEl = fullTimeSection.querySelector(
                        `[data-status='${status}']`
                    );
                    if (statusEl) {
                        if (
                            !statusEl.dataset.originalValue &&
                            statusEl.textContent !== "0"
                        ) {
                            statusEl.dataset.originalValue =
                                statusEl.textContent;
                        }

                        statusEl.textContent =
                            data.fullTime.count !== 0
                                ? statusEl.dataset.originalValue ||
                                  statusEl.textContent
                                : "0";
                    }
                });
            }
        }

        // Part-Time Tab
        const partTimeSection = document.querySelector(
            "#employees #part-time-section"
        );
        if (partTimeSection) {
            const partTimeCountEl = partTimeSection.querySelector("#part");
            if (partTimeCountEl)
                partTimeCountEl.textContent = data.partTime.count;

            if (data.partTime.count === 0) {
                partTimeSection
                    .querySelectorAll("[data-status]")
                    .forEach((el) => (el.textContent = "0"));
            } else {
                const statuses = [
                    "For screening",
                    "Shortlisted",
                    "For Interview",
                    "Scheduled for Interview",
                    "Completed Interview",
                    "Offer Made",
                    "Hired",
                    "Rejected",
                ];

                statuses.forEach((status) => {
                    const statusEl = partTimeSection.querySelector(
                        `[data-status='${status}']`
                    );
                    if (statusEl) {
                        if (
                            !statusEl.dataset.originalValue &&
                            statusEl.textContent !== "0"
                        ) {
                            statusEl.dataset.originalValue =
                                statusEl.textContent;
                        }

                        statusEl.textContent =
                            data.partTime.count !== 0
                                ? statusEl.dataset.originalValue ||
                                  statusEl.textContent
                                : "0";
                    }
                });
            }
        }
        // Interns Tab
        const internSection = document.querySelector("#interns");

        if (internSection) {
            const internCountEl = internSection.querySelector(".display-3");
            if (internCountEl) {
                internCountEl.textContent = data.interns.count;

                if (data.interns.count === 0) {
                    internSection
                        .querySelectorAll("[data-status]")
                        .forEach((el) => (el.textContent = "0"));
                } else {
                    const statuses = [
                        "For screening",
                        "Shortlisted",
                        "For Interview",
                        "Scheduled for Interview",
                        "Completed Interview",
                        "Offer Made",
                        "Hired",
                        "Rejected",
                    ];

                    statuses.forEach((status) => {
                        const statusEl = internSection.querySelector(
                            `[data-status='${status}']`
                        );
                        if (statusEl) {
                            if (
                                !statusEl.dataset.originalValue &&
                                statusEl.textContent !== "0"
                            ) {
                                statusEl.dataset.originalValue =
                                    statusEl.textContent;
                            }

                            statusEl.textContent =
                                data.interns.count !== 0
                                    ? statusEl.dataset.originalValue ||
                                      statusEl.textContent
                                    : "0";
                        }
                    });
                }
            }
        }
        // Recruitment Pipeline Tab
        const pipelineSection = document.querySelector("#recruitment");

        if (pipelineSection) {
            const totalAppsEl = pipelineSection.querySelector(".display-3");
            if (totalAppsEl) {
                totalAppsEl.textContent = data.totalApplications;

                if (data.totalApplications === 0) {
                    pipelineSection
                        .querySelectorAll("[data-status]")
                        .forEach((el) => (el.textContent = "0"));
                } else {
                    const stages = [
                        "For screening",
                        "Shortlisted",
                        "For Interview",
                        "Scheduled for Interview",
                        "Completed Interview",
                        "Offer Made",
                        "Hired",
                        "Rejected",
                    ];

                    stages.forEach((stage) => {
                        const stageEl = pipelineSection.querySelector(
                            `[data-status='${stage}']`
                        );
                        if (stageEl) {
                            if (
                                !stageEl.dataset.originalValue &&
                                stageEl.textContent !== "0"
                            ) {
                                stageEl.dataset.originalValue =
                                    stageEl.textContent;
                            }

                            stageEl.textContent =
                                data.totalApplications !== 0
                                    ? stageEl.dataset.originalValue ||
                                      stageEl.textContent
                                    : "0";
                        }
                    });
                }
            }
        }

        const internDaysEl = document.querySelector(
            "#recruitment .text-success"
        );

        if (internDaysEl) {
            internDaysEl.textContent =
                data.interns.avg_days === 0
                    ? "0"
                    : data.interns.avg_days + " Days";
        }

        const fullTimeDaysEl = document.querySelector(
            "#recruitment .text-info"
        );
        if (fullTimeDaysEl) {
            fullTimeDaysEl.textContent =
                data.fullTime.avg_days === 0
                    ? "0"
                    : data.fullTime.avg_days + " Days";
        }

        const partTimeDaysEl = document.querySelector(
            "#recruitment .text-warning"
        );
        if (partTimeDaysEl) {
            partTimeDaysEl.textContent =
                data.partTime.avg_days === 0
                    ? "0"
                    : data.partTime.avg_days + " Days";
        }

        // Update applicant counts text
        const internsTextEl = document.querySelector(
            "#recruitment .text-success"
        ).nextElementSibling;
        if (internsTextEl)
            internsTextEl.textContent = `${data.interns.count} applicants, ${data.interns.hired_count} hired`;

        const fullTimeTextEl = document.querySelector(
            "#recruitment .text-info"
        ).nextElementSibling;
        if (fullTimeTextEl)
            fullTimeTextEl.textContent = `${data.fullTime.count} applicants, ${data.fullTime.hired_count} hired`;

        const partTimeTextEl = document.querySelector(
            "#recruitment .text-warning"
        ).nextElementSibling;
        if (partTimeTextEl)
            partTimeTextEl.textContent = `${data.partTime.count} applicants, ${data.partTime.hired_count} hired`;
    }
});

function updatePartTimePositionsTable(positions) {
    const table = document.querySelector("#part-time tbody");
    table.innerHTML = "";
    // Check if data exists and handle accordingly
    if (!positions || positions.length === 0) {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td colspan="2" class="text-center">No Part-Time position available</td>
        `;
        table.appendChild(row);
        return;
    }
    positions.forEach((position) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${position.jobPosition.position_title}</td>
            <td>${position.count}</td>
        `;
        table.appendChild(row);
    });
}
function updateFullTimePositionsTable(positions) {
    const table = document.querySelector("#full-time tbody");
    table.innerHTML = "";
    // Check if data exists and handle accordingly
    if (!positions || positions.length === 0) {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td colspan="2" class="text-center">No Full-Time position available</td>
        `;
        table.appendChild(row);
        return;
    }
    positions.forEach((position) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${position.jobPosition.position_title}</td>
            <td>${position.count}</td>
        `;
        table.appendChild(row);
    });
}

function updateInternPositionsTable(positions) {
    const table = document.querySelector("#intern tbody");

    // Clear existing rows
    table.innerHTML = "";

    // Check if data exists and handle accordingly
    if (!positions || positions.length === 0) {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td colspan="2" class="text-center">No intern position available</td>
        `;
        table.appendChild(row);
        return;
    }

    // Add new rows with the position data
    positions.forEach((position) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${position.jobPosition.position_title}</td>
            <td>${position.count}</td>
        `;
        table.appendChild(row);
    });
}

function updateUniversitiesTable(data) {
    // Make sure we have the correct selector for the universities table
    const table = document.querySelector("#education tbody");

    if (!table) {
        console.error(
            "Universities table not found with selector '#education tbody'"
        );
        return;
    }

    // Clear existing rows
    table.innerHTML = "";

    // Check if data exists and handle accordingly
    if (!data || data.length === 0) {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td colspan="2" class="text-center">No university data available</td>
        `;
        table.appendChild(row);
        return;
    }

    data.forEach((university) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${university.university || "Unknown"}</td>
            <td>${university.count}</td>
        `;
        table.appendChild(row);
    });
}
