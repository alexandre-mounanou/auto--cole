<?php
class Vehicule {
    private $conn;
    private $table_name = "vehicules";

    public $id;
    public $marque;
    public $modele;
    public $immatriculation;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Créer un véhicule
    function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET marque=:marque, modele=:modele, immatriculation=:immatriculation";

        $stmt = $this->conn->prepare($query);

        $this->marque = htmlspecialchars(strip_tags($this->marque));
        $this->modele = htmlspecialchars(strip_tags($this->modele));
        $this->immatriculation = htmlspecialchars(strip_tags($this->immatriculation));

        $stmt->bindParam(":marque", $this->marque);
        $stmt->bindParam(":modele", $this->modele);
        $stmt->bindParam(":immatriculation", $this->immatriculation);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    // Lire tous les véhicules
    function read() {
        $query = "SELECT id, marque, modele, immatriculation 
                  FROM " . $this->table_name . " 
                  ORDER BY marque, modele";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Lire un véhicule par ID
    function readOne() {
        $query = "SELECT id, marque, modele, immatriculation 
                  FROM " . $this->table_name . " 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->marque = $row['marque'];
            $this->modele = $row['modele'];
            $this->immatriculation = $row['immatriculation'];
            return true;
        }

        return false;
    }

    // Mettre à jour un véhicule
    function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET marque=:marque, modele=:modele, immatriculation=:immatriculation 
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->marque = htmlspecialchars(strip_tags($this->marque));
        $this->modele = htmlspecialchars(strip_tags($this->modele));
        $this->immatriculation = htmlspecialchars(strip_tags($this->immatriculation));

        $stmt->bindParam(":marque", $this->marque);
        $stmt->bindParam(":modele", $this->modele);
        $stmt->bindParam(":immatriculation", $this->immatriculation);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    // Supprimer un véhicule
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    // Vérifier si l'immatriculation existe déjà
    function immatriculationExists() {
        $query = "SELECT id FROM " . $this->table_name . " WHERE immatriculation = :immatriculation";

        if($this->id) {
            $query .= " AND id != :id";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":immatriculation", $this->immatriculation);

        if($this->id) {
            $stmt->bindParam(":id", $this->id);
        }

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    // Obtenir les véhicules disponibles à une date/heure donnée
    function getAvailable($date_heure_debut, $date_heure_fin) {
        $query = "SELECT v.id, v.marque, v.modele, v.immatriculation 
                  FROM " . $this->table_name . " v
                  WHERE v.id NOT IN (
                      SELECT l.vehicule_id FROM lecons l
                      WHERE (
                          (:date_heure_debut BETWEEN l.date_heure_debut AND l.date_heure_fin) OR
                          (:date_heure_fin BETWEEN l.date_heure_debut AND l.date_heure_fin) OR
                          (l.date_heure_debut BETWEEN :date_heure_debut AND :date_heure_fin)
                      )
                  )
                  ORDER BY v.marque, v.modele";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":date_heure_debut", $date_heure_debut);
        $stmt->bindParam(":date_heure_fin", $date_heure_fin);
        $stmt->execute();

        return $stmt;
    }
}
?>

