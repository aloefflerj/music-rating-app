<?php

namespace MusicRating\Models;

use MusicRating\Models\Helpers\DBConnection;
use MusicRating\Models\Helpers\QueryHelper;
use MusicRating\Models\Helpers\UsersConnConfig;

class UsersModel
{
    use DBConnection;
    use UsersConnConfig;
    use QueryHelper;

    private $pdo;

    private $error;

    private $userConn;

    public function __construct()
    {
        $this->userConn = $this->getUserConn();
        $this->pdo = $this->conn($this->userConn);
    }

    /**
     * Retorna todos os usuários do banco
     *
     * @return stdClass[] | null
     */
    public function getAll(): ?array
    {
        //'SELECT id, username, mail, user_type FROM web_users'
        $select = $this->select(
            'id, username, mail, user_type',
            'users',
        );

        try {
            $query = $this->pdo->prepare($select);
            $query->execute();
        } catch (\PDOException $e) {
            $this->error = $e;
            return null;
        }

        $users = $query->fetchAll();

        return $users;
    }

    public function get(int $id)
    {
        //'SELECT * FROM users WHERE id = :id'
        $select = $this->select('id, username, mail, user_type', 'users', 'id = :id');

        if($this->userConn->user === 'adm') {
            $select = $this->select(
                'id, username, mail, user_type', 
                $this->userConn->tablesPrefix . 'users', 
                'id = :id'
            );
        } 

        try {
            $query = $this->pdo->prepare($select);
            $query->execute(['id' => $id]);
        } catch (\PDOException $e) {
            $this->error = $e;
            return null;
        }

        $user = $query->fetch();

        if (!$user) {
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

        if(isset($user_type)) {
            $validatedUserType = $this->validateUserType($user_type);
            if(!$validatedUserType) {
                return null;
            }
        }

        $params = [
            'username' => $username,
            'mail' => $mail,
            'passwd' => $passwd,
            'user_type' => $user_type ?? 'app'
        ];


        try {
            $query = $this->pdo->prepare(
                "INSERT INTO users (username, mail, passwd, user_type) VALUES (:username, :mail, :passwd, :user_type)"
            );
            $query->execute($params);
        } catch (\Exception $e) {
            $this->error = $e;
            return null;
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
        
        if(!$users) {
            return null;
        }

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
            "CALL DeleteUser(:id)"
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

        if (empty($body)) {
            $this->error = new \Exception('Insira um valor para alterar');
            return null;
        }

        // Grant temporary root access ------------------>
        $this->userConn = $this->getUserConn(true);
        $this->pdo = $this->conn($this->userConn);

        $users = $this->getAll();
        $user = $this->get($id);
        if(isset($_SESSION['user']) && ($_SESSION['user']->user_type === 'app')) {
            if($user != $_SESSION['user']) {
                $this->error = new \Exception('Sem permissão para alterar o usuário');
                return null;
            }
        }

        if(!$user || !$users) {
            return null;
        }

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

        $this->userConn = $this->getUserConn();
        $this->pdo = $this->conn($this->userConn);
        // Revoke temporary root access ----------------------------------------------->

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

        if($_SESSION['user']->user_type === 'app') {
            $body->user_type = 'app';
        }

        $bodyVars = get_object_vars($body);
        $bodyVarKeys = array_keys($bodyVars);
        $bodyVarValues = array_values($bodyVars);

        $sqlSet = '';

        foreach ($bodyVarKeys as $bodyVarKey) {
            $sqlSet .= "{$bodyVarKey} = :{$bodyVarKey}, ";
        }
        $sqlSet = rtrim($sqlSet, ", ");

        $bodyArr = array_combine($bodyVarKeys, $bodyVarValues);
        $bodyArr['id'] = $id;

        $sql = "UPDATE {$this->userConn->tablesPrefix}users SET {$sqlSet} WHERE id = :id";

        try {
            $sql = $this->pdo->prepare($sql);
            $sql->execute($bodyArr);
        } catch (\Exception $e) {
            $this->error = $e;
            return null;
        }

        if($_SESSION['user']->user_type === 'app') {

            $select = $this->select(
                'id, username, mail, user_type', 
                $this->userConn->tablesPrefix . 'users', 
                'id = :id'
            );
            try{
                $query = $this->pdo->prepare($select);
                $query->execute(['id' => $id]);
            }catch (\PDOException $e) {
                $this->error = $e;
                return null;
            }

            $user = $query->fetch();

            if($user) {
                $_SESSION['user'] = $user;
                return $user;
            }
         }
        return $this->get($id);
    }

    public function register(?string $username, ?string $mail, ?string $passwd, ?string $passwdConfirm, ?string $user_type)
    {
        if (empty($passwdConfirm)) {
            $this->error = new \Exception("Insira um valor de confirmação de senha");
            return null;
        }

        if ($passwd != $passwdConfirm) {
            $this->error = new \Exception("A senha e a confirmação devem ser iguais");
            return null;
        }

        // Grant temporary root access ------------------>
        $this->userConn = $this->getUserConn(true);
        $this->pdo = $this->conn($this->userConn);

        $users = $this->new($username, $mail, $passwd, null);

        $this->userConn = $this->getUserConn();
        $this->pdo = $this->conn($this->userConn);
        //----------------------------------------------->

        if(!$users) {
            return null;
        }

        // 'SELECT id, username, mail FROM users WHERE username = :username'
        $select = $this->select(
            'id, username, mail', 
            $this->userConn->tablesPrefix . 'users', 
            'username = :username'
        );

        try {
            $query = $this->pdo->prepare($select);
            $query->execute(['username' => $username]);
        } catch (\Exception $e) {
            $this->error = $e;
            return null;
        }

        $user = $query->fetch();

        return $user;
    }

    public function login(?string $username, ?string $passwd)
    {

        if (empty($username)) {
            $this->error = new \Exception('Insira um nome de usuário');
            return null;
        }

        $username = filter_var($username, FILTER_SANITIZE_STRIPPED);
        if (!$username) {
            $this->error = new \Exception('Ocorreu um erro inseperado');
            return null;
        }

        if (empty($passwd)) {
            $this->error = new \Exception('Insira sua senha');
            return null;
        }

        $passwd = filter_var($passwd, FILTER_SANITIZE_STRIPPED);
        if (!$passwd) {
            $this->error = new \Exception('Ocorreu um erro inseperado');
            return null;
        }

        // 'SELECT id, username, mail, passwd, user_type FROM users WHERE username = :username'
        $select = $this->select('id, username, mail, passwd, user_type', $this->userConn->tablesPrefix . 'users', 'username = :username');
        $query = $this->pdo->prepare($select);

        try {
            $query->execute(['username' => $username]);
        } catch (\Exception $e) {
            $this->error = $e;
            return null;
        }

        $user = $query->fetch();

        if (!$user || !password_verify($passwd, $user->passwd)) {
            $this->error = new \Exception("Usuário ou senha inválidos");
            return null;
        }

        unset($user->passwd);

        $_SESSION['user'] = $user;

        return $user;

        // check correct passwd
    }

    public function logged()
    {
        return isset($_SESSION['user']) ? $_SESSION['user'] : false;
    }

    public function logout()
    {
        if (!isset($_SESSION['user'])) {
            $this->error = new \Exception('Não existe nenhum usuário logado');
            return null;
        }

        unset($_SESSION['user']);
        return true;
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
        $username = filter_var($username, FILTER_DEFAULT);

        if (empty($username)) {
            $this->error = new \Exception('Insira um nome de usuário');
            return false;
        }

        try {
            $users = $this->getAll();
        } catch (\PDOException $e) {
            $this->error = $e;
            return false;
        }

        if ($users) {
            foreach ($users as $user) {
                if ($user->username === $username) {
                    if ($currentUser && $currentUser->username === $username) {
                        break;
                    }
                    $this->error = new \Exception('Este nome de usuário já está sendo usado');
                    return false;
                }
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

        try {
            $users = $this->getAll();
        } catch (\PDOException $e) {
            $this->error = $e;
            return false;
        }

        if ($users) {
            foreach ($users as $user) {
                if ($user->mail === $mail) {
                    if ($currentUser && $currentUser->mail === $mail) {
                        break;
                    }
                    $this->error = new \Exception('Este email já está cadastrado em nosso sitema');
                    return false;
                }
            }
        }

        return true;
    }

    private function validateUserType(?string &$user_type)
    {
        $user_type = filter_var($user_type, FILTER_SANITIZE_STRIPPED);

        $user_type = mb_strtolower($user_type);

        if ($user_type !== 'web' &&  $user_type !== 'app' &&  $user_type !== 'adm') {
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
