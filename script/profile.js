document.addEventListener("DOMContentLoaded", () => {
    const profileForm = document.getElementById("profileForm");
    const fullNameInput = document.getElementById("fullName");
    const bioInput = document.getElementById("bio");
    const usernameInput = document.getElementById("username");
    const emailInput = document.getElementById("email");
    const usernameDisplay = document.querySelector(".username-display");
    const profileUsernameDisplay = document.querySelector(".profile-username-display");

    // Function to fetch and display the user's profile data
    function loadProfile() {
        fetch('get_profile.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success' && data.profile) {
                    // Populate the form fields with data from the database
                    if (data.profile.full_name) {
                        fullNameInput.value = data.profile.full_name;
                    }
                    if (data.profile.bio) {
                        bioInput.value = data.profile.bio;
                    }
                    if (data.profile.username) {
                        usernameInput.value = data.profile.username;
                        // Also update the username in the top right and main card
                        usernameDisplay.textContent = data.profile.username;
                        profileUsernameDisplay.textContent = data.profile.username;
                    }
                    if (data.profile.email) {
                        emailInput.value = data.profile.email;
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching profile:', error);
                alert('Failed to load profile data.');
            });
    }

    // Function to handle the form submission and save changes
    profileForm.addEventListener("submit", (e) => {
        e.preventDefault();

        const formData = new FormData(profileForm);

        fetch('update_profile.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                alert('Profile updated successfully!');
                const newName = fullNameInput.value.trim();
                if (newName) {
                    profileUsernameDisplay.textContent = newName;
                }
            } else {
                alert('Error updating profile: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error updating profile:', error);
            alert('Failed to update profile.');
        });
    });

    // Load the profile data when the page loads
    loadProfile();
});