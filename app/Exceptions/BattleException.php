<?php

namespace App\Exceptions;

use Exception;

class BattleException extends Exception
{
    const ERROR_SAME_TRAINER = 10;
    const ERROR_NO_POKEMONS = 20;
    const ERROR_INVALID_BATTLE_DATA = 30;
}