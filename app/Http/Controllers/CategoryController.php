<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Category;

class CategoryController extends Controller
{
    //

    public function index(){
        $categories = Category::all();

        $data = [
            'code'=> 200,
            'status' => 'success',
            'categories' => $categories
        ];

        return response()->json($data,$data['code']);
    }


    public function create(Request $request){
        $json = $request->input('json',null);
        $params_array = json_decode($json,true);

        

        if(!empty($params_array)){

            $validate = \Validator::make($params_array, [
                'name' => 'required|string|unique:categories'
            ]);

            if($validate->fails()){                

                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'Datos no validos',
                    'error' => $validate->errors()
                ];
            }else{

                $category = new Category();
                $category->name = $params_array['name'];
                $category->save();

                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Datos recibidos',
                    'category' => $category
                ];
            }


            

        }else{
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Envia los datos correctamente'
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function update(Request $request, $id){
        $json = $request->input('json',null);
        $params_array = json_decode($json,true);

        if(!empty($params_array)){

            $validate = \Validator::make($params_array,[
                'name' => 'required|alpha|unique:categories'
            ]);

            if($validate->fails()){
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'error' => $validate->errors()
                ];
            }else{

                // unset para excluir lo que no queremos actualizar
                unset($params_array['id']);
                unset($params_array['created_at']);


                // Actualizar en el registro
                $category = Category::where('id',$id)->update($params_array);

                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'category' => $category,
                    'changes' => $params_array
                ];
    
                
            }

        }else{
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'No has enviado una categoria para actualizar'
            ];
        }
        return response()->json($data,$data['code']);
        

    }

    public function delete($id){

        
        $category = Category::find($id);

        if($category){
            $category->delete();

            $data = [
                'code' => 200,
                'status' => 'success',
                'category' => $category
            ];
        }else{
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'No se encontro la categoria'
            ];
        }


        return response()->json($data,$data['code']);
    }
    
}
