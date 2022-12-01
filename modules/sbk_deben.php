<?php

class Deben
{	
	public static function setup() {
		$sql = "INSERT INTO deben (`value`) VALUES 
			(3), (3), (3), 
			(4), (4), 
			(5), (5), 
			(6), (6), 
			(7), (7), 
			(8), 
			(9);";
		
		SobekTwoPlayers::DbQuery( $sql );
	}
	
	public static function getBag() {
		$deck = SobekTwoPlayers::getObjectList( "SELECT * FROM deben WHERE location = 'bag'" );
		shuffle($deck);
		return $deck;
	}
	
	public static function getOwned( $player_id ) {
		$deck = SobekTwoPlayers::getObjectList( "SELECT * FROM deben WHERE location = 'player' AND player_id = $player_id" );
		return $deck;
	}
	
	public static function draw( $player_id, $deben = NULL ) {
		if (! isset($deben)) {
			$bag = self::getBag();
			if (count($bag) == 0) return null;
			$deben = array_pop($bag);
		}
		
		SobekTwoPlayers::DbQuery( "UPDATE deben SET location = 'player', player_id = $player_id WHERE deben_id = $deben[deben_id] LIMIT 1" );
		$deben["location"] = 'player';
		$deben["player_id"] = $player_id;
		return $deben;
	}
}