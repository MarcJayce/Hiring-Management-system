// Load Quill CSS
const quillCss = document.createElement("link");
quillCss.rel = "stylesheet";
quillCss.href = "https://cdn.quilljs.com/1.3.6/quill.snow.css";
document.head.appendChild(quillCss);

// Load Quill JS
const quillScript = document.createElement("script");
quillScript.src = "https://cdn.quilljs.com/1.3.6/quill.js";
quillScript.onload = () => {
    // Once Quill is loaded, initialize it when needed
    // You can call your openOfferDialog() or Quill setup logic here if needed
};
document.head.appendChild(quillScript);

document.addEventListener("DOMContentLoaded", () => {
    console.log("Candidates JS loaded");

    // Handle clickable steps (tabs)
    const steps = document.querySelectorAll(".nav-link");
    steps.forEach((step) => {
        step.addEventListener("click", () => {
            steps.forEach((s) => s.classList.remove("active"));
            step.classList.add("active");
            console.log(`Selected step: ${step.dataset.step}`);
        });
    });

    // Handle row click navigation (trigger view button)
    const rows = document.querySelectorAll("tbody tr");
    rows.forEach((row) => {
        row.addEventListener("click", (e) => {
            if (!e.target.closest("button") && !e.target.closest("input")) {
                const viewButton = row.querySelector(".btn-secondary");
                if (viewButton) viewButton.click();
            }
        });
    });

    // Handle pagination buttons
    const prevButton = document.querySelector(
        ".btn-outline-secondary.previous"
    );
    const nextButton = document.querySelector(".btn-outline-secondary.next");

    if (prevButton)
        prevButton.addEventListener("click", (e) => e.preventDefault());
    if (nextButton)
        nextButton.addEventListener("click", (e) => e.preventDefault());

    // Handle "No candidates found" alert
    const alertBox = document.getElementById("noCandidatesAlert");
    if (alertBox) {
        setTimeout(() => {
            alertBox.style.opacity = "0";
            setTimeout(() => alertBox.remove(), 500);
        }, 3000);
    }
});

document.querySelectorAll(".action-button").forEach((button) => {
    button.addEventListener("click", () => {
        $(".modal").modal("hide");
    });
});

window.shortlistCandidate = function (candidateId) {
    fetch(`/candidates/${candidateId}/shortlist`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            "Content-Type": "application/json",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                const button = document.querySelector(
                    `button[onclick="shortlistCandidate(${candidateId})"]`
                );
                if (button) {
                    button.innerText = "Shortlisted";
                    button.disabled = true;
                    button.classList.remove("btn-success");
                    button.classList.add("btn-secondary");
                }

                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: "Candidate has been shortlisted!",
                    timer: 1500,
                    showConfirmButton: false,
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Failed",
                    text: "Failed to update candidate!",
                });
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "An error occurred.",
            });
        });
};

function moveToForInterview(id) {
    fetch(`/candidates/${id}/for-interview`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            "Content-Type": "application/json",
            Accept: "application/json",
        },
        body: JSON.stringify({
            status: "for_interview",
        }),
    })
        .then((response) => {
            return response.text().then((text) => {
                if (!response.ok)
                    throw new Error(
                        `Failed to update candidate! Status: ${response.status}`
                    );
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error("JSON parse error:", e);
                    throw new Error("Invalid JSON response");
                }
            });
        })
        .then((data) => {
            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "Updated!",
                    text: "Candidate moved to For Interview.",
                    timer: 1500,
                    showConfirmButton: false,
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Update Failed",
                    text: data.message || "Unknown error occurred.",
                });
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Error updating candidate: " + error.message,
            });
        });
}

function undoAction(candidateId) {
    fetch(`/candidates/${candidateId}/undo`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                location.reload(); // Reload the page to update the UI
            } else {
                alert(data.message);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("Something went wrong. Please try again.");
        });
}

function moveToInterview(candidateId) {
    fetch(`/candidates/${candidateId}/for-interview`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            "Content-Type": "application/json",
        },
        body: JSON.stringify({}),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                location.reload(); // Reload the page to update the UI
            } else {
                alert("Failed to update candidate!");
            }
        })
        .catch((error) => console.error("Error:", error));
}

