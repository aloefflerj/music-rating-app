<?php

namespace MusicRating\Models;

use MusicRating\Models\Helpers\DBConnection;
use MusicRating\Models\Helpers\UsersConnConfig;

class RelationshipsModel extends BaseModel
{
    use DBConnection;
    use UsersConnConfig;

    private $pdo;
    private SongsModel $songs;
    private AlbumsModel $albums;
    private ArtistsModel $artists;

    public function __construct()
    {
        $userConn = $this->getUserConn();
        $this->pdo = $this->conn($userConn);

        /** @var SongsModel */
        $this->songs = new SongsModel();

        /** @var AlbumsModel */
        $this->albums = new AlbumsModel();

        /** @var ArtistsModel */
        $this->artists = new ArtistsModel();
    }

    public function getAllSongsFromAlbum($albumId)
    {
        $validatedId = $this->validateId($albumId);
        if(!$validatedId) {
            return null;
        }

        $album = $this->albums->get($albumId);
        if(!$album) {
            $this->error = $this->albums->error();
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

    public function getAllSongsFromArtist($artistId)
    {
        $validatedId = $this->validateId($artistId);
        if(!$validatedId) {
            return null;
        }

        $artist = $this->artists->get($artistId);
        if(!$artist) {
            $this->error = $this->artists->error();
            return null;
        }
        
        $query = $this->pdo->prepare(
            'SELECT s.id, s.title, s.song_order, s.created_at, s.updated_at FROM songs s
            INNER JOIN artists_songs a_s ON a_s.songs = s.id 
            INNER JOIN artists ar ON ar.id = a_s.artists 
            WHERE ar.id = :artistId
            ORDER BY s.song_order'
        );
        
        try {
            $query->execute(['artistId' => $artistId]);
        } catch (\Exception $e) {
            $this->error = $e;
        }
        
        $songs = $query->fetchAll();
        
        return $songs;
    }

    public function getAllAlbumsFromArtist($artistId)
    {
        $validatedId = $this->validateId($artistId);
        if(!$validatedId) {
            return null;
        }

        $artist = $this->artists->get($artistId);
        if(!$artist) {
            $this->error = $this->artists->error();
            return null;
        }
        
        $query = $this->pdo->prepare(
            'SELECT al.id, al.title, al.created_at, al.updated_at FROM albums al
            INNER JOIN artists_albums ar_al ON ar_al.albums = al.id 
            INNER JOIN artists ar ON ar.id = ar_al.artists 
            WHERE ar.id = :artistId
            ORDER BY al.created_at'
        );
        
        try {
            $query->execute(['artistId' => $artistId]);
        } catch (\Exception $e) {
            $this->error = $e;
        }
        
        $songs = $query->fetchAll();
        
        return $songs;
    }
    
    public function addSongToAlbum($songId, $albumId)
    {
        // Valida????o m??sica
        $validatedId = $this->validateId($songId);
        if(!$validatedId) {
            return null;
        }
        $song = $this->songs->get($songId);
        if(!$song) {
            $this->error = $this->songs->error();
            return null;
        }
        
        // Valida????o ??lbum
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

        // Valida????o de ocorr??ncia
        $query = $this->pdo->prepare(
            'SELECT id, albums, songs FROM albums_songs WHERE songs = :songId AND albums = :albumId'
        );
        
        try {
            $query->execute($params);
        } catch (\Exception $e) {
            $this->error = $e;
            return null;
        }

        $occurence = $query->fetch();
        if($occurence) {
            $this->error = new \Exception('Essa m??sica j?? est?? atribu??da a esse ??lbum');
            return null;
        }

        // Inser????o
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

        $query = $this->pdo->prepare('SELECT id, title FROM albums WHERE id = :albumId');

        try {
            $query->execute(['albumId' => $albumId]);
        } catch (\Exception $e) {
            $this->error = $e;
            return null;
        }
        
        $album = $query->fetch();
        
        if (!$album) {
            $this->error = new \Exception("O album que voc?? procura n??o existe");
            return null;
        }

        // validar se os artistas atribu??dos ao album est??o atribu??dos a musica em quest??o 

        return $album;
        
    }

    public function addSongToArtist($songId, $artistId)
    {
        // Valida????o m??sica
        $validatedId = $this->validateId($songId);
        if(!$validatedId) {
            return null;
        }
        $song = $this->songs->get($songId);
        if(!$song) {
            $this->error = $this->songs->error();
            return null;
        }
        
        // Valida????o artista
        $validatedId = $this->validateId($artistId);
        if(!$validatedId) {
            return null;
        }

        $artist = $this->artists->get($artistId);
        if(!$artist) {
            $this->error = $this->artists->error();
            return null;
        }

        $params = [
            'songId' => $songId,
            'artistId' => $artistId
        ];

        // Valida????o de ocorr??ncia
        $query = $this->pdo->prepare(
            'SELECT id, artists, songs FROM artists_songs WHERE songs = :songId AND artists = :artistId'
        );
        
        try {
            $query->execute($params);
        } catch (\Exception $e) {
            $this->error = $e;
            return null;
        }

        $occurence = $query->fetch();
        if($occurence) {
            $this->error = new \Exception('Essa m??sica j?? est?? atribu??da a esse artista');
            return null;
        }

        // Inser????o
        $query = $this->pdo->prepare(
            'INSERT INTO artists_songs (songs, artists) VALUES 
                (
                    (SELECT id from songs WHERE id = :songId),
                    (SELECT id from artists WHERE id = :artistId)
                )'
        );

        try {
            $query->execute($params);
        } catch (\Exception $e) {
            $this->error = $e;
            return null;
        }

        $query = $this->pdo->prepare('SELECT id, name FROM artists WHERE id = :artistId');

        try {
            $query->execute(['artistId' => $artistId]);
        } catch (\Exception $e) {
            $this->error = $e;
            return null;
        }
        
        $artist = $query->fetch();
        
        if (!$artist) {
            $this->error = new \Exception("O artista que voc?? procura n??o existe");
            return null;
        }

        // validar se os artistas atribu??dos ao album est??o atribu??dos a musica em quest??o 

        return $artist;
        
    }

    public function addAlbumToArtist($albumId, $artistId)
    {
        // Valida????o m??sica
        $validatedId = $this->validateId($albumId);
        if(!$validatedId) {
            return null;
        }
        $album = $this->albums->get($albumId);
        if(!$album) {
            $this->error = $this->albums->error();
            return null;
        }
        
        // Valida????o artista
        $validatedId = $this->validateId($artistId);
        if(!$validatedId) {
            return null;
        }

        $artist = $this->artists->get($artistId);
        if(!$artist) {
            $this->error = $this->artists->error();
            return null;
        }

        $params = [
            'albumId' => $albumId,
            'artistId' => $artistId
        ];

        // Valida????o de ocorr??ncia
        $query = $this->pdo->prepare(
            'SELECT id, artists, albums FROM artists_albums WHERE albums = :albumId AND artists = :artistId'
        );
        
        try {
            $query->execute($params);
        } catch (\Exception $e) {
            $this->error = $e;
            return null;
        }

        $occurence = $query->fetch();
        if($occurence) {
            $this->error = new \Exception('Este ??lbum j?? est?? atribu??do a esse artista');
            return null;
        }

        // Inser????o
        $query = $this->pdo->prepare(
            'INSERT INTO artists_albums (albums, artists) VALUES 
                (
                    (SELECT id from albums WHERE id = :albumId),
                    (SELECT id from artists WHERE id = :artistId)
                )'
        );

        try {
            $query->execute($params);
        } catch (\Exception $e) {
            $this->error = $e;
            return null;
        }

        $query = $this->pdo->prepare('SELECT id, name FROM artists WHERE id = :artistId');

        try {
            $query->execute(['artistId' => $artistId]);
        } catch (\Exception $e) {
            $this->error = $e;
            return null;
        }
        
        $artist = $query->fetch();
        
        if (!$artist) {
            $this->error = new \Exception("O artista que voc?? procura n??o existe");
            return null;
        }

        // validar se os artistas atribu??dos ao album est??o atribu??dos a musica em quest??o 

        return $artist;
        
    }

    

}
