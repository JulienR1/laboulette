<?php

class WordManager
{
    private static $model = null;

    public function RecordWord()
    {
        if (self::WordIsPassed()) {
            $word = $_GET["w"];
            $word = filter_var($word, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

            self::$model = new m_Word();
            $validationError = self::WordIsValid($word);
            if ($validationError === Errors::NO_ERROR) {
                self::$model->SaveWord($word, $_SESSION["lobbyId"]);
            } else {
                return $validationError;
            }
        } else {
            return Errors::EMPTY_FIELDS;
        }
        return Errors::NO_ERROR;
    }

    private function WordIsPassed()
    {
        return isset($_GET["w"]) && !empty($_GET["w"]);
    }

    private function WordIsValid($word)
    {
        if (strlen($word) < Settings::MAX_STRING_SIZE) {
            $wordTable = self::$model->GetWordDoubles($word, $_SESSION["lobbyId"]);
            if ($wordTable === null) {
                return Errors::NO_ERROR;
            } else {
                return Errors::DOUBLE_DATA;
            }
        } else {
            return Errors::FIELD_TOO_BIG;
        }
        return Errors::FORM_NOT_SENT;
    }

}