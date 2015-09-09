<?php
require_once('config.php');
require("vendor/phpmailer/phpmailer/PHPMailerAutoload.php");

$radio=$_POST['sessions'];
$ePrice=$_POST['price'];
$pSize=$_POST['party'];
$amountPaid=$pSize*$ePrice;
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

$token  = $_POST['stripeToken'];
$email  =$_POST['email'];
$customer = \Stripe\Customer::create(array(
    'email' => $email,
    'card'  => $token
));

$charge = \Stripe\Charge::create(array(
    'customer' => $customer->id,
    'amount'   => 5000,
    'currency' => 'usd'
));

echo '<h1>Successfully charged $50.00!</h1>';

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


    for ($i = 0; $i < count($name); $i++) {

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




//the email payment module

// $email and $message are the data that is being
// posted to this page from our html contact form
$email = $_REQUEST['email'] ;
$message = $_REQUEST['message'] ;
// $email and $message are the data that is being
// posted to this page from our html contact form
$email = $_POST['email'] ;
$message = 'oiodiadioasiohassiofhasiosfhqioihiohioiahfioaihf';

// When we unzipped PHPMailer, it unzipped to
// public_html/PHPMailer_5.2.0


$mail = new PHPMailer();

// set mailer to use SMTP
$mail->IsSMTP();

// As this email.php script lives on the same server as our email server
// we are setting the HOST to localhost
$mail->Host = "smtp.gmail.com";  // specify main and backup server

$mail->SMTPAuth = true;     // turn on SMTP authentication

// When sending email using PHPMailer, you need to send from a valid email address
// In this case, we setup a test email account with the following credentials:
// email: send_from_PHPMailer@bradm.inmotiontesting.com
// pass: password
$mail->Username = "nandwa.moses@gmail.com";  // SMTP username
$mail->Password = "M41NANDWA"; // SMTP password

// $email is the user's email address the specified
// on our contact us page. We set this variable at
// the top of this page with:
// $email = $_REQUEST['email'] ;
$mail->From = $email;

// below we want to set the email address we will be sending our email to.
$mail->AddAddress($_POST['email']);

// set word wrap to 50 characters
$mail->WordWrap = 50;
// set email format to HTML
$mail->IsHTML(true);

$mail->Subject = "You have received feedback from your website!";

// $message is the user's message they typed in
// on our contact us page. We set this variable at
// the top of this page with:
// $message = $_REQUEST['message'] ;
$mail->Body    = $message;
$mail->AltBody = $message;

if(!$mail->Send())
{
    echo "Message could not be sent. <p>";
    echo "Mailer Error: " . $mail->ErrorInfo;
    exit;
}

echo "Message has been sent";




