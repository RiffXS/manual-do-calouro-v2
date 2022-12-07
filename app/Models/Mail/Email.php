<?php

namespace App\Models\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email {

    /**
     * Endereço servidor SMTP
     */
    const HOST = 'smtp.gmail.com';

    /**
     * E-mail do projeto
     */
    const USERNAME = 'manualdocalouro.ifes@gmail.com';

    /**
     * Token de acesso
     */
    const PASSWORD = 'srjboszkjzmqbysg';

    /**
     * Tipo de segurança
     */
    const SECURITY = 'tls';

    /**
     * Porta de uso
     */
    const PORT = 587;

    /**
     * Tipo charset
     */
    const CHARSET  = 'UTF-8';

    /**
     * E-mail remetente
     */
    const FROM_EMAIL = 'manualdocalouro.ifes@gmail.com';

    /**
     * Nome remetente
     */
    const FROM_NAME  = 'Manual do Calouro';

    /**
     * Mensagem de erro do envio
     * @var string
     */
    private $error;

    /**
     * Método responsável por retornar a mensagem de erro do envio
     * @return string
     */
    public function getError(): string {
        return $this->error;
    }

    /**
     * Método responsável por enviar o email
     * @param string|array $adresses
     * @param string       $subject
     * @param string       $body
     * @param string|array $atachments
     * @param string|array $css
     * @param string|array $bcss
     * 
     * @return boolean
     */
    public function sendEmail($adresses, $subject, $body, $atachments = [], $css = [], $bcss = []) {
        // LIMPAR A MENSAGEM DE ERRO
        $this->error = '';

        // INSTANCIA DE PHPMailer
        $obMail = new PHPMailer(true);

        try {
            // CREDENCIAIS DE ACESSO AO SMTP
            $obMail->isSMTP();
            $obMail->Host       = self::HOST;
            $obMail->SMTPAuth   = true;
            $obMail->SMTPSecure = self::SECURITY;
            $obMail->Username   = self::USERNAME;
            $obMail->Password   = self::PASSWORD; 
            $obMail->Port       = self::PORT;
            $obMail->CharSet    = self::CHARSET;

            // REMETENTE
            $obMail->setFrom(self::FROM_EMAIL, self::FROM_NAME);

            // DESTINATARIOS
            $adresses = is_array($adresses) ? $adresses : [$adresses];

            foreach ($adresses as $adress) {
                $obMail->addAddress($adress);
            }

            // ANEXOS
            $atachments = is_array($atachments) ? $atachments : [$atachments];

            foreach ($atachments as $atachment) {
                $obMail->addAttachment($atachment);
            }

            // CC
            $css = is_array($css) ? $css : [$css];

            foreach ($css as $cc) {
                $obMail->addCC($cc);
            }

            // BCC
            $bcss = is_array($bcss) ? $bcss : [$bcss];
            
            foreach ($bcss as $bcc) {
                $obMail->addBCC($bcc);
            }

            // CONTEUDO DO EMAIL
            $obMail->isHTML();
            $obMail->Subject = $subject;
            $obMail->Body = $body;

            // ENVIA O EMAIL
            return $obMail->send();

        } catch (Exception $e) {
            $this->error = $e->getMessage();

            return false; 
        }
    }
}