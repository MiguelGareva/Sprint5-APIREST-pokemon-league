<?php

namespace App\Exceptions;

use Exception;

class PokemonAssignmentException extends Exception
{
    const ERROR_TRAINER_MAX_POKEMON_REACHED = 10;
    const ERROR_POKEMON_NOT_ASSIGNED = 20;
    const ERROR_POKEMON_ALREADY_ASSIGNED = 30;
}