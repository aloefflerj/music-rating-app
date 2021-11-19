<?php

namespace MusicRating\Models;

use MusicRating\Models\Helpers\DBConnection;

class SongsModel extends BaseModel
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
        $query = $this->pdo->prepare('SELECT * FROM songs');

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
        $query = $this->pdo->prepare('SELECT * FROM songs WHERE id = :id');

        try {
            $query->execute(['id' => $id]);
        } catch (\Exception $e) {
            $this->error = $e;
        }

        $user = $query->fetch();

        if (!$user) {
            $this->error = new \Exception("A música que você procura não existe");
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
    public function new(?string $title, ?int $song_order): ?array
    {
        $validatedId = $this->validateId($song_order);
        if (!$validatedId) {
            return null;
        }

        $validatedTitle = $this->validateTitle($title);
        if (!$validatedTitle) {
            return null;
        }

        $validatedSongOrder = $this->validateSongOrder($song_order);
        if (!$validatedSongOrder) {
            return null;
        }

        $params = [
            'title' => $title,
            'song_order' => $song_order
        ];

        $query = $this->pdo->prepare(
            "INSERT INTO songs (title, song_order) VALUES (:title, :song_order)"
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

        if (empty($body)) {
            $this->error = new \Exception('Insira um valor para alterar');
            return null;
        }


        $users = $this->getAll();
        $user = $this->get($id);

        if (!in_array($user, $users)) {
            $this->error = new \Exception('Este usuário não existe');
            return null;
        };

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

        $sql = "UPDATE users SET {$sqlSet} WHERE id = :id";

        $sql = $this->pdo->prepare($sql);

        try {
            $sql->execute($bodyArr);
        } catch (\Exception $e) {
            $this->error = $e;
        }

        return $this->get($id);
    }

    /*
     * =============
     * || HELPERS || =============================================>
     * =============
     */

    private function validateTitle($title)
    {
        if (empty($title)) {
            $this->error = new \Exception('Insira um título para a música');
            return false;
        }

        $songs = $this->getAll();

        foreach ($songs as $song) {
            if ($song->title === $title) {
                $this->error = new \Exception('Esta música já foi cadastrada');
                return false;
            }
        }

        return true;
    }

    private function validateSongOrder($song_order)
    {
        if (empty($song_order)) {
            $this->error = new \Exception('Insira número da música no álbum');
            return false;
        }

        // Fazer a validação de album aqui

        return true;
    }

    public function error()
    {
        return $this->error ?? false;
    }
}
