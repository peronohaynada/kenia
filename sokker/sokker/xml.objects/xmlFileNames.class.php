<?php
/**
 * File names class
 */

class XMLFileNames {
	private $teamID;
	
	public function setTeamID($teamID) {
		$this->teamID = $teamID;
	}
	
	public function getTeam() {
		return "team-".$this->teamID.".xml";
	}
	
	public function getPlayer($playerID) {
		return "player-$playerID.xml";
	}
	
	public function getPlayers() {
		return "players-".$this->teamID.".xml";
	}
	
	public function getJuniors() {
		return "juniors.xml";
	}
	
	public function getTrainers() {
		return "trainers.xml";
	}
	
	public function getReports() {
		return "reports.xml";
	}
	
	public function getVars() {
		return "vars.xml";
	}
	
	public function getTransfers() {
		return "transfers.xml";
	}
	
	public function getCountry($countryID) {
		return "country-$countryID.xml";
	}
	
	public function getCountries() {
		return "countries.xml";
	}
	
	public function getRegion($regionID) {
		return "region-$regionID.xml";
	}
	
	public function getRegions($countryID) {
		return "regions-$countryID.xml";
	}
	
	public function getLeague($leagueID) {
		return "league-$leagueID.xml";
	}
	
	public function getLeagueByCountry($countryID, $division, $number) {
		return "league-$countryID-$division-$number.xml";
	}
	
	public function getMatch($matchID) {
		return "match-$matchID.xml";
	}
	
	public function getMatchesTeamID($teamID) {
		return "matches-team-$teamID.xml";
	}
	
	public function getMatchesLeague($leagueID) {
		return "matches-league-$leagueID.xml";
	}
	
	//los comentados es porque no estan soportados aun.
	public function getNombresXmlIndependientes() {
		$xmlNames = array();
		
		//$xmlNames [] = $this->getTeam();
		//$xmlNames [] = $this->getPlayers();
		$xmlNames [] = $this->getJuniors();
		//$xmlNames [] = $this->getTrainers();
		//$xmlNames [] = $this->getReports(); // No se entiende proposito del contenido
		//$xmlNames [] = $this->getVars();
		//$xmlNames [] = $this->getTransfers();
		//$xmlNames [] = $this->getCountries(); // No vale la pena descargarlo todas las veces
		
		return $xmlNames;
	}
}