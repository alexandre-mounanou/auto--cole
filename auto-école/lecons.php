<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../classes/Lecon.php';

$database = new Database();
$db = $database->getConnection();

$lecon = new Lecon($db);

$data = json_decode(file_get_contents("php://input"));

switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if(isset($_GET['candidat_id'])) {
            $stmt = $lecon->readByCandidat($_GET['candidat_id']);
        } elseif(isset($_GET['moniteur_id'])) {
            $stmt = $lecon->readByMoniteur($_GET['moniteur_id']);
        } elseif(isset($_GET['id'])) {
            $lecon->id = $_GET['id'];
            if($lecon->readOne()) {
                echo json_encode(array(
                    "id" => $lecon->id,
                    "candidat_id" => $lecon->candidat_id,
                    "moniteur_id" => $lecon->moniteur_id,
                    "vehicule_id" => $lecon->vehicule_id,
                    "date_heure_debut" => $lecon->date_heure_debut,
                    "date_heure_fin" => $lecon->date_heure_fin,
                    "type_lecon" => $lecon->type_lecon
                ));
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Leçon non trouvée."));
            }
            exit;
        } else {
            $stmt = $lecon->read();
        }

        $lecons = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $lecons[] = $row;
        }

        echo json_encode($lecons);
        break;

    case 'POST':
        if(!empty($data->candidat_id) && !empty($data->moniteur_id) && !empty($data->vehicule_id) &&
           !empty($data->date_heure_debut) && !empty($data->date_heure_fin)) {
            
            $lecon->candidat_id = $data->candidat_id;
            $lecon->moniteur_id = $data->moniteur_id;
            $lecon->vehicule_id = $data->vehicule_id;
            $lecon->date_heure_debut = $data->date_heure_debut;
            $lecon->date_heure_fin = $data->date_heure_fin;
            $lecon->type_lecon = $data->type_lecon ?? 'Conduite';

            // Vérifier les conflits
            if($lecon->checkConflicts()) {
                http_response_code(409);
                echo json_encode(array("message" => "Conflit de planning détecté."));
            } else {
                if($lecon->create()) {
                    http_response_code(201);
                    echo json_encode(array("message" => "Leçon créée avec succès.", "id" => $lecon->id));
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "Impossible de créer la leçon."));
                }
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Données incomplètes."));
        }
        break;

    case 'PUT':
        if(!empty($data->id) && !empty($data->candidat_id) && !empty($data->moniteur_id) && 
           !empty($data->vehicule_id) && !empty($data->date_heure_debut) && !empty($data->date_heure_fin)) {
            
            $lecon->id = $data->id;
            $lecon->candidat_id = $data->candidat_id;
            $lecon->moniteur_id = $data->moniteur_id;
            $lecon->vehicule_id = $data->vehicule_id;
            $lecon->date_heure_debut = $data->date_heure_debut;
            $lecon->date_heure_fin = $data->date_heure_fin;
            $lecon->type_lecon = $data->type_lecon ?? 'Conduite';

            // Vérifier les conflits
            if($lecon->checkConflicts()) {
                http_response_code(409);
                echo json_encode(array("message" => "Conflit de planning détecté."));
            } else {
                if($lecon->update()) {
                    http_response_code(200);
                    echo json_encode(array("message" => "Leçon mise à jour avec succès."));
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "Impossible de mettre à jour la leçon."));
                }
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Données incomplètes."));
        }
        break;

    case 'DELETE':
        if(!empty($data->id)) {
            $lecon->id = $data->id;

            if($lecon->delete()) {
                http_response_code(200);
                echo json_encode(array("message" => "Leçon supprimée avec succès."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Impossible de supprimer la leçon."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "ID manquant."));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array("message" => "Méthode non autorisée."));
}
?>

