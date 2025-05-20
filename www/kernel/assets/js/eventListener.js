function attachRegisterFormHandler() {
    const form = document.getElementById('registerForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const password = form.password.value.trim();
        const confirmPassword = form.confirmPassword.value.trim();
        const errorDiv = document.getElementById('passwordError');

        if (password !== confirmPassword) {
            errorDiv.style.display = 'block';
        } else {
            errorDiv.style.display = 'none';
            console.log("Création de compte avec succès !");
            closeModal();
            // Tu peux ajouter ici un appel à une fonction d'inscription
        }
    });
}