function scheduleInterview(candidateId) {
    fetch(`/candidates/${candidateId}/schedule-interview`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            "Content-Type": "application/json",
        },
        body: JSON.stringify({}),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                location.reload(); // Refresh the page to reflect changes
            } else {
                alert("Failed to schedule the interview.");
            }
        })
        .catch((error) => console.error("Error:", error));
}
function conductInterview(button) {
    const interviewId = button.getAttribute("data-interview-id");
    if (!interviewId) {
        alert("Interview ID not found.");
        return;
    }

    // Route pattern: /interviews/{id}/conduct
    const url = `/interviews/conduct/${interviewId}`;
    window.location.href = url;
}

function openScheduleInterviewDialog(candidateId) {
    let existingModal = document.getElementById("scheduleInterviewModal");
    if (existingModal) existingModal.remove(); // Remove existing modal if present

    let modalHtml = `
        <div id="scheduleInterviewModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Schedule Interview</h5>
                        <button type="button" class="close" data-dismiss="modal" onclick="closeScheduleInterviewDialog()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="scheduleInterviewForm">
                            <input type="hidden" id="scheduleCandidateId" value="${candidateId}">
                            <div class="form-group">
                                <label for="interviewer">Select Interviewer</label>
                                <select id="interviewer" class="form-control" required>
                                    <option value="">Loading interviewers...</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="interviewDate">Select Interview Date</label>
                                <input type="date" id="interviewDate" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="interviewTime">Select Interview Time</label>
                                <input type="time" id="interviewTime" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="interviewLocation">Interview Location</label>
                                <textarea id="interviewLocation" class="form-control" placeholder="Enter location" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">Schedule</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML("beforeend", modalHtml);
    $("#scheduleInterviewModal").modal("show"); // Show the Bootstrap modal

    fetch("/api/users")
        .then((response) => response.json())
        .then((users) => {
            const interviewerSelect = document.getElementById("interviewer");
            interviewerSelect.innerHTML =
                '<option value="">Select an interviewer</option>';
            users.forEach((user) => {
                const option = document.createElement("option");
                option.value = user.id;
                option.textContent = user.name;
                interviewerSelect.appendChild(option);
            });
        })
        .catch((error) => {
            console.error("Error fetching users:", error);
            const interviewerSelect = document.getElementById("interviewer");
            interviewerSelect.innerHTML =
                '<option value="">Error loading interviewers</option>';
        });
    // Handle form submission
    document
        .getElementById("scheduleInterviewForm")
        .addEventListener("submit", function (event) {
            event.preventDefault();

            let interviewerId = document.getElementById("interviewer").value;
            let interviewDate = document.getElementById("interviewDate").value;
            let interviewTime = document.getElementById("interviewTime").value;
            let interviewLocation =
                document.getElementById("interviewLocation").value;
            let candidateId = document.getElementById(
                "scheduleCandidateId"
            ).value;

            if (
                !interviewerId ||
                !interviewDate ||
                !interviewTime ||
                !interviewLocation
            ) {
                alert("Please fill in all fields.");
                return;
            }

            fetch(`/candidates/${candidateId}/schedule-interview`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    interviewer: interviewerId,
                    interview_date: interviewDate,
                    interview_time: interviewTime,
                    location: interviewLocation,
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        closeScheduleInterviewDialog();
                        location.reload();
                    } else {
                        alert("Error scheduling interview.");
                    }
                })
                .catch((error) => console.error("Error:", error));
        });
}

// Function to close the modal manually
function closeScheduleInterviewDialog() {
    $("#scheduleInterviewModal").modal("hide");
    setTimeout(() => {
        document.getElementById("scheduleInterviewModal")?.remove();
    }, 300);
}

function markAsCompleted(candidateId) {
    fetch(`/candidates/${candidateId}/mark-completed`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            "Content-Type": "application/json",
        },
        body: JSON.stringify({}),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                location.reload(); // Refresh the page to reflect changes
            } else {
                alert("Failed to schedule the interview.");
            }
        })
        .catch((error) => console.error("Error:", error));
}

// Offer
function openOfferDialog(candidateId, positionTitle, firstName, lastName) {
    let existingModal = document.getElementById("offerModal");
    if (existingModal) existingModal.remove(); // Remove existing modal if present

    let modalHtml = `
        <div id="offerModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Make an Offer</h5>
                        <button type="button" class="close" data-dismiss="modal" onclick="closeOfferDialog()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="offerForm">
                            <input type="hidden" id="offerCandidateId" value="${candidateId}">
                            <div class="form-group">
                                <label for="offerDate">Offer Date</label>
                                <input type="date" id="offerDate" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="offerEndDate">Offer End Date</label>
                                <input type="datetime-local" id="offerEndDate" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="startDate">Start Date</label>
                                <input type="date" id="startDate" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="emailTemplate">Email Template</label>
                                <select id="emailTemplate" class="form-control">
                                    <option value="">Select a template</option>
                                    <option value="Regular">Standard Offer Letter</option>
                                    <option value="Intern">Internship Offer</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="emailForm">Email Content</label>
                                <!-- Quill Editor Container -->
                                <div id="emailEditor" style="height: 300px;"></div>

                                <!-- Hidden input to hold Quill content for submission -->
                                <input type="hidden" name="email_content" id="emailContentInput" required>
                            </div>
                            <div class="form-group">
                                <label for="offerAttachment">Attachments (PDF, DOCX, etc.)</label>
                                <input type="file" id="offerAttachment" name="attachment" class="form-control" multiple>
                            </div>
                            <button type="submit" class="btn btn-success">Send Offer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML("beforeend", modalHtml);
    $("#offerModal").modal("show"); // Show the Bootstrap modal
    let quill = new Quill("#emailEditor", {
        theme: "snow",
    });
    const offerDateInput = document.getElementById("offerDate");
    const today = new Date().toISOString().split("T")[0];
    if (offerDateInput) {
        offerDateInput.value = today;
    }
    // Handle form submission
    // Handle email template selection
    document
        .getElementById("emailTemplate")
        .addEventListener("change", function () {
            const template = this.value;
            quill.root.innerHTML = ""; // Clear editor

            let html = "";
            if (template === "Regular") {
                html = `
    <body style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -webkit-text-size-adjust: none; background-color: #ffffff; color: rgb(0, 0, 0); height: 100%; line-height: 1.4; margin: 0; padding: 0; width: 100% !important; font-size: 16px;">
    <p>Dear ${firstName} ${lastName}</p>          
    <p>Greetings!</p>
    <p>On behalf of Chimes Consulting, we are pleased to extend to you a formal offer for the position of <strong>${positionTitle}</strong>. Please review this summary of terms and conditions for your anticipated employment with us. </p>
    <p>If you accept this offer, you start date will be <strong>[start date]</strong></p>
    <p>Please find attached the terms and conditions of your employment, should you accept this offer letter. We would like to have your response by <strong>[offer end date]</strong>. In the meantime, please feel free to send us an email  if you have any questions.
    </p>
    <p>We  are looking forward to having you on our team<p>
    <p>Thank you and congratulations!</p>
    </body>
    `;
            } else if (template === "Intern") {
                html = `
    <body style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -webkit-text-size-adjust: none; background-color: #ffffff; color: rgb(0, 0, 0); height: 100%; line-height: 1.4; margin: 0; padding: 0; width: 100% !important; font-size: 16px;">
    <p>Dear ${firstName} ${lastName}</p>          
    <p>Greetings!</p>       
    <p>On behalf of Chimes Consulting, we would like to extend our heartfelt thanks for choosing us as your internship host. We are pleased to offer you an internship position as an <strong>${positionTitle}</strong>.</p>
    <p>Kindly confirm receipt of this email and inform us of your decision regarding the internship offer <strong>on or before [offer end date]</strong>, so that we can begin your onboarding process.</p>
    <p>Your proposed start date is <strong>[start date]</strong>, unless there are additional documents you need to complete beforehand. If so, please feel free to coordinate with us so we can assist you accordingly.</p>
    <p>Should you have any questions or require further information, do not hesitate to reach out via email. We look forward to welcoming you to the team.</p>
    </body>`;
            }

            quill.root.innerHTML = html;
            updateEmailPlaceholders(quill);
        });

    function updateEmailPlaceholders(quill) {
        const offerDate = document.getElementById("offerDate").value;
        const startDate = document.getElementById("startDate").value;
        const offerEndDate = document.getElementById("offerEndDate").value;

        let html = quill.root.innerHTML;

        // Helper function to format datetime-local string nicely
        function formatDateTime(datetimeString) {
            if (!datetimeString) return "[date/time]";
            const options = {
                year: "numeric",
                month: "long",
                day: "numeric",
                hour: "2-digit",
                minute: "2-digit",
                hour12: true,
            };
            return new Date(datetimeString).toLocaleString(undefined, options);
        }

        // Helper function to format date only
        function formatDate(dateString) {
            if (!dateString) return "[date]";
            const options = {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
            };
            return new Date(dateString).toLocaleDateString(undefined, options);
        }

        html = html.replace(/\[offer date\]/gi, formatDate(offerDate));
        html = html.replace(/\[start date\]/gi, formatDate(startDate));
        html = html.replace(
            /\[offer end date\]/gi,
            formatDateTime(offerEndDate)
        );

        quill.root.innerHTML = html;
    }
    document
        .getElementById("offerDate")
        .addEventListener("input", () => updateEmailPlaceholders(quill));
    document
        .getElementById("startDate")
        .addEventListener("input", () => updateEmailPlaceholders(quill));
    document
        .getElementById("offerEndDate")
        .addEventListener("input", () => updateEmailPlaceholders(quill));
    updateEmailPlaceholders(quill);

    document
        .getElementById("offerForm")
        .addEventListener("submit", function (event) {
            event.preventDefault();

            let offerDate = document.getElementById("offerDate").value;
            let startDate = document.getElementById("startDate").value;
            let offerEndDate = document.getElementById("offerEndDate").value; // Get the offer end date
            let candidateId = document.getElementById("offerCandidateId").value;
            let emailContent = quill.root.innerHTML; // Get content from Quill editor

            if (!offerDate || !startDate || !offerEndDate) {
                alert("Please fill in all fields.");
                return;
            }

            let formData = new FormData();
            formData.append("offer_date", offerDate);
            formData.append("start_date", startDate);
            formData.append("offer_end_date", offerEndDate);
            formData.append("email_content", emailContent);
            formData.append("candidate_id", candidateId);

            // Append file(s)
            let fileInput = document.getElementById("offerAttachment");
            if (fileInput && fileInput.files.length > 0) {
                for (let i = 0; i < fileInput.files.length; i++) {
                    formData.append("attachments[]", fileInput.files[i]);
                }
            }

            fetch(`/candidates/${candidateId}/make-offer`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: formData,
            })
                .then((response) => response.json())
                .then((data) => {
                    console.log(data);
                    if (data.success) {
                        closeOfferDialog();
                        location.reload();

                        // Move candidate to Offer Tab
                        let candidateRow = document
                            .querySelector(
                                `[data-candidate-id="${candidateId}"]`
                            )
                            .closest("tr");
                        let offerTab = document.getElementById("offerTab");

                        if (candidateRow && offerTab) {
                            candidateRow.querySelector(
                                ".status-column"
                            ).textContent = "Offer Made";
                            offerTab.appendChild(candidateRow);
                        }

                        $("#offerModal").modal("hide"); // Close modal immediately
                        setTimeout(() => {
                            document.getElementById("offerModal")?.remove();
                        }, 300);
                    } else {
                        alert("Error making offer.");
                    }
                })
                .catch((error) => console.error("Error:", error));
        });
}

