function togglePassword(toggleElement) {
    const input = toggleElement.previousElementSibling;
    if (input.type === "password") {
        input.type = "text";
        toggleElement.textContent = "🙈";
    } else {
        input.type = "password";
        toggleElement.textContent = "👁️";
    }
}