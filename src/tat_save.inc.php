<?php
require_once 'config.inc.php';
require_once 'classes/dbCon.class.php';
require_once 'classes/time.class.php';

//Unterbindet die Fehler bei falscher Zeiteingabe
error_reporting(E_STRICT);

//Initialisierung der Klassen MySql u. Time
$mydb 			=	new DB_MySQL($host, $dbname, $user, $pw);
$mytime			=	new timestamp();

//Daten des Formulars abfangen mit $_POST
$datepicker		=	$_POST['datepicker'];
$worker				=	$_POST['worker'];
$client				=	$_POST['client'];
$workplace		= 	utf8_decode($_POST['workplace']);
$project			=	$_POST['project'];
$foodoverall		=	$_POST['foodoverall'];
$hinfahrt_1		=	$_POST['hinfahrt_1'];
$hinfahrt_2		=	$_POST['hinfahrt_2'];
$zeit_1				=	$_POST['zeit_1'];
$zeit_2				=	$_POST['zeit_2'];
$pause_1			=	$_POST['pause_1'];
$pause_2			=	$_POST['pause_2'];
$rueckfahrt_1	=	$_POST['rueckfahrt_1'];
$rueckfahrt_2	=	$_POST['rueckfahrt_2'];
$wagen				=	$_POST['wagen'];
$hotelgarni		=	$_POST['hotelgarni'];
$rechnungstext	=	$_POST['rechnungstext'];
$workAction		=	$_POST['workAction'];
$workflow_ID 	=	$_POST['workflow_ID'];
$art_1_1			=	$_POST['art_1_1'];
$art_1_2			=	$_POST['art_1_2'];
$art_2_1			=	$_POST['art_2_1'];
$art_2_2			=	$_POST['art_2_2'];
$art_3_1			=	$_POST['art_3_1'];
$art_3_2			=	$_POST['art_3_2'];
$kilometer			=	$_POST['kilometer'];

//Datum in einen MySQL String umwandeln
$datum_1	=	$mytime->timestamp_german2mysql($datepicker);

//Uhrzeiten in Sekunden umrechnen
$hin_1		=	$mytime->secondsR($hinfahrt_1);
$hin_2		=	$mytime->secondsR($hinfahrt_2);
$z_1			=	$mytime->secondsR($zeit_1);
$z_2			=	$mytime->secondsR($zeit_2);
$p_1			=	$mytime->secondsR($pause_1);
$p_2			=	$mytime->secondsR($pause_2);
$rb_1		=	$mytime->secondsR($rueckfahrt_1);
$rb_2		=	$mytime->secondsR($rueckfahrt_2);
$notthere 	=  ( ($hin_2 - $hin_1) + ($z_2 - $z_1) +  ($rb_2 - $rb_1) ) /60 / 60;



/*
 * Wird nicht mehr ben�tigt zur not noch im code
 * 
//Zeiten umrechnen in MySQL Format
$hinf1		=	$mytime->timestamp_time2mysql($hinfahrt_1);
$hinf2		=	$mytime->timestamp_time2mysql($hinfahrt_2);
$zeit1	 	=	$mytime->timestamp_time2mysql($zeit_1);
$zeit2		=	$mytime->timestamp_time2mysql($zeit_2);
$pause1		=	$mytime->timestamp_time2mysql($pause_1);
$pause2		=	$mytime->timestamp_time2mysql($pause_2);
$rueck1		=	$mytime->timestamp_time2mysql($rueckfahrt_1);
$rueck2		=	$mytime->timestamp_time2mysql($rueckfahrt_2);
*/

$l_arr[$art_1_1] += $art_1_2;
$l_arr[$art_2_1] += $art_2_2;
$l_arr[$art_3_1] += $art_3_2;

if($kilometer >= 50  && $notthere >= 24 && $foodoverall == 1){
		$notthere	= 24;
		}
		elseif($kilometer >= 50 && $notthere >= 14 && $foodoverall == 1){
		$notthere	= 12;
		}
		elseif($kilometer >= 50 && $notthere >= 8 && $foodoverall == 1){
		$notthere = 6;
		}
		else{
		$notthere = 0;
		}
		
if($hotelgarni == 1 && $notthere != 0){
		$notthere = $notthere - 4.50;
		}
		 

if($project == ""){
$project = 0;
}


		if($workAction == 1)
		{
			//Query neue T�tigkeit
			$l_query = "INSERT INTO synetics_data (synetics_data_date,synetics_data_client,
					synetics_data_city,synetics_data_outjourneyex,synetics_data_outjourneyto,synetics_data_worktimefrom,
					synetics_data_worktimeto,synetics_data_pause,
					synetics_data_wtpause,synetics_data_whichcar,synetics_data_text,
					synetics_data_km,
					synetics_data_returnjourneyex,synetics_data_returnjourneyto,synetics_data_system_id,
					synetics_data_projects_id,synetics_data_foodoverall";
			
			foreach($l_arr AS $l_key => $l_val){
				$l_query .= ",".$l_key;
			}
			$l_query .= ") ";
			$l_query .= "VALUES ('$datum_1','$client','$workplace','$hin_1','$hin_2',
					'$z_1','$z_2','$p_1','$p_2','$wagen','$rechnungstext','$kilometer',
					'$rb_1','$rb_2','$worker','$project' ,'$notthere'";
			
			foreach($l_arr AS $l_key => $l_val){
				$l_query .= ",'$l_val'";
			}
			$l_query .= ")";
		
			$mydb->query($l_query);
                        
                        var_dump($l_query);
		}
		elseif($workAction == 2)
		{
			//Query Edit T�tigkeit
			$l_query_edit = "UPDATE synetics_data SET synetics_data_date=$datum_1,synetics_data_client=$client,
					synetics_data_city='$workplace',synetics_data_outjourneyex=$hin_1,synetics_data_outjourneyto=$hin_2,
					synetics_data_worktimefrom=$z_1,synetics_data_worktimeto=$z_2,
					synetics_data_pause=$p_1,synetics_data_wtpause=$p_2,synetics_data_whichcar=$wagen,
					synetics_data_text='$rechnungstext',
					synetics_data_km=$kilometer,
					synetics_data_returnjourneyex=$rb_1,synetics_data_returnjourneyto=$rb_2,
					synetics_data_projects_id=$project, synetics_data_foodoverall=$notthere";
			
			foreach($l_arr AS $l_key => $l_val){
				$l_query_edit .= ",".$l_key."=".$l_val;
			}
			$l_query_edit .= " WHERE synetics_data_ID = '$workflow_ID'";
			
			$mydb->query($l_query_edit);
		}
		else 
		{
			$mydb->query("DELETE FROM synetics_data WHERE synetics_data_ID ='$workflow_ID'");			
		}

?>
