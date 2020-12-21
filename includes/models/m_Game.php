<?php

class m_Game extends DatabaseHandler
{

    public function DisconnectPlayer($playerId)
    {
        $sql = "UPDATE players SET connected=FALSE, isHost=FALSE WHERE id=?";
        parent::query($sql, $playerId);
    }

    public function AssignHost($lobbyId)
    {
        $sql = "CALL AssignHost(?)";
        return parent::query($sql, $lobbyId);
    }

}