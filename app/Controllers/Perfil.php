<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Perfil extends BaseController
{
    public function index()
    {
        $usuario = (new UsuarioModel())->find(session()->get('id_usuario'));

        if (! $usuario) {
            session()->destroy();
            return redirect()->to('/login');
        }

        return view('perfil/index', ['usuario' => $usuario]);
    }

    public function actualizar()
    {
        if (session()->get('rol') !== 'cliente') {
            return redirect()->to('/perfil')->with('error', 'Los datos de identificación y contacto solo se gestionan para cuentas de cliente.');
        }

        $rules = [
            'dpi'       => 'permit_empty|max_length[20]',
            'nit'       => 'permit_empty|max_length[20]',
            'telefono'  => 'permit_empty|max_length[30]',
            'direccion' => 'permit_empty|max_length[255]',
        ];

        if (! $this->validateData($this->request->getPost(), $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $usuarioId = session()->get('id_usuario');
        (new UsuarioModel())->update($usuarioId, [
            'dpi'       => trim((string) $this->request->getPost('dpi')) ?: null,
            'nit'       => trim((string) $this->request->getPost('nit')) ?: null,
            'telefono'  => trim((string) $this->request->getPost('telefono')) ?: null,
            'direccion' => trim((string) $this->request->getPost('direccion')) ?: null,
        ]);

        return redirect()->to('/perfil')->with('success', 'Tus datos de cliente se actualizaron correctamente.');
    }
}
