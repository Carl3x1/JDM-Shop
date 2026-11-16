<?php

namespace App\Controllers;

use App\Models\VehiculoModel;

class Vehiculos extends BaseController
{
    public function index()
    {
        return view('vehiculos/index', [
            'vehiculos' => (new VehiculoModel())->findAll(),
        ]);
    }

    public function crear()
    {
        return view('vehiculos/crear');
    }

    public function guardar()
    {
        if (! $this->esAdministrador()) {
            return $this->accesoDenegado();
        }

        $data = $this->vehiculoData();

        if (! $this->validate($this->vehiculoRules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        if ((new VehiculoModel())->insert($data) === false) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No se pudo guardar el vehículo.');
        }

        return redirect()->to('/vehiculos')->with('success', 'Vehículo creado correctamente.');
    }

    public function mostrar($id = null)
    {
        $vehiculo = (new VehiculoModel())->find($id);

        if ($vehiculo === null) {
            return redirect()->to('/vehiculos')->with('error', 'Vehículo no encontrado.');
        }

        return view('vehiculos/ver', ['vehiculo' => $vehiculo]);
    }

    public function actualizar($id = null)
    {
        if (! $this->esAdministrador()) {
            return $this->accesoDenegado();
        }

        $model = new VehiculoModel();
        $vehiculo = $model->find($id);

        if ($vehiculo === null) {
            return redirect()->to('/vehiculos')->with('error', 'Vehículo no encontrado.');
        }

        if ($this->request->getMethod() !== 'post') {
            return view('vehiculos/editar', ['vehiculo' => $vehiculo]);
        }

        $data = $this->vehiculoData();

        if (! $this->validate($this->vehiculoRules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        if (! $model->update($id, $data)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No se pudo actualizar el vehículo.');
        }

        return redirect()->to('/vehiculos')->with('success', 'Vehículo actualizado correctamente.');
    }

    public function eliminar($id = null)
    {
        if (! $this->esAdministrador()) {
            return $this->accesoDenegado();
        }

        $model = new VehiculoModel();

        if ($model->find($id) === null) {
            return redirect()->to('/vehiculos')->with('error', 'Vehículo no encontrado.');
        }

        if (! $model->delete($id)) {
            return redirect()->to('/vehiculos')->with('error', 'No se pudo eliminar el vehículo.');
        }

        return redirect()->to('/vehiculos')->with('success', 'Vehículo eliminado correctamente.');
    }

    private function vehiculoData(): array
    {
        return [
            'marca'         => $this->request->getPost('marca'),
            'modelo'        => $this->request->getPost('modelo'),
            'descripcion'   => $this->request->getPost('descripcion'),
            'anio'          => $this->request->getPost('anio'),
            'codigo_chasis' => $this->request->getPost('codigo_chasis'),
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

    private function vehiculoRules(): array
    {
        return [
            'marca'         => 'required|max_length[100]',
            'modelo'        => 'required|max_length[100]',
            'descripcion'   => 'permit_empty|max_length[5000]',
            'anio'          => 'required|integer|greater_than[1885]|less_than_equal_to[' . (date('Y') + 1) . ']',
            'codigo_chasis' => 'required|max_length[100]',
        ];
    }
}
