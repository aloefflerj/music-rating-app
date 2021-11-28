<?php

namespace MusicRating\Models;

use MusicRating\Models\Helpers\DBConnection;
use MusicRating\Models\Helpers\UsersConnConfig;

class ArtistsModel extends BaseModel
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
        $query = $this->pdo->prepare('SELECT * FROM artists');

        try {
            $query->execute();
        } catch (\Exception $e) {
            $this->error = $e;
            return null;
        }

        $artists = $query->fetchAll();

        return $artists;
    }

    public function get(int $id)
    {
        $query = $this->pdo->prepare('SELECT * FROM artists WHERE id = :id');

        try {
            $query->execute(['id' => $id]);
        } catch (\Exception $e) {
            $this->error = $e;
            return null;
        }

        $artist = $query->fetch();

        if (!$artist) {
            $this->error = new \Exception("O artista que você procura não existe");
            return null;
        }

        return $artist;
    }

    /**
     * Cria um novo artista
     *
     * @param string|null $user
     * @return stdClass[]|null
     */
    public function new(?string $name): ?array
    {
        $validatedName = $this->validateName($name);
        if (!$validatedName) {
            return null;
        }

        $params = [
            'name' => $name
        ];

        
        try {
            $query = $this->pdo->prepare(
                "INSERT INTO artists (name) VALUES (:name)"
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
            $this->error = new \Exception('Este artista/banda não existe');
            return null;
        }

        $query = $this->pdo->prepare(
            "CALL DeleteArtist(:id)"
        );

        try {
            $query->execute(['id' => $id]);
        } catch (\Exception $e) {
            $this->error = $e;
            return null;
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
        
        
        $artists = $this->getAll();
        $artist = $this->get($id);

        if (isset($body->name)) {
            $validatedArtistName = $this->validateName($body->name, $artist);
            if (!$validatedArtistName) {
                return null;
            }
        }

        if (!in_array($artist, $artists)) {
            $this->error = new \Exception('Este artista/banda não existe');
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

        $sql = "UPDATE artists SET {$sqlSet} WHERE id = :id";

        
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

    private function validateName($name, $currentArtist = null)
    {
        if (empty($name)) {
            $this->error = new \Exception('Insira um nome para o artista/banda');
            return false;
        }

        $artists = $this->getAll();

        foreach ($artists as $artist) {
            if ($artist->name === $name) {
                if($currentArtist && $currentArtist->name === $name) {
                    break;
                }
                $this->error = new \Exception('Este(a) artista/banda já está cadastrado');
                return false;
            }
        }

        return true;
    }

}
