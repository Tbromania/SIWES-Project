
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

function sendTicketEmail($recipient, $ticketID, $topic, $message)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'emmanuelolulana1@gmail.com';
        $mail->Password   = 'hueezquwryhbehjp';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('emmanuelolulana1@gmail.com', 'IT Support Portal');
        $mail->addAddress($recipient);

        $mail->isHTML(true);
        $mail->Subject = "Your Support Ticket #$ticketID";

        $mail->Body = "
            <h2>Ticket Submitted Successfully</h2>
            <p>Your support request has been received.</p>
            <p><strong>Ticket ID:</strong> $ticketID</p>
            <p><strong>Topic:</strong> $topic</p>
            <p><strong>Message:</strong> $message</p>
            <p>Keep your ticket ID safe for future reference.</p>
        ";

        $mail->send();
        return true;

    } catch (Exception $e) {
        die("Email error: " . $mail->ErrorInfo);
    }
}


function sendStatusUpdateEmail($recipient, $ticketID, $newStatus)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'emmanuelolulana1@gmail.com';
        $mail->Password   = 'hueezquwryhbehjp';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('emmanuelolulana1@gmail.com', 'IT Support Portal');
        $mail->addAddress($recipient);

        $mail->isHTML(true);
        $mail->Subject = "Ticket #$ticketID Status Updated";

        $mail->Body = "
            <h2>Your Ticket Has Been Updated</h2>
            <p>Your support ticket has a new status.</p>
            <p><strong>Ticket ID:</strong> $ticketID</p>
            <p><strong>New Status:</strong> $newStatus</p>
            <p>You can log in to the portal to view your ticket.</p>
        ";

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log($mail->ErrorInfo);
        return false;
    }
}

