document.addEventListener('DOMContentLoaded', () => {
    const profileForm = document.getElementById('profileForm');

    profileForm.addEventListener('submit', function(event) {
        event.preventDefault();

        // Get form data
        const formData = new FormData();
        formData.append('fullName', document.getElementById('fullName').value);
        formData.append('email', document.getElementById('email').value);
        formData.append('bio', document.getElementById('bio').value);
        formData.append('interests', document.getElementById('interests').value);
        
        // Send data to the backend using fetch
        fetch('../student-dashboard/update_profile.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Profile updated successfully!');
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('An error occurred while updating your profile.');
        });
    });
});