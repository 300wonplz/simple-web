<?php
/**
 * Created by PhpStorm.
 * Date: 2017-07-31
 * User: chou6
 * Time: 오후 2:58
 */
//ini_set('display_errors', 1);
//error_reporting(~0);

$random_hash = substr(md5(uniqid(rand(), true)), 16, 16);
$name = $_POST["username"];
do_job($name, $random_hash);

function do_job($name, $random_hash){
    $servername = "localhost";
    $username = "root";
    $password = "wkfyrnwhtlqka1";
    $conn = new mysqli($servername, $username, $password, "hycube");
    if($conn->connect_error){
        echo "db_connection error";
        return;
    }

    $sql = "SELECT email FROM activation where email = '".$name."'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $sql = "UPDATE activation SET code='.$random_hash.' WHERE email='".$name."'";
        if ($conn->query($sql) !== TRUE) {
            echo "Error: " . $sql . "<br>" . $conn->error;
            return;
        }
    }
    else{
        $sql = "INSERT INTO activation (email, code) VALUES ('".$name."', '".$random_hash."')";
        if ($conn->query($sql) !== TRUE) {
            echo "Error: " . $sql . "<br>" . $conn->error;
            return;
        }
    }
    if(!sendMail($name,$random_hash)){
        echo "메일 전송에 문제가 발생했습니다.";
        return;
    }
    echo "success";
    $conn->close();
}
function sendMail($name, $random_hash){
    /**
     * This example shows settings to use when sending via Google's Gmail servers.
     */
    define('PATH', dirname(__FILE__));

    //SMTP needs accurate times, and the PHP time zone MUST be set
    //This should be done in your php.ini, but this is how to do it if you don't have access to that
    date_default_timezone_set('Asia/Seoul');

    require $_SERVER['DOCUMENT_ROOT']."/lib/PHPMailer-master/PHPMailerAutoload.php";

    //Create a new PHPMailer instance
    $mail = new PHPMailer;
    $mail->SMTPSecure = 'ssl';

    //Tell PHPMailer to use SMTP
    $mail->isSMTP();

    //Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    $mail->SMTPDebug = 0;

    //Ask for HTML-friendly debug output
    $mail->Debugoutput = 'html';

    //Set the hostname of the mail server
    $mail->Host = '	smtp.gmail.com';

    //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
    $mail->Port = 465;

    //Set the encryption system to use - ssl (deprecated) or tls

    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;

    //Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = "hycubewinter@gmail.com";

    //Password to use for SMTP authentication
    $mail->Password = "diamond!";

    //Set who the message is to be sent from
    $mail->setFrom('hycubewinter@gmail.com', 'Hycube');

    //Set an alternative reply-to address
    $mail->addReplyTo('hycubewinter@gmail.com', 'Hycube');

    //Set who the message is to be sent to
    $mail->addAddress($name, 'Customer');

    //Set the subject line
    $mail->Subject = 'Confirm your Hycube account';

    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    $mail->msgHTML("abcd", dirname(__FILE__));

    //mail body
    $mail->Body = '<p>Dear Customer,</p><p><br></p><p>To confirm you e-mail, please go to following link :</p><div align=""><a href="http://13.124.2.154/choi/check_activation.php?code='.$random_hash.'">http://13.124.2.154/choi/check_activation.php?code='.$random_hash.'</a>&nbsp;</div><p><br></p><p>Hycube</p><p>Information Security of Hanyang University&nbsp;</p>';

    //Replace the plain text body with one created manually
    $mail->AltBody = 'This is a plain-text message body';

    //Attach an image file

    //send the message, check for errors
    if (!$mail->send()) {
        return false;
    } else {
        return true;
    }
}


?>