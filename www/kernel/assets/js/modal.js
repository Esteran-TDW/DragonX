function openModal(type, customContent = null) {
    closeModal();

    const config = modalConfigs[type];

    if (!config) {
        console.error(`Configuration pour le type '${type}' introuvable.`);
        return;
    }

    const modal = document.createElement("div");
    modal.classList.add("modal-overlay");
    modal.classList.add(`overlay-${config.position || 'center'}`);
    if (config.position === "top") {
    modal.classList.add("modal-fixed-top");
}
    // Déterminer la position
    const positionClass = config.position ? `modal-${config.position}` : 'modal-center';

    let headerHTML = "";
    if (config.icon) {
        headerHTML = `<h2 style="color: ${config.color || '#333'};">${config.icon} ${config.title}</h2>`;
    } else {
        headerHTML = `<h2>${config.title}</h2>`;
    }

    const bodyContent = customContent !== null ? customContent : config.content;
    modal.innerHTML = `
        <div class="modal-content ${positionClass}" style="width: ${config.width || '400px'}; border-top: 6px solid ${config.color || '#333'};">
            <div class="modal-header-bar">
                ${config.backButton ? `<button class="modal-back" onclick="openModal('${config.backTarget || 'login'}')">←</button>` : ''}
                <button class="modal-close" onclick="closeModal()">×</button>
            </div>
            ${headerHTML}
            <div class="modal-body">
                ${bodyContent}
            </div>
        </div>
    `;

    // Fermer la modale si on clique sur l'overlay
    modal.addEventListener("click", function (e) {
        // Vérifie si on clique directement sur l'overlay (et pas dans le contenu)
        if (e.target === modal) {
            closeModal();
        }
    });

    document.body.appendChild(modal);

    if (type === 'login') {
        const form = document.getElementById('loginForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const username = form.username.value.trim();
                const password = form.password.value.trim();
                console.log("Tentative de login avec", username, password);
                closeModal();
            });
        }
    }

    if (type === 'register') {
        attachRegisterFormHandler(); // Appel de la fonction globale
    }
}

function closeModal() {
    const modal = document.querySelector(".modal-overlay");
    if (modal) {
        modal.remove();
    }
}

function openRegisterModal() {
    openModal('register'); // Il faudra créer un type "register" dans `modalConfigs`
}
