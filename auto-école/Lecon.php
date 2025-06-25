<?php
class Lecon {
    private $conn;
    private $table_name = "lecons";

    public $id;
    public $candidat_id;
    public $moniteur_id;
    public $vehicule_id;
    public $date_heure_debut;
    public $date_heure_fin;
    public $type_lecon;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Créer une leçon
    function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET candidat_id=:candidat_id, moniteur_id=:moniteur_id, vehicule_id=:vehicule_id,
                      date_heure_debut=:date_heure_debut, date_heure_fin=:date_heure_fin, type_lecon=:type_lecon";

        $stmt = $this->conn->prepare($query);

        $this->candidat_id = htmlspecialchars(strip_tags($this->candidat_id));
        $this->moniteur_id = htmlspecialchars(strip_tags($this->moniteur_id));
        $this->vehicule_id = htmlspecialchars(strip_tags($this->vehicule_id));
        $this->date_heure_debut = htmlspecialchars(strip_tags($this->date_heure_debut));
        $this->date_heure_fin = htmlspecialchars(strip_tags($this->date_heure_fin));
        $this->type_lecon = htmlspecialchars(strip_tags($this->type_lecon));

        $stmt->bindParam(":candidat_id", $this->candidat_id);
        $stmt->bindParam(":moniteur_id", $this->moniteur_id);
        $stmt->bindParam(":vehicule_id", $this->vehicule_id);
        $stmt->bindParam(":date_heure_debut", $this->date_heure_debut);
        $stmt->bindParam(":date_heure_fin", $this->date_heure_fin);
        $stmt->bindParam(":type_lecon", $this->type_lecon);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    // Lire toutes les leçons
    function read() {
        $query = "SELECT l.id, l.date_heure_debut, l.date_heure_fin, l.type_lecon,
                         uc.nom as candidat_nom, uc.prenom as candidat_prenom,
                         um.nom as moniteur_nom, um.prenom as moniteur_prenom,
                         v.marque, v.modele, v.immatriculation
                  FROM " . $this->table_name . " l
                  LEFT JOIN utilisateurs uc ON l.candidat_id = uc.id
                  LEFT JOIN utilisateurs um ON l.moniteur_id = um.id
                  LEFT JOIN vehicules v ON l.vehicule_id = v.id
                  ORDER BY l.date_heure_debut DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Lire les leçons d'un candidat
    function readByCandidat($candidat_id) {
        $query = "SELECT l.id, l.date_heure_debut, l.date_heure_fin, l.type_lecon,
                         um.nom as moniteur_nom, um.prenom as moniteur_prenom,
                         v.marque, v.modele, v.immatriculation
                  FROM " . $this->table_name . " l
                  LEFT JOIN utilisateurs um ON l.moniteur_id = um.id
                  LEFT JOIN vehicules v ON l.vehicule_id = v.id
                  WHERE l.candidat_id = :candidat_id
                  ORDER BY l.date_heure_debut";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":candidat_id", $candidat_id);
        $stmt->execute();

        return $stmt;
    }

    // Lire les leçons d'un moniteur
    function readByMoniteur($moniteur_id) {
        $query = "SELECT l.id, l.date_heure_debut, l.date_heure_fin, l.type_lecon,
                         uc.nom as candidat_nom, uc.prenom as candidat_prenom,
                         v.marque, v.modele, v.immatriculation
                  FROM " . $this->table_name . " l
                  LEFT JOIN utilisateurs uc ON l.candidat_id = uc.id
                  LEFT JOIN vehicules v ON l.vehicule_id = v.id
                  WHERE l.moniteur_id = :moniteur_id
                  ORDER BY l.date_heure_debut";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":moniteur_id", $moniteur_id);
        $stmt->execute();

        return $stmt;
    }

    // Lire une leçon par ID
    function readOne() {
        $query = "SELECT l.id, l.candidat_id, l.moniteur_id, l.vehicule_id,
                         l.date_heure_debut, l.date_heure_fin, l.type_lecon,
                         uc.nom as candidat_nom, uc.prenom as candidat_prenom,
                         um.nom as moniteur_nom, um.prenom as moniteur_prenom,
                         v.marque, v.modele, v.immatriculation
                  FROM " . $this->table_name . " l
                  LEFT JOIN utilisateurs uc ON l.candidat_id = uc.id
                  LEFT JOIN utilisateurs um ON l.moniteur_id = um.id
                  LEFT JOIN vehicules v ON l.vehicule_id = v.id
                  WHERE l.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->candidat_id = $row['candidat_id'];
            $this->moniteur_id = $row['moniteur_id'];
            $this->vehicule_id = $row['vehicule_id'];
            $this->date_heure_debut = $row['date_heure_debut'];
            $this->date_heure_fin = $row['date_heure_fin'];
            $this->type_lecon = $row['type_lecon'];
            return true;
        }

        return false;
    }

    // Mettre à jour une leçon
    function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET candidat_id=:candidat_id, moniteur_id=:moniteur_id, vehicule_id=:vehicule_id,
                      date_heure_debut=:date_heure_debut, date_heure_fin=:date_heure_fin, type_lecon=:type_lecon
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->candidat_id = htmlspecialchars(strip_tags($this->candidat_id));
        $this->moniteur_id = htmlspecialchars(strip_tags($this->moniteur_id));
        $this->vehicule_id = htmlspecialchars(strip_tags($this->vehicule_id));
        $this->date_heure_debut = htmlspecialchars(strip_tags($this->date_heure_debut));
        $this->date_heure_fin = htmlspecialchars(strip_tags($this->date_heure_fin));
        $this->type_lecon = htmlspecialchars(strip_tags($this->type_lecon));

        $stmt->bindParam(":candidat_id", $this->candidat_id);
        $stmt->bindParam(":moniteur_id", $this->moniteur_id);
        $stmt->bindParam(":vehicule_id", $this->vehicule_id);
        $stmt->bindParam(":date_heure_debut", $this->date_heure_debut);
        $stmt->bindParam(":date_heure_fin", $this->date_heure_fin);
        $stmt->bindParam(":type_lecon", $this->type_lecon);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    // Supprimer une leçon
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    // Vérifier les conflits de planning
    function checkConflicts() {
        $query = "SELECT COUNT(*) as conflicts FROM " . $this->table_name . " 
                  WHERE (candidat_id = :candidat_id OR moniteur_id = :moniteur_id OR vehicule_id = :vehicule_id)
                  AND (
                      (:date_heure_debut BETWEEN date_heure_debut AND date_heure_fin) OR
                      (:date_heure_fin BETWEEN date_heure_debut AND date_heure_fin) OR
                      (date_heure_debut BETWEEN :date_heure_debut AND :date_heure_fin)
                  )";

        if($this->id) {
            $query .= " AND id != :id";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":candidat_id", $this->candidat_id);
        $stmt->bindParam(":moniteur_id", $this->moniteur_id);
        $stmt->bindParam(":vehicule_id", $this->vehicule_id);
        $stmt->bindParam(":date_heure_debut", $this->date_heure_debut);
        $stmt->bindParam(":date_heure_fin", $this->date_heure_fin);

        if($this->id) {
            $stmt->bindParam(":id", $this->id);
        }

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['conflicts'] > 0;
    }
}
?>

