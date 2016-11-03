<?php

namespace App\Http\Controllers;
use App\User;
use Facebook\Facebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\CountValidator\Exception;

class loginController extends Controller
{
    public function loginFb(Request $request)
    {
        $fb = new Facebook([
            'app_id' => '1100936123367194}',
            'app_secret' => 'dd16902736433d99c962f0739a995970',
            'default_graph_version' => 'v2.8',
        ]);
        $token = $request->input('token');
        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->get('/me?fields=id,name,email,last_name,first_name', $token);
        } catch(Exception $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Exception $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $user = $response->getGraphUser();
        $results = app('db')->select("SELECT * FROM client WHERE login='{$user["id"]}'");

        if($results==null or $results[0]->login != $user["id"]){
        $results = app('db')->select("INSERT INTO `client`(`id`,`image`, `login`,`nom`, `password`, `prenom`, `tel`, `verifier`,`total_point`,`token`) VALUES ('','','{$user["id"]}','{$user["last_name"]}','','{$user["first_name"]}','0691948548','',0,'$token')");
        return json_encode($results);
        }else
            return null;

        //return $results;

        //$permissions = ['user_friends']; // optionnal
        /*$url = 'https://graph.facebook.com/me?'.http_build_query(array(
                'access_token' => $token,
            ));

        $user = json_decode(file_get_contents($url));

        // Create a response from the request
        return array(
            'uid' => $user->id,
            'friends' => 'https://graph.facebook.com/me?fields=id,name,email&access_token='.$token,
        );*/


    }
    public function loginSimple(Request $request)
    {
        $login = $request->input('login');
        $pass = $request->input('password');
        $results = app('db')->select("SELECT * FROM client WHERE login='$login' and password='$pass'");
        return json_encode($results);
    }
    public function createClient(Request $request){

        $login = $request->input('login');
        $password = $request->input('password');
        $nom = $request->input('nom');
        $prenom = $request->input('prenom');
        $tel = $request->input('tel');
        $image = $request->input('image');
        $results = app('db')->select("INSERT INTO `client`(`nom`, `prenom`, `tel`, `verifier`, `login`, `password`, `image`) VALUES ('$nom','$prenom','$tel','','$login','$password','$image')");
        return json_encode($results);

    }

}