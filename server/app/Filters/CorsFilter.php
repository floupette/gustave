<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class CORSFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Autoriser l'origine spécifique
        header('Access-Control-Allow-Origin: http://localhost:5173');
        
        // Autoriser certaines méthodes HTTP
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

        // Autoriser certains en-têtes HTTP
        header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

        // Autoriser les cookies en réponse
        header('Access-Control-Allow-Credentials: true');

        // Définir la durée de validité des préflights
        header('Access-Control-Max-Age: 3600');

        // Vérifier la méthode de requête pour les préflights
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header('HTTP/1.1 200 OK');
            exit();
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Faire quelque chose après le traitement
    }
}
