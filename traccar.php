<?php

class traccar {

    public static $host='http://localhost:8082';

    private static $adminEmail='admin';
    
    private static $adminPassword='admin';

    public static $cookie;

    private static $json='Content-Type: application/json';

    private static $urlencoded='Content-Type: application/x-www-form-urlencoded';

    public static function loginAdmin() {

        return self::login(self::$adminEmail,self::$adminPassword);
    }
    
    public static function register($name,$email,$password,$cookie='') {

        $data='{"name":"'.$name.'","email":"'.$email.'","password":"'.$password.'"}';

        return self::curl('/api/users','POST',$cookie,$data,array(self::$json));
    }

    public static function login($email,$password) {

        $data='email='.$email.'&password='.$password;

        return self::curl('/api/session','POST','',$data,array(self::$urlencoded));
    }

    public static function addUser($name,$email,$password,$cookie) {

        $data='{"id":-1,"name":"'.$name.'","email":"'.$email.'","password":"'.$password.'","admin":false,"map":"","distanceUnit":"","speedUnit":"","latitude":0,"longitude":0,"zoom":0,"twelveHourFormat":false}';

        return self::curl('/api/users/','POST',$cookie ,$data,array(self::$json));
    }

    public static function updateUser($id,$name,$email,$password,$cookie) {

        $password=$password!='' ? $password:'';

        $data='{"id":'.$id.',"attributes":{},"name":"'.$name.'","email":"'.$email.'","readonly":false,"admin":false,"map":"","distanceUnit":"","speedUnit":"","latitude":0,"longitude":0,"zoom":0,"twelveHourFormat":false,"password":"'.$password.'"}';

        return self::curl('/api/users/'.$id,'PUT',$cookie ,$data,array(self::$json));
    }

    public static function deleteUser($id,$name,$email,$cookie) {

        $data='{"id":'.$id.',"attributes":{},"name":"'.$name.'","email":"'.$email.'","readonly":false,"admin":false,"map":"","distanceUnit":"","speedUnit":"","latitude":0,"longitude":0,"zoom":0,"twelveHourFormat":false,"password":""}';

        return self::curl('/api/users/'.$id,'DELETE',$cookie ,$data,array(self::$json));
    }

    public static function addDevice($name,$uniqueId,$cookie) {

        $data='{"id":-1,"name":"'.$name.'","uniqueId":"'.$uniqueId.'","status":"","lastUpdate":null,"groupId":0}';

        return self::curl('/api/devices','POST',$cookie ,$data,array(self::$json));
    }

    public static function editDevice($id,$name,$uniqueId,$cookie) {

        $data='{"id":'.$id.',"name":"'.$name.'","uniqueId":"'.$uniqueId.'","status":"","lastUpdate":null,"groupId":0}';

        return self::curl('/api/devices/'.$id,'PUT',$cookie ,$data,array(self::$json));
    }

    public static function deleteDevice($id,$name,$uniqueId,$cookie) {

        $data='{"id":'.$id.',"name":"'.$name.'","uniqueId":"'.$uniqueId.'","status":"","lastUpdate":null,"groupId":0,"positionId":0}';

        return self::curl('/api/devices/'.$id,'DELETE',$cookie ,$data,array(self::$json));
    }

    public static function addDevicePermissions($userId,$deviceId,$cookie) {

        $data='{"userId":'.$userId.',"deviceId":'.$deviceId.'}';

        return self::curl('/api/permissions/devices','POST',$cookie ,$data,array(self::$json));
    }

    public static function deleteDevicePermissions($userId,$deviceId,$cookie) {

        $data='{"userId":'.$userId.',"deviceId":'.$deviceId.'}';

        return self::curl('/api/permissions/devices','DELETE',$cookie ,$data,array(self::$json));
    }

    public static function logout($cookie) {
        
        return self::curl('/api/session','DELETE',$cookie ,'',array(self::$urlencoded));
    }

    public static function addGeofance($name,$area,$cookie) {

        $data='{"id":-1,"name":"'.$name.'","description":"","area":"'.$area.'"}';

        return self::curl('/api/geofences','POST',$cookie ,$data,array(self::$json));
    }

