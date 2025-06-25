<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../classes/Vehicule.php';

$database = new Database();
$db = $database->getConnection();

$vehicule = new Vehicule($db);

$data = json_decode(file_get_contents("php://input"));

switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if(isset($_GET['id'])) {
            $vehicule->id = $_GET['id'];
            if($vehicule->readOne()) {
                echo json_encode(array(
                    "id" => $vehicule->id,
                    "marque" => $vehicule->marque,
                    "modele" => $vehicule->modele,
                    "immatriculation" => $vehicule->immatriculation
                ));
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Véhicule non trouvé."));
            }
        } elseif(isset($_GET['available']) && isset($_GET['date_debut']) && isset($_GET['date_fin'])) {
            $stmt = $vehicule->getAvailable($_GET['date_debut'], $_GET['date_fin']);
            $vehicules = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $vehicules[] = $row;
            }
            echo json_encode($vehicules);
        } else {
            $stmt = $vehicule->read();
            $vehicules = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $vehicules[] = $row;
            }
            echo json_encode($vehicules);
        }
        break;

    case 'POST':
        if(!empty($data->marque) && !empty($data->modele) && !empty($data->immatriculation)) {
            $vehicule->marque = $data->marque;
            $vehicule->modele = $data->modele;
            $vehicule->immatriculation = $data->immatriculation;

            if($vehicule->immatriculationExists()) {
                http_response_code(400);
                echo json_encode(array("message" => "Cette immatriculation existe déjà."));
            } else {
                if($vehicule->create()) {
                    http_response_code(201);
                    echo json_encode(array("message" => "Véhicule créé avec succès.", "id" => $vehicule->id));
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "Impossible de créer le véhicule."));
                }
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Données incomplètes."));
        }
        break;

    case 'PUT':
        if(!empty($data->id) && !empty($data->marque) && !empty($data->modele) && !empty($data->immatriculation)) {
            $vehicule->id = $data->id;
            $vehicule->marque = $data->marque;
            $vehicule->modele = $data->modele;
            $vehicule->immatriculation = $data->immatriculation;

            if($vehicule->immatriculationExists()) {
                http_response_code(400);
                echo json_encode(array("message" => "Cette immatriculation existe déjà."));
            } else {
                if($vehicule->update()) {
                    http_response_code(200);
                    echo json_encode(array("message" => "Véhicule mis à jour avec succès."));
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "Impossible de mettre à jour le véhicule."));
                }
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Données incomplètes."));
        }
        break;

    case 'DELETE':
        if(!empty($data->id)) {
            $vehicule->id = $data->id;

            if($vehicule->delete()) {
                http_response_code(200);
                echo json_encode(array("message" => "Véhicule supprimé avec succès."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Impossible de supprimer le véhicule."));
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

