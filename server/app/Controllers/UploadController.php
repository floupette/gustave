<?php

namespace App\Controllers;

class UploadController extends BaseController
{
    protected $helpers = ['url'];

    // Fonction permettant d'afficher une image du logement lorsque l'on clic sur son URL
    public function show($imageName)
    {
        // Récupérez le chemin complet de l'image
        $imagePath = WRITEPATH . 'uploads/' . $imageName;

        // Vérifiez si le fichier existe
        if (!file_exists($imagePath) || !is_file($imagePath)) {
            // Si le fichier n'existe pas, renvoyez une réponse 404
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Image not found']);
        }

        // Récupérer le type MIME de l'image
        $mimeType = mime_content_type($imagePath);

        // Charger le contenu de l'image
        $imageContent = file_get_contents($imagePath);

        // Envoyer une réponse avec le contenu de l'image et le type MIME approprié
        return $this->response->setContentType($mimeType)->setBody($imageContent);
    }
}
