<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ==========================================
// Rutas Públicas (Autenticación)
// ==========================================
$routes->get('login', static function () {
    return redirect()->to('/');
});
$routes->get('acceso', 'Auth::login');
$routes->post('autenticar', 'Auth::autenticar');
$routes->get('registro', 'Auth::registro');
$routes->post('registrar', 'Auth::registrar');
$routes->get('logout', 'Auth::logout');

// Portada y catálogos públicos: no requieren cuenta para explorar.
$routes->get('/', 'Dashboard::index');
$routes->get('productos', 'Productos::index');
$routes->get('productos/mostrar/(:num)', 'Productos::mostrar/$1');
$routes->get('vehiculos', 'Vehiculos::index');
$routes->get('vehiculos/mostrar/(:num)', 'Vehiculos::mostrar/$1');
$routes->get('motores', 'Motores::index');
$routes->get('motores/mostrar/(:num)', 'Motores::mostrar/$1');
$routes->get('componentes', 'Componentes::index');
$routes->get('componentes/mostrar/(:num)', 'Componentes::mostrar/$1');

// ==========================================
// Rutas Protegidas (Requieren Sesión 'auth')
// ==========================================
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('perfil', 'Perfil::index');
    $routes->post('perfil', 'Perfil::actualizar');

    // Dashboard principal

    // ------------------------------------------
    // Catálogos: Lectura General (Admin y Clientes)
    // ------------------------------------------
    // Productos

    // Vehículos Base

    // Catálogo Motores

    // Componentes de Modificación

    // Proyectos de Taller & Cotizador
    $routes->get('proyectos', 'Proyectos::index');
    $routes->get('proyectos/crear', 'Proyectos::crear', ['filter' => 'role:admin,cliente']);
    $routes->post('proyectos/guardar', 'Proyectos::guardar', ['filter' => 'role:admin,cliente']);
    $routes->get('proyectos/mostrar/(:num)', 'Proyectos::mostrar/$1');
    $routes->get('proyectos/editar/(:num)', 'Proyectos::editar/$1', ['filter' => 'role:admin,cliente']);
    $routes->post('proyectos/actualizar/(:num)', 'Proyectos::actualizar/$1', ['filter' => 'role:admin,cliente']);
    $routes->post('proyectos/eliminar/(:num)', 'Proyectos::eliminar/$1', ['filter' => 'role:admin']);
    $routes->post('proyectos/fase/(:num)', 'Proyectos::actualizarFase/$1', ['filter' => 'role:admin,trabajador']);
    $routes->post('proyectos/solicitud/(:num)', 'Proyectos::revisarSolicitud/$1', ['filter' => 'role:admin']);

    // ------------------------------------------
    // Rutas Administrativas (Solo rol 'admin')
    // ------------------------------------------
    $routes->group('', ['filter' => 'role:admin'], static function ($routes) {
        // Módulo: Gestión de Usuarios (Exclusivo Administradores)
        $routes->get('usuarios', 'Usuarios::index');
        $routes->get('usuarios/crear', 'Usuarios::crear');
        $routes->post('usuarios/guardar', 'Usuarios::guardar');
        $routes->get('usuarios/editar/(:num)', 'Usuarios::editar/$1');
        $routes->post('usuarios/editar/(:num)', 'Usuarios::editar/$1');
        $routes->post('usuarios/eliminar/(:num)', 'Usuarios::eliminar/$1');

        // Módulo: Escritura / Modificación de Productos
        $routes->get('productos/crear', 'Productos::crear');
        $routes->post('productos/guardar', 'Productos::guardar');
        $routes->get('productos/actualizar/(:num)', 'Productos::actualizar/$1');
        $routes->post('productos/actualizar/(:num)', 'Productos::actualizar/$1');
        $routes->post('productos/eliminar/(:num)', 'Productos::eliminar/$1');

        // Módulo: Escritura / Modificación de Vehículos Base
        $routes->get('vehiculos/crear', 'Vehiculos::crear');
        $routes->post('vehiculos/guardar', 'Vehiculos::guardar');
        $routes->get('vehiculos/actualizar/(:num)', 'Vehiculos::actualizar/$1');
        $routes->post('vehiculos/actualizar/(:num)', 'Vehiculos::actualizar/$1');
        $routes->post('vehiculos/eliminar/(:num)', 'Vehiculos::eliminar/$1');

        // Módulo: Escritura / Modificación de Catálogo Motores
        $routes->get('motores/crear', 'Motores::crear');
        $routes->post('motores/guardar', 'Motores::guardar');
        $routes->get('motores/actualizar/(:num)', 'Motores::actualizar/$1');
        $routes->post('motores/actualizar/(:num)', 'Motores::actualizar/$1');
        $routes->post('motores/eliminar/(:num)', 'Motores::eliminar/$1');

        // Módulo: Escritura / Modificación de Componentes
        $routes->get('componentes/crear', 'Componentes::crear');
        $routes->post('componentes/guardar', 'Componentes::guardar');
        $routes->get('componentes/actualizar/(:num)', 'Componentes::actualizar/$1');
        $routes->post('componentes/actualizar/(:num)', 'Componentes::actualizar/$1');
        $routes->post('componentes/eliminar/(:num)', 'Componentes::eliminar/$1');
    });
});
