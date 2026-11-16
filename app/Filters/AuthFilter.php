<?php

namespace App\Filters;

use App\Models\UsuarioModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Verifica si el usuario tiene una sesión activa ('isLoggedIn').
     * Si no está autenticado, redirige a la vista de login.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Debes iniciar sesión para acceder al sistema.');
        }

        $usuario = (new UsuarioModel())->find(session()->get('id_usuario'));
        if (! $usuario || ! in_array($usuario['rol'] ?? '', ['admin', 'trabajador', 'cliente'], true)) {
            session()->destroy();
            return redirect()->to('/login')->with('error', 'La cuenta ya no está disponible.');
        }

        // Refleja cambios de rol inmediatamente en las sesiones abiertas.
        session()->set([
            'nombre' => $usuario['nombre'],
            'email' => $usuario['email'],
            'rol' => $usuario['rol'],
        ]);
    }

    /**
     * Post-procesamiento posterior a la ejecución del controlador.
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

