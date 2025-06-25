// Vérifier l'authentification au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    const user = checkAuth();
    if (!user || user.type !== 'candidat') {
        window.location.href = 'connexion.html';
        return;
    }
    
    // Afficher les informations de l'utilisateur
    document.getElementById('userInfo').textContent = `Bienvenue, ${user.email}`;
    
    // Charger les données du candidat
    loadCandidatData();
    loadLecons();
});

// Fonction pour afficher les différentes sections
function showSection(sectionName) {
    // Masquer toutes les sections
    const sections = document.querySelectorAll('.dashboard-content');
    sections.forEach(section => {
        section.style.display = 'none';
    });
    
    // Retirer la classe active de tous les liens
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.classList.remove('active');
    });
    
    // Afficher la section demandée
    document.getElementById(sectionName + '-section').style.display = 'block';
    
    // Ajouter la classe active au lien correspondant
    event.target.classList.add('active');
    
    // Charger les données spécifiques à la section
    switch(sectionName) {
        case 'lecons':
            loadLecons();
            break;
        case 'examens':
            loadExamens();
            break;
        case 'profil':
            loadProfil();
            break;
    }
}

// Charger les données du candidat
function loadCandidatData() {
    // Simulation des données - à remplacer par des appels API réels
    const userData = JSON.parse(localStorage.getItem('userData') || '{}');
    
    // Mettre à jour les statistiques
    document.getElementById('totalLecons').textContent = '12';
    document.getElementById('prochainExamen').textContent = userData.dateCode || 'Non défini';
    document.getElementById('heuresRestantes').textContent = '8h';
    
    // Charger les prochaines leçons
    loadProchaines Lecons();
}

// Charger les prochaines leçons
function loadProchainesLecons() {
    const tbody = document.getElementById('prochaines-lecons');
    
    // Simulation de données - à remplacer par un appel API réel
    const prochaines = [
        {
            date: '2025-01-15',
            heure_debut: '14:00',
            moniteur: 'M. Dupont',
            vehicule: 'Renault Clio',
            type: 'Conduite'
        },
        {
            date: '2025-01-17',
            heure_debut: '10:00',
            moniteur: 'Mme Martin',
            vehicule: 'Peugeot 208',
            type: 'Créneau'
        }
    ];
    
    if (prochaines.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center">Aucune leçon programmée</td></tr>';
        return;
    }
    
    tbody.innerHTML = '';
    prochaines.forEach(lecon => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${formatDate(lecon.date)}</td>
            <td>${lecon.heure_debut}</td>
            <td>${lecon.moniteur}</td>
            <td>${lecon.vehicule}</td>
            <td><span class="badge bg-primary">${lecon.type}</span></td>
        `;
        tbody.appendChild(row);
    });
}

// Charger toutes les leçons
function loadLecons() {
    const tbody = document.getElementById('mes-lecons');
    
    // Simulation de données - à remplacer par un appel API réel
    const lecons = [
        {
            date: '2025-01-10',
            heure_debut: '14:00',
            heure_fin: '15:00',
            moniteur: 'M. Dupont',
            vehicule: 'Renault Clio',
            type: 'Conduite',
            statut: 'Terminée'
        },
        {
            date: '2025-01-12',
            heure_debut: '10:00',
            heure_fin: '11:00',
            moniteur: 'Mme Martin',
            vehicule: 'Peugeot 208',
            type: 'Créneau',
            statut: 'Terminée'
        },
        {
            date: '2025-01-15',
            heure_debut: '14:00',
            heure_fin: '15:00',
            moniteur: 'M. Dupont',
            vehicule: 'Renault Clio',
            type: 'Conduite',
            statut: 'Programmée'
        }
    ];
    
    if (lecons.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center">Aucune leçon trouvée</td></tr>';
        return;
    }
    
    tbody.innerHTML = '';
    lecons.forEach(lecon => {
        const row = document.createElement('tr');
        const statutClass = lecon.statut === 'Terminée' ? 'bg-success' : 'bg-warning';
        
        row.innerHTML = `
            <td>${formatDate(lecon.date)}</td>
            <td>${lecon.heure_debut}</td>
            <td>${lecon.heure_fin}</td>
            <td>${lecon.moniteur}</td>
            <td>${lecon.vehicule}</td>
            <td><span class="badge bg-primary">${lecon.type}</span></td>
            <td><span class="badge ${statutClass}">${lecon.statut}</span></td>
        `;
        tbody.appendChild(row);
    });
}

// Charger les informations d'examens
function loadExamens() {
    const userData = JSON.parse(localStorage.getItem('userData') || '{}');
    
    if (userData.dateCode) {
        document.getElementById('dateCode').textContent = formatDate(userData.dateCode);
    }
    
    if (userData.datePermis) {
        document.getElementById('datePermis').textContent = formatDate(userData.datePermis);
    }
}

// Charger le profil
function loadProfil() {
    const userData = JSON.parse(localStorage.getItem('userData') || '{}');
    
    document.getElementById('nom').value = userData.nom || '';
    document.getElementById('prenom').value = userData.prenom || '';
    document.getElementById('email').value = userData.email || '';
    document.getElementById('dateCodeProfil').value = userData.dateCode || '';
    document.getElementById('datePermisProfil').value = userData.datePermis || '';
}

// Gestion du formulaire de profil
document.getElementById('profilForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const userData = JSON.parse(localStorage.getItem('userData') || '{}');
    userData.dateCode = document.getElementById('dateCodeProfil').value;
    userData.datePermis = document.getElementById('datePermisProfil').value;
    
    localStorage.setItem('userData', JSON.stringify(userData));
    
    // Afficher un message de succès
    const alert = document.createElement('div');
    alert.className = 'alert alert-success alert-dismissible fade show';
    alert.innerHTML = `
        Profil mis à jour avec succès !
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const form = document.getElementById('profilForm');
    form.parentNode.insertBefore(alert, form);
    
    // Mettre à jour les autres sections
    loadExamens();
    loadCandidatData();
});

// Fonction utilitaire pour formater les dates
function formatDate(dateString) {
    if (!dateString) return 'Non définie';
    
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

// Fonction utilitaire pour formater les heures
function formatTime(timeString) {
    if (!timeString) return '';
    
    const time = new Date('2000-01-01T' + timeString);
    return time.toLocaleTimeString('fr-FR', {
        hour: '2-digit',
        minute: '2-digit'
    });
}

