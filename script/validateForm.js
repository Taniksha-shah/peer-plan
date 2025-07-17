function validateForm() {
  // Get form fields
  const username = document.getElementById("username").value.trim();
  const password = document.getElementById("password").value;

  // Remove any previous alerts
  const oldAlert = document.querySelector(".alert");
  if (oldAlert) oldAlert.remove();

  // Create a container for error message
  const form = document.getElementById("registration-form-container");

  if (username.length < 3) {
    showError("Username must be at least 3 characters");
    return false;
  }

  if (password.length < 6) {
    showError("Password must be at least 6 characters");
    return false;
  }

  return true; // All good, allow form to submit

  // Helper function to show error message
  function showError(message) {
    const errorDiv = document.createElement("div");
    errorDiv.className = "alert alert-danger mt-2";
    errorDiv.innerText = message;
    form.insertBefore(errorDiv, form.querySelector("button"));
  }
}
