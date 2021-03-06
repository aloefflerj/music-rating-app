<?php

namespace MusicRating\Models;

use MusicRating\Models\Helpers\DBConnection;
use MusicRating\Models\Helpers\UsersConnConfig;

class SongsModel extends BaseModel
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
            $query = $this->pdo->prepare('SELECT id, title FROM songs');
            $query->execute();
        } catch (\Exception $e) {
            $this->error = $e;
            return null;
        }

        $songs = $query->fetchAll();

        return $songs;
    }

    public function get(int $id)
    {
        
        try {
            $query = $this->pdo->prepare('SELECT id, title FROM songs WHERE id = :id');
            $query->execute(['id' => $id]);
        } catch (\Exception $e) {
            $this->error = $e;
        }

        $song = $query->fetch();

        if (!$song) {
            $this->error = new \Exception("A música que você procura não existe");
        }

        return $song;
    }

    /**
     * Cria uma nova música
     *
     * @param string|null $title
     * @param int|null $song_order
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

        
        try {
            $query = $this->pdo->prepare(
                "INSERT INTO songs (title, song_order) VALUES (:title, :song_order)"
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

        $songs = $this->getAll();

        // song not found ------->
        $found = false;
        foreach ($songs as $song) {
            if ($song->id === (int)$id) {
                $found = true;
            }
        }

        if (!$found) {
            $this->error = new \Exception('Esta música não existe');
            return null;
        }

        
        try {
            $query = $this->pdo->prepare(
                "CALL DeleteSong(:id)"
            );
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


        $songs = $this->getAll();
        $song = $this->get($id);

        if (!in_array($song, $songs)) {
            $this->error = new \Exception('Este usuário não existe');
            return null;
        };

        if (isset($body->title)) {
            $validatedTitle = $this->validateTitle($body->title, $song);
            if (!$validatedTitle) {
                return null;
            }
        }

        if (isset($body->song_order)) {
            $validatedSongOrder = $this->validateSongOrder($body->song_order, $song);
            if (!$validatedSongOrder) {
                return null;
            }
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

        $sql = "UPDATE songs SET {$sqlSet} WHERE id = :id";

        
        try {
            $sql = $this->pdo->prepare($sql);
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

    private function validateTitle($title, $currentSong = null)
    {
        if (empty($title)) {
            $this->error = new \Exception('Insira um título para a música');
            return false;
        }

        $songs = $this->getAll();

        foreach ($songs as $song) {
            if($currentSong && $currentSong->title === $title) {
                break;
            }
            if ($song->title === $title) {
                $this->error = new \Exception('Esta música já foi cadastrada');
                return false;
            }
        }

        return true;
    }

    private function validateSongOrder($song_order, $currentSongOrder = null)
    {
        if (empty($song_order)) {
            $this->error = new \Exception('Insira número da música no álbum');
            return false;
        }

        // Validar se o valor repetido não é da própria música

        // Fazer a validação de album aqui 
        // $this->validateSongInAlbum

        return true;
    }

    private function validateSongInAlbum()
    {
        // Verificar se a música está atrelada a algum álbum
        // Verificar se a ordem da música nesse álbum já existe para gerar erro
    }

}
