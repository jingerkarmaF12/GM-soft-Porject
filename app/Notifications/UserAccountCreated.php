<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserAccountCreated extends Notification
{
    use Queueable;

    public function __construct(
        public string $temporaryPassword
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Création de votre compte')
            ->greeting('Bonjour ' . $notifiable->prenom . ',')
            ->line('Votre compte a été créé avec succès.')
            ->line('Voici vos informations de connexion :')
            ->line('Email : ' . $notifiable->email)
            ->line('Mot de passe temporaire : ' . $this->temporaryPassword)
            ->action('Se connecter', url('/login'))
            ->line('Pour des raisons de sécurité, veuillez modifier votre mot de passe après votre première connexion.');
    }
}