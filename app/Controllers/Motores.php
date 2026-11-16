<?php

namespace App\Controllers;

use App\Models\MotorModel;

class Motores extends BaseController
{
    public function index()
    {
        return view('motores/index', [
            'motores' => (new MotorModel())->findAll(),
        ]);
    }

    public function crear()
    {
        return view('motores/crear');
    }

    public function guardar()
    {
        if (! $this->esAdministrador()) {
            return $this->accesoDenegado();
        }

        $data = $this->motorData();

        if (! $this->validate($this->motorRules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        if ((new MotorModel())->insert($data) === false) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No se pudo guardar el motor.');
        }

        return redirect()->to('/motores')->with('success', 'Motor creado correctamente.');
    }

    public function mostrar($id = null)
    {
        $motor = (new MotorModel())->find($id);

        if ($motor === null) {
            return redirect()->to('/motores')->with('error', 'Motor no encontrado.');
        }

        return view('motores/ver', ['motor' => $motor]);
    }

    public function actualizar($id = null)
    {
        if (! $this->esAdministrador()) {
            return $this->accesoDenegado();
        }

        $model = new MotorModel();
        $motor = $model->find($id);

        if ($motor === null) {
            return redirect()->to('/motores')->with('error', 'Motor no encontrado.');
        }

        if ($this->request->getMethod() !== 'post') {
            return view('motores/editar', ['motor' => $motor]);
        }

        $data = $this->motorData();

        if (! $this->validate($this->motorRules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        if (! $model->update($id, $data)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No se pudo actualizar el motor.');
        }

        return redirect()->to('/motores')->with('success', 'Motor actualizado correctamente.');
    }

    public function eliminar($id = null)
    {
        if (! $this->esAdministrador()) {
            return $this->accesoDenegado();
        }

        $model = new MotorModel();

        if ($model->find($id) === null) {
            return redirect()->to('/motores')->with('error', 'Motor no encontrado.');
        }

        if (! $model->delete($id)) {
            return redirect()->to('/motores')->with('error', 'No se pudo eliminar el motor.');
        }

        return redirect()->to('/motores')->with('success', 'Motor eliminado correctamente.');
    }

    private function motorData(): array
    {
        return [
            'codigo_motor'         => $this->request->getPost('codigo_motor'),
            'descripcion'          => $this->request->getPost('descripcion'),
            'hp_stock'             => $this->request->getPost('hp_stock'),
            'torque_stock'         => $this->request->getPost('torque_stock'),
            'limite_hp_bloque_stock' => $this->request->getPost('limite_hp_bloque_stock'),
        ];
    }

    private function motorRules(): array
    {
        return [
            'codigo_motor'          => 'required|max_length[100]',
            'descripcion'           => 'permit_empty|max_length[5000]',
            'hp_stock'              => 'required|decimal|greater_than_equal_to[0]',
            'torque_stock'          => 'required|decimal|greater_than_equal_to[0]',
            'limite_hp_bloque_stock' => 'required|decimal|greater_than_equal_to[0]',
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
