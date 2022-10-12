<?php

namespace App\Http\Controllers;

use App\Models\Zoom;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/*
 * Creación de controlador para la 
 * Conexión a api de Zoom.
 * @author Angel Concha Mallea <angelconchamallea@outlook.com>
*/

class ZoomController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $objZomm = new Zoom();

        $getEstadoCuenta = $objZomm->getUser(auth()->user()->id);

        if($getEstadoCuenta)
        {
            $this->data['zoom'] = $getEstadoCuenta;
            return view('zoom.users', $this->data);
        }
        else
        {
            $this->data['cuenta'] = 'NOCUENTA';
            return view('zoom.index', $this->data);
        }
    }

    /**
     * funcion que redirecciona a zoom cada vez que el usuario desee vincular su cuenta.
     *
     * @return \Illuminate\Http\Response
    */
    public function auth()
    {
        $url = "https://zoom.us/oauth/authorize?response_type=code&client_id=".env('ZOOM_CLIENT_ID')."&redirect_uri=".env('ZOOM_REDIRECT_URL')."";
        
        $this->data['status'] = 'success';
        $this->data['url']    = $url;
            
        return response()->json($this->data);
    }

    /**
     * funcion que recibe los parametros y realiza una peticion de zoom para obtener los datos de la cuenta correspondiente.
     *
     * @return \Illuminate\Http\Response
    */
    public function successAuth(Request $req)
    {
        $objZomm = new Zoom();

        if (isset($req->code)) 
        {
            $result = $this->get_acces_token($req->code);

            if (isset($result->access_token)) 
            {
                $horas = floor($result->expires_in / 3600);
                $minutos = floor(($result->expires_in - ($horas * 3600)) / 60);
                $segundos = $result->expires_in - ($horas * 3600) - ($minutos * 60);

                $expira_en = $horas . ':' . $minutos . ":" . $segundos;

                $date = Carbon::now();

                $user = $this->get_user_zoom($result->access_token);

                $data = array(
                    'id_user'       => auth()->user()->id,
                    'id_zoom'       => $user->id,
                    'user_zoom'     => $user->first_name.' '.$user->last_name,
                    'email_zoom'    => $user->email,
                    'code'          => $req->code,
                    'access_token'  => $result->access_token,
                    'token_type'    => $result->token_type,
                    'refresh_token' => $result->refresh_token,
                    'expires_in'    => $expira_en,
                    'scope'         => $result->scope,
                    'estado'        => 'activado',
                    'created_at'     => $date,
                );

                $insert = $objZomm->insertUserByZoom($data);

                if($insert)
                {
                    $this->data['zoom'] = $objZomm->getUser(auth()->user()->id);
                    return view('zoom.users', $this->data);
                }
                else
                {
                    $this->data['cuenta'] = 'NOCONECTADO';
                    return view('zoom.index', $this->data);
                }
            }
            else
            {
                $this->data['cuenta'] = 'NOCONECTADO';
                return view('zoom.index', $this->data);
            }
        } 
        else 
        {
            $this->data['cuenta'] = 'NOCONECTADO';
            return view('zoom.index', $this->data);
        }
    }

    /**
     * funcion que realiza un refresh del token de la cuenta del usuario en zoom y nuestro sistema.
     *
     * @return \Illuminate\Http\Response
    */
    public function reautorizar()
    {
        $objZomm = new Zoom();

        $getEstadoCuenta = $objZomm->getUser(auth()->user()->id);
        
        if($getEstadoCuenta)
        {
            $user = $this->reautorizar_cuenta($getEstadoCuenta->refresh_token);

            if($user->access_token)
            {
                $date = Carbon::now();

                $horas = floor($user->expires_in / 3600);
                $minutos = floor(($user->expires_in - ($horas * 3600)) / 60);
                $segundos = $user->expires_in - ($horas * 3600) - ($minutos * 60);

                $expira_en = $horas . ':' . $minutos . ":" . $segundos;

                $data = array(
                    'access_token'  => $user->access_token,
                    'token_type'    => $user->token_type,
                    'refresh_token' => $user->refresh_token,
                    'expires_in'    => $expira_en,
                    'scope'         => $user->scope,
                    'estado'        => 'activado',
                    'updated_at'    => $date,
                );

                $update = $objZomm->updateUserByZoom(auth()->user()->id,$data);

                if($update)
                {
                    $this->data['status'] = 'success';
                }
                else
                {
                    $this->data['status'] = 'error';
                    $this->data['msg'] = 'No se pudo reautorizar la cuenta de zoom, intente nuevamente si el error persiste por favor contacte a soporte (err:003).';
                }
            }
            else
            {
                $this->data['status'] = 'error';
                $this->data['msg'] = 'No se pudo reautorizar la cuenta de zoom, intente nuevamente si el error persiste por favor contacte a soporte (err:002).';
            }
        }
        else
        {
            $this->data['status'] = 'error';
            $this->data['msg'] = 'No se encuenta la cuenta de zoom, intente nuevamente si el error persiste por favor contacte a soporte (err:001).';
        }

        return json_encode($this->data);
    }

    /**
     * funcion que sirve para desvincular la cuenta correspondiente del usuario de zoom y nuestro sistema.
     *
     * @return \Illuminate\Http\Response
    */
    public function desvincular()
    {
        $objZomm = new Zoom();

        $getEstadoCuenta = $objZomm->getUser(auth()->user()->id);
        
        if($getEstadoCuenta)
        {
            $user = $this->desvincular_cuenta($getEstadoCuenta->access_token);

            if($user->status == 'success')
            {
                $desZoom = $objZomm->desvincularCuenta(auth()->user()->id);

                if($desZoom)
                {
                    $this->data['status'] = 'success';
                }
                else
                {
                    $this->data['status'] = 'error';
                    $this->data['msg'] = 'No se pudo desvincular la cuenta de zoom, intente nuevamente si el error persiste por favor contacte a soporte (err:003).';
                }
            }
            else
            {
                $this->data['status'] = 'error';
                $this->data['msg'] = 'No se pudo desvincular la cuenta de zoom, intente nuevamente si el error persiste por favor contacte a soporte (err:002).';
            }
        }
        else
        {
            $this->data['status'] = 'error';
            $this->data['msg'] = 'No se encuenta la cuenta de zoom, intente nuevamente si el error persiste por favor contacte a soporte (err:001).';
        }

        return json_encode($this->data);
    }

    /*
    * Api que obtiene el acces y refresh token del cliente de nuestra app zoom.
    */
    protected function get_acces_token($code)
    {
        $api_url = "https://zoom.us/oauth/token";

        $post_data = array(
            'code'         => $code,
            'grant_type'   => 'authorization_code',
            'redirect_uri' => env('ZOOM_REDIRECT_URL')
        );

        $type = 'post';

        $code_base64 = base64_encode(env('ZOOM_CLIENT_ID').':'.env('ZOOM_CLIENT_SECRET'));
        $acces_token = 'Authorization: Basic '.$code_base64;

        $result = $this->_conecttionM($api_url, $post_data, $type, $acces_token);

        return json_decode($result);
    }

    /*
    * Api que obtiene los datos del usuario que dio permisos a la app de zoom.
    */
    protected function get_user_zoom($token)
    {
        $api_url = "https://api.zoom.us/v2/users/me";

        $post_data = array();

        $type = 'get';

        $acces_token = 'Authorization: Bearer '.$token;

        $result = $this->_conecttionM($api_url, $post_data, $type, $acces_token);

        return json_decode($result);
    }

    /*
    * Api que desvincula la cuenta del usuario que dio permisos a la app de zoom.
    */
    protected function reautorizar_cuenta($refresh_token)
    {
        $api_url = "https://zoom.us/oauth/token";

        $post_data = array(
            'grant_type'    => 'refresh_token',
            'refresh_token' => $refresh_token
        );

        $type = 'post';

        $code_base64 = base64_encode(env('ZOOM_CLIENT_ID').':'.env('ZOOM_CLIENT_SECRET'));
        $acces_token = 'Authorization: Basic '.$code_base64;

        $result = $this->_conecttionM($api_url, $post_data, $type, $acces_token);

        return json_decode($result);
    }

    /*
    * Api que reautoriza la cuenta del usuario que dio permisos a la app de zoom.
    */
    protected function desvincular_cuenta($token)
    {
        $api_url = "https://zoom.us/oauth/revoke";

        $post_data = array(
            'token' => $token 
        );

        $type = 'post';

        $code_base64 = base64_encode(env('ZOOM_CLIENT_ID').':'.env('ZOOM_CLIENT_SECRET'));
        $acces_token = 'Authorization: Basic '.$code_base64;

        $result = $this->_conecttionM($api_url, $post_data, $type, $acces_token);

        return json_decode($result);
    }

    private function _conecttionM($api_url, $post_data, $type, $acces_token)
    {
        $headers = [
            $acces_token,
            'Content-Type: application/x-www-form-urlencoded',
        ];

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_URL, $api_url);

        switch ($type) {
            case 'post':
                curl_setopt($curl, CURLOPT_POST, TRUE);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data));
            break;
            case 'get':
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            break;
            case 'delete':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            break;
            case 'put':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
            break;
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);

        // Comprobar si occurió algún error
        if (curl_errno($curl)) {
            dump(curl_error($curl));
        }
    
        curl_close($curl);

        return $result;
    }
}
