<?php
class User {
    private $conn;
    private $table_name = "utilisateurs";

    public $id;
    public $nom;
    public $prenom;
    public $email;
    public $mot_de_passe;
    public $type_utilisateur;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Créer un utilisateur
    function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET nom=:nom, prenom=:prenom, email=:email, 
                      mot_de_passe=:mot_de_passe, type_utilisateur=:type_utilisateur";

        $stmt = $this->conn->prepare($query);

        // Nettoyer les données
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->prenom = htmlspecialchars(strip_tags($this->prenom));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->mot_de_passe = password_hash($this->mot_de_passe, PASSWORD_DEFAULT);
        $this->type_utilisateur = htmlspecialchars(strip_tags($this->type_utilisateur));

        // Lier les valeurs
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":prenom", $this->prenom);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":mot_de_passe", $this->mot_de_passe);
        $stmt->bindParam(":type_utilisateur", $this->type_utilisateur);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    // Authentifier un utilisateur
    function authenticate() {
        $query = "SELECT id, nom, prenom, email, mot_de_passe, type_utilisateur 
                  FROM " . $this->table_name . " 
                  WHERE email = :email";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row && password_verify($this->mot_de_passe, $row['mot_de_passe'])) {
            $this->id = $row['id'];
            $this->nom = $row['nom'];
            $this->prenom = $row['prenom'];
            $this->type_utilisateur = $row['type_utilisateur'];
            return true;
        }

        return false;
    }

    // Lire tous les utilisateurs
    function read() {
        $query = "SELECT id, nom, prenom, email, type_utilisateur 
                  FROM " . $this->table_name . " 
                  ORDER BY nom, prenom";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Lire un utilisateur par ID
    function readOne() {
        $query = "SELECT id, nom, prenom, email, type_utilisateur 
                  FROM " . $this->table_name . " 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->nom = $row['nom'];
            $this->prenom = $row['prenom'];
            $this->email = $row['email'];
            $this->type_utilisateur = $row['type_utilisateur'];
            return true;
        }

        return false;
    }

    // Mettre à jour un utilisateur
    function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET nom=:nom, prenom=:prenom, email=:email, type_utilisateur=:type_utilisateur 
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->prenom = htmlspecialchars(strip_tags($this->prenom));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->type_utilisateur = htmlspecialchars(strip_tags($this->type_utilisateur));

        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":prenom", $this->prenom);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":type_utilisateur", $this->type_utilisateur);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    // Supprimer un utilisateur
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    // Vérifier si l'email existe déjà
    function emailExists() {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}
?>

