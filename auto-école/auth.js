// Gestion de l'authentification et des formulaires

// Afficher le formulaire d'inscription
function showRegisterForm() {
    document.getElementById('registerSection').style.display = 'block';
    document.querySelector('.form-container').style.display = 'none';
}

// Afficher le formulaire de connexion
function showLoginForm() {
    document.getElementById('registerSection').style.display = 'none';
    document.querySelector('.form-container').style.display = 'block';
}

// Afficher/masquer les champs spécifiques aux candidats
document.getElementById('regUserType')?.addEventListener('change', function() {
    const candidatFields = document.getElementById('candidatFields');
    if (this.value === 'candidat') {
        candidatFields.style.display = 'block';
    } else {
        candidatFields.style.display = 'none';
    }
});

// Afficher/masquer les champs école pour les étudiants
document.getElementById('estEtudiant')?.addEventListener('change', function() {
    const ecoleFields = document.getElementById('ecoleFields');
    if (this.checked) {
        ecoleFields.style.display = 'block';
    } else {
        ecoleFields.style.display = 'none';
    }
});

// Gestion du formulaire de connexion
document.getElementById('loginForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const userType = document.getElementById('userType').value;
    
    // Simulation de la connexion
    if (email && password && userType) {
        // Stocker les informations de session
        localStorage.setItem('userEmail', email);
        localStorage.setItem('userType', userType);
        
        // Rediriger vers le tableau de bord approprié
        switch(userType) {
            case 'candidat':
                window.location.href = 'dashboard-candidat.html';
                break;
            case 'moniteur':
                window.location.href = 'dashboard-moniteur.html';
                break;
            case 'administrateur':
                window.location.href = 'dashboard-admin.html';
                break;
        }
    } else {
        showAlert('Veuillez remplir tous les champs', 'danger');
    }
});

// Gestion du formulaire d'inscription
document.getElementById('registerForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const nom = document.getElementById('regNom').value;
    const prenom = document.getElementById('regPrenom').value;
    const email = document.getElementById('regEmail').value;
    const password = document.getElementById('regPassword').value;
    const confirmPassword = document.getElementById('regConfirmPassword').value;
    const userType = document.getElementById('regUserType').value;
    
    // Validation
    if (password !== confirmPassword) {
        showAlert('Les mots de passe ne correspondent pas', 'danger');
        return;
    }
    
    if (password.length < 6) {
        showAlert('Le mot de passe doit contenir au moins 6 caractères', 'danger');
        return;
    }
    
    // Simulation de l'inscription
    const userData = {
        nom: nom,
        prenom: prenom,
        email: email,
        password: password,
        userType: userType
    };
    
    // Ajouter les données spécifiques aux candidats
    if (userType === 'candidat') {
        userData.datePermis = document.getElementById('datePermis').value;
        userData.dateCode = document.getElementById('dateCode').value;
        userData.estEtudiant = document.getElementById('estEtudiant').checked;
        
        if (userData.estEtudiant) {
            userData.nomEcole = document.getElementById('nomEcole').value;
            userData.adresseEcole = document.getElementById('adresseEcole').value;
        }
    }
    
    // Stocker les données (simulation)
    localStorage.setItem('userData', JSON.stringify(userData));
    
    showAlert('Inscription réussie ! Vous pouvez maintenant vous connecter.', 'success');
    
    // Retourner au formulaire de connexion après 2 secondes
    setTimeout(() => {
        showLoginForm();
    }, 2000);
});

// Fonction pour afficher les alertes
function showAlert(message, type) {
    const alertContainer = document.getElementById('alert-container');
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    alertContainer.innerHTML = '';
    alertContainer.appendChild(alert);
}

// Vérifier si l'utilisateur est connecté
function checkAuth() {
    const userEmail = localStorage.getItem('userEmail');
    const userType = localStorage.getItem('userType');
    
    if (userEmail && userType) {
        return { email: userEmail, type: userType };
    }
    return null;
}

// Déconnexion
function logout() {
    localStorage.removeItem('userEmail');
    localStorage.removeItem('userType');
    localStorage.removeItem('userData');
    window.location.href = 'index.html';
}

// Rediriger si déjà connecté
if (window.location.pathname.includes('connexion.html')) {
    const user = checkAuth();
    if (user) {
        switch(user.type) {
            case 'candidat':
                window.location.href = 'dashboard-candidat.html';
                break;
            case 'moniteur':
                window.location.href = 'dashboard-moniteur.html';
                break;
            case 'administrateur':
                window.location.href = 'dashboard-admin.html';
                break;
        }
    }
}