// Function to close the modal manually
function closeOfferDialog() {
    $("#offerModal").modal("hide");
    setTimeout(() => {
        document.getElementById("offerModal")?.remove();
    }, 300);
}
//Mark As Offer Accepted
function markOfferAccepted(candidateId) {
    Swal.fire({
        title: "Are you sure?",
        text: 'This will mark the candidate as "Offer Accepted".',
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, proceed!",
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/candidates/${candidateId}/offer-accepted`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({}),
            })
                .then((response) => {
                    if (response.ok) {
                        Swal.fire({
                            icon: "success",
                            title: "Success!",
                            text: 'Candidate marked as "Offer Accepted".',
                            timer: 2000,
                            showConfirmButton: false,
                        }).then(() => location.reload());
                    } else {
                        Swal.fire("Error", "Failed to update status.", "error");
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    Swal.fire("Error", "An error occurred.", "error");
                });
        }
    });
}

// Hire
function openHireDialog(candidateId) {
    let existingModal = document.getElementById("hireModal");
    if (existingModal) existingModal.remove(); // Remove existing modal if present

    let modalHtml = `
        <div id="hireModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hire Candidate</h5>
                        <button type="button" class="close" data-dismiss="modal" onclick="closeHireDialog()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="hireForm">
                            <input type="hidden" id="hireCandidateId" value="${candidateId}">
                            <div class="form-group">
                                <label for="department">Department</label>
                                <input type="text" id="department" class="form-control" placeholder="Enter Department" required>
                            </div>
                            <div class="form-group">
                                <label for="hiringDate">Hiring Date</label>
                                <input type="date" id="hiringDate" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-success">Confirm Hire</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML("beforeend", modalHtml);
    $("#hireModal").modal("show"); // Show the Bootstrap modal

    // Handle form submission
    document
        .getElementById("hireForm")
        .addEventListener("submit", function (event) {
            event.preventDefault();

            let department = document.getElementById("department").value;
            let hiringDate = document.getElementById("hiringDate").value;
            let candidateId = document.getElementById("hireCandidateId").value;

            if (!department || !hiringDate) {
                alert("Please fill in all fields.");
                return;
            }

            // Send data to Laravel backend
            fetch(`/candidates/${candidateId}/hire`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ department, hiring_date: hiringDate }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        closeHireDialog();
                        location.reload();

                        // Move candidate to Hired Tab dynamically
                        let candidateRow = document
                            .querySelector(
                                `[data-candidate-id="${candidateId}"]`
                            )
                            .closest("tr");
                        let hiredTab = document.getElementById("hiredTab");

                        if (candidateRow && hiredTab) {
                            candidateRow.querySelector(
                                ".status-column"
                            ).textContent = "Hired";
                            hiredTab.appendChild(candidateRow);
                        }

                        $("#hireModal").modal("hide");
                        setTimeout(() => {
                            document.getElementById("hireModal")?.remove();
                        }, 300);
                    } else {
                        alert("Error hiring candidate.");
                    }
                })
                .catch((error) => console.error("Error:", error));
        });
}

