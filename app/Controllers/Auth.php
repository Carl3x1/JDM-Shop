<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Auth extends BaseController
{
    protected $helpers = ['form', 'url'];
    /**
     * Muestra la vista del formulario de inicio de sesión.
     * Si ya existe una sesión activa, redirige al Dashboard.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        return view('auth/login');
    }

    /**
     * Procesa la autenticación del usuario mediante POST.
     * Verifica credenciales contra UsuarioModel y password_hash.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function autenticar()
    {
        $email    = trim($this->request->getPost('email') ?? '');
        $password = (string) $this->request->getPost('password');

        if (empty($email) || empty($password)) {
            return redirect()->to('/acceso')
                ->with('error', 'Por favor ingresa correo electrónico y contraseña.')
                ->withInput();
        }

        $usuarioModel = new UsuarioModel();
        $usuario      = $usuarioModel->where('email', $email)->first();

        $storedPassword = (string) ($usuario['password_hash'] ?? '');
        $passwordIsHashed = password_get_info($storedPassword)['algo'] !== null;
        $passwordMatches = $passwordIsHashed
            ? password_verify($password, $storedPassword)
            : hash_equals($storedPassword, $password);

        if ($usuario && $passwordMatches) {
            // Migra cuentas antiguas que guardaban la contraseña en texto plano.
            if (! $passwordIsHashed) {
                $usuarioModel->update($usuario['id_usuario'], [
                    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                ]);
            }

            $sessionData = [
                'id_usuario' => $usuario['id_usuario'],
                'nombre'     => $usuario['nombre'],
                'email'      => $usuario['email'],
                'rol'        => ! empty($usuario['rol']) ? $usuario['rol'] : 'cliente',
                'isLoggedIn' => true,
            ];

            session()->set($sessionData);

            return redirect()->to('/');
        }

        return redirect()->to('/acceso')
            ->with('error', 'Credenciales incorrectas. Verifique su correo o contraseña.')
            ->withInput();
    }

    /**
     * Muestra la vista del formulario de registro de un nuevo usuario.
     * Si ya existe una sesión activa, redirige al Dashboard.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function registro()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        return view('auth/registro');
    }

    /**
     * Procesa el registro de un nuevo usuario mediante POST.
     * Valida unicidad de correo, encripta la contraseña, asigna rol 'cliente'
     * y guarda en la base de datos.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function registrar()
    {
        $rules = [
            'nombre'           => 'required|min_length[3]|max_length[100]',
            'email'            => 'required|valid_email|is_unique[usuarios.email]',
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];

        $messages = [
            'nombre' => [
                'required'   => 'El nombre es obligatorio.',
                'min_length' => 'El nombre debe tener al menos 3 caracteres.',
                'max_length' => 'El nombre no debe exceder los 100 caracteres.',
            ],
            'email' => [
                'required'    => 'El correo electrónico es obligatorio.',
                'valid_email' => 'Debes ingresar un correo electrónico válido.',
                'is_unique'   => 'El correo electrónico ya se encuentra registrado.',
            ],
            'password' => [
                'required'   => 'La contraseña es obligatoria.',
                'min_length' => 'La contraseña debe tener al menos 6 caracteres.',
            ],
            'password_confirm' => [
                'required' => 'La confirmación de la contraseña es obligatoria.',
                'matches'  => 'Las contraseñas no coinciden.',
            ],
        ];

        if (! $this->validateData($this->request->getPost(), $rules, $messages)) {
            return redirect()->to('/registro')
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        $usuarioModel = new UsuarioModel();
        $nuevoUsuario = [
            'nombre'        => trim($this->request->getPost('nombre')),
            'email'         => trim($this->request->getPost('email')),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'rol'           => 'cliente',
        ];

        $usuarioModel->insert($nuevoUsuario);

        return redirect()->to('/acceso')
            ->with('success', 'Registro completado con éxito. Ahora puedes iniciar sesión.');
    }

    /**
     * Cierra la sesión activa del usuario y redirige al login.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function logout()
    {
        session()->destroy();

        return redirect()->to('/');
    }
}
