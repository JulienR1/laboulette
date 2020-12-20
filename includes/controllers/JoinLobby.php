<?php

class JoinLobby extends Controller
{
    static $joinError = Errors::NO_ERROR;

    public static function Build()
    {
        self::$joinError = self::JoinLobbyIfExists();
        if (self::$joinError === Errors::NO_ERROR) {
            exit;
        }

        self::SetFiles();
        parent::CreateView("joinLobby");
    }

    private static function SetFiles()
    {
        // cssFiles
        // jsFiles
        // ...
    }

    private static function JoinLobbyIfExists()
    {
        if (isset($_POST["join-btn"])) {
            if (self::FieldsAreSet()) {
                $fields = self::GetFields();
                $fields = self::SanitizeFields($fields);

                $validationError = self::ValidateFields($fields);
                if ($validationError === Errors::NO_ERROR) {
                    $model = new m_LobbyJoiner();
                    $hash = $fields["lobbyHash"];
                    $lobbyData = $model->GetLobbyData($hash);

                    if ($lobbyData !== null) {
                        $lobbyData = $lobbyData[0];
                        if (password_verify($fields["password"], $lobbyData["password"])) {
                            if ($model->IsUsernameFree($fields["username"], $lobbyData["id"])) {
                                $playerInfo = $model->AddPlayer($fields["username"], $lobbyData["id"])[0];

                                session_start();
                                $_SESSION["userId"] = $playerInfo["id"];
                                $_SESSION["username"] = $playerInfo["username"];
                                $_SESSION["isHost"] = $playerInfo["isHost"];

                                header("Location: /?id=" . $hash);
                            } else {
                                return Errors::USER_ALREADY_REGISTERED;
                            }
                        } else {
                            return Errors::INVALID_PASSWORD;
                        }
                    } else {
                        return Errors::INVALID_LOBBY;
                    }
                } else {
                    return $validationError;
                }
            } else {
                return Errors::EMPTY_FIELDS;
            }
        } else {
            return Errors::FORM_NOT_SENT;
        }

        return Errors::NO_ERROR;
    }

    private static function FieldsAreSet()
    {
        return self::SingleFieldIsSet("username") && self::SingleFieldIsSet("lobbyHash") && self::SingleFieldIsSet("password");
    }

    private static function SingleFieldIsSet($fieldName)
    {
        return isset($_POST[$fieldName]) && !empty($_POST[$fieldName]);
    }

    private static function GetFields()
    {
        return array(
            "username" => $_POST["username"],
            "lobbyHash" => $_POST["lobbyHash"],
            "password" => $_POST["password"],
        );
    }

    private static function SanitizeFields($fields)
    {
        $fields["username"] = filter_var($fields["username"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $fields["lobbyHash"] = filter_var($fields["lobbyHash"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $fields["password"] = filter_var($fields["password"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        return $fields;
    }

    private static function ValidateFields($fields)
    {
        if (strlen($fields["lobbyHash"]) !== Settings::HASH_SIZE) {
            return Errors::INVALID_FIELD_TYPE;
        }
        if (strlen($fields["username"]) > Settings::MAX_STRING_SIZE) {
            return Errors::FIELD_TOO_BIG;
        }
        return Errors::NO_ERROR;
    }

}