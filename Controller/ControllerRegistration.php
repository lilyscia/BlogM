<?php

use Alice\Framework\Configuration;
use Alice\Framework\Controller;
use Blog\Model\Manager\UsersManager;
use Blog\Model\Entity\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class ControllerRegistration extends Controller
{
    private $usersManager;
    
    public function __construct()
    {
        $this->usersManager = new UsersManager();
    }
    
    public function index()
    {
        $this->generateView();
    }
    
    public function register()
    {
        if ($this->request->existsParameter("username") 
            && $this->request->existsParameter("email") 
            && $this->request->existsParameter("password") 
            && $this->request->existsParameter("confirmPassword"))
        {
            $username = $this->request->getParameter("username");
            $email = $this->request->getParameter("email");
            $password = $this->request->getParameter("password");
            $confirmPassword = $this->request->getParameter("confirmPassword");
            
            if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                throw new Exception("L'email est invalide !");
            }
            
            if (strlen($password) < 6) {
                throw new Exception("Mot de passe trop court ! Minimum 6 caractères");
            }
            
            if($password === $confirmPassword)
            {
                $password = password_hash($password, PASSWORD_BCRYPT);
                $validationKey = md5(microtime(TRUE)*100000);
                
                $user = new User(array(
                    'username' => $username, 
                    'email' => $email, 
                    'password' => $password, 
                    'activated' => 0, 
                    'validationKey' => $validationKey, 
                    'userType' => 1, 
                    'dateCreation' => date('Y-m-d H:i:s')
                ));
                
                $this->usersManager->add($user);
                
                // Envoi d'un mail avec la clé de validation
                $to = $email;
                $email_subject = "Activer votre comptre du blog de Alice Dejean";
                // Le lien devra être adapté en fonction du nom de domaine.
                //http://localhost/P5-Blog/index.php?controller=registration&action=validation&username='.urlencode($username).'&key='.urlencode($validationKey)
                $email_body = 'Bienvenue sur le blog de Alice Dejean,
                    
                               Pour activer votre compte, veuillez cliquer sur le lien ci dessous
                               ou copier/coller dans votre navigateur internet.
                               
                               '.Configuration::get("domain").'registration/validation/' . urlencode($username) . '/' . urlencode($validationKey).'
                
                                ---------------
                                Ceci est un mail automatique, Merci de ne pas y répondre.';
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
                                    $mail->addAddress($to);     //Add a recipient
                                    
                    
                                    //Content
                                    $mail->isHTML(true);                                  //Set email format to HTML
                                    $mail->Subject = $email_subject;
                                    $mail->Body    = $email_body;
                                   
                                    $mail->send();
                                    $this->successMessage = 'Message envoyé avec succès !';
                                } catch (Exception $e) {
                                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                                }
                
                $this->redirect("Login");
            }
            else 
            {
                throw new Exception("Le mot de passe de confirmation ne correspond pas.");
            }
        }
    }
    
    // Lorsque l'utilisateur clique sur le lien du mail, valide son compte.
    public function validation()
    {
        if($this->request->existsParameter("username") && $this->request->existsParameter("key"))
        {
            $username = $this->request->getParameter("username");
            $key = $this->request->getParameter("key");
            $user = $this->usersManager->getByUsername($username);
            // Si la clef de validation de la BDD est la même que celle du mail
            if($user->getValidationKey() === $key)
            {
                $user->setActivated(1);
                $this->usersManager->update($user);
                $this->generateView();
            }
            else
            {
                throw new Exception("Clé de validation incorrect !");
            }
        }
    }
}
