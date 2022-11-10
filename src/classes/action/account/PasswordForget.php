<?php

namespace iutnc\netvod\action\account;

use iutnc\netvod\action\Action;
use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\users\User;

class PasswordForget extends Action
{

    public function execute(): string
    {
        $html = "Service de renouvellement de mot de passe";
        if ($this->http_method == "GET") {
            $html = <<<END
            <form action="index.php?action=password-forget" method="post">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
                <input type="submit" value="Envoyer">
            </form>
            END;

        } else if ($this->http_method == "POST") {
            $email = $_POST['email'];
            $user = User::findByEmail($email);
            if (is_null($user)) {
                $html .= "Utilisateur inconnu";
            } else {
                $token = bin2hex(random_bytes(16));
                $tokenEpiration = date('Y-m-d H:i:s', time() + 3 * 60);
                try {
                    ResetPassword::supprToken($user);
                } catch (\Exception $e) {
                }
                $sql = "insert into lost_password_tokens(email, token, token_expiration) values (?, ?, ?)";
                $pdo = ConnectionFactory::getConnection();
                $statement = $pdo->prepare($sql);

                $email = filter_var($email, FILTER_SANITIZE_EMAIL);
                $statement->bindParam(1, $email);
                $statement->bindParam(2, $token);
                $statement->bindParam(3, $tokenEpiration);

                $statement->execute();
                $html .= "Token généré";
                $html .= "<a href=\"index.php?action=reset-password&token=$token&user=$user->email\">Lien de réinitialisation</a>";
            }
        }
        return $html;
    }

    public
    function getActionName(): string
    {
        return "password-forget";
    }

    public
    function shouldBeAuthenticated(): bool
    {
        return false;
    }
}