// Function to close the modal manually
function closeHireDialog() {
    $("#hireModal").modal("hide");
    setTimeout(() => {
        document.getElementById("hireModal")?.remove();
    }, 300);
}

function sendHiredEmail(candidateId, candidateEmail) {
    Swal.fire({
        title: "Send Hiring Email?",
        text: `Are you sure you want to send the hiring email to ${candidateEmail}?`,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Yes, send it!",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            // Proceed with sending the email
            fetch(`/candidates/${candidateId}/send-hire-email`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ email: candidateEmail }),
            })
                .then((res) => {
                    if (!res.ok) throw new Error("Failed to send hired email.");
                    return res.json();
                })
                .then((data) => {
                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Email Sent!",
                            text: "The hiring email was successfully sent.",
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Failed",
                            text: "Failed to send the hiring email.",
                        });
                    }
                })
                .catch((err) => {
                    console.error("Error sending email:", err);
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Something went wrong while sending the email.",
                    });
                });
        }
    });
}

// Reject
function rejectCandidate(candidateId) {
    Swal.fire({
        title: "Reject Candidate?",
        text: "This action cannot be undone.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Proceed",
        cancelButtonText: "Cancel",
        reverseButtons: false,
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/candidates/${candidateId}/reject`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    "Content-Type": "application/json",
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        let candidateElement = document.querySelector(
                            `[data-candidate-id="${candidateId}"]`
                        );
                        if (candidateElement) {
                            let candidateRow = candidateElement.closest("tr");
                            let rejectedTab =
                                document.getElementById("rejectedTab");

                            if (candidateRow && rejectedTab) {
                                candidateRow.querySelector(
                                    ".status-column"
                                ).textContent = "Rejected";
                                rejectedTab.appendChild(candidateRow);
                            }
                        }

                        Swal.fire(
                            "Rejected!",
                            "Candidate has been rejected.",
                            "success"
                        );
                    } else {
                        Swal.fire(
                            "Error",
                            "Error rejecting candidate.",
                            "error"
                        );
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    Swal.fire(
                        "Error",
                        "An unexpected error occurred.",
                        "error"
                    );
                });
        }
    });
}

function sendRejectionEmail(candidateId, candidateEmail) {
    Swal.fire({
        title: "Send Rejection Email?",
        text: `Are you sure you want to send the rejection email to ${candidateEmail}?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, send it!",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/candidates/${candidateId}/send-rejection-email`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ email: candidateEmail }),
            })
                .then((res) => {
                    if (!res.ok)
                        throw new Error("Failed to send rejection email.");
                    return res.json();
                })
                .then((data) => {
                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Email Sent!",
                            text: "The rejection email was successfully sent.",
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Failed",
                            text: "Failed to send the rejection email.",
                        });
                    }
                })
                .catch((err) => {
                    console.error("Error sending email:", err);
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Something went wrong while sending the email.",
                    });
                });
        }
    });
}

function reconsiderCandidate(candidateId) {
    Swal.fire({
        title: "Reconsider Candidate?",
        text: "This will update the candidate's status based on their interview history.",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, proceed!",
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/candidates/${candidateId}/reconsider`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({}),
            })
                .then((response) => response.json()) // parse JSON here
                .then((data) => {
                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Success!",
                            text: data.message, // show controller message here
                            timer: 2000,
                            showConfirmButton: false,
                        }).then(() => location.reload());
                    } else {
                        Swal.fire(
                            "Error",
                            data.message || "Failed to update status.",
                            "error"
                        );
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    Swal.fire("Error", "An error occurred.", "error");
                });
        }
    });
}
// Your email template function with date/time placeholders
function to12HourFormat(time24) {
    if (!time24 || time24 === "00:00") return "12NN";
    const [hourStr, minute] = time24.split(":");
    let hour = parseInt(hourStr, 10);
    const ampm = hour >= 12 ? "PM" : "AM";
    hour = hour % 12 || 12; // convert 0 to 12 for midnight
    return `${hour}:${minute} ${ampm}`;
}
function getEmailTemplate(date, time) {
    const dateText = date
        ? new Date(date).toLocaleDateString(undefined, {
              month: "long",
              day: "numeric",
              year: "numeric",
          })
        : "May 24";

    // Convert time from 24-hour to 12-hour format here
    const timeText = time ? to12HourFormat(time) : "12NN";
    // Pre invtation email
    return `
    <p>Good day,</p>
    <p>Thank you for applying!</p>
    <p>We’re excited to move forward with your application and would like to invite you to the next step of the process.</p>
    <p>To book your interview, please fill out the form through the link below:</p>
    <p>👉 <a href="https://form.jotform.com/251411371890453" target="_blank" rel="noopener noreferrer">https://form.jotform.com/251411371890453</a></p>
    <p>Kindly complete and submit the form on or before <strong>${dateText}, ${timeText}</strong> so we can finalize your interview schedule.</p>
    <p>If you have any questions, feel free to reply to this email. We look forward to speaking with you soon!</p>
    <p>Best regards</p>
    <p>HR Department</p>
    <p>hr@chimesconsulting.com</p>
    <p>Chimes Consulting</p>
  `;
}

function openInterviewInviteModal(candidateId, candidateName) {
    // Remove existing modal if any
    const existingModal = document.getElementById(
        `interviewInviteModal-${candidateId}`
    );

    if (existingModal) {
        // Use jQuery to show modal (Bootstrap 4)
        $(`#interviewInviteModal-${candidateId}`).modal("show");
        return;
    }

    // Insert modal HTML (same as you have)
    const modalHtml = `
    <div class="modal fade" id="interviewInviteModal-${candidateId}" tabindex="-1" aria-labelledby="inviteModalLabel-${candidateId}" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content p-3">
          <div class="modal-header">
            <h5 class="modal-title" id="inviteModalLabel-${candidateId}">Send Interview Invite to ${candidateName}</h5>
            <button type="button" class="close" data-dismiss="modal" onclick="closeInterviewInviteModal(${candidateId})">&times;</button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="interviewDate-${candidateId}" class="form-label">Deadline Date</label>
              <input type="date" class="form-control" id="interviewDate-${candidateId}">
            </div>
            <div class="mb-3">
              <label for="interviewTime-${candidateId}" class="form-label">Deadline Time</label>
              <input type="time" class="form-control" id="interviewTime-${candidateId}">
            </div>
            <div class="mb-3">
              <label for="emailEditor-${candidateId}" class="form-label">Email Content</label>
              <div id="emailEditor-${candidateId}" style="height: 250px; background: #fff;"></div>
              <input type="hidden" id="emailBody-${candidateId}">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" onclick="updateEmailAndSend(${candidateId})">Send Email</button>
          </div>
        </div>
      </div>
    </div>
  `;

    document.body.insertAdjacentHTML("beforeend", modalHtml);

    // Show the modal with Bootstrap
    const modalElement = document.getElementById(
        `interviewInviteModal-${candidateId}`
    );
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
    window[`modalInstance-${candidateId}`] = modal;
    // Initialize Quill editor (avoid multiple initialization)
    if (!window.quillEditors) window.quillEditors = {};
    if (!window.quillEditors[`emailEditor-${candidateId}`]) {
        window.quillEditors[`emailEditor-${candidateId}`] = new Quill(
            `#emailEditor-${candidateId}`,
            { theme: "snow" }
        );
    }
    const quill = window.quillEditors[`emailEditor-${candidateId}`];

    // Get inputs
    const dateInput = document.getElementById(`interviewDate-${candidateId}`);
    const timeInput = document.getElementById(`interviewTime-${candidateId}`);

    // Function to update Quill content based on inputs
    function updateEmailContent() {
        const emailHtml = getEmailTemplate(dateInput.value, timeInput.value);
        quill.root.innerHTML = emailHtml;
    }

    // Set initial content with placeholders
    updateEmailContent();

    // Update email content live on input change
    dateInput.addEventListener("input", updateEmailContent);
    timeInput.addEventListener("input", updateEmailContent);
}

function closeInterviewInviteModal(candidateId) {
    $(`#interviewInviteModal-${candidateId}`).modal("hide");
    setTimeout(() => {
        document
            .getElementById(`#interviewInviteModal-${candidateId}`)
            ?.remove();
    }, 300);
}
// Your send function grabs quill content when sending
function updateEmailAndSend(candidateId) {
    const quill = window.quillEditors[`emailEditor-${candidateId}`];
    if (!quill) {
        Swal.fire("Error", "Email editor not initialized!", "error");
        return;
    }

    const emailContent = quill.root.innerHTML;
    const date = document.getElementById(`interviewDate-${candidateId}`).value;
    const time = document.getElementById(`interviewTime-${candidateId}`).value;

    fetch(`/candidates/${candidateId}/send-interview-invite`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            date,
            time,
            emailContent,
        }),
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.success) {
                Swal.fire(
                    "Success",
                    "Interview invite sent successfully!",
                    "success"
                ).then(() => {
                    closeInterviewInviteModal(candidateId);
                });
            } else {
                Swal.fire(
                    "Failed",
                    "Failed to send interview invite.",
                    "error"
                );
            }
        })
        .catch((err) => {
            console.error("Error sending interview invite:", err);
            Swal.fire("Error", "Error sending email.", "error");
        });
}
