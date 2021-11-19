<?php

namespace MusicRating\Models;

use MusicRating\Models\Helpers\DBConnection;

class UsersModel
{
    use DBConnection;

    private $pdo;

    private $error;

    public function __construct()
    {
        $this->pdo = $this->conn('music_rating_app', 'music_rating_db', 'root', '123#@!');
    }

    /**
     * Retorna todos os usuários do banco
     *
     * @return stdClass[] | null
     */
    public function getAll(): ?array
    {
        $query = $this->pdo->prepare('SELECT * FROM users');

        try {
            $query->execute();
        } catch (\Exception $e) {
            $this->error = $e;
        }

        $users = $query->fetchAll();

        return $users;
    }

    public function get(int $id)
    {
        $query = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');

        try {
            $query->execute(['id' => $id]);
        } catch (\Exception $e) {
            $this->error = $e;
        }

        $user = $query->fetch();

        if(!$user) {
            $this->error = new \Exception("O usuário procurado não existe");
        }

        return $user;
    }

    /**
     * Cria um novo usuário
     *
     * @param string|null $username
     * @param string|null $mail
     * @param string|null $passwd
     * @param string|null $user_type
     * @return stdClass[]|null
     */
    public function new(?string $username, ?string $mail, ?string $passwd, ?string $user_type): ?array
    {
        $validatedPasswd = $this->validatePasswd($passwd);
        if (!$validatedPasswd) {
            return null;
        }

        $validatedUsername = $this->validateUsername($username);
        if (!$validatedUsername) {
            return null;
        }

        $validatedMail = $this->validateMail($mail);
        if (!$validatedMail) {
            return null;
        }


        $params = [
            'username' => $username,
            'mail' => $mail,
            'passwd' => $passwd,
            'user_type' => $user_type ?? 'regular'
        ];

        $query = $this->pdo->prepare(
            "INSERT INTO users (username, mail, passwd, user_type) VALUES (:username, :mail, :passwd, :user_type)"
        );

        try {
            $query->execute($params);
        } catch (\Exception $e) {
            $this->error = $e;
        }


        return $this->getAll();
    }

    public function delete($id)
    {
        $validatedId = $this->validateId($id);
        if (!$validatedId) {
            return null;
        }

        $users = $this->getAll();

        // user not found ------->
        $found = false;
        foreach ($users as $user) {
            if ($user->id === (int)$id) {
                $found = true;
            }
        }

        if (!$found) {
            $this->error = new \Exception('Este usuário não existe');
            return null;
        }

        $query = $this->pdo->prepare(
            "DELETE FROM users WHERE id = :id"
        );

        try {
            $query->execute(['id' => $id]);
        } catch (\Exception $e) {
            $this->error = $e;
        }

        return $this->getAll();
    }

    public function update($id, \stdClass $body)
    {
        $id = (int)$id;

        $validatedId = $this->validateId($id);
        if (!$validatedId) {
            return null;
        }

        if(empty($body)) {
            $this->error = new \Exception('Insira um valor para alterar');
            return null;
        }


        $users = $this->getAll();
        $user = $this->get($id);
        
        if (!in_array($user, $users)) {
            $this->error = new \Exception('Este usuário não existe');
            return null;
        };
        
        if (isset($body->username)) {
            $validatedUsername = $this->validateUsername($body->username, $user);
            if (!$validatedUsername) {
                return null;
            }
        }

        if (isset($body->mail)) {
            $validatedMail = $this->validateMail($body->mail, $user);
            if (!$validatedMail) {
                return null;
            }
        }

        if (isset($body->passwd)) {
            $validatedPasswd = $this->validatePasswd($body->passwd);
            if (!$validatedPasswd) {
                return null;
            }
        }

        if (isset($body->user_type)) {
            $validatedUserType = $this->validateUserType($body->user_type);
            if (!$validatedUserType) {
                return null;
            }
        }

        $bodyVars = get_object_vars($body);
        $bodyVarKeys = array_keys($bodyVars);
        $bodyVarValues = array_values($bodyVars);
        
        $sqlSet = '';

        foreach($bodyVarKeys as $bodyVarKey) {
            $sqlSet .= "{$bodyVarKey} = :{$bodyVarKey}, ";
        }
        $sqlSet = rtrim($sqlSet, ", ");

        $bodyArr = array_combine($bodyVarKeys, $bodyVarValues);
        $bodyArr['id'] = $id;

        $sql = "UPDATE users SET {$sqlSet} WHERE id = :id";

        // var_dump($sql);
        // die();
        
        $sql = $this->pdo->prepare($sql);

        try {
            $sql->execute($bodyArr);
        } catch(\Exception $e) {
            $this->error = $e;
        }

        return $this->get($id);
    }

    /*
     * =======================
     * || FIELD VALIDATIONS || =====================================>
     * =======================
     */

    // id
    private function validateId(&$id)
    {
        if (empty($id) || !filter_var($id, FILTER_VALIDATE_INT)) {
            $this->error = new \Exception('Insira uma id válido');
            return false;
        }

        return true;
    }

    // password ---------------------------------------------->
    private function validatePasswd(?string &$passwd)
    {
        if (empty($passwd)) {
            $this->error = new \Exception('Insira uma senha');
            return false;
        }

        if (!preg_match('/^(?=(.*[a-z]){3,})(?=(.*[A-Z]){2,})(?=(.*[0-9]){2,})(?=(.*[!@#$%^&*()\-__+.]){1,}).{8,}$/', $passwd)) {
            $this->error = new \Exception('A senha não atende aos requisitos mínimos');
            return false;
        }

        $passwd = password_hash($passwd, PASSWORD_DEFAULT);

        return true;
    }

    // username
    private function validateUsername(?string &$username, $currentUser = null)
    {

        if (empty($username)) {
            $this->error = new \Exception('Insira um nome de usuário');
            return false;
        }

        $users = $this->getAll();

        foreach ($users as $user) {
            if ($user->username === $username) {
                if($currentUser && $currentUser->username === $username) {
                    break;
                }
                $this->error = new \Exception('Este nome de usuário já está sendo usado');
                return false;
            }
        }

        return true;
    }

    // mail
    private function validateMail(?string &$mail, $currentUser = null)
    {

        if (empty($mail)) {
            $this->error = new \Exception('Insira um email');
            return false;
        }

        $mail = filter_var($mail, FILTER_VALIDATE_EMAIL);

        if (!$mail) {
            $this->error = new \Exception('Insira um email válido');
            return false;
        }

        $users = $this->getAll();

        foreach ($users as $user) {
            if ($user->mail === $mail) {
                if($currentUser && $currentUser->mail === $mail) {
                    break;
                }
                $this->error = new \Exception('Este email já está cadastrado em nosso sitema');
                return false;
            }
        }

        return true;
    }

    private function validateUserType(?string &$user_type)
    {
        $user_type = filter_var($user_type, FILTER_SANITIZE_STRIPPED);

        if ($user_type !== 'regular' &&  $user_type !== 'adm') {
            $this->error = new \Exception('Essa permissão de usuário não existe');
            return false;
        }

        return true;
    }

    /*
     * =============
     * || HELPERS || =============================================>
     * =============
     */
    public function error()
    {
        return $this->error ?? false;
    }
}
