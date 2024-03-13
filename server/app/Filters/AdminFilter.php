<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Vérifie si l'utilisateur est un administrateur
        if (!session()->get('isLoggedIn') || !session()->get('isAdmin')) {
            // Si l'utilisateur n'est pas un administrateur, redirigez-le ou affichez une erreur
            return service('response')->setStatusCode(403)->setBody('Vous n\'êtes pas autorisé à effectuer cette action.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Faites quelque chose après le traitement de la requête
    }
}