    public static function editGeofance($id,$name,$area,$cookie) {

        $data='{"id":'.$id.',"attributes":{},"name":"'.$name.'","description":"","area":"'.$area.'"}';

        return self::curl('/api/geofences/'.$id,'PUT',$cookie,$data,array(self::$json));
    }

    public static function deleteGeofance($id,$name,$area,$cookie) {

        $data='{"id":'.$id.',"attributes":{},"name":"'.$name.'","description":"","area":"'.$area.'"}';

        return self::curl('/api/geofences/'.$id,'DELETE',$cookie ,$data,array(self::$json));
    }

    public static function addGeofancePermisions($userId,$geofenceId,$cookie) {

        $data='{"userId":'.$userId.',"geofenceId":'.$geofenceId.'}';

        return self::curl('/api/permissions/geofences','POST',$cookie ,$data,array(self::$json));
    }

    public static function deleteGeofancePermisions($userId,$geofenceId,$cookie) {

        $data='{"userId":'.$userId.',"geofenceId":'.$geofenceId.'}';

        return self::curl('/api/permissions/geofences','DELETE',$cookie ,$data,array(self::$json));
    }

    public static function addDeviceGeofance($deviceId,$geofenceId,$cookie) {

        $data='{"deviceId":'.$deviceId.',"geofenceId":'.$geofenceId.'}';

        return self::curl('/api/devices/geofences','POST',$cookie ,$data,array(self::$json));
    }

    public static function deleteDeviceGeofance($deviceId,$geofenceId,$cookie) {

        $data='{"deviceId":'.$deviceId.',"geofenceId":'.$geofenceId.'}';

        return self::curl('/api/devices/geofences', 'DELETE',$cookie ,$data,array(self::$json));
    }

    public static function addUsersNotifications($userId,$type,$attributes,$cookie) {

        $data='{"userId":'.$userId.',"type":"'.$type.'","attributes":'.$attributes.'}';

        return self::curl('/api/users/notifications','POST',$cookie ,$data,array(self::$json));
    }

    public static function positions($deviceId,$from,$to,$cookie) {

        $data='deviceId='.$deviceId.'&from='.$from.'&to='.$to;

        return self::curl('/api/positions?'.$data,'GET',$cookie ,'',array());
    }

    public static function commandtypes($deviceId,$cookie) {

        $data='deviceId='.$deviceId;

        return self::curl('/api/commandtypes?'.$data,'GET',$cookie ,'',array());
    }

    public static function sendCommand($deviceId,$type,$attributes,$cookie) {

        if($type=='custom') $attributes=',"attributes":{"data":"'.$attributes.'"}';
        else if($type=='positionPeriodic') $attributes=',"attributes":{"frequency":'.$attributes.'}';

        $data='{"deviceId":'.$deviceId.',"type":"'.$type.'","id":-1'.$attributes.'}';

        return self::curl('/api/commands','POST',$cookie ,$data,array(self::$json));
    }

    public static function curl($task,$method,$cookie,$data,$header) {

        $res=new stdClass();

        $res->responseCode='';

        $res->error='';

        $header[]="Cookie: ".$cookie;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, self::$host.$task);

        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        curl_setopt($ch, CURLOPT_HEADER, 1);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if($method=='POST' || $method=='PUT' || $method=='DELETE') {

            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);

        $data=curl_exec($ch);

        $size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        if (preg_match('/^Set-Cookie:\s*([^;]*)/mi', substr($data, 0, $size), $c) == 1) self::$cookie = $c[1];

        $res->response = substr($data, $size);

        if(!curl_errno($ch)) {

            $res->responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        }
        else {

            $res->responseCode=400;

            $res->error= curl_error($ch);
        }

        curl_close($ch);

        return $res;
    }
}

$t=traccar::login($email,$password);

if($t->responseCode=='200') {

    $traccarCookie = traccar::$cookie;

    /*

    ......

     */
}
else echo 'Incorrect email address or password';