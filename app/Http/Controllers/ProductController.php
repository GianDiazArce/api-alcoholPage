<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Product;
use App\Category;


class ProductController extends Controller
{
    //
    public function index(){

        $product = Product::all()->load('category');

        $data = [
            'code' => 200,
            'status' => 'success',
            'product' => $product
        ];

        return response()->json($data,$data['code']);
    }

    public function create(Request $request){
        $json = $request->input('json',null);
        $params_array = json_decode($json,true);

        if(!empty($params_array)){

            $validate = \Validator::make($params_array,[
                'name' => 'required|string',
                'stock' => 'required',
                'price' => 'required',
                'size' => 'required'
            ]);

            if($validate->fails()){
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'Datos no validos',
                    'error' => $validate->errors()
                ];
            }else{

                $product = new Product();
                $product->name = $params_array['name'];
                $product->category_id = $params_array['category_id'];
                $product->description = $params_array['description'];
                $product->stock = $params_array['stock'];
                $product->price = $params_array['price'];
                $product->size = $params_array['size'];
                $product->save();

                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'product' => $product
                ];
            }


        }else{
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Ingrese datos por favor'
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function update(Request $request,$id){
        $json = $request->input('json',null);
        $params_array = json_decode($json,true);

        if(!empty($params_array)){

            $validate = \Validator::make($params_array,[
                'name' => 'required',
                'category_id' => 'required',
                'description' => 'required',
                'stock' => 'required',
                'price' => 'required',
                'size' => 'required'                
            ]);

            if($validate->fails()){
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'Datos invalidos',
                    'error' => $validate->errors()
                ];
            }else{

                unset($params_array['id']);
                unset($params_array['created_at']);

                $product = Product::where(id,$id)->update($params_array);

                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'product' => $product,
                    'changes' => $params_array
                ];
            }


        }else{
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Los datos enviados estan incorrectos o faltan datos'
            ];
        }

        return response()->json($data,$data['code']);
    }
    public function upload(Request $request) {
        // Recoger datos de la peticion
        $image = $request->file('file0');
        $disk = 'products';
    
        // Validacion de la imagen
    
        $validate = \Validator::make($request->all(), [
                    'file0' => 'required|image|mimes:jpg,jpeg,png,gif'
        ]);
    
        // Guardar imagen
        if (!$image || $validate->fails()) {
    
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'Error al subir imagen'
            );
        } else {
    
            $image_name = time() . $image->getClientOriginalName();
            \Storage::disk($disk)->put($image_name, \File::get($image));
    
            $data = array(
                'code' => 200,
                'status' => 'success',
                'image' => $image_name
            );
        }
    
        return response()->json($data, $data['code']);
    }
    
    public function getImage($filename) {
        $isset = \Storage::disk('products')->exists($filename);
        if ($isset) {
            $file = \Storage::disk('products')->get($filename);
            return new Response($file, 200);
        } else {
            $data = array(
                'code' => 200,
                'status' => 'success',
                'message' => 'La imagen no existe'
            );
    
            return response()->json($data, $data['code']);
        }
    }

    public function delete($id){
        $product = Product::find($id);

        if($product){
            $product->delete();
            
            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => 'El producto fue borrado exitosamente'
            ];
        }else{
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'El producto no fue eliminado, hubo un error!'
            ];
        }

        return response()->json($data, $data['code']);
    }
}


