<?php

class m_Word extends DatabaseHandler
{

    public function GetWordDoubles($word, $lobbyId)
    {
        $sql = "SELECT id FROM words WHERE LOWER(word)=LOWER(?) AND lobbyId=?";
        return parent::query($sql, $word, $lobbyId);
    }

    public function SaveWord($word, $lobbyId)
    {
        $sql = "INSERT INTO words (id, word, lobbyId) VALUES (NULL, ?, ?)";
        parent::query($sql, $word, $lobbyId);
        parent::query(Settings::UPDATE_SQL, $lobbyId);
    }

}