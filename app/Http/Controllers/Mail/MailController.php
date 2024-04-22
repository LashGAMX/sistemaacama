<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;
use App\Models\ContactoCliente;
use App\Models\Solicitud;
use App\Models\SolicitudPuntos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';


class MailController extends Controller
{
    
    public function idSol(){
        return  $id = 256;
    }

    public function mail(){
        //Get viewSolicitudes 
        $solicitud = DB::table('viewsolicitud2')->where('Id_solicitud', $this->idSol())->first(); 
        $contacto = ContactoCliente::where('id_contacto', $solicitud->Id_contacto)->first();
        $punto = SolicitudPuntos::where('Id_solicitud', $solicitud->Id_solicitud)->first();
        $sol = Solicitud::where('id_solicitud', $solicitud->Id_solicitud)->first();


        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'mail.acama.com.mx';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'test2@acama.com.mx';                     //SMTP username
            $mail->Password   = '2880fmypbsy7';                               //SMTP password
            $mail->SMTPSecure = 'ssl';            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
            //Recipients
            $mail->setFrom('test2@acama.com.mx', 'Acama Laboratorio');
            $mail->addAddress('javierabundis88@gmail.com', $solicitud->Nombres);
            //$mail->addAddress('isaacyannis@gmail.com', $solicitud->Nombres);  
            //Add a recipient
            // $mail->addAddress('ellen@example.com');               //Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('isaacyannis@gmail.com');
            // $mail->addBCC('bcc@example.com');
        
            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
        
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Este es un correo de prueba';
            //$mail->Body    = 'Correo de prueba, no responder <b>in bold!</b>';
            $mail->Body    = view('emails.confirmacion', compact('solicitud','contacto', 'punto','sol'));
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        
            $mail->send();
            echo 'Correo enviado';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    }
    public function viewmail() {
        $solicitud = DB::table('viewsolicitud2')->where('Id_solicitud', $this->idSol())->first(); 
        $contacto = ContactoCliente::where('id_contacto', $solicitud->Id_contacto)->first();
        $punto = SolicitudPuntos::where('Id_solicitud', $solicitud->Id_solicitud)->first();
        $sol = Solicitud::where('id_solicitud', $solicitud->Id_solicitud)->first();

        return view('emails.confirmacion', compact('solicitud', 'contacto', 'punto', 'sol'));
    }
}