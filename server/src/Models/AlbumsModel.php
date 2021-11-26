<?php

namespace MusicRating\Models;

use MusicRating\Models\Helpers\DBConnection;
use MusicRating\Models\Helpers\UsersConnConfig;

class AlbumsModel extends BaseModel
{
    use DBConnection;
    use UsersConnConfig;

    private $pdo;

    public function __construct()
    {
        $userConn = $this->getUserConn();
        $this->pdo = $this->conn($userConn);
    }

    /**
     * Retorna todos os usuários do banco
     *
     * @return stdClass[] | null
     */
    public function getAll(): ?array
    {
        
        try {
            $query = $this->pdo->prepare('SELECT * FROM albums');
            $query->execute();
        } catch (\Exception $e) {
            $this->error = $e;
            return null;
        }

        $albums = $query->fetchAll();

        return $albums;
    }

    public function get(int $id)
    {
        
        try {
            $query = $this->pdo->prepare('SELECT * FROM albums WHERE id = :id');
            $query->execute(['id' => $id]);
        } catch (\Exception $e) {
            $this->error = $e;
            return null;
        }

        $album = $query->fetch();

        if (!$album) {
            $this->error = new \Exception("O album que você procura não existe");
            return null;
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

        
        try {
            $query = $this->pdo->prepare(
                "INSERT INTO albums (title) VALUES (:title)"
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

        
        try {
            $query = $this->pdo->prepare(
                "CALL DeleteAlbum(:id)"
            );
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

        
        try {
            $sql = $this->pdo->prepare($sql);
            $sql->execute($bodyArr);
        } catch (\Exception $e) {
            $this->error = $e;
            return null;
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

}
