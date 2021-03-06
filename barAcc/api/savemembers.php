<?php
/*
 if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
        header('Access-Control-Allow-Headers: token, Content-Type');
        header('Access-Control-Max-Age: 1728000');
        header('Content-Length: 0');
        header('Content-Type: text/plain');
        die();
    }

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
*/

// Allow from any origin
if(isset($_SERVER["HTTP_ORIGIN"]))
{
    // You can decide if the origin in $_SERVER['HTTP_ORIGIN'] is something you want to allow, or as we do here, just allow all
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
}
else
{
    //No HTTP_ORIGIN set, so we allow any. You can disallow if needed here
    header("Access-Control-Allow-Origin: *");
}

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 600");    // cache for 10 minutes

if($_SERVER["REQUEST_METHOD"] == "OPTIONS")
{
    if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"]))
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT"); //Make sure you remove those you do not want to support

    if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"]))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    //Just exit with 200 OK with the above headers for OPTIONS method
    exit(0);
}
//From here, handle the request as it is ok


if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $data = json_decode(file_get_contents("php://input"));
  //print_r($data);
  $dataArray = (object) $data ;
//printf("Name: %s contact: %s\n\n", $dataArray->name, $dataArray->contact);

$servername = "localhost";
$username = "sanwalk_baracc";
$password = "Welcome@12#";
$dbname = "sanwalk_baracc";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    $error = ("Connection failed: " . $conn->connect_error);
    $response = array(
             'status' => -1,
             'message' => $error
         );
}

$sql = "INSERT INTO members (code, name,email, designation,sodowo,contact,picpath)
VALUES ('$dataArray->code', '$dataArray->name', '$dataArray->email', '$dataArray->designation', '$dataArray->sowodo',$dataArray->contact,'$dataArray->picPath')";

if ($conn->query($sql) === TRUE) {
    //echo "New record created successfully";
    $response = array(
            'status' => 0,
            'member' => $dataArray->name,
            'message' => 'member saved successfully'
        );
} else {
     $error =  "Error: " . $sql . "<br>" . $conn->error;
     $response = array(
                 'status' => -1,
                 'message' => $error
             );
}

$conn->close();

}else {
 //echo "invalid request method";
  $response = array(
         'status' => -1,
         'message' => 'invalid request method'
     );
}

echo json_encode($response);

?>