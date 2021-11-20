<?php

namespace MusicRating\Models;

use MusicRating\Models\Helpers\DBConnection;

class AlbumsModel extends BaseModel
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
        $query = $this->pdo->prepare('SELECT * FROM albums');

        try {
            $query->execute();
        } catch (\Exception $e) {
            $this->error = $e;
        }

        $albums = $query->fetchAll();

        return $albums;
    }

    public function get(int $id)
    {
        $query = $this->pdo->prepare('SELECT * FROM albums WHERE id = :id');

        try {
            $query->execute(['id' => $id]);
        } catch (\Exception $e) {
            $this->error = $e;
        }

        $album = $query->fetch();

        if (!$album) {
            $this->error = new \Exception("O album que você procura não existe");
        }

        return $album;
    }

    /**
     * Cria um novo usuário
     *
     * @param string|null $title
     * @return stdClass[]|null
     */
    public function new(?string $title): ?array
    {
        $validatedTitle = $this->validateTitle($title);
        if (!$validatedTitle) {
            return null;
        }

        $params = [
            'title' => $title
        ];

        $query = $this->pdo->prepare(
            "INSERT INTO albums (title) VALUES (:title)"
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

        $artists = $this->getAll();

        // user not found ------->
        $found = false;
        foreach ($artists as $artist) {
            if ($artist->id === (int)$id) {
                $found = true;
            }
        }

        if (!$found) {
            $this->error = new \Exception('Este album não existe');
            return null;
        }

        $query = $this->pdo->prepare(
            "DELETE FROM albums WHERE id = :id"
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
        
        $albums = $this->getAll();
        $album = $this->get($id);

        if (isset($body->title)) {
            $validatedTitle = $this->validateTitle($body->title, $album);
            if (!$validatedTitle) {
                return null;
            }
        }

        if (!in_array($album, $albums)) {
            $this->error = new \Exception('Este album não existe');
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

        $sql = "UPDATE albums SET {$sqlSet} WHERE id = :id";

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

    private function validateTitle($title, $currentAlbum = null)
    {
        if (empty($title)) {
            $this->error = new \Exception('Insira um nome para o artista/banda');
            return false;
        }

        $albums = $this->getAll();

        foreach ($albums as $album) {
            if ($album->title === $title) {
                if($currentAlbum && $currentAlbum->title === $title) {
                    break;
                }
                $this->error = new \Exception('Este album já está cadastrado');
                return false;
            }
        }

        return true;
    }

    public function error()
    {
        return $this->error ?? false;
    }
}
