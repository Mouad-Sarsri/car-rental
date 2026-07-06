<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Agency;
use App\Models\Car;
use App\Models\Reservation;

class ContactController extends Controller
{
    public function index(): void
    {
        $agencyModel = new Agency();
        $agencies    = $agencyModel->allActive();

        $this->render('contact/index', [
            'agencies' => $agencies,
            'authUser' => $this->authUser(),
        ]);
    }

    public function send(): void
    {
        // Anti-spam : honeypot
        if (!empty($_POST['website'])) {
            $this->redirect('/contact');
            return;
        }

        $agencyModel = new Agency();
        $agencies    = $agencyModel->allActive();

        $data = [
            'prenom'    => trim($this->post('prenom', '')),
            'nom'       => trim($this->post('nom', '')),
            'email'     => trim($this->post('email', '')),
            'telephone' => trim($this->post('telephone', '')),
            'sujet'     => $this->post('sujet', ''),
            'message'   => trim($this->post('message', '')),
        ];

        $errors = $this->validate($data, [
            'prenom'  => 'required|max:100',
            'nom'     => 'required|max:100',
            'email'   => 'required|email',
            'sujet'   => 'required',
            'message' => 'required|min:20|max:1000',
        ]);

        if (!empty($errors)) {
            $this->render('contact/index', [
                'errors'   => $errors,
                'old'      => $data,
                'agencies' => $agencies,
                'authUser' => $this->authUser(),
            ]);
            return;
        }

        // Envoi email 
        $to      = 'contact@carrental.ma';
        $subject = '[CarRental Contact] ' . $this->sanitize($data['sujet']) . ' — ' . $data['prenom'] . ' ' . $data['nom'];
        $body    = "Nom : {$data['prenom']} {$data['nom']}\n"
                 . "Email : {$data['email']}\n"
                 . "Téléphone : {$data['telephone']}\n"
                 . "Sujet : {$data['sujet']}\n\n"
                 . "Message :\n{$data['message']}";

        $headers = "From: {$data['email']}\r\nReply-To: {$data['email']}\r\nX-Mailer: CarRental";

        // mail($to, $subject, $body, $headers); 

        // Journaliser le message (alternative à l'email)
        error_log("[CONTACT] {$data['email']} — {$data['sujet']}: " . substr($data['message'], 0, 80));

        $this->render('contact/index', [
            'success'  => true,
            'agencies' => $agencies,
            'authUser' => $this->authUser(),
        ]);
    }
}
