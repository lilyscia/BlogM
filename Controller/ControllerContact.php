<?php

use \Alice\Framework\Controller;
use \Alice\Framework\Configuration;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class ControllerContact extends Controller
{
    private $errorMessage = "";
    private $successMessage = "";
    
    public function index()
    {
        $this->generateView(array('errorMessage' => $this->errorMessage, 'successMessage' => $this->successMessage));
    }
    
    public function sendMessage()
    {
        $name = strip_tags(htmlspecialchars($this->request->getParameter("name")));
        $email = strip_tags(htmlspecialchars($this->request->getParameter("email")));
        $message = strip_tags(htmlspecialchars($this->request->getParameter("message")));
        $submitted = strip_tags(htmlspecialchars($this->request->getParameter("submitted")));
        
        if(isset($submitted))
        {
            if(empty($name) || empty($email) || empty($message) || !filter_var($email,FILTER_VALIDATE_EMAIL))
            {
                $this->errorMessage = 'Un champs n\'a pas été rempli correctement !';
                $this->executeAction("index");
            }
            
            $to = Configuration::get("email");
            $email_subject = "Blog Formulaire de contact:  $name";
            $email_body = "Vous avez reçu un nouveau message à partir du formulaire de contact du blog.\n\n"."Details :\n\nNom: $name\nEmail: $email\nMessage:\n$message";
            //$headers = "From:" . Configuration::get("noreply") . "\n";
            //$headers .= "Reply-To: $email";
            //mail($to,$email_subject,$email_body,$headers);
            //Instantiation and passing `true` enables exceptions
            $mail = new PHPMailer(true);

            try {
                //Server settings
                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'in-v3.mailjet.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = '89aadcb6588d1b1b14763251200477cb';                     //SMTP username
                $mail->Password   = '670cd58d42ab8e4a2fa61cc86697d209';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

                //Recipients
                $mail->setFrom('aliceagnissey@gmail.com', 'Alice Dejean');
                $mail->addAddress('aliceagnissey@gmail.com', 'Alice Dejean');     //Add a recipient
                

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = $email_subject;
                $mail->Body    = $email_body;
               
                $mail->send();
                $this->successMessage = 'Message envoyé avec succès !';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
            
           
        }
        
        // Exécution de l'action par défaut pour réafficher le formulaire de contact
        $this->executeAction("index");
    }
}
