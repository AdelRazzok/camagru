document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.querySelector('#password');
    const passwordVisibilityIcon = document.querySelector('#toggleVisibilityIcon i');

    let isPasswordVisible = passwordInput.getAttribute('type') === 'text';

    function togglePasswordVisibility() {
        isPasswordVisible = !isPasswordVisible;
        passwordInput.setAttribute('type', isPasswordVisible ? 'text' : 'password');
    }

    function toggleVisibilityIcon() {
        passwordVisibilityIcon.classList.toggle('fa-eye', isPasswordVisible);
        passwordVisibilityIcon.classList.toggle('fa-eye-slash', !isPasswordVisible);
    }

    passwordVisibilityIcon.addEventListener('click', () => {
        toggleVisibilityIcon();
        togglePasswordVisibility();
    });
});
