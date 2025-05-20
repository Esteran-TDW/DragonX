const modalConfigs = {
    debug: {
        title: "Debug",
        content: "<p>Information de débogage pour développeur.</p>",
        width: "500px",
        color: "#888", // gris
        icon: "🛠️",
        position: "top", // "top", "center"
        closeButtonPosition: "" // "top" ou "bottom"
    },
    info: {
        title: "Information",
        content: "<p>Ceci est un message d'information important.</p>",
        width: "400px",
        color: "#2196F3", // bleu
        icon: "ℹ️",
        position: "top", // "top", "center"
        closeButtonPosition: "" // "top" ou "bottom"
    },
    notice: {
        title: "Notice",
        content: "<p>Petit avertissement : tout fonctionne mais attention.</p>",
        width: "400px",
        color: "#4CAF50", // vert
        icon: "📌",
        position: "top", // "top", "center"
        closeButtonPosition: "" // "top" ou "bottom"
    },
    warning: {
        title: "Avertissement",
        content: "<p>Attention, un problème potentiel a été détecté.</p>",
        width: "400px",
        color: "#FF9800", // orange
        icon: "⚠️",
        position: "top", // "top", "center"
        closeButtonPosition: "" // "top" ou "bottom"

        
    },
    error: {
        title: "Erreur",
        content: "<p>Une erreur est survenue. Merci de réessayer plus tard.</p>",
        width: "400px",
        color: "#f44336", // rouge
        icon: "❌",
        position: "top", // "top", "center"
        closeButtonPosition: "" // "top" ou "bottom"
    },
    critical: {
        title: "Critique",
        content: "<p>Erreur critique. Intervention nécessaire.</p>",
        width: "400px",
        color: "#D32F2F", // rouge foncé
        icon: "🚨",
        position: "top", // "top", "center"
        closeButtonPosition: "" // "top" ou "bottom"
    },
    alert: {
        title: "Alerte",
        content: "<p>Alerte système : agissez immédiatement.</p>",
        width: "400px",
        color: "#C2185B", // rose foncé
        icon: "🚨",
        position: "top", // "top", "center"
        closeButtonPosition: "" // "top" ou "bottom"
    },
    emergency: {
        title: "Urgence",
        content: "<p>Système indisponible. Intervention urgente requise !</p>",
        width: "400px",
        color: "#B71C1C", // rouge très foncé
        icon: "🆘",
        position: "top", // "top", "center"
        closeButtonPosition: "" // "top" ou "bottom"
    },
    success: {
        title: "Succès",
        content: "<p>Opération réussie !</p>",
        width: "400px",
        color: "#4CAF50", // vert
        icon: "✅",
        position: "top", // "top", "center"
        closeButtonPosition: "" // "top" ou "bottom"
    },
    confirm: {
        title: "Confirmation",
        content: "<p>Êtes-vous sûr de vouloir continuer ?</p>",
        width: "400px",
        color: "#FF9800", // orange
        icon: "❓",
        position: "top", // "top", "center"
        closeButtonPosition: "" // "top" ou "bottom"
    },
    login: {
        title: "Connexion requise",
        content: `
            <form id="loginForm" class="login-form">
                <input type="text" id="username" name="username" placeholder="Nom d'utilisateur" required>
                <div class="password-field">
                    <input type="password" id="password" name="password" placeholder="Mot de passe" required>
                    <span class="toggle-password" onclick="togglePassword(this)">👁️</span>
                </div>
                <button type="submit">Connexion</button>
                <p class="create-account-link">
                    Pas encore de compte ?
                    <a href="#" onclick="openRegisterModal()">Créer un compte</a>
                </p>
            </form>
        `,
        color: "#333",
        width: "400px",
        icon: "🔑",
        position: "center", // "top", "center"
        closeButtonPosition: "", // "top" ou "bottom"
        closeButtonVisibility: false, // true ou false
        
    },
    register: {
        title: "Création de compte",
        content: `
            <form id="registerForm" class="registrer-form">
                <input type="text" id="newUsername" name="username" placeholder="Nom d'utilisateur" required>
                <div class="password-field">
                    <input type="password" id="newPassword" name="password" placeholder="Mot de passe" required>
                    <span class="toggle-password" onclick="togglePassword(this)">👁️</span>
                </div>
                <div class="password-field">
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirmer le mot de passe" required>
                    <span class="toggle-password" onclick="togglePassword(this)">👁️</span>
                </div>
                <div id="passwordError" style="color: red; display: none; margin-top: 5px;">
                    Les mots de passe ne correspondent pas.
                </div>
                <button type="submit">Créer le compte</button>
            </form>
        `,
        color: "green",
        width: "400px",
        icon: "📝",
        position: "center",
        closeButtonPosition: "",
        closeButtonVisibility: true,
        backButton: true, // 👈 Back Button
        backTarget: "login" // 👈 vers quelle modale revenir
    },
};
