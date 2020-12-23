<?php

abstract class Errors
{
    const NO_ERROR = null;
    const EMPTY_FIELDS = 1;
    const FORM_NOT_SENT = 2;
    const FIELD_TOO_BIG = 3;
    const INVALID_FIELD_TYPE = 4;
    const INVALID_LOBBY = 5;
    const INVALID_PASSWORD = 6;
    const USER_ALREADY_REGISTERED = 7;
    const DOUBLE_DATA = 8;
    const GAME_NOT_CREATED = 9;
}