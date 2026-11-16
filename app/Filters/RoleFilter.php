<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    /**
     * Verifica que el usuario cuente con los roles autorizados para acceder a la ruta.
     * Si no ha iniciado sesión, lo redirige al login.
     * Si no posee los permisos requeridos (ej. rol 'admin'), lo redirige al Dashboard
     * con un mensaje flash de acceso denegado.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments Roles permitidos (ej. ['admin'])
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // 1. Debe existir una sesión activa
        if (! $session->get('isLoggedIn')) {
            return redirect()->to('/login')
                ->with('error', 'Debes iniciar sesión para acceder a esta sección.');
        }

        // 2. Obtener rol actual del usuario
        $userRole     = (string) ($session->get('rol') ?? 'cliente');
        $allowedRoles = ! empty($arguments) ? $arguments : ['admin'];

        // 3. Comprobar si el rol del usuario está entre los permitidos
        if (! in_array($userRole, $allowedRoles, true)) {
            return redirect()->to('/')->with('error', 'Acceso denegado: No tienes permisos para acceder a esta función.');
        }
    }

    /**
     * Procesamiento posterior (sin acciones requeridas).
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se requiere acción posterior
    }
}

