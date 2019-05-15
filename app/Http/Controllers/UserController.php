<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class UserController extends Controller
{
    public function register(Request $request)
    {
        
        $json = $request->input('json',null);
        $params = json_decode($json); // Objeto
        $params_array = json_decode($json,true);

        if (!empty($params_array) && !empty($params)) {
            // Limpiar datos
            $params_array = array_map('trim', $params_array);

            // Validar datos
            $validate = \Validator::make($params_array, [
                        'name' => 'required|alpha',
                        'surname' => 'required|string',
                        'email' => 'required|email|unique:users',
                        'password' => 'required',
                        'dni' => 'required'
            ]);

            if ($validate->fails()) {
                //Validacion ha fallado
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'El usuario no se ha creado',
                    'errors' => $validate->errors()
                );
                return response()->json($data, $data['code']);
            } else {

                //Validacion pasada correctamente
                // Cifrar la contraseña
                $pwd = hash('sha256', $params->password);

                // Crear el usuario
                $user = new User();
                $user->name = $params_array['name'];
                $user->surname = $params_array['surname'];
                $user->email = $params_array['email'];
                $user->password = $pwd;
                $user->role = "ROLE_USER";

                //Guardar el usuario
                $user->save();


                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'El usuario se ha creado correctamente',
                    'user' => $user
                );
            }
        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'Los datos enviados no son correctos'
            );
        }

        return response()->json($data, $data['code']);


        /*
        if(!empty($params_array)){
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255|unique:users',
                'name' => 'required',
                'password'=> 'required'
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors());
            }
            User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => bcrypt($request->get('password')),
            ]);
            $user = User::first();
            $token = JWTAuth::fromUser($user);
        }else{

            $data = [
                "code" => 404,
                "status" => "error",
                "user" => $user
            ];
        }
        
            
        
        return Response::json(compact('token'));
        */


        
    }

    public function login(Request $request){

        $jwtAuth = new \JwtAuth();

        // Recibir los datos por post
        $json = $request->input('json',null);
        $params = json_decode($json);
        $params_array = json_decode($json,true);

        // Validar los datos
        $validate = \Validator::make($params_array, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validate->fails()) {
            //Validacion ha fallado
            $signup = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'El usuario no se ha podido identificar',
                'errors' => $validate->errors()
            );
            return response()->json($signup, $signup['code']);
        } else{            

           // Cifrar la password
           $pwd = hash('sha256', $params->password);
           //Devolver token o datos
           $signup = $jwtAuth->signup($params->email, $pwd);
           if (!empty($params->gettoken)) {
               $signup = $jwtAuth->signup($params->email, $pwd, true);
           }
        }

        return response()->json($signup,200);
    }

    public function update(Request $request){

        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        // Recoger los datos por post
        $json = $request->input('json',null);
        $params_array = json_decode($json, true);

        if($checkToken && !empty($params_array)){

            // Sacar usuario identificado
            $user = $jwtAuth->checkToken($token, true);            
            
            // Validar los datos
            $validate = \Validator::make($params_array, [
                'name' => 'required|alpha',
                'surname' => 'required|alpha',
                'email' => 'required|email|unique:users,email,'.$user->sub
            ]);

            if($validate->fails()){

                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'Datos no validos',
                    'error' => $validate->errors()
                ];

            }else{
                // Quitar los campos que no quiero actualizar
                unset($params_array['id']);
                unset($params_array['created_at']);
                unset($params_array['role']);
                unset($params_array['password']);
                unset($params_array['remember_token']);                

                // Actualizar usuario en la BD
                $user_update = User::where('id',$user->sub)->update($params_array);

                // Devolver array
                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'user' => $user,
                    'changes' => $params_array
                ];
            }            

        }else{
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'El usuario no esta identificado.'
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function upload(Request $request){
        $data = [
            'code' => 400,
            'status' => 'error',
            'message' => 'Error al subir imagen'
        ];

        return response()->json($data, $data['code']);
    }

    public function delete($id){
        $user = User::find($id);

        if($user->role == 'ROLE_USER'){
            $data = [
                'code' => 401,
                'status' => 'error',
                'message' => 'No tiene los permisos suficiente para esta accion'
            ];
        }else{

            $user->delete();

            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => 'El usuario fue eliminado correctamente',
                'user' => $user
            ];
        }

        return response()->json($data, $data['code']);
                
    }
}
