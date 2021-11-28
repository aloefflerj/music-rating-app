<?php

namespace MusicRating\Models;

use MusicRating\Models\Helpers\DBConnection;
use MusicRating\Models\Helpers\UsersConnConfig;

class DataLoaderModel extends BaseModel
{
    use DBConnection;
    use UsersConnConfig;

    private $pdo;

    public function __construct()
    {
        $userConn = $this->getUserConn();
        $this->pdo = $this->conn($userConn);
    }


    public function populateDb($data)
    {
        if ($_SESSION['user']->user_type !== 'dba') {
            $this->error = new \Exception("Você não tem permissão para isso");
            return null;
        }

        $values = [];
        foreach ($data as $value) {
            $values[] = $this->validateTitle($value);
        }
        if (in_array(false, $values)) {
            return null;
        }

        $artistsNames = array_slice($values, 0, 333);
        $this->insertIntoArtists($artistsNames);

        $albumsTitles = array_slice($values, 333, 666);
        $this->insertIntoAlbums($albumsTitles);

        $songsTitles = array_slice($values, 666, 999);
        $this->insertIntoSongs($songsTitles);

        $randomInsertRelationships = $this->insertRandomRelationsShips();
        if(!$randomInsertRelationships) {
            return null;
        }

    }


    private function validateTitle($title)
    {
        if (empty($title)) {
            $this->error = new \Exception('Insira um título para a música');
            return false;
        }

        $title = filter_var($title, FILTER_SANITIZE_STRIPPED);

        return $title;
    }

    private function insertIntoArtists($artistsNames)
    {
        $insert = 'INSERT INTO artists (name) VALUES ';

        foreach ($artistsNames as $artistName) {
            $insert .= "(?), ";
        }

        $insert = substr($insert, 0, -2);

        try {
            $this->pdo->setAttribute(\PDO::ATTR_AUTOCOMMIT, 0);
            $this->pdo->beginTransaction();
            $sql = $this->pdo->prepare($insert);
            $sql->execute($artistsNames);
        } catch (\PDOException $e) {
            $this->error = $e;
            $this->pdo->rollBack();
            return null;
        }

        $this->pdo->commit();
    }

    private function insertIntoAlbums($albumsTitles)
    {
        $insert = 'INSERT INTO albums (title) VALUES ';

        foreach ($albumsTitles as $albumTitle) {
            $insert .= "(?), ";
        }

        $insert = substr($insert, 0, -2);

        try {
            $this->pdo->setAttribute(\PDO::ATTR_AUTOCOMMIT, 0);
            $this->pdo->beginTransaction();
            $sql = $this->pdo->prepare($insert);
            $sql->execute($albumsTitles);
        } catch (\PDOException $e) {
            $this->error = $e;
            $this->pdo->rollBack();
            return null;
        }

        $this->pdo->commit();
    }

    private function insertIntoSongs($songsTitles)
    {
        $insert = 'INSERT INTO songs (title) VALUES ';

        foreach ($songsTitles as $songTitle) {
            $insert .= "(?), ";
        }

        $insert = substr($insert, 0, -2);

        try {
            $this->pdo->setAttribute(\PDO::ATTR_AUTOCOMMIT, 0);
            $this->pdo->beginTransaction();
            $sql = $this->pdo->prepare($insert);
            $sql->execute($songsTitles);
        } catch (\PDOException $e) {
            $this->error = $e;
            $this->pdo->rollBack();
            return null;
        }

        $this->pdo->commit();
    }

    private function insertRandomRelationsShips()
    {
        $artistsIds = [];
        $albumIds = [];
        $songIds = [];

        $artistsIds = $this->selectIdFromTable('artists');
        $albumIds = $this->selectIdFromTable('albums');
        $songIds = $this->selectIdFromTable('songs');

        $randomArtistsIds = $this->randomizeArrayIndexes($artistsIds);
        $randomAlbumIds = $this->randomizeArrayIndexes($albumIds);
        $randomSongIds = $this->randomizeArrayIndexes($songIds);

        $insertionSuccess = $this->insertRelationshipTables('artists', 'songs', $randomArtistsIds, $randomSongIds);
        if(!$insertionSuccess) {
            return false;
        }
        
        $insertionSuccess = $this->insertRelationshipTables('albums', 'songs', $randomAlbumIds, $randomSongIds);
        if(!$insertionSuccess) {
            return false;
        }

        $insertionSuccess = $this->insertRelationshipTables('artists', 'albums', $randomArtistsIds, $randomAlbumIds);
        if(!$insertionSuccess) {
            return false;
        }

    }

    private function selectIdFromTable($table)
    {
        $select = "SELECT id FROM {$table}";
        try {
            $stmt = $this->pdo->query($select, \PDO::FETCH_COLUMN, 0);
            $tableIds = $stmt->fetchAll();
        } catch (\PDOException $e) {
            $this->error = $e;
            return null;
        }

        return $tableIds;
    }

    private function randomizeArrayIndexes($array)
    {
        $randomizedArray = [];
        $count = 0;

        while ($count !== 300) {
            $randomizedArray[] = $array[array_rand($array)];
            $count++;
        }

        return $randomizedArray;
    }

    private function prepareIdsForTransaction($idsArray, $prefix)
    {
        $idsPrepared = [];
        foreach ($idsArray as $index => $id) {
            $idsPrepared[] = $prefix . "_{$index}";
        }

        return $idsPrepared;
    }

    private function insertRelationshipTables($column1, $column2, $randomIdsColumn1, $randomIdsColumn2)
    {
        $insert = "INSERT INTO {$column1}_{$column2} ({$column1}, {$column2}) VALUES ";

        foreach($randomIdsColumn1 as $index => $randomIdColumn1) {
            $insert .= "(:{$column1}_{$index}, :{$column2}_{$index}), "; 
        }
        
        $insert = substr($insert, 0, -2);

        $preparedColumn1Ids = $this->prepareIdsForTransaction($randomIdsColumn1, $column1);
        $preparedColumn2Ids = $this->prepareIdsForTransaction($randomIdsColumn2, $column2);

        $cobinedIdArrayColumn1 = array_combine($preparedColumn1Ids, $randomIdsColumn1);
        $cobinedIdArrayColumn2 = array_combine($preparedColumn2Ids, $randomIdsColumn2);
        $params = array_merge($cobinedIdArrayColumn1, $cobinedIdArrayColumn2);

        try {
            $this->pdo->setAttribute(\PDO::ATTR_AUTOCOMMIT, 0);
            $this->pdo->beginTransaction();
            $sql = $this->pdo->prepare($insert);
            $sql->execute($params);
        } catch (\PDOException $e) {
            $this->error = $e;
            $this->pdo->rollBack();
            return false;
        }

        $this->pdo->commit();
        $this->pdo->setAttribute(\PDO::ATTR_AUTOCOMMIT, 1);

        return true;
    }
}
