<?php
require_once('config.php');
require("vendor/phpmailer/phpmailer/PHPMailerAutoload.php");

$radio=$_POST['sessions'];
$ePrice=$_POST['price'];
$pSize=$_POST['party'];
$amountPaid=$pSize*$ePrice;
$stripeAmount=$_POST['price']*$_POST['party']*100;
$methodd=$_POST['payments'];

if(($_POST['payments'])=="card"){
    $code=$token;
}
if($methodd=="mpesa"){
    $status="not confirmed";
    $code=$_POST['code'];
}
else{
    //stripe value$code=$_POST[''];
    $status=="confirmed";
}


$servername = "localhost";
$username = "root";
$password = "qwerty41";
$dbname = "book";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exc    eption

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "INSERT INTO bookings (payMethod, code, eventID, paid, sittingID, amountPaid)
    VALUES ('".$_POST['payments']."','".$_POST['code']."','".$_POST['eventID']."','$status','".$_POST['sessions']."',$amountPaid)";

    // use exec() because no results are returned
    $conn->exec($sql);

    $last_id= $conn->lastInsertId();
    global$last_id;

}
catch(PDOException $e)
{
    echo $sql . "<br>" . $e->getMessage();

}

$conn = null;


$conn = mysqli_connect("localhost", "root", "qwerty41", "book");
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "INSERT INTO attendees (fName, lName, email,phone, allergies,bookerStatus,bookingID)
VALUES ('".$_POST['fName']."', '".$_POST['lName']."','".$_POST['email']."','".$_POST['phone']."' ,'".$_POST['allergies']."','".$_POST['fName']."','$last_id')";

if (mysqli_query($conn, $sql)) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);

if($_POST['payments']=='card'){
$token  = $_POST['stripeToken'];
$email  =$_POST['email'];
$customer = \Stripe\Customer::create(array(
    'email' => $email,
    'card'  => $token
));

$charge = \Stripe\Charge::create(array(
    'customer' => $customer->id,
    'amount'   => $stripeAmount,
    'currency' => 'kes'
));

echo '<h1>Successfully charged $50.00!</h1>';


}
try {
    $pdo = new PDO('mysql:host=localhost;dbname=book;','root','qwerty41');


//Output any connection error


    $name ="";
    if(isset($_POST["namee"])){
        foreach($_POST["namee"] as $key => $text_field){
            $name .= $text_field .", ";
        }
    }

    $name=explode(',',($name));

    $email ="";
    if(isset($_POST["emaill"])){
        foreach($_POST["emaill"] as $key => $text_field){
            $email .= $text_field .", ";
        }
    }

    $email=explode(',',($email));


    $phone ="";
    if(isset($_POST["phonee"])){
        foreach($_POST["phonee"] as $key => $text_field){
            $phone.= $text_field .", ";
        }
    }

    $phone=explode(',',($phone));

    $allergies ="";
    if(isset($_POST["allergyy"])){
        foreach($_POST["allergyy"] as $key => $text_field){
            $allergies .= $text_field .", ";
        }
    }

    $allergies=explode(',',($allergies));
    $query=null;
    $pdo;
    $prepare;


    for ($i = 0; $i < (count($name)-1); $i++) {

        $query = $pdo->prepare("INSERT INTO attendees (fName, email, phone, allergies,bookerStatus,bookingID) VALUES (:name, :email, :phone ,:allergies,:bName,:id)");
        $query->execute(array(
            ":name" => $name[$i],
            ":email" => $email[$i],
            ":phone" => $phone[$i],
            ":allergies" => $allergies[$i],
            ":id"=>$last_id,
            ":bName"=>$_POST['fName']
        ));
    }



} catch(PDOException $e){
    echo 'Connection failed'.$e->getMessage();
}









$mail             = new PHPMailer(); // defaults to using php "mail()"

$mail->IsSendmail(); // telling the class to use SendMail transport

$body             = file_get_contents('partyMail.html');
$body             = eregi_replace("[\]",'',$body);

$mail->AddReplyTo("bookings.gizani.com","Gizani Bookings");

$mail->SetFrom('bookings.gizani.com', 'Gizani Bookings');


$address = $_POST['email'];
$mail->AddAddress($address, "Customer");

$mail->Subject    = "Booking has been confirmed";

$mail->AltBody    = "The booking has been nconfirmed"; // optional, comment out and test

$mail->MsgHTML($body);


if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
}
    



