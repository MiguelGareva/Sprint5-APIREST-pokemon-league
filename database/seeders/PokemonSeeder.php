<?php

namespace Database\Seeders;

use App\Models\Pokemon;
use Illuminate\Database\Seeder;

class PokemonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * @return void
     */
    public function run(): void
    {
        $pokemonData = [
            // #001-#010
            [
                'name' => 'Bulbasaur',
                'type' => 'Grass',
                'level' => 5,
                'stats' => json_encode(['attack' => 49, 'defense' => 49, 'hp' => 45, 'speed' => 45]),
            ],
            [
                'name' => 'Ivysaur',
                'type' => 'Grass',
                'level' => 16,
                'stats' => json_encode(['attack' => 62, 'defense' => 63, 'hp' => 60, 'speed' => 60]),
            ],
            [
                'name' => 'Venusaur',
                'type' => 'Grass',
                'level' => 32,
                'stats' => json_encode(['attack' => 82, 'defense' => 83, 'hp' => 80, 'speed' => 80]),
            ],
            [
                'name' => 'Charmander',
                'type' => 'Fire',
                'level' => 5,
                'stats' => json_encode(['attack' => 52, 'defense' => 43, 'hp' => 39, 'speed' => 65]),
            ],
            [
                'name' => 'Charmeleon',
                'type' => 'Fire',
                'level' => 16,
                'stats' => json_encode(['attack' => 64, 'defense' => 58, 'hp' => 58, 'speed' => 80]),
            ],
            [
                'name' => 'Charizard',
                'type' => 'Fire',
                'level' => 36,
                'stats' => json_encode(['attack' => 84, 'defense' => 78, 'hp' => 78, 'speed' => 100]),
            ],
            [
                'name' => 'Squirtle',
                'type' => 'Water',
                'level' => 5,
                'stats' => json_encode(['attack' => 48, 'defense' => 65, 'hp' => 44, 'speed' => 43]),
            ],
            [
                'name' => 'Wartortle',
                'type' => 'Water',
                'level' => 16,
                'stats' => json_encode(['attack' => 63, 'defense' => 80, 'hp' => 59, 'speed' => 58]),
            ],
            [
                'name' => 'Blastoise',
                'type' => 'Water',
                'level' => 36,
                'stats' => json_encode(['attack' => 83, 'defense' => 100, 'hp' => 79, 'speed' => 78]),
            ],
            [
                'name' => 'Caterpie',
                'type' => 'Bug',
                'level' => 3,
                'stats' => json_encode(['attack' => 30, 'defense' => 35, 'hp' => 45, 'speed' => 45]),
            ],
            
            // #011-#020
            [
                'name' => 'Metapod',
                'type' => 'Bug',
                'level' => 7,
                'stats' => json_encode(['attack' => 20, 'defense' => 55, 'hp' => 50, 'speed' => 30]),
            ],
            [
                'name' => 'Butterfree',
                'type' => 'Bug',
                'level' => 10,
                'stats' => json_encode(['attack' => 45, 'defense' => 50, 'hp' => 60, 'speed' => 70]),
            ],
            [
                'name' => 'Weedle',
                'type' => 'Bug',
                'level' => 3,
                'stats' => json_encode(['attack' => 35, 'defense' => 30, 'hp' => 40, 'speed' => 50]),
            ],
            [
                'name' => 'Kakuna',
                'type' => 'Bug',
                'level' => 7,
                'stats' => json_encode(['attack' => 25, 'defense' => 50, 'hp' => 45, 'speed' => 35]),
            ],
            [
                'name' => 'Beedrill',
                'type' => 'Bug',
                'level' => 10,
                'stats' => json_encode(['attack' => 90, 'defense' => 40, 'hp' => 65, 'speed' => 75]),
            ],
            [
                'name' => 'Pidgey',
                'type' => 'Flying',
                'level' => 3,
                'stats' => json_encode(['attack' => 45, 'defense' => 40, 'hp' => 40, 'speed' => 56]),
            ],
            [
                'name' => 'Pidgeotto',
                'type' => 'Flying',
                'level' => 18,
                'stats' => json_encode(['attack' => 60, 'defense' => 55, 'hp' => 63, 'speed' => 71]),
            ],
            [
                'name' => 'Pidgeot',
                'type' => 'Flying',
                'level' => 36,
                'stats' => json_encode(['attack' => 80, 'defense' => 75, 'hp' => 83, 'speed' => 101]),
            ],
            [
                'name' => 'Rattata',
                'type' => 'Normal',
                'level' => 3,
                'stats' => json_encode(['attack' => 56, 'defense' => 35, 'hp' => 30, 'speed' => 72]),
            ],
            [
                'name' => 'Raticate',
                'type' => 'Normal',
                'level' => 20,
                'stats' => json_encode(['attack' => 81, 'defense' => 60, 'hp' => 55, 'speed' => 97]),
            ],
            
            // #021-#030
            [
                'name' => 'Spearow',
                'type' => 'Flying',
                'level' => 3,
                'stats' => json_encode(['attack' => 60, 'defense' => 30, 'hp' => 40, 'speed' => 70]),
            ],
            [
                'name' => 'Fearow',
                'type' => 'Flying',
                'level' => 20,
                'stats' => json_encode(['attack' => 90, 'defense' => 65, 'hp' => 65, 'speed' => 100]),
            ],
            [
                'name' => 'Ekans',
                'type' => 'Poison',
                'level' => 5,
                'stats' => json_encode(['attack' => 60, 'defense' => 44, 'hp' => 35, 'speed' => 55]),
            ],
            [
                'name' => 'Arbok',
                'type' => 'Poison',
                'level' => 22,
                'stats' => json_encode(['attack' => 95, 'defense' => 69, 'hp' => 60, 'speed' => 80]),
            ],
            [
                'name' => 'Pikachu',
                'type' => 'Electric',
                'level' => 10,
                'stats' => json_encode(['attack' => 55, 'defense' => 40, 'hp' => 35, 'speed' => 90]),
            ],
            [
                'name' => 'Raichu',
                'type' => 'Electric',
                'level' => 25,
                'stats' => json_encode(['attack' => 90, 'defense' => 55, 'hp' => 60, 'speed' => 110]),
            ],
            [
                'name' => 'Sandshrew',
                'type' => 'Ground',
                'level' => 8,
                'stats' => json_encode(['attack' => 75, 'defense' => 85, 'hp' => 50, 'speed' => 40]),
            ],
            [
                'name' => 'Sandslash',
                'type' => 'Ground',
                'level' => 22,
                'stats' => json_encode(['attack' => 100, 'defense' => 110, 'hp' => 75, 'speed' => 65]),
            ],
            [
                'name' => 'Nidoran♀',
                'type' => 'Poison',
                'level' => 5,
                'stats' => json_encode(['attack' => 47, 'defense' => 52, 'hp' => 55, 'speed' => 41]),
            ],
            [
                'name' => 'Nidorina',
                'type' => 'Poison',
                'level' => 16,
                'stats' => json_encode(['attack' => 62, 'defense' => 67, 'hp' => 70, 'speed' => 56]),
            ],
            // #031-#040
            [
                'name' => 'Nidoqueen',
                'type' => 'Poison',
                'level' => 32,
                'stats' => json_encode(['attack' => 92, 'defense' => 87, 'hp' => 90, 'speed' => 76]),
            ],
            [
                'name' => 'Nidoran♂',
                'type' => 'Poison',
                'level' => 5,
                'stats' => json_encode(['attack' => 57, 'defense' => 40, 'hp' => 46, 'speed' => 50]),
            ],
            [
                'name' => 'Nidorino',
                'type' => 'Poison',
                'level' => 16,
                'stats' => json_encode(['attack' => 72, 'defense' => 57, 'hp' => 61, 'speed' => 65]),
            ],
            [
                'name' => 'Nidoking',
                'type' => 'Poison',
                'level' => 32,
                'stats' => json_encode(['attack' => 102, 'defense' => 77, 'hp' => 81, 'speed' => 85]),
            ],
            [
                'name' => 'Clefairy',
                'type' => 'Normal',
                'level' => 10,
                'stats' => json_encode(['attack' => 45, 'defense' => 48, 'hp' => 70, 'speed' => 35]),
            ],
            [
                'name' => 'Clefable',
                'type' => 'Normal',
                'level' => 25,
                'stats' => json_encode(['attack' => 70, 'defense' => 73, 'hp' => 95, 'speed' => 60]),
            ],
            [
                'name' => 'Vulpix',
                'type' => 'Fire',
                'level' => 10,
                'stats' => json_encode(['attack' => 41, 'defense' => 40, 'hp' => 38, 'speed' => 65]),
            ],
            [
                'name' => 'Ninetales',
                'type' => 'Fire',
                'level' => 25,
                'stats' => json_encode(['attack' => 76, 'defense' => 75, 'hp' => 73, 'speed' => 100]),
            ],
            [
                'name' => 'Jigglypuff',
                'type' => 'Normal',
                'level' => 10,
                'stats' => json_encode(['attack' => 45, 'defense' => 20, 'hp' => 115, 'speed' => 20]),
            ],
            [
                'name' => 'Wigglytuff',
                'type' => 'Normal',
                'level' => 25,
                'stats' => json_encode(['attack' => 70, 'defense' => 45, 'hp' => 140, 'speed' => 45]),
            ],
            
            // #041-#050
            [
                'name' => 'Zubat',
                'type' => 'Poison',
                'level' => 5,
                'stats' => json_encode(['attack' => 45, 'defense' => 35, 'hp' => 40, 'speed' => 55]),
            ],
            [
                'name' => 'Golbat',
                'type' => 'Poison',
                'level' => 22,
                'stats' => json_encode(['attack' => 80, 'defense' => 70, 'hp' => 75, 'speed' => 90]),
            ],
            [
                'name' => 'Oddish',
                'type' => 'Grass',
                'level' => 5,
                'stats' => json_encode(['attack' => 50, 'defense' => 55, 'hp' => 45, 'speed' => 30]),
            ],
            [
                'name' => 'Gloom',
                'type' => 'Grass',
                'level' => 21,
                'stats' => json_encode(['attack' => 65, 'defense' => 70, 'hp' => 60, 'speed' => 40]),
            ],
            [
                'name' => 'Vileplume',
                'type' => 'Grass',
                'level' => 35,
                'stats' => json_encode(['attack' => 80, 'defense' => 85, 'hp' => 75, 'speed' => 50]),
            ],
            [
                'name' => 'Paras',
                'type' => 'Bug',
                'level' => 5,
                'stats' => json_encode(['attack' => 70, 'defense' => 55, 'hp' => 35, 'speed' => 25]),
            ],
            [
                'name' => 'Parasect',
                'type' => 'Bug',
                'level' => 24,
                'stats' => json_encode(['attack' => 95, 'defense' => 80, 'hp' => 60, 'speed' => 30]),
            ],
            [
                'name' => 'Venonat',
                'type' => 'Bug',
                'level' => 8,
                'stats' => json_encode(['attack' => 55, 'defense' => 50, 'hp' => 60, 'speed' => 45]),
            ],
            [
                'name' => 'Venomoth',
                'type' => 'Bug',
                'level' => 31,
                'stats' => json_encode(['attack' => 65, 'defense' => 60, 'hp' => 70, 'speed' => 90]),
            ],
            [
                'name' => 'Diglett',
                'type' => 'Ground',
                'level' => 8,
                'stats' => json_encode(['attack' => 55, 'defense' => 25, 'hp' => 10, 'speed' => 95]),
            ],
            
            // #051-#060
            [
                'name' => 'Dugtrio',
                'type' => 'Ground',
                'level' => 26,
                'stats' => json_encode(['attack' => 100, 'defense' => 50, 'hp' => 35, 'speed' => 120]),
            ],
            [
                'name' => 'Meowth',
                'type' => 'Normal',
                'level' => 8,
                'stats' => json_encode(['attack' => 45, 'defense' => 35, 'hp' => 40, 'speed' => 90]),
            ],
            [
                'name' => 'Persian',
                'type' => 'Normal',
                'level' => 28,
                'stats' => json_encode(['attack' => 70, 'defense' => 65, 'hp' => 65, 'speed' => 115]),
            ],
            [
                'name' => 'Psyduck',
                'type' => 'Water',
                'level' => 10,
                'stats' => json_encode(['attack' => 52, 'defense' => 48, 'hp' => 50, 'speed' => 55]),
            ],
            [
                'name' => 'Golduck',
                'type' => 'Water',
                'level' => 33,
                'stats' => json_encode(['attack' => 82, 'defense' => 78, 'hp' => 80, 'speed' => 85]),
            ],
            [
                'name' => 'Mankey',
                'type' => 'Fighting',
                'level' => 10,
                'stats' => json_encode(['attack' => 80, 'defense' => 35, 'hp' => 40, 'speed' => 70]),
            ],
            [
                'name' => 'Primeape',
                'type' => 'Fighting',
                'level' => 28,
                'stats' => json_encode(['attack' => 105, 'defense' => 60, 'hp' => 65, 'speed' => 95]),
            ],
            [
                'name' => 'Growlithe',
                'type' => 'Fire',
                'level' => 10,
                'stats' => json_encode(['attack' => 70, 'defense' => 45, 'hp' => 55, 'speed' => 60]),
            ],
            [
                'name' => 'Arcanine',
                'type' => 'Fire',
                'level' => 35,
                'stats' => json_encode(['attack' => 110, 'defense' => 80, 'hp' => 90, 'speed' => 95]),
            ],
            [
                'name' => 'Poliwag',
                'type' => 'Water',
                'level' => 5,
                'stats' => json_encode(['attack' => 50, 'defense' => 40, 'hp' => 40, 'speed' => 90]),
            ],
            // #061-#070
            [
                'name' => 'Poliwhirl',
                'type' => 'Water',
                'level' => 25,
                'stats' => json_encode(['attack' => 65, 'defense' => 65, 'hp' => 65, 'speed' => 90]),
            ],
            [
                'name' => 'Poliwrath',
                'type' => 'Water',
                'level' => 40,
                'stats' => json_encode(['attack' => 95, 'defense' => 95, 'hp' => 90, 'speed' => 70]),
            ],
            [
                'name' => 'Abra',
                'type' => 'Psychic',
                'level' => 10,
                'stats' => json_encode(['attack' => 20, 'defense' => 15, 'hp' => 25, 'speed' => 90]),
            ],
            [
                'name' => 'Kadabra',
                'type' => 'Psychic',
                'level' => 16,
                'stats' => json_encode(['attack' => 35, 'defense' => 30, 'hp' => 40, 'speed' => 105]),
            ],
            [
                'name' => 'Alakazam',
                'type' => 'Psychic',
                'level' => 35,
                'stats' => json_encode(['attack' => 50, 'defense' => 45, 'hp' => 55, 'speed' => 120]),
            ],
            [
                'name' => 'Machop',
                'type' => 'Fighting',
                'level' => 10,
                'stats' => json_encode(['attack' => 80, 'defense' => 50, 'hp' => 70, 'speed' => 35]),
            ],
            [
                'name' => 'Machoke',
                'type' => 'Fighting',
                'level' => 28,
                'stats' => json_encode(['attack' => 100, 'defense' => 70, 'hp' => 80, 'speed' => 45]),
            ],
            [
                'name' => 'Machamp',
                'type' => 'Fighting',
                'level' => 40,
                'stats' => json_encode(['attack' => 130, 'defense' => 80, 'hp' => 90, 'speed' => 55]),
            ],
            [
                'name' => 'Bellsprout',
                'type' => 'Grass',
                'level' => 5,
                'stats' => json_encode(['attack' => 75, 'defense' => 35, 'hp' => 50, 'speed' => 40]),
            ],
            [
                'name' => 'Weepinbell',
                'type' => 'Grass',
                'level' => 21,
                'stats' => json_encode(['attack' => 90, 'defense' => 50, 'hp' => 65, 'speed' => 55]),
            ],
            
            // #071-#080
            [
                'name' => 'Victreebel',
                'type' => 'Grass',
                'level' => 35,
                'stats' => json_encode(['attack' => 105, 'defense' => 65, 'hp' => 80, 'speed' => 70]),
            ],
            [
                'name' => 'Tentacool',
                'type' => 'Water',
                'level' => 5,
                'stats' => json_encode(['attack' => 40, 'defense' => 35, 'hp' => 40, 'speed' => 70]),
            ],
            [
                'name' => 'Tentacruel',
                'type' => 'Water',
                'level' => 30,
                'stats' => json_encode(['attack' => 70, 'defense' => 65, 'hp' => 80, 'speed' => 100]),
            ],
            [
                'name' => 'Geodude',
                'type' => 'Rock',
                'level' => 5,
                'stats' => json_encode(['attack' => 80, 'defense' => 100, 'hp' => 40, 'speed' => 20]),
            ],
            [
                'name' => 'Graveler',
                'type' => 'Rock',
                'level' => 25,
                'stats' => json_encode(['attack' => 95, 'defense' => 115, 'hp' => 55, 'speed' => 35]),
            ],
            [
                'name' => 'Golem',
                'type' => 'Rock',
                'level' => 40,
                'stats' => json_encode(['attack' => 120, 'defense' => 130, 'hp' => 80, 'speed' => 45]),
            ],
            [
                'name' => 'Ponyta',
                'type' => 'Fire',
                'level' => 15,
                'stats' => json_encode(['attack' => 85, 'defense' => 55, 'hp' => 50, 'speed' => 90]),
            ],
            [
                'name' => 'Rapidash',
                'type' => 'Fire',
                'level' => 40,
                'stats' => json_encode(['attack' => 100, 'defense' => 70, 'hp' => 65, 'speed' => 105]),
            ],
            [
                'name' => 'Slowpoke',
                'type' => 'Water',
                'level' => 10,
                'stats' => json_encode(['attack' => 65, 'defense' => 65, 'hp' => 90, 'speed' => 15]),
            ],
            [
                'name' => 'Slowbro',
                'type' => 'Water',
                'level' => 37,
                'stats' => json_encode(['attack' => 75, 'defense' => 110, 'hp' => 95, 'speed' => 30]),
            ],
            
            // #081-#090
            [
                'name' => 'Magnemite',
                'type' => 'Electric',
                'level' => 10,
                'stats' => json_encode(['attack' => 35, 'defense' => 70, 'hp' => 25, 'speed' => 45]),
            ],
            [
                'name' => 'Magneton',
                'type' => 'Electric',
                'level' => 30,
                'stats' => json_encode(['attack' => 60, 'defense' => 95, 'hp' => 50, 'speed' => 70]),
            ],
            [
                'name' => 'Farfetch\'d',
                'type' => 'Flying',
                'level' => 20,
                'stats' => json_encode(['attack' => 90, 'defense' => 55, 'hp' => 52, 'speed' => 60]),
            ],
            [
                'name' => 'Doduo',
                'type' => 'Flying',
                'level' => 15,
                'stats' => json_encode(['attack' => 85, 'defense' => 45, 'hp' => 35, 'speed' => 75]),
            ],
            [
                'name' => 'Dodrio',
                'type' => 'Flying',
                'level' => 31,
                'stats' => json_encode(['attack' => 110, 'defense' => 70, 'hp' => 60, 'speed' => 110]),
            ],
            [
                'name' => 'Seel',
                'type' => 'Water',
                'level' => 15,
                'stats' => json_encode(['attack' => 45, 'defense' => 55, 'hp' => 65, 'speed' => 45]),
            ],
            [
                'name' => 'Dewgong',
                'type' => 'Water',
                'level' => 34,
                'stats' => json_encode(['attack' => 70, 'defense' => 80, 'hp' => 90, 'speed' => 70]),
            ],
            [
                'name' => 'Grimer',
                'type' => 'Poison',
                'level' => 15,
                'stats' => json_encode(['attack' => 80, 'defense' => 50, 'hp' => 80, 'speed' => 25]),
            ],
            [
                'name' => 'Muk',
                'type' => 'Poison',
                'level' => 38,
                'stats' => json_encode(['attack' => 105, 'defense' => 75, 'hp' => 105, 'speed' => 50]),
            ],
            [
                'name' => 'Shellder',
                'type' => 'Water',
                'level' => 10,
                'stats' => json_encode(['attack' => 65, 'defense' => 100, 'hp' => 30, 'speed' => 40]),
            ],
            // #091-#100
            [
                'name' => 'Cloyster',
                'type' => 'Water',
                'level' => 35,
                'stats' => json_encode(['attack' => 95, 'defense' => 180, 'hp' => 50, 'speed' => 70]),
            ],
            [
                'name' => 'Gastly',
                'type' => 'Ghost',
                'level' => 10,
                'stats' => json_encode(['attack' => 35, 'defense' => 30, 'hp' => 30, 'speed' => 80]),
            ],
            [
                'name' => 'Haunter',
                'type' => 'Ghost',
                'level' => 25,
                'stats' => json_encode(['attack' => 50, 'defense' => 45, 'hp' => 45, 'speed' => 95]),
            ],
            [
                'name' => 'Gengar',
                'type' => 'Ghost',
                'level' => 40,
                'stats' => json_encode(['attack' => 65, 'defense' => 60, 'hp' => 60, 'speed' => 110]),
            ],
            [
                'name' => 'Onix',
                'type' => 'Rock',
                'level' => 20,
                'stats' => json_encode(['attack' => 45, 'defense' => 160, 'hp' => 35, 'speed' => 70]),
            ],
            [
                'name' => 'Drowzee',
                'type' => 'Psychic',
                'level' => 15,
                'stats' => json_encode(['attack' => 48, 'defense' => 45, 'hp' => 60, 'speed' => 42]),
            ],
            [
                'name' => 'Hypno',
                'type' => 'Psychic',
                'level' => 35,
                'stats' => json_encode(['attack' => 73, 'defense' => 70, 'hp' => 85, 'speed' => 67]),
            ],
            [
                'name' => 'Krabby',
                'type' => 'Water',
                'level' => 10,
                'stats' => json_encode(['attack' => 105, 'defense' => 90, 'hp' => 30, 'speed' => 50]),
            ],
            [
                'name' => 'Kingler',
                'type' => 'Water',
                'level' => 28,
                'stats' => json_encode(['attack' => 130, 'defense' => 115, 'hp' => 55, 'speed' => 75]),
            ],
            [
                'name' => 'Voltorb',
                'type' => 'Electric',
                'level' => 15,
                'stats' => json_encode(['attack' => 30, 'defense' => 50, 'hp' => 40, 'speed' => 100]),
            ],
            
            // #101-#110
            [
                'name' => 'Electrode',
                'type' => 'Electric',
                'level' => 30,
                'stats' => json_encode(['attack' => 50, 'defense' => 70, 'hp' => 60, 'speed' => 150]),
            ],
            [
                'name' => 'Exeggcute',
                'type' => 'Grass',
                'level' => 15,
                'stats' => json_encode(['attack' => 40, 'defense' => 80, 'hp' => 60, 'speed' => 40]),
            ],
            [
                'name' => 'Exeggutor',
                'type' => 'Grass',
                'level' => 35,
                'stats' => json_encode(['attack' => 95, 'defense' => 85, 'hp' => 95, 'speed' => 55]),
            ],
            [
                'name' => 'Cubone',
                'type' => 'Ground',
                'level' => 15,
                'stats' => json_encode(['attack' => 50, 'defense' => 95, 'hp' => 50, 'speed' => 35]),
            ],
            [
                'name' => 'Marowak',
                'type' => 'Ground',
                'level' => 28,
                'stats' => json_encode(['attack' => 80, 'defense' => 110, 'hp' => 60, 'speed' => 45]),
            ],
            [
                'name' => 'Hitmonlee',
                'type' => 'Fighting',
                'level' => 30,
                'stats' => json_encode(['attack' => 120, 'defense' => 53, 'hp' => 50, 'speed' => 87]),
            ],
            [
                'name' => 'Hitmonchan',
                'type' => 'Fighting',
                'level' => 30,
                'stats' => json_encode(['attack' => 105, 'defense' => 79, 'hp' => 50, 'speed' => 76]),
            ],
            [
                'name' => 'Lickitung',
                'type' => 'Normal',
                'level' => 25,
                'stats' => json_encode(['attack' => 55, 'defense' => 75, 'hp' => 90, 'speed' => 30]),
            ],
            [
                'name' => 'Koffing',
                'type' => 'Poison',
                'level' => 15,
                'stats' => json_encode(['attack' => 65, 'defense' => 95, 'hp' => 40, 'speed' => 35]),
            ],
            [
                'name' => 'Weezing',
                'type' => 'Poison',
                'level' => 35,
                'stats' => json_encode(['attack' => 90, 'defense' => 120, 'hp' => 65, 'speed' => 60]),
            ],
            
            // #111-#120
            [
                'name' => 'Rhyhorn',
                'type' => 'Rock',
                'level' => 20,
                'stats' => json_encode(['attack' => 85, 'defense' => 95, 'hp' => 80, 'speed' => 25]),
            ],
            [
                'name' => 'Rhydon',
                'type' => 'Rock',
                'level' => 42,
                'stats' => json_encode(['attack' => 130, 'defense' => 120, 'hp' => 105, 'speed' => 40]),
            ],
            [
                'name' => 'Chansey',
                'type' => 'Normal',
                'level' => 25,
                'stats' => json_encode(['attack' => 5, 'defense' => 5, 'hp' => 250, 'speed' => 50]),
            ],
            [
                'name' => 'Tangela',
                'type' => 'Grass',
                'level' => 25,
                'stats' => json_encode(['attack' => 55, 'defense' => 115, 'hp' => 65, 'speed' => 60]),
            ],
            [
                'name' => 'Kangaskhan',
                'type' => 'Normal',
                'level' => 30,
                'stats' => json_encode(['attack' => 95, 'defense' => 80, 'hp' => 105, 'speed' => 90]),
            ],
            [
                'name' => 'Horsea',
                'type' => 'Water',
                'level' => 15,
                'stats' => json_encode(['attack' => 40, 'defense' => 70, 'hp' => 30, 'speed' => 60]),
            ],
            [
                'name' => 'Seadra',
                'type' => 'Water',
                'level' => 32,
                'stats' => json_encode(['attack' => 65, 'defense' => 95, 'hp' => 55, 'speed' => 85]),
            ],
            [
                'name' => 'Goldeen',
                'type' => 'Water',
                'level' => 10,
                'stats' => json_encode(['attack' => 67, 'defense' => 60, 'hp' => 45, 'speed' => 63]),
            ],
            [
                'name' => 'Seaking',
                'type' => 'Water',
                'level' => 33,
                'stats' => json_encode(['attack' => 92, 'defense' => 65, 'hp' => 80, 'speed' => 68]),
            ],
            [
                'name' => 'Staryu',
                'type' => 'Water',
                'level' => 15,
                'stats' => json_encode(['attack' => 45, 'defense' => 55, 'hp' => 30, 'speed' => 85]),
            ],
            // #121-#130
            [
                'name' => 'Starmie',
                'type' => 'Water',
                'level' => 35,
                'stats' => json_encode(['attack' => 75, 'defense' => 85, 'hp' => 60, 'speed' => 115]),
            ],
            [
                'name' => 'Mr. Mime',
                'type' => 'Psychic',
                'level' => 30,
                'stats' => json_encode(['attack' => 45, 'defense' => 65, 'hp' => 40, 'speed' => 90]),
            ],
            [
                'name' => 'Scyther',
                'type' => 'Bug',
                'level' => 30,
                'stats' => json_encode(['attack' => 110, 'defense' => 80, 'hp' => 70, 'speed' => 105]),
            ],
            [
                'name' => 'Jynx',
                'type' => 'Ice',
                'level' => 30,
                'stats' => json_encode(['attack' => 50, 'defense' => 35, 'hp' => 65, 'speed' => 95]),
            ],
            [
                'name' => 'Electabuzz',
                'type' => 'Electric',
                'level' => 30,
                'stats' => json_encode(['attack' => 83, 'defense' => 57, 'hp' => 65, 'speed' => 105]),
            ],
            [
                'name' => 'Magmar',
                'type' => 'Fire',
                'level' => 30,
                'stats' => json_encode(['attack' => 95, 'defense' => 57, 'hp' => 65, 'speed' => 93]),
            ],
            [
                'name' => 'Pinsir',
                'type' => 'Bug',
                'level' => 30,
                'stats' => json_encode(['attack' => 125, 'defense' => 100, 'hp' => 65, 'speed' => 85]),
            ],
            [
                'name' => 'Tauros',
                'type' => 'Normal',
                'level' => 35,
                'stats' => json_encode(['attack' => 100, 'defense' => 95, 'hp' => 75, 'speed' => 110]),
            ],
            [
                'name' => 'Magikarp',
                'type' => 'Water',
                'level' => 5,
                'stats' => json_encode(['attack' => 10, 'defense' => 55, 'hp' => 20, 'speed' => 80]),
            ],
            [
                'name' => 'Gyarados',
                'type' => 'Water',
                'level' => 40,
                'stats' => json_encode(['attack' => 125, 'defense' => 79, 'hp' => 95, 'speed' => 81]),
            ],
            
            // #131-#140
            [
                'name' => 'Lapras',
                'type' => 'Water',
                'level' => 40,
                'stats' => json_encode(['attack' => 85, 'defense' => 80, 'hp' => 130, 'speed' => 60]),
            ],
            [
                'name' => 'Ditto',
                'type' => 'Normal',
                'level' => 25,
                'stats' => json_encode(['attack' => 48, 'defense' => 48, 'hp' => 48, 'speed' => 48]),
            ],
            [
                'name' => 'Eevee',
                'type' => 'Normal',
                'level' => 15,
                'stats' => json_encode(['attack' => 55, 'defense' => 50, 'hp' => 55, 'speed' => 55]),
            ],
            [
                'name' => 'Vaporeon',
                'type' => 'Water',
                'level' => 35,
                'stats' => json_encode(['attack' => 65, 'defense' => 60, 'hp' => 130, 'speed' => 65]),
            ],
            [
                'name' => 'Jolteon',
                'type' => 'Electric',
                'level' => 35,
                'stats' => json_encode(['attack' => 65, 'defense' => 60, 'hp' => 65, 'speed' => 130]),
            ],
            [
                'name' => 'Flareon',
                'type' => 'Fire',
                'level' => 35,
                'stats' => json_encode(['attack' => 130, 'defense' => 60, 'hp' => 65, 'speed' => 65]),
            ],
            [
                'name' => 'Porygon',
                'type' => 'Normal',
                'level' => 25,
                'stats' => json_encode(['attack' => 60, 'defense' => 70, 'hp' => 65, 'speed' => 40]),
            ],
            [
                'name' => 'Omanyte',
                'type' => 'Rock',
                'level' => 20,
                'stats' => json_encode(['attack' => 40, 'defense' => 100, 'hp' => 35, 'speed' => 35]),
            ],
            [
                'name' => 'Omastar',
                'type' => 'Rock',
                'level' => 40,
                'stats' => json_encode(['attack' => 60, 'defense' => 125, 'hp' => 70, 'speed' => 55]),
            ],
            [
                'name' => 'Kabuto',
                'type' => 'Rock',
                'level' => 20,
                'stats' => json_encode(['attack' => 80, 'defense' => 90, 'hp' => 30, 'speed' => 55]),
            ],
            
            // #141-#151
            [
                'name' => 'Kabutops',
                'type' => 'Rock',
                'level' => 40,
                'stats' => json_encode(['attack' => 115, 'defense' => 105, 'hp' => 60, 'speed' => 80]),
            ],
            [
                'name' => 'Aerodactyl',
                'type' => 'Rock',
                'level' => 45,
                'stats' => json_encode(['attack' => 105, 'defense' => 65, 'hp' => 80, 'speed' => 130]),
            ],
            [
                'name' => 'Snorlax',
                'type' => 'Normal',
                'level' => 45,
                'stats' => json_encode(['attack' => 110, 'defense' => 65, 'hp' => 160, 'speed' => 30]),
            ],
            [
                'name' => 'Articuno',
                'type' => 'Ice',
                'level' => 50,
                'stats' => json_encode(['attack' => 85, 'defense' => 100, 'hp' => 90, 'speed' => 85]),
            ],
            [
                'name' => 'Zapdos',
                'type' => 'Electric',
                'level' => 50,
                'stats' => json_encode(['attack' => 90, 'defense' => 85, 'hp' => 90, 'speed' => 100]),
            ],
            [
                'name' => 'Moltres',
                'type' => 'Fire',
                'level' => 50,
                'stats' => json_encode(['attack' => 100, 'defense' => 90, 'hp' => 90, 'speed' => 90]),
            ],
            [
                'name' => 'Dratini',
                'type' => 'Dragon',
                'level' => 15,
                'stats' => json_encode(['attack' => 64, 'defense' => 45, 'hp' => 41, 'speed' => 50]),
            ],
            [
                'name' => 'Dragonair',
                'type' => 'Dragon',
                'level' => 30,
                'stats' => json_encode(['attack' => 84, 'defense' => 65, 'hp' => 61, 'speed' => 70]),
            ],
            [
                'name' => 'Dragonite',
                'type' => 'Dragon',
                'level' => 55,
                'stats' => json_encode(['attack' => 134, 'defense' => 95, 'hp' => 91, 'speed' => 80]),
            ],
            [
                'name' => 'Mewtwo',
                'type' => 'Psychic',
                'level' => 70,
                'stats' => json_encode(['attack' => 110, 'defense' => 90, 'hp' => 106, 'speed' => 130]),
            ],
            [
                'name' => 'Mew',
                'type' => 'Psychic',
                'level' => 70,
                'stats' => json_encode(['attack' => 100, 'defense' => 100, 'hp' => 100, 'speed' => 100]),
            ]

        ];

        foreach ($pokemonData as $pokemon) {
            Pokemon::create($pokemon);
        }

        $this->command->info('The first 151 Pokemon have been created successfully!');
    }
}