<?php

namespace MusicRating\Models;

use MusicRating\Models\Helpers\DBConnection;

class RelationshipsModel extends BaseModel
{
    use DBConnection;

    private $pdo;
    private SongsModel $songs;
    private AlbumsModel $albums;

    public function __construct()
    {
        $this->pdo = $this->conn('music_rating_app', 'music_rating_db', 'root', '123#@!');

        /** @var SongsModel */
        $this->songs = new SongsModel();

        /** @var AlbumsModel */
        $this->albums = new AlbumsModel();
    }

    public function getAllSongsFromAlbum($albumId)
    {
        $validatedId = $this->validateId($albumId);
        if(!$validatedId) {
            return null;
        }
        
        $query = $this->pdo->prepare(
            'SELECT s.id, s.title, s.song_order, s.created_at, s.updated_at FROM songs s
            INNER JOIN albums_songs a_s ON a_s.songs = s.id 
            INNER JOIN albums al ON al.id = a_s.albums 
            WHERE al.id = :albumId
            ORDER BY s.song_order'
        );
        
        try {
            $query->execute(['albumId' => $albumId]);
        } catch (\Exception $e) {
            $this->error = $e;
        }
        
        $songs = $query->fetchAll();
        
        return $songs;
    }
    
    public function addSongToAlbum($songId, $albumId)
    {
        // Validação música
        $validatedId = $this->validateId($songId);
        if(!$validatedId) {
            return null;
        }
        $song = $this->songs->get($songId);
        if(!$song) {
            $this->error = $this->songs->error();
            return null;
        }
        
        // Validação álbum
        $validatedId = $this->validateId($albumId);
        if(!$validatedId) {
            return null;
        }

        $album = $this->albums->get($albumId);
        if(!$album) {
            $this->error = $this->albums->error();
            return null;
        }

        $params = [
            'songId' => $songId,
            'albumId' => $albumId
        ];

        // Validação de ocorrência
        $query = $this->pdo->prepare(
            'SELECT * FROM albums_songs WHERE songs = :songId AND albums = :albumId'
        );
        
        try {
            $query->execute($params);
        } catch (\Exception $e) {
            $this->error = $e;
            return null;
        }

        $occurence = $query->fetch();
        if($occurence) {
            $this->error = new \Exception('Essa música já está atribuída a esse álbum');
            return null;
        }

        // Inserção
        $query = $this->pdo->prepare(
            'INSERT INTO albums_songs (songs, albums) VALUES 
                (
                    (SELECT id from songs WHERE id = :songId),
                    (SELECT id from albums WHERE id = :albumId)
                )'
        );

        try {
            $query->execute($params);
        } catch (\Exception $e) {
            $this->error = $e;
            return null;
        }

        $query = $this->pdo->prepare('SELECT * FROM albums WHERE id = :albumId');

        try {
            $query->execute(['albumId' => $albumId]);
        } catch (\Exception $e) {
            $this->error = $e;
            return null;
        }
        
        $album = $query->fetch();
        
        if (!$album) {
            $this->error = new \Exception("O album que você procura não existe");
            return null;
        }

        // validar se os artistas atribuídos ao album estão atribuídos a musica em questão 

        return $album;
        
    }

    

   
    

   
   

    /*
     * =============
     * || HELPERS || =============================================>
     * =============
     */

    

}
