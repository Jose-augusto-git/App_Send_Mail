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

        // Atributo publico
        public $status = array('codigo_status' => null, 'descricao_status'=>'');
        
        //----------------//

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
      // die();

      //redirecionando o processa_envio.php para o index

      header('Location: index.php');
   }
   //--------------------------------------------

   $mail = new PHPMailer(true);

   try {
       //Server settings
       $mail->SMTPDebug = false;                      //Enable verbose debug output
       $mail->isSMTP();                                            //Send using SMTP
       $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
       $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
       $mail->Username   = 'vajeke7950@ppp998.com';                     //SMTP username
       $mail->Password   = '$PH0G$xZImfa';                               //SMTP password
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
       $mail->Subject = $mensagem->__get('assunto');
       $mail->Body    = $mensagem->__get('mensagem');
       $mail->AltBody = 'É necessario utilizar um client que suporte HTML para ter acesso total ao conteúdo dessa mensagem';
   
       $mail->send();

       // Informa o usuario caso tenha sucesso na mensagem
       $mensagem->status['codigo_status'] = 1;
       $mensagem->status['descricao_status'] = 'E-mail enviado com sucesso';

       // Informa o usuario caso tenha erro na mensagem
   } catch (Exception $e) {
        $mensagem->status['codigo_status'] = 2;
        $mensagem->status['descricao_status'] = 'Não foi possivel enviar este e-mail! Por favor tente novamente mais tarde: Detalhes do erro: '. $mail->ErrorInfo;

        //logicia para armazenar os erros para o programador poder analisar


   }

   ?>

   <!DOCTYPE html>
   <html lang="pt-br">
   <head>
		<meta charset="utf-8" />
    	<title>App Mail Send</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>
   <body>
       <div class="container">
            <div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="img/logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>

            <div class="row">
                <div class="col-md-12">
                    <? if($mensagem->status['codigo_status']==1){?>

                        <div class="container">
                            <h1 class="display-4 text-success">Sucesso</h1>
                            <p><?= $mensagem->status['descricao_status'] ?></p>
                            <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                        </div>
                        
                    <? } ?>
                    <? if($mensagem->status['codigo_status']==2){?>

                        <div class="container">
                            <h1 class="display-4 text-danger">Ops!Erro</h1>
                            <p><?= $mensagem->status['descricao_status'] ?></p>
                            <a href="index.php" class="btn btn-danger btn-lg mt-5 text-white">Voltar</a>
                        </div>

                    <? } ?>

                </div>
            </div>
       </div>
   </body>
   </html>