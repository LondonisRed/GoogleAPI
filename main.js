document.addEventListener('DOMContentLoaded', function() {
    loadEmails();
});

function loadEmails() {
    fetch('read.php')
        .then(response => response.json())
        .then(data => {
            let emailsDiv = document.getElementById('emails');
            data.forEach(email => {
                let emailDiv = document.createElement('div');
                emailDiv.textContent = email.snippet;
                emailsDiv.appendChild(emailDiv);
            });
        });
}

function sendMail() {
    let formData = new FormData();
    formData.append('to', document.getElementById('to').value);
    formData.append('subject', document.getElementById('subject').value);
    formData.append('body', document.getElementById('body').value);
    formData.append('attachment', document.getElementById('attachment').files[0]);

    fetch('send.php', {
        method: 'POST',
        body: formData
    }).then(response => {
        if (response.ok) {
            alert('Email sent successfully');
        } else {
            alert('Failed to send email');
        }
    });
}
