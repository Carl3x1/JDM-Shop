<?php

namespace App\Controllers;

use App\Models\ProyectoModel;
use App\Models\UsuarioModel;

class Usuarios extends BaseController
{
    private const ROLES = ['admin', 'trabajador', 'cliente'];

    public function index()
    {
        $role = (string) $this->request->getGet('rol');
        $role = in_array($role, self::ROLES, true) ? $role : 'todos';
        $model = new UsuarioModel();
        $query = $model->orderBy('fecha_registro', 'DESC')->orderBy('id_usuario', 'DESC');

        if ($role !== 'todos') {
            $query->where('rol', $role);
        }

        $counts = [];
        foreach (self::ROLES as $itemRole) {
            $counts[$itemRole] = (new UsuarioModel())->where('rol', $itemRole)->countAllResults();
        }

        return view('usuarios/index', [
            'usuarios' => $query->findAll(),
            'activeRole' => $role,
            'roleCounts' => $counts,
        ]);
    }

    public function crear()
    {
        return view('usuarios/form', ['usuario' => null, 'esEdicion' => false]);
    }

    public function guardar()
    {
        $rules = [
            'nombre' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|max_length[255]|is_unique[usuarios.email]',
            'password' => 'required|min_length[10]|max_length[72]',
            'rol' => 'required|in_list[admin,trabajador,cliente]',
            'dpi' => 'permit_empty|max_length[20]',
            'nit' => 'permit_empty|max_length[20]',
            'telefono' => 'permit_empty|max_length[30]',
            'direccion' => 'permit_empty|max_length[255]',
        ];

        if (! $this->validateData($this->request->getPost(), $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UsuarioModel();
        $userId = $userModel->insert($this->userData(true));

        if ($userId === false) {
            return redirect()->back()->withInput()->with('error', 'No se pudo crear la cuenta.');
        }

        return redirect()->to('/usuarios?rol=' . rawurlencode((string) $this->request->getPost('rol')))
            ->with('success', 'La cuenta se creó correctamente.');
    }

    public function editar($id = null)
    {
        $model = new UsuarioModel();
        $usuario = $model->find($id);

        if (! $usuario) {
            return redirect()->to('/usuarios')->with('error', 'No se encontró la cuenta solicitada.');
        }

        if ($this->request->getMethod() !== 'post') {
            return view('usuarios/form', ['usuario' => $usuario, 'esEdicion' => true]);
        }

        $rules = [
            'nombre' => 'required|min_length[3]|max_length[100]',
            'email' => "required|valid_email|max_length[255]|is_unique[usuarios.email,id_usuario,{$id}]",
            'password' => 'permit_empty|min_length[10]|max_length[72]',
            'rol' => 'required|in_list[admin,trabajador,cliente]',
            'dpi' => 'permit_empty|max_length[20]',
            'nit' => 'permit_empty|max_length[20]',
            'telefono' => 'permit_empty|max_length[30]',
            'direccion' => 'permit_empty|max_length[255]',
        ];

        if (! $this->validateData($this->request->getPost(), $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $newRole = (string) $this->request->getPost('rol');
        if ($usuario['rol'] === 'admin' && $newRole !== 'admin' && ! $this->hayOtroAdministrador((int) $id)) {
            return redirect()->back()->withInput()->with('error', 'No puedes quitar el último acceso de administrador.');
        }

        $data = $this->userData(false);
        if ($usuario['rol'] === 'admin' && $newRole !== 'admin') {
            return redirect()->to('/usuarios?rol=admin')->with('error', 'No puedes cambiar tu propio rol administrativo.');
        }

        if ($this->request->getPost('password')) {
            $data['password_hash'] = password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        if (! $model->update($id, $data)) {
            return redirect()->back()->withInput()->with('error', 'No se pudieron guardar los cambios de la cuenta.');
        }

        return redirect()->to('/usuarios?rol=' . rawurlencode($newRole))->with('success', 'La cuenta se actualizó correctamente.');
    }

    public function eliminar($id = null)
    {
        $model = new UsuarioModel();
        $usuario = $model->find($id);

        if (! $usuario) {
            return redirect()->to('/usuarios')->with('error', 'No se encontró la cuenta solicitada.');
        }

        if ((int) $id === (int) session()->get('id_usuario')) {
            return redirect()->to('/usuarios')->with('error', 'No puedes eliminar la cuenta con la que tienes iniciada sesión.');
        }

        if ($usuario['rol'] === 'admin' && ! $this->hayOtroAdministrador((int) $id)) {
            return redirect()->to('/usuarios?rol=admin')->with('error', 'No puedes eliminar el último administrador.');
        }

        $hasProjects = (new ProyectoModel())->where('id_usuario', $id)->countAllResults() > 0;
        if ($hasProjects) {
            return redirect()->to('/usuarios?rol=' . rawurlencode($usuario['rol']))
                ->with('error', 'La cuenta tiene proyectos asociados. Reasigna esos proyectos antes de eliminarla.');
        }

        if (! $model->delete($id)) {
            return redirect()->to('/usuarios')->with('error', 'No se pudo eliminar la cuenta.');
        }

        return redirect()->to('/usuarios?rol=' . rawurlencode($usuario['rol']))->with('success', 'La cuenta se eliminó correctamente.');
    }

    private function userData(bool $includePassword): array
    {
        $role = (string) $this->request->getPost('rol');
        $data = [
            'nombre' => trim((string) $this->request->getPost('nombre')),
            'email' => trim((string) $this->request->getPost('email')),
            'rol' => $role,
            'dpi' => $role === 'cliente' ? (trim((string) $this->request->getPost('dpi')) ?: null) : null,
            'nit' => $role === 'cliente' ? (trim((string) $this->request->getPost('nit')) ?: null) : null,
            'telefono' => $role === 'cliente' ? (trim((string) $this->request->getPost('telefono')) ?: null) : null,
            'direccion' => $role === 'cliente' ? (trim((string) $this->request->getPost('direccion')) ?: null) : null,
        ];

        if ($includePassword) {
            $data['password_hash'] = password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        return $data;
    }

    private function hayOtroAdministrador(int $exceptId): bool
    {
        return (new UsuarioModel())
            ->where('rol', 'admin')
            ->where('id_usuario !=', $exceptId)
            ->countAllResults() > 0;
    }
}
