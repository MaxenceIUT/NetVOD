<?php

namespace iutnc\netvod\action\account;

use iutnc\netvod\action\Action;
use iutnc\netvod\users\User;

class ActivateAccountAction extends Action
{

    public function execute(): string
    {
        $email = $_GET['email'];
        $token = $_GET['token'];
        $user = User::find($email);
        if ($user->activated) {
            return "Votre compte est déjà activé";
        } else {
            if ($user->checkActivationToken($token)) {
                $user->activated = true;
                $user->save();
                return "Votre compte a bien été activé";
            } else {
                return "Le lien d'activation est invalide";
            }
        }
    }

    public function getActionName(): string
    {
        return "activate-account";
    }

    public function shouldBeAuthenticated(): bool
    {
        return false;
    }

}