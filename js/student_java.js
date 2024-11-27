document.querySelector('.inquiry-form').addEventListener('submit', async function (event) {
    event.preventDefault(); // Prevent form reload

    // Gather form data
    const formData = {
        firstName: document.getElementById('first-name').value,
        lastName: document.getElementById('last-name').value,
        learnerReferenceNumber: document.getElementById('learner-reference-number').value,
        documentType: document.getElementById('document-type').value,
        details: document.getElementById('details').value,
    };

    // Send data to the server
    const response = await fetch('/api/submit-inquiry', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData),
    });

    if (response.ok) {
        // Optionally fetch the updated history to refresh the table
        fetchInquiryHistory();
    } else {
        alert('Failed to submit the inquiry. Please try again.');
    }
});

async function fetchInquiryHistory() {
    const response = await fetch('/api/inquiry-history');
    const data = await response.json();

    // Populate the table
    const tbody = document.querySelector('#history table tbody');
    tbody.innerHTML = ''; // Clear existing rows

    data.forEach((item) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.date}</td>
            <td>${item.documentType}</td>
            <td><span class="status-${item.status.toLowerCase()}">${item.status}</span></td>
            <td>
                <div class="action-buttons">
                    <button class="view-btn" data-id="${item.id}">View</button>
                    <button class="delete-btn" data-id="${item.id}">Delete</button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}