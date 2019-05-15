<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Order;

class OrderController extends Controller
{
    //
    public function index(){
        $order = Order::all()->load('user','products');

        $data = [
            'code' => 200,
            'status' => 'success',
            'order' => $order
        ];

        return response()->json($data, $data['code']);

    }

    public function create(Request $request){
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if(!empty($params_array)){
            $validate = \Validator::make($params_array,[
                'total' => 'required',
            ]);
    
            if($validate->fails()){
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'Los datos enviados no son validos',
                    'error' => $validate->errors()
                ];
    
            }else{
                $order = new Order();
    
                $dineroRecibido = $params_array['money'];
                $total = $params_array['total'];
    
                $cambio = $dineroRecibido - $total ;
    
                $order->user_id = $params_array['user_id'];
                $order->product_id = $params_array['product_id'];
                $order->total = $params_array['total'];
                $order->money = $params_array['money'];
                $order->money_change = $cambio;
    
                $order->save();
    
                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'order' => $order
                ];
            }

        }else{

            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'No se encontraron datos'
            ];
        }     



        return response()->json($data, $data['code']);
    }

    public function findOrder($id){
        $order = Order::find($id);

        if(!is_null($order)){
            $order->load('user', 'products');
            $data = [
                'code' => 200,
                'status' => 'success',
                'order' => $order
            ];
        }else{
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'No se encontro el numero de orden que busca, vuelva a intentarlo'
            ];
        }

        return response()->json($data, $data['code']);
        
    }
}
