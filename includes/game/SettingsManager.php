<?php

class SettingsManager
{

    public function RecordSettings()
    {
        if (self::SettingsAreValid()) {
            $data = self::GetSettings();
            $data = self::SanitizeSettings($data);
            $validationError = self::ValidateSettings($data);
            if ($validationError === Errors::NO_ERROR) {
                if (isset($_SESSION["gameId"])) {
                    $model = new m_GameSettings();
                    $model->UpdateGameSettings($data["timer"], $data["teamCount"], $_SESSION["gameId"], $_SESSION["lobbyId"]);
                } else {
                    return Errors::GAME_NOT_CREATED;
                }
            } else {
                return $validationError;
            }
        } else {
            return Errors::EMPTY_FIELDS;
        }
        return Errors::NO_ERROR;
    }

    private function SettingsAreValid()
    {
        return isset($_GET["timer"]) && !empty($_GET["timer"]) &&
        isset($_GET["teamCount"]) && !empty($_GET["teamCount"]);
    }

    private function GetSettings()
    {
        return array(
            "timer" => $_GET["timer"],
            "teamCount" => $_GET["teamCount"],
        );
    }

    private function SanitizeSettings($data)
    {
        $data["timer"] = filter_var($data["timer"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $data["teamCount"] = filter_var($data["teamCount"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        return $data;
    }

    private function ValidateSettings($data)
    {
        foreach ($data as $setting) {
            if (is_numeric($setting)) {
                if (intval($setting) > 1) {
                    if (intval($setting) > Settings::MAX_INT) {
                        return Errors::FIELD_TOO_BIG;
                    }
                } else {
                    return Errors::INVALID_FIELD_TYPE;
                }
            } else {
                return Errors::INVALID_FIELD_TYPE;
            }
        }
        return Errors::NO_ERROR;
    }
}