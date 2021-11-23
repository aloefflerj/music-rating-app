<?php

namespace MusicRating\Models;

use MusicRating\Models\Helpers\DBConnection;
use MusicRating\Models\Helpers\UsersConnConfig;

class StarsModel extends BaseModel
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
    public function getAllStarredSongs(): ?array
    {
        // 'SELECT *, StarsText(stars) FROM starred_songs WHERE user = :user'
        $select = $this->select('*, StarsText(stars)', 'starred_songs', 'users = :user');

        $user = $_SESSION['user'];

        try {
            $query = $this->pdo->prepare($select);
            $query->execute(['user' => $user->id]);
            $songs = $query->fetchAll();
        } catch (\Exception $e) {
            $this->error = $e;
        }


        return $songs;
    }

    public function getStarredSong($id)
    {
        $validatedId = $this->validateId($id);
        if(!$validatedId) {
            return null;
        }

        $user = $_SESSION['user'];

        // 'SELECT *, StarsText(stars) FROM starred_songs WHERE id = :id'
        $select = $this->select('*, StarsText(stars)', 'starred_songs', 'id = :id AND users = :user');
        
        try {
            $query = $this->pdo->prepare($select);
            $query->execute([
                'id' => $id,
                'user' => $user->id
            ]);
        } catch (\Exception $e) {
            $this->error = $e;
        }

        $songs = $query->fetchAll();

        return $songs;
    }

    /*
     * =============
     * || HELPERS || =============================================>
     * =============
     */

    private function validateSongInAlbum()
    {
        // Verificar se a música está atrelada a algum álbum
        // Verificar se a ordem da música nesse álbum já existe para gerar erro
    }

}
