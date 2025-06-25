<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../classes/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($data->action)) {
        switch($data->action) {
            case 'login':
                if(!empty($data->email) && !empty($data->password)) {
                    $user->email = $data->email;
                    $user->mot_de_passe = $data->password;

                    if($user->authenticate()) {
                        http_response_code(200);
                        echo json_encode(array(
                            "message" => "Connexion réussie.",
                            "user" => array(
                                "id" => $user->id,
                                "nom" => $user->nom,
                                "prenom" => $user->prenom,
                                "email" => $user->email,
                                "type_utilisateur" => $user->type_utilisateur
                            )
                        ));
                    } else {
                        http_response_code(401);
                        echo json_encode(array("message" => "Email ou mot de passe incorrect."));
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(array("message" => "Données incomplètes."));
                }
                break;

            case 'register':
                if(!empty($data->nom) && !empty($data->prenom) && !empty($data->email) && 
                   !empty($data->password) && !empty($data->type_utilisateur)) {
                    
                    $user->email = $data->email;
                    
                    if($user->emailExists()) {
                        http_response_code(400);
                        echo json_encode(array("message" => "Cet email est déjà utilisé."));
                    } else {
                        $user->nom = $data->nom;
                        $user->prenom = $data->prenom;
                        $user->mot_de_passe = $data->password;
                        $user->type_utilisateur = $data->type_utilisateur;

                        if($user->create()) {
                            // Si c'est un candidat, créer l'entrée dans la table candidats
                            if($data->type_utilisateur == 'candidat') {
                                $candidat_query = "INSERT INTO candidats (id, date_permis, date_code, est_etudiant) 
                                                  VALUES (:id, :date_permis, :date_code, :est_etudiant)";
                                $candidat_stmt = $db->prepare($candidat_query);
                                $candidat_stmt->bindParam(":id", $user->id);
                                $candidat_stmt->bindParam(":date_permis", $data->date_permis);
                                $candidat_stmt->bindParam(":date_code", $data->date_code);
                                $candidat_stmt->bindParam(":est_etudiant", $data->est_etudiant);
                                $candidat_stmt->execute();

                                // Si c'est un étudiant, créer l'école
                                if($data->est_etudiant && !empty($data->nom_ecole)) {
                                    $ecole_query = "INSERT INTO ecoles (nom, adresse) VALUES (:nom, :adresse)";
                                    $ecole_stmt = $db->prepare($ecole_query);
                                    $ecole_stmt->bindParam(":nom", $data->nom_ecole);
                                    $ecole_stmt->bindParam(":adresse", $data->adresse_ecole);
                                    $ecole_stmt->execute();
                                    
                                    $ecole_id = $db->lastInsertId();
                                    
                                    $relation_query = "INSERT INTO etudiants_ecoles (candidat_id, ecole_id) VALUES (:candidat_id, :ecole_id)";
                                    $relation_stmt = $db->prepare($relation_query);
                                    $relation_stmt->bindParam(":candidat_id", $user->id);
                                    $relation_stmt->bindParam(":ecole_id", $ecole_id);
                                    $relation_stmt->execute();
                                }
                            }

                            // Si c'est un moniteur, créer l'entrée dans la table moniteurs
                            if($data->type_utilisateur == 'moniteur') {
                                $moniteur_query = "INSERT INTO moniteurs (id) VALUES (:id)";
                                $moniteur_stmt = $db->prepare($moniteur_query);
                                $moniteur_stmt->bindParam(":id", $user->id);
                                $moniteur_stmt->execute();
                            }

                            http_response_code(201);
                            echo json_encode(array("message" => "Utilisateur créé avec succès."));
                        } else {
                            http_response_code(503);
                            echo json_encode(array("message" => "Impossible de créer l'utilisateur."));
                        }
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(array("message" => "Données incomplètes."));
                }
                break;

            default:
                http_response_code(400);
                echo json_encode(array("message" => "Action non reconnue."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Action manquante."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Méthode non autorisée."));
}
?>

