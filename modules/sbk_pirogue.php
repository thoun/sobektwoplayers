<?php

class Pirogue
{	
	public static function setup($game, $isExpansion) {
		$tokens = [
			3, 3, // take extra turn
			7, 7, // add 2 scarabs
			5,    // take all corruption to hand
			1, 2, // 1 or 2 corruption
			6,    // 7 points
			8, 8, // 2 points + take deben
			9,    // look at 2 deben and take 1
			4, 4, // force pick
		];

		if ($isExpansion) {
			$tokens = array_merge($tokens, [
				10, // discard deben
				11, // add 2 scarabs
			]);
		}

		shuffle($tokens);
		
		$sql = "INSERT INTO pirogue (`ability`, `location`, `slot`) VALUES ";
		
		// 5 go on slots
		for ($i = 0; $i < 5; $i++) {
			$sql .= "($tokens[$i], 'slot', $i), ";
		}
		
		// Rest go in bag
		for ($i = 5; $i < 13; $i++) {
			$sql .= "($tokens[$i], 'bag', NULL) ";
			if ($i < 12) {
				$sql .= ',';
			}
		}
		
		$game->DbQuery( $sql );
	}
	
	public static function getSlots($game) {
		$all = $game->getObjectList( "SELECT * FROM pirogue WHERE location = 'slot'" );
		return $all;
	}
	
	public static function getBoard($game) {
		$all = $game->getObject( "SELECT * FROM pirogue WHERE location = 'board' LIMIT 1" );
		return $all;
	}
	
	public static function getSoldSets($game, $player_id) {
		$all = $game->getObjectList( "SELECT * FROM pirogue WHERE location = 'soldset' AND player_id = $player_id" );
		return $all;
	}
	
	public static function getSlot($game, $slot) {
		return $game->getObject( "SELECT * FROM pirogue WHERE location = 'slot' AND slot = $slot" );
	}
	
	public static function getOwned($game, $player_id) {
		return $game->getObjectList( "SELECT * FROM pirogue WHERE location = 'player' AND player_id = $player_id" );
	}
	
	public static function get($game, $pirogue_id) {
		return $game->getObject( "SELECT * FROM pirogue WHERE pirogue_id = $pirogue_id" );
	}
	
	public static function getBag($game) {
		$all = $game->getObjectList( "SELECT * FROM pirogue WHERE location = 'bag'" );
		shuffle($all);
		return $all;
	}
	
	public static function getAll($game) {
		$all = $game->getObjectList( "SELECT * FROM pirogue WHERE location != 'bag'" );
		return $all;
	}
}