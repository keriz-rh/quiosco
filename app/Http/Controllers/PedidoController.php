<?php

namespace App\Http\Controllers;

use App\Http\Resources\PedidoCollection;
use App\Models\Pedido;
use App\Models\PedidoProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pedidos = Pedido::with(['user', 'productos' => function($query) {
            $query->orderBy('productos.nombre'); // Ordenar por nombre o 'id'
        }])->where('estado', 0)->get();
    
        return new PedidoCollection($pedidos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        //Almacenar una orden
        $pedido = new Pedido;
        $pedido->user_id = Auth::user()->id;
        $pedido->total = $request->total;
        $pedido->save();

        //obntener el ID producto
        $id = $pedido->id;
        //Obtener producto
        $productos = $request->productos;
        //Formatear un arregle
        $pedido_producto = [];

        foreach($productos as $producto){
            $pedido_producto[] = [
                'pedido_id' => $id,
                'producto_id' => $producto['id'],
                'cantidad' => $producto['cantidad'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        //ALmacenar en la DB
        PedidoProducto::insert($pedido_producto);

    return [
        'message' => 'realizando pedido, pronto estará listo' 
     ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Pedido $pedido)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pedido $pedido)
    {

        $pedido->estado = 1;
        $pedido->save();


        return [
           'pedido' => $pedido
        ];
        }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pedido $pedido)
    {
        //
    }
}
