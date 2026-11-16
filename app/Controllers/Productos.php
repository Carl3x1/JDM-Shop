<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\MotorModel;
use App\Models\ComponenteModel;

class Productos extends BaseController
{
    public function index()
    {
        $tipo = (string) $this->request->getGet('tipo');
        if (! in_array($tipo, ['todos', 'producto', 'motor', 'componente'], true)) {
            $tipo = 'todos';
        }
        $busqueda = trim((string) $this->request->getGet('q'));
        $catalogo = [];
        if (in_array($tipo, ['todos', 'producto'], true)) {
            foreach ((new ProductoModel())->findAll() as $item) {
                $catalogo[] = ['tipo'=>'producto','id'=>$item['id_producto'],'nombre'=>$item['nombre'],'categoria'=>$item['categoria'],'descripcion'=>$item['descripcion'] ?? '','detalle'=>'Precio $'.number_format((float)$item['precio'],2).' · Stock '.$item['stock'],'url'=>'productos/mostrar/'.$item['id_producto'],'editar'=>'productos/actualizar/'.$item['id_producto']];
            }
        }
        if (in_array($tipo, ['todos', 'motor'], true)) {
            foreach ((new MotorModel())->findAll() as $item) {
                $catalogo[] = ['tipo'=>'motor','id'=>$item['id_motor'],'nombre'=>$item['codigo_motor'],'categoria'=>'Motores','descripcion'=>$item['descripcion'] ?? '','detalle'=>number_format((float)$item['hp_stock'],0).' HP · '.number_format((float)$item['torque_stock'],0).' lb-ft','url'=>'motores/mostrar/'.$item['id_motor'],'editar'=>'motores/actualizar/'.$item['id_motor']];
            }
        }
        if (in_array($tipo, ['todos', 'componente'], true)) {
            foreach ((new ComponenteModel())->findAll() as $item) {
                $catalogo[] = ['tipo'=>'componente','id'=>$item['id_componente'],'nombre'=>$item['nombre'],'categoria'=>$item['categoria'],'descripcion'=>$item['descripcion'] ?? '','detalle'=>'Aporte +'.$item['aporte_hp_pct'].'% HP · $'.number_format((float)$item['precio'],2),'url'=>'componentes/mostrar/'.$item['id_componente'],'editar'=>'componentes/actualizar/'.$item['id_componente']];
            }
        }
        if ($busqueda !== '') {
            $catalogo = array_values(array_filter($catalogo, static fn ($item) => mb_stripos($item['nombre'].' '.$item['categoria'].' '.$item['descripcion'], $busqueda) !== false));
        }
        $categorias = array_values(array_unique(array_column($catalogo, 'categoria')));
        sort($categorias, SORT_NATURAL | SORT_FLAG_CASE);
        $categoria = trim((string) $this->request->getGet('categoria'));
        if ($categoria !== '') {
            $catalogo = array_values(array_filter($catalogo, static fn ($item) => $item['categoria'] === $categoria));
        }
        return view('productos/index', ['catalogo'=>$catalogo, 'tipo'=>$tipo, 'busqueda'=>$busqueda, 'categoria'=>$categoria, 'categorias'=>$categorias]);
    }

    public function crear()
    {
        return view('productos/crear');
    }

    public function guardar()
    {
        if (! $this->esAdministrador()) {
            return $this->accesoDenegado();
        }

        $data = $this->productoData();

        if (! $this->validate($this->productoRules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $model = new ProductoModel();

        if ($model->insert($data) === false) {
            return redirect()->back()->withInput()->with(
                'error',
                'No se pudo guardar el producto.'
            );
        }

        return redirect()->to('/productos')->with('success', 'Producto creado correctamente.');
    }

    public function mostrar($id = null)
    {
        $producto = (new ProductoModel())->find($id);

        if ($producto === null) {
            return redirect()->to('/productos')->with('error', 'Producto no encontrado.');
        }

        return view('productos/ver', ['producto' => $producto]);
    }

    public function actualizar($id = null)
    {
        if (! $this->esAdministrador()) {
            return $this->accesoDenegado();
        }

        $model = new ProductoModel();
        $producto = $model->find($id);

        if ($producto === null) {
            return redirect()->to('/productos')->with('error', 'Producto no encontrado.');
        }

        if ($this->request->getMethod() !== 'post') {
            return view('productos/editar', ['producto' => $producto]);
        }

        $data = $this->productoData();

        if (! $this->validate($this->productoRules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        if (! $model->update($id, $data)) {
            return redirect()->back()->withInput()->with(
                'error',
                'No se pudo actualizar el producto.'
            );
        }

        return redirect()->to('/productos')->with('success', 'Producto actualizado correctamente.');
    }

    public function eliminar($id = null)
    {
        if (! $this->esAdministrador()) {
            return $this->accesoDenegado();
        }

        $model = new ProductoModel();

        if ($model->find($id) === null) {
            return redirect()->to('/productos')->with('error', 'Producto no encontrado.');
        }

        if (! $model->delete($id)) {
            return redirect()->to('/productos')->with('error', 'No se pudo eliminar el producto.');
        }

        return redirect()->to('/productos')->with('success', 'Producto eliminado correctamente.');
    }

    private function productoData(): array
    {
        return [
            'nombre'         => $this->request->getPost('nombre'),
            'descripcion'    => $this->request->getPost('descripcion'),
            'categoria'      => $this->request->getPost('categoria'),
            'precio'         => $this->request->getPost('precio'),
            'stock'          => $this->request->getPost('stock'),
            'aporte_hp'      => $this->request->getPost('aporte_hp'),
            'aporte_torque'  => $this->request->getPost('aporte_torque'),
        ];
    }

    private function productoRules(): array
    {
        return [
            'nombre'        => 'required|max_length[255]',
            'descripcion'   => 'permit_empty|max_length[5000]',
            'categoria'     => 'required|max_length[100]',
            'precio'        => 'required|decimal|greater_than_equal_to[0]',
            'stock'         => 'required|integer|greater_than_equal_to[0]',
            'aporte_hp'     => 'required|decimal',
            'aporte_torque' => 'required|decimal',
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
