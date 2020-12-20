<?php

class CreateLobby extends Controller
{
    static $buildError = Errors::NO_ERROR;

    public static function Build()
    {
        self::$buildError = self::BuildLobbyIfCreated();
        if (self::$buildError === Errors::NO_ERROR) {
            exit;
        }

        self::SetFiles();
        parent::CreateView("newLobby");
    }

    private static function SetFiles()
    {
        // cssFiles
        // jsFiles
        // ...
    }

    private static function BuildLobbyIfCreated()
    {
        if (isset($_POST["create-btn"])) {
            return self::BuildLobbyIfValid();
        }
        return Errors::FORM_NOT_SENT;
    }

    private static function BuildLobbyIfValid()
    {
        if (self::AllFieldsAreSet()) {
            $fields = self::GetFields();
            $fields = self::SanitizeFields($fields);

            $validationError = self::ValidateFields($fields);
            if ($validationError === Errors::NO_ERROR) {
                self::BuildLobby($fields);
            } else {
                return $validationError;
            }
        } else {
            return Errors::EMPTY_FIELDS;
        }

        return Errors::NO_ERROR;
    }

    private static function BuildLobby($fields)
    {
        $encryptedPassword = password_hash($fields["gamePassword"], PASSWORD_DEFAULT);

        $model = new m_LobbyCreator();
        $hash = $model->GetHash()[0]["hash"];
        $lobbyId = $model->CreateLobby($hash, $encryptedPassword, $fields["minWords"])[0]["id"];
        $userData = $model->AddHost($fields["user"], $lobbyId)[0];

        session_start();
        $_SESSION["userId"] = $userData["id"];
        $_SESSION["username"] = $userData["username"];
        $_SESSION["isHost"] = $userData["isHost"];

        header("Location: /?id=" . $hash);
    }

    private static function AllFieldsAreSet()
    {
        return
        static::IsSingleFieldSet("username") &&
        static::IsSingleFieldSet("password") &&
        static::IsSingleFieldSet("minWords");
    }

    private static function IsSingleFieldSet($fieldName)
    {
        return isset($_POST[$fieldName]) && !empty($_POST[$fieldName]);
    }

    private static function GetFields()
    {
        $fields = array();
        $fields["user"] = $_POST["username"];
        $fields["gamePassword"] = $_POST["password"];
        $fields["minWords"] = $_POST["minWords"];
        return $fields;
    }

    private static function SanitizeFields($fields)
    {
        $fields["user"] = filter_var($fields["user"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $fields["gamePassword"] = filter_var($fields["gamePassword"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $fields["minWords"] = filter_var($fields["minWords"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        return $fields;
    }

    private static function ValidateFields($fields)
    {
        if (strlen($fields["user"]) > Settings::MAX_STRING_SIZE ||
            strlen($fields["gamePassword"]) > Settings::MAX_STRING_SIZE) {
            return Errors::FIELD_TOO_BIG;
        }
        if (!(is_numeric($fields["minWords"]) &&
            intval($fields["minWords"]) > 0 &&
            intval($fields["minWords"]) < Settings::MAX_INT)) {
            return Errors::INVALID_FIELD_TYPE;
        }
        return Errors::NO_ERROR;
    }

}