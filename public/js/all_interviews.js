document.addEventListener('DOMContentLoaded', () => {
    console.log("All Interviews JS loaded");
});

// Function to move candidate to Pending Confirmation tab
function moveToPendingConfirmation(candidateId) {
    if (!candidateId) {
        alert('Invalid candidate ID');
        return;
    }

    if (!confirm('Are you sure you want to move this candidate to Pending Confirmation?')) {
        return;
    }

    fetch(`/candidates/${candidateId}/pending-confirmation`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({})
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                // Reload the page with pending_confirmation tab active
                const url = new URL(window.location);
                url.searchParams.set('step', 'pending_confirmation');
                window.location.href = url.toString();
            } else {
                alert('Failed to move to Pending Confirmation.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while moving to Pending Confirmation.');
        });
}

// Function to confirm schedule and move candidate to Interview Schedule tab
function confirmSchedule(candidateId) {
    if (!candidateId) {
        alert('Invalid candidate ID');
        return;
    }

    if (!confirm('Are you sure you want to confirm the schedule for this candidate?')) {
        return;
    }

    fetch(`/candidates/confirm-schedule/${candidateId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({})
    })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
                // Reload the page to reflect changes
                window.location.reload();
            } else {
                alert('Failed to confirm schedule.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while confirming schedule.');
        });
}

// Function to undo action and move candidate back to previous status
function undoAction(candidateId) {
    if (!candidateId) {
        alert('Invalid candidate ID');
        return;
    }

    if (!confirm('Are you sure you want to undo the last action for this candidate?')) {
        return;
    }

    fetch(`/candidates/${candidateId}/undo`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({})
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                // Reload the page to reflect changes
                window.location.reload();
            } else {
                alert('Failed to undo action.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while undoing action.');
        });
}

// Function to reject candidate and move to Interview Closed tab
function rejectCandidate(candidateId) {
    if (!confirm("Are you sure you want to reject this candidate?")) {
        return;
    }

    fetch(`/candidates/${candidateId}/reject`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Candidate rejected successfully.');
                location.reload(); // Refresh to move candidate to Interview Closed tab
            } else {
                alert("Error rejecting candidate.");
            }
        })
        .catch(error => console.error('Error:', error));
}

// New function to mark candidate as completed
function markAsCompleted(candidateId) {
    if (!candidateId) {
        alert('Invalid candidate ID');
        return;
    }
    if (!confirm('Are you sure you want to mark this interview as complete?')) {
        return;
    }
    fetch(`/candidates/${candidateId}/mark-completed`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({})
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                // Reload page with completed_interview tab active
                const url = new URL(window.location);
                url.searchParams.set('step', 'completed_interview');
                window.location.href = url.toString();
            } else {
                alert('Failed to mark as complete.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while marking as complete.');
        });
}

// New function to open conduct interview form
function conductInterview(candidateId) {
    if (!candidateId) {
        alert('Invalid candidate ID');
        return;
    }
    // Redirect to the conduct interview form page
    window.location.href = `/interviews/conduct/${candidateId}`;
}

// New function to reschedule interview (move back to FOR INTERVIEW tab)
function rescheduleInterview(candidateId) {
    if (!candidateId) {
        alert('Invalid candidate ID');
        return;
    }
    if (!confirm('Are you sure you want to reschedule this interview? This will move the candidate back to the For Interview tab.')) {
        return;
    }
    fetch(`/candidates/${candidateId}/reschedule`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({})
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                const url = new URL(window.location);
                url.searchParams.set('step', 'for_interview');
                window.location.href = url.toString();
            } else {
                alert('Failed to reschedule interview.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while rescheduling interview.');
        });
}

// New function to mark as no show with modal options
function markNoShow(candidateId) {
    if (!candidateId) {
        alert('Invalid candidate ID');
        return;
    }
    // Show a modal with options to Reschedule or Cancel Interview
    // For simplicity, use confirm dialogs here; ideally, implement a proper modal
    if (confirm('Applicant did not attend the interview. Click OK to Reschedule, Cancel to Cancel Interview.')) {
        rescheduleInterview(candidateId);
    } else {
        cancelInterview(candidateId);
    }
}

// New function to cancel interview (move status to Interview Closed)
function cancelInterview(candidateId) {
    if (!candidateId) {
        alert('Invalid candidate ID');
        return;
    }
    if (!confirm('Are you sure you want to cancel this interview? This will move the candidate to the Interview Closed tab.')) {
        return;
    }
    fetch(`/interviews/${candidateId}/cancel`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({})
    })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                alert(data.message);
                const url = new URL(window.location);
                url.searchParams.set('step', 'interview_closed');
                window.location.href = url.toString();
            } else {
                alert('Failed to cancel interview.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while canceling interview.');
        });
}

// New function to reopen interview (move candidate back to For Interview tab)
function reopenInterview(candidateId) {
    if (!candidateId) {
        alert('Invalid candidate ID');
        return;
    }
    if (!confirm('Are you sure you want to reopen this interview? This will move the candidate back to the For Interview tab.')) {
        return;
    }
    fetch(`/candidates/${candidateId}/reopen-interview`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({})
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                const url = new URL(window.location);
                url.searchParams.set('step', 'for_interview');
                window.location.href = url.toString();
            } else {
                alert('Failed to reopen interview.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while reopening interview.');
        });
}

function openMarkPassedModal(candidateId) {
    // Show modal with options: Incomplete Interview (move to For Interview), Complete Interview (initiate Offer)
    // For simplicity, use confirm dialogs; ideally implement proper modal
    if (confirm('Mark as Passed. Click OK for Complete Interview (initiate Offer), Cancel for Incomplete Interview (move to For Interview).')) {
        // Complete Interview - initiate offer
        openOfferDialog(candidateId);
    } else {
        // Incomplete Interview - move to For Interview
        moveToForInterview(candidateId);
    }
}

function openMarkFailedModal(candidateId) {
    // Show modal with options: Incomplete Interview (move to For Interview), Complete Interview (reject email confirmation)
    if (confirm('Mark as Failed. Click OK for Complete Interview (send rejection email), Cancel for Incomplete Interview (move to For Interview).')) {
        // Complete Interview - send rejection email with confirmation modal
        openRejectConfirmationModal(candidateId);
    } else {
        // Incomplete Interview - move to For Interview
        moveToForInterview(candidateId);
    }
}

function openAddFeedbackModal(candidateId) {
    // Open modal or page to add interview feedback
    // For simplicity, redirect to feedback page
    window.location.href = `/interviews/${candidateId}/feedback`;
}

function moveToForInterview(candidateId) {
    if (!candidateId) {
        alert('Invalid candidate ID');
        return;
    }
    if (!confirm('Move candidate back to For Interview tab?')) {
        return;
    }
    fetch(`/candidates/${candidateId}/move-to-for-interview`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({})
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                const url = new URL(window.location);
                url.searchParams.set('step', 'for_interview');
                window.location.href = url.toString();
            } else {
                alert('Failed to move candidate to For Interview.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while moving candidate to For Interview.');
        });
}

function openRejectConfirmationModal(candidateId) {
    // Show confirmation modal before sending rejection email
    if (confirm('Are you sure you want to send rejection email to this candidate?')) {
        sendRejectionEmail(candidateId);
    }
}

function sendRejectionEmail(candidateId) {
    if (!candidateId) {
        alert('Invalid candidate ID');
        return;
    }
    fetch(`/candidates/${candidateId}/send-rejection-email`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({})
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Failed to send rejection email.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while sending rejection email.');
        });
}
