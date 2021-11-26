<?php

namespace MusicRating\Models;

use MusicRating\Models\Helpers\DBConnection;
use MusicRating\Models\Helpers\QueryHelper;
use MusicRating\Models\Helpers\UsersConnConfig;

class StarsModel extends BaseModel
{
    use DBConnection;
    use UsersConnConfig;
    use QueryHelper;

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
        // $select = $this->select('*, StarsText(stars)', 'starred_songs', 'users = :user');

        $select = "SELECT s.id as id, title, stars, StarsText(stars) AS label  FROM starred_songs ss
                    INNER JOIN songs s
                    WHERE s.id  = ss.songs AND users = :user";

        $user = $_SESSION['user'];

        try {
            $query = $this->pdo->prepare($select);
            $query->execute(['user' => $user->id]);
            $songs = $query->fetchAll();
        } catch (\Exception $e) {
            $this->error = $e;
            return null;
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
        $select = "SELECT s.id as id, title, stars, StarsText(stars) AS label  FROM starred_songs ss
                    INNER JOIN songs s
                    WHERE s.id  = ss.songs AND users = :user AND ss.id = :id";
        
        try {
            $query = $this->pdo->prepare($select);
            $query->execute([
                'id' => $id,
                'user' => $user->id
            ]);
        } catch (\Exception $e) {
            $this->error = $e;
            return null;
        }

        $songs = $query->fetchAll();
        
        return $songs;
    }
    
    public function starASong($stars, $song) {
        
        $validatedStars = $this->validateStars($stars);
        if(!$validatedStars) {
            return null;
        }
        
        $validatedSong = $this->validateSong($song);
        if(!$validatedSong) {
            return null;
        }

        $user = $_SESSION['user'];

        // verify if the song isn't already starred
        $select = $this->select('songs, users', 'starred_songs', 'songs = :song AND users = :user');
        try {
            $sql = $this->pdo->prepare($select);
            $sql->execute([
                'song' => $validatedSong,
                'user' => $user->id
            ]);
        } catch(\PDOException $e) {
            $this->error = $e;
            return null;
        }

        $occurence = $sql->fetch();
        if($occurence) {
            $this->error = new \Exception("Está música já tem um registro de avaliação");
            return null;
        }
        
        $insert = "INSERT INTO starred_songs (stars, songs, users) VALUES (:stars, :song, :user)";

        try {
            $sql = $this->pdo->prepare($insert);
            $sql->execute([
                'stars' => $validatedStars,
                'song' => $validatedSong,
                'user' => $user->id
            ]);
        } catch(\PDOException $e) {
            $this->error = $e;
            return null;
        }

        return $this->getAllStarredSongs();

    }

    // Average text query -> SELECT stars, AverageText(stars, (SELECT AVG(stars) FROM starred_albums)) from starred_albums;

    /*
     * =============
     * || HELPERS || =============================================>
     * =============
     */

     private function validateStars($stars) 
     {
        if(empty($stars) || !filter_var($stars, FILTER_VALIDATE_INT) || $stars < 1 || $stars > 5) {
            $this->error = new \Exception('Adicione uma pontuação válida');
            return false;
        }

        return $stars;

    }
    
    private function validateSong($song)
    {
        if(empty($song)) {
            $this->error = new \Exception('Adicione uma música válida');
            return false;   
        }

        $song = filter_var($song, FILTER_SANITIZE_STRIPPED);

        return $song;
     }

}
