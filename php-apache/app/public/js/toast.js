function closeToast(toastId) {
    const toast = document.getElementById(toastId);
    if (toast) {
        toast.classList.add('translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }
}

function setupAutoCloseToast(toastId, timeout = 5000) {
    setTimeout(() => {
        closeToast(toastId);
    }, timeout);
}

document.addEventListener('DOMContentLoaded', function () {
    const toasts = document.querySelectorAll('[id^="toast-"]');
    toasts.forEach((toast) => {
        const toastId = toast.id;
        setupAutoCloseToast(toastId);
    });
});
