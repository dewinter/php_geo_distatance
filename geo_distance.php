<?php
/**
* Entfernung berechnen
*
* Die Funktion berechnet die Entfernung zwischen zwei jeweils
* durch Laengen- und Breitengrad definierten geographischen Punkten
*
* @access public
* @param float $longitude1 Laengengrad des ersten Punktes
* @param float $latitude1 Breitengrad des ersten Punktes
* @param float $longitude2 Laengengrad des zweiten Punktes
* @param float $latitude2 Breitengrad des zweiten Punktes
* @return float Entfernung zwischen den beiden Punkten in km
*/
function get_distance($longitude1, $latitude1, $longitude2, $latitude2){
	$pi = 3.1415926535898;
	$radius = 6371.0; // ist die Erde doch ne Scheibe?
	$psm60 = $radius * 2.0 * $pi / 360.0; // 60 pseudo Seemeilen
	$deg2rad = $pi / 180.0;
	$rad2deg = 180.0 / $pi;
	$correction = 0.000001; // etwas Platt die Dame

	//Nord- und Suedpol nicht verwenden
	//*********************************
	//Startpunkt
	if (abs($latitude1) == 90.0){
		if ($latitude1 > 0){
			$latitude1 -= $correction;
		}
		else
		{
			$latitude1 += $correction;
		}
	}
	//Endpunkt
	if (abs($latitude2) == 90.0){
		if ($latitude2 > 0){
			$latitude2 -= $correction;
		}
		else
		{
			$latitude2 += $correction;
		}
	}

	//Keine Antipoden-Punkte verwenden
	//********************************
	if (($latitude1 == -$latitude2) && (abs($longitude1 - $longitude2) == 180.0)){
		if ($latitude1 > 0){
			$latitude1 -= $correction;
		}
		else
		{
			$latitude1 += $correction;
		}
		return $pi * $radius; // halber Kugelumfang
	}

	//gleiche Länge führt zur Abkürzung
	//*********************************
	if ($longitude1 == $longitude2){
		//Meridian-Teilstück
		return $deg2rad * $radius * abs($latitude1 - $latitude2);
	}

	//gleiche Breite ist ok
	//*********************
	$dlon = $deg2rad * ($longitude2 - $longitude1);
	$lat0 = $deg2rad * $latitude1;
	$lat1 = $deg2rad * $latitude2;

	//Entfernung ermitteln
	//********************
	$drad = acos(sin($lat0) * sin($lat1) + cos($lat0) * cos($lat1) * cos($dlon));
	return $psm60 * ($drad / $deg2rad); //konvertieren von Grad nach Länge
}
