<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Vérifie si l'utilisateur est connecté
        if (!session()->get('isLoggedIn')) {
            // Si l'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
            return redirect()->to('http://localhost:5173/connexion');
        }
        // L'utilisateur est connecté, autorisez la requête à poursuivre
        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Ne rien faire après la réponse
    }
}