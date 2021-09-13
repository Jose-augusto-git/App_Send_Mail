<?php 
    // print_r($_POST);
    //importação da biblioteca PHPMailer
    require "./bibliotecas/PHPMailer/Exception.php";
    require "./bibliotecas/PHPMailer/OAuth.php";  
    require "./bibliotecas/PHPMailer/POP3.php";
    require "./bibliotecas/PHPMailer/SMTP.php";
    require "./bibliotecas/PHPMailer/PHPMailer.php";


    //------------------------------------------------//

    //Configuração do namespace para o envio de email
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    //--------------------------------------//

    class Mensagem {

        //atributos privados
        private $para = null;
        private $assunto = null;
        private $mensagem = null;
        //---------------//


        //metodos publicos
        //metodos magicos get e set
        public function __get($atributo){
            return $this->$atributo;
        }

        public function __set($atributo, $valor){
            $this-> $atributo = $valor;
        }

        public function mensagemValida(){
            //valida as mensagem
            if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)){
                return false;
            }
            return true;

        }
        //-------------------------------//

    }

    //instancia do objeto
    $mensagem = new Mensagem();

    $mensagem-> __set('para', $_POST['para']);
    $mensagem-> __set('assunto' , $_POST['assunto']);
    $mensagem-> __set('mensagem', $_POST['mensagem']);
    // print_r($mensagem);

    //----------------------//
   

   //recuperando a instancia validando a mensagem

   if(!$mensagem->mensagemValida()){
       echo 'Mensagem não e válida';
       //mata o processamento do script no ponto que ela e lida
       die();
   }
   //--------------------------------------------

   $mail = new PHPMailer(true);

   try {
       //Server settings
       $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
       $mail->isSMTP();                                            //Send using SMTP
       $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
       $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
       $mail->Username   = 'vajeke7950@ppp998.com';                     //SMTP username
       $mail->Password   = 'test@teste123';                               //SMTP password
       $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
       $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
   
       //Recipients
       $mail->setFrom('vajeke7950@ppp998.com', 'Mensagem de remetente');
       $mail->addAddress('vajeke7950@ppp998.com', 'Mensagem de destinatario');     //Add a recipient
      // $mail->addAddress('ellen@example.com');               //Name is optional
     //  $mail->addReplyTo('info@example.com', 'Information');
      // $mail->addCC('cc@example.com');
      // $mail->addBCC('bcc@example.com');
   
       //Attachments
      // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
      // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
   
       //Content
       $mail->isHTML(true);                                  //Set email format to HTML
       $mail->Subject = 'Oi assunto';
       $mail->Body    = 'Oi conteúdo <strong>e-mail</strong>';
       $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
   
       $mail->send();
       echo 'Message has been sent';
   } catch (Exception $e) {
       echo "Não foi possivel enviar este e-mail! Por favor tente novamente mais tarde:";
       echo 'Detalhes do erro:'. $mail->ErrorInfo;

   }