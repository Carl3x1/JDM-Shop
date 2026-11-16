<?php

namespace App\Controllers;

use App\Models\ComponenteModel;

class Componentes extends BaseController
{
    public function index()
    {
        return view('componentes/index', [
            'componentes' => (new ComponenteModel())->findAll(),
        ]);
    }

    public function crear()
    {
        return view('componentes/crear');
    }

    public function guardar()
    {
        if (! $this->esAdministrador()) {
            return $this->accesoDenegado();
        }

        $data = $this->componenteData();

        if (! $this->validate($this->componenteRules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        if ((new ComponenteModel())->insert($data) === false) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No se pudo guardar el componente.');
        }

        return redirect()->to('/componentes')->with('success', 'Componente creado correctamente.');
    }

    public function mostrar($id = null)
    {
        $componente = (new ComponenteModel())->find($id);

        if ($componente === null) {
            return redirect()->to('/componentes')->with('error', 'Componente no encontrado.');
        }

        return view('componentes/ver', ['componente' => $componente]);
    }

    public function actualizar($id = null)
    {
        if (! $this->esAdministrador()) {
            return $this->accesoDenegado();
        }

        $model = new ComponenteModel();
        $componente = $model->find($id);

        if ($componente === null) {
            return redirect()->to('/componentes')->with('error', 'Componente no encontrado.');
        }

        if ($this->request->getMethod() !== 'post') {
            return view('componentes/editar', ['componente' => $componente]);
        }

        $data = $this->componenteData();

        if (! $this->validate($this->componenteRules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        if (! $model->update($id, $data)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No se pudo actualizar el componente.');
        }

        return redirect()->to('/componentes')->with('success', 'Componente actualizado correctamente.');
    }

    public function eliminar($id = null)
    {
        if (! $this->esAdministrador()) {
            return $this->accesoDenegado();
        }

        $model = new ComponenteModel();

        if ($model->find($id) === null) {
            return redirect()->to('/componentes')->with('error', 'Componente no encontrado.');
        }

        if (! $model->delete($id)) {
            return redirect()->to('/componentes')->with('error', 'No se pudo eliminar el componente.');
        }

        return redirect()->to('/componentes')->with('success', 'Componente eliminado correctamente.');
    }

    private function componenteData(): array
    {
        return [
            'categoria'         => $this->request->getPost('categoria'),
            'nombre'            => $this->request->getPost('nombre'),
            'descripcion'       => $this->request->getPost('descripcion'),
            'aporte_hp_pct'     => $this->request->getPost('aporte_hp_pct'),
            'aporte_torque_pct' => $this->request->getPost('aporte_torque_pct'),
            'precio'            => $this->request->getPost('precio'),
            'stock'             => $this->request->getPost('stock'),
        ];
    }

    private function componenteRules(): array
    {
        return [
            'categoria'         => 'required|max_length[100]',
            'nombre'            => 'required|max_length[255]',
            'descripcion'       => 'permit_empty|max_length[5000]',
            'aporte_hp_pct'     => 'required|decimal|greater_than_equal_to[0]',
            'aporte_torque_pct' => 'required|decimal|greater_than_equal_to[0]',
            'precio'            => 'required|decimal|greater_than_equal_to[0]',
            'stock'             => 'required|integer|greater_than_equal_to[0]',
        ];
    }

    private function esAdministrador(): bool
    {
        return session()->get('rol') === 'admin';
    }

    private function accesoDenegado()
    {
        return redirect()->to('/')->with('error', 'Acceso denegado: se requieren permisos de administrador.');
    }
}
