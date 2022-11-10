<?php

namespace iutnc\netvod\action\account;

use iutnc\netvod\action\Action;
use iutnc\netvod\auth\Auth;
use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\users\User;

class ResetPassword extends Action
{

    public function getActionName(): string
    {
        return "reset-password";
    }

    public function shouldBeAuthenticated(): bool
    {
        return false;
    }

    private function deleteToken(?User $user)
    {
        $pdo = ConnectionFactory::getConnection();
        $sql = "delete from lost_password_tokens where email = :email";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(":email", $user->email);
        $statement->execute();
    }

    public function execute(): string
    {
        //Verification token
        //Verfiy token
        $user = User::find($_GET['user']);
        $userData = $this->getToken($user);

        if ($this->http_method == "GET") {
            $token = $_GET['token'];
            if ($userData['token'] == $token) {
                if (date('Y-m-d H:i:s', time()) < $userData['token_expiration']) {
                    //Token valide
                    $html = "<form action='index.php?action=reset-password&user=$user->email' method='post'>
                <label for='password'>Nouveau mot de passe</label>
                <input type='password' name='password' id='password'>
                <label for='password2'>Confirmer le mot de passe</label>
                <input type='password' name='password2' id='password2'>
                <input type='submit' value='Valider'>
                </form>";
                } else {
                    //Token expiré
                    $html = "Le lien de réinitialisation a expiré";
                    //Supprimer le token
                    ResetPassword::supprToken($user);
                    $html .= "<a href='index.php?action=password-forget'>Réinitialiser le mot de passe</a>";
                }
            } else {
                $html = "Le token n'est pas valide";
            }
            return $html;

        } else if ($this->http_method == "POST") {
            $password = $_POST['password'];
            $password2 = $_POST['password2'];
            if ($password == $password2) {
                Auth::changePassword($user->email, $password);
                $html = "Mot de passe modifié";
                $html .= "<a href='index.php?action=login'>Se connecter</a>";
            } else {
                $html = "Les mots de passe ne correspondent pas";
                $html .= "<a href='index.php?action=password-forget'>Réinitialiser le mot de passe</a>";
            }
            return $html;
        }


        return '';
    }

    public function getToken(User $user): array
    {
        $pdo = ConnectionFactory::getConnection();
        $sql = "select * from lost_password_tokens where email = :email";
        $statement = $pdo->prepare($sql);
        $email = $user->email;
        $statement->bindParam(":email", $email);
        $statement->execute();
        if ($statement->rowCount() == 0) {
            return [];
        }
        return $statement->fetch();

    }

    public static function supprToken(User $user)
    {
        $pdo = ConnectionFactory::getConnection();
        $sql = "delete from lost_password_tokens where email = :email";
        $statement = $pdo->prepare($sql);
        $email = $user->email;
        $statement->bindParam(":email", $email);
        $statement->execute();
    }
}