<?php
// patchread3.php

include 'header.html.php';


echo "<h3><center>" . basename($_SERVER['PHP_SELF']) . "2nd release" . "</center></h3>" ;

$fd=fopen("2ndreleaseB.syx","r");

// strip & denibble
fseek($fd,5);
while(ftell($fd)<8165)
{
       $i1=ord(fgetc($fd));
       $i2=ord(fgetc($fd));
	   
       $sounds = $sounds . sprintf("%c",($i2*16)+$i1);  
	   // sprintf returns a formatted string
	   // c* argument formats the string's characters according to their ASCII value
}  // end while	   
fclose($fd);


// split $sounds into 40 equal patches
echo 'Length of stripped input file: '.strlen($sounds).'<br>';
// echo 'sounds length/40 '.strlen($sounds)/40 .'<br>';

// echo "strlen of sounds / 40: " . strlen($sounds)/40 . "<br>";
$bank = str_split($sounds, 102);

echo 'Verify number of elements in bank: '.count($bank).'<br>';

// echo 'Verify output of 1st patch: ' . $bank[0].'<br><br>';

// set patch nmber here
$patchnum = 1;  // set a human readable number (e.g. patch "1" will return midi patch 0)
$patchnum--;
$pcb  = $bank[$patchnum];


function neg63($in)  {  // returns correct Ensoniq parameter values correcting for twos-complement
	$out=(($in&0x80)?-($in^0xff)-1:$in )>>1;
	return $out;
} // end function neg63()

/*    this modulators function works for screwed up output from the LFO mos source bits
function modulators($mod_num)  {  // returns name of mod source based on numerical value
		switch ($mod_num) {
			case 00:  
				$mod = "LFO1";
				break;
			case 01:  
				$mod = "LFO2";
				break;				
			case 02:  
				$mod = "LFO3";
				break;
			case 03:  
				$mod = "ENV1";
				break;		   
			case 04:  
				$mod = "ENV2";
				break;	
			case 11:  
				$mod = "ENV3";
				break;
			case 12:  
				$mod = "ENV4";
				break;
			case 13:  
				$mod = "VEL";
				break;	
			case 20:  
				$mod = "VEL-X";
				break;	
			case 21:  
				$mod = "KBD";
				break;	
			case 22:  
				$mod = "KBD2";
				break;	
			case 23:  
				$mod = "WHEEL";
				break;	
			case 30:  
				$mod = "PEDAL";
				break;	
			case 31:  
				$mod = "XCTRL";
				break;	
			case 32:  
				$mod = "PRESS";
				break;	
			case 33:  
				$mod = "*OFF*";
				break;   
	   }
	return $mod;
} 	   
*/



function modulators($mod_num)  {  // returns name of mod source based on numerical value
		switch ($mod_num) {
			case 0:  
				$mod = "LFO1";
				break;
			case 1:  
				$mod = "LFO2";
				break;				
			case 2:  
				$mod = "LFO3";
				break;
			case 3:  
				$mod = "ENV1";
				break;		   
			case 4:  
				$mod = "ENV2";
				break;	
			case 5:  
				$mod = "ENV3";
				break;
			case 6:  
				$mod = "ENV4";
				break;
			case 7:  
				$mod = "VEL";
				break;	
			case 8:  
				$mod = "VEL-X";
				break;	
			case 9:  
				$mod = "KBD";
				break;	
			case 10:  
				$mod = "KBD2";
				break;	
			case 11:  
				$mod = "WHEEL";
				break;	
			case 12:  
				$mod = "PEDAL";
				break;	
			case 13:  
				$mod = "XCTRL";
				break;	
			case 14:  
				$mod = "PRESS";
				break;	
			case 15:  
				$mod = "*OFF*";
				break;   
	   }
	return $mod;
} 	   


// Patch name formatted
$patchname = substr($pcb, 0, 6);
echo "Current Patch Name: <b>" . preg_replace("/[^a-zA-Z0-9\s]/", "", $patchname) . "</b><br>" ;
echo "Current Patch Number: <b>" . ($patchnum + 1) . "</b><br><br>";



 echo "<br><br>" ;
// Patch name and number
echo "<table border=\"1\" align=\"center\" width=\"80%\"><tr>";
echo "<TH>Patch Name: <b>" . preg_replace("/[^a-zA-Z0-9\s]/", "", $patchname) . "</b></TH>";
echo "<Td>Patch Number: :" . ($patchnum + 1) . "</Td>";
echo "</tr></table>";
	
// OSC values Table	
echo "<table border=\"1\" align=\"center\" width=\"80%\">";
	for ( $osc = 0; $osc <= 2; $osc++ ) {
		echo "<TR>";
		echo "<TH><b>OSC " 	. ($osc+1) . "</b></TH>";
			echo "<td> OSC " . ($osc+1) . " Octave : " ;
		// get oct from semitone	
			$semi = $pcb[6+40+12+$osc*10+0]  ;
			$semi = ord($semi);
			$semi = $semi &0x7f;  
		// verify that the bitmask does not screw up the case statement.  
				
			switch ($semi) {
 			
				case (($semi < 12)) :
					$oct = "-3";
					break;  
				case ( $semi < 24  ) :
					$oct = "-2";	
					break;  
				case ( $semi < 36  ) :
					$oct = "-1";	
					break;  
				case ( $semi < 48 ) :
					$oct = "+0";
					break;  					
				case ( $semi < 60 ) :
					$oct = "+1";
					break;  					
				case ($semi < 72 ) :
					$oct = "+2";
					break;  					
				case ( $semi < 84  ) :
					$oct = "+3";
					break;  
				case ( $semi < 96 ) :
					$oct = "+4";
					break; 	
				case ( $semi < 109 ) :
					$oct = "+5";					
					break; 	
				default:
					// echo "Bring lots of underwear!";
					$oct = "-3";
					break;
					
			}
			
			echo "<font color=\"red\">";
			echo   $oct;
			echo "</font>";
			// echo 	" semi: " . $semi; //for checking total semitones
			echo "</td>";
		// semitone
			echo "<td> OSC " . ($osc+1) . " Semitone : ";
			$semi = $semi % 12 ;
			echo "<font color=\"red\">";
			echo ($semi) ;
			echo "</font>";
			echo "</td>" ;  // outstanding issue: are values of Oct 5 + 12 semitones valid?
		// fine
		echo "<td> OSC " . ($osc+1) . " Fine Tune : "  ;
		$fine = $pcb[6+40+12+$osc*10+1];
		$fine = ord($fine) ;
		echo "<font color=\"red\">";
		echo  ($fine / 8);
		echo "</font>";		
		echo  "</td>";
		// osc wave
		echo "<td> OSC " . ($osc+1) . " Wave: ";
		$wave_loc = $pcb[6+40+12+$osc*10+5];
		$wave_loc = ord($wave_loc) ;
		
		
		switch ($wave_loc) {
			case 0 : 
			$wave= " SAW"; 
			  break ;
			case 1 : 
			$wave= " BELL"; 
			  break ;
			case 2 : 
			$wave= " SINE"; 
			  break ;
			case 3 : 
			$wave= " SQUARE"; 
			  break ;
			case 4 : 
			$wave= " PULSE"; 
			  break ;
			case 5 : 
			$wave= " NOISE1"; 
			  break ;
			case 6 : 
			$wave= " NOISE2"; 
			  break ;
			case 7 : 
			$wave= " NOISE3"; 
			  break ;
			case 8 : 
			$wave= " BASS"; 
			  break ;
			case 9 : 
			$wave= " PIANO"; 
			  break ;
			case 10 : 
			$wave= " ELPNO"; 
			  break ;
			case 11 : 
			$wave= " VOICE1"; 
			  break ;
			case 12 : 
			$wave= " VOICE2"; 
			  break ;
			case 13 : 
			$wave= " KICK"; 
			  break ;
			case 14 : 
			$wave= " REED"; 
			  break ;
			case 15 : 
			$wave= " ORGAN"; 
			  break ;
			case 16 : 
			$wave= " SYNTH1"; 
			  break ;
			case 17 : 
			$wave= " SYNTH2"; 
			  break ;
			case 18 : 
			$wave= " SYNTH3"; 
			  break ;
			case 19 : 
			$wave= " FORMT1"; 
			  break ;
			case 20 : 
			$wave= " FORMT2"; 
			  break ;
			case 21 : 
			$wave= " FORMT3"; 
			  break ;
			case 22 : 
			$wave= " FORMT4"; 
			  break ;
			case 23 : 
			$wave= " FORMT5"; 
			  break ;
			case 24 : 
			$wave= " PULSE2"; 
			  break ;
			case 25 : 
			$wave= " SQR2"; 
			  break ;
			case 26 : 
			$wave= " 4OCTS"; 
			  break ;
			case 27 : 
			$wave= " PRIME"; 
			  break ;
			case 28 : 
			$wave= " BASS2"; 
			  break ;
			case 29 : 
			$wave= " EPNO2"; 
			  break ;
			case 30 : 
			$wave= " OCTAVE"; 
			  break ;
			case 31 : 
			$wave= " OCT+5"; 
			  break ;
			case 32 : 
			$wave= " SAW2"; 
			  break ;
			case 33 : 
			$wave= " TRIANG"; 
			  break ;
			case 34 : 
			$wave= " REED2"; 
			  break ;
			case 35 : 
			$wave= " REED3"; 
			  break ;
			case 36 : 
			$wave= " GRIT1"; 
			  break ;
			case 37 : 
			$wave= " GRIT2"; 
			  break ;
			case 38 : 
			$wave= " GRIT3"; 
			  break ;
			case 39 : 
			$wave= " GLINT1"; 
			  break ;
			case 40 : 
			$wave= " GLINT2"; 
			  break ;
			case 41 : 
			$wave= " GLINT3"; 
			  break ;
			case 42 : 
			$wave= " CLAV"; 
			  break ;
			case 43 : 
			$wave= " BRASS"; 
			  break ;
			case 44 : 
			$wave= " STRING"; 
			  break ;
			case 45 : 
			$wave= " DIGIT1"; 
			  break ;
			case 46 : 
			$wave= " DIGIT2"; 
			  break ;
			case 47 : 
			$wave= " BELL2"; 
			  break ;
			case 48 : 
			$wave= " ALIEN"; 
			  break ;
			case 49 : 
			$wave= " BREATH"; 
			  break ;
			case 50 : 
			$wave= " VOICE3"; 
			  break ;
			case 51 : 
			$wave= " STEAM"; 
			  break ;
			case 52 : 
			$wave= " METAL"; 
			  break ;
			case 53 : 
			$wave= " CHIME"; 
			  break ;
			case 54 : 
			$wave= " BOWING"; 
			  break ;
			case 55 : 
			$wave= " PICK1"; 
			  break ;
			case 56 : 
			$wave= " PICK2"; 
			  break ;
			case 57 : 
			$wave= " MALLET"; 
			  break ;
			case 58 : 
			$wave= " SLAP"; 
			  break ;
			case 59 : 
			$wave= " PLINK"; 
			  break ;
			case 60 : 
			$wave= " PLUCK"; 
			  break ;
			case 61 : 
			$wave= " PLUNK"; 
			  break ;
			case 62 : 
			$wave= " CLICK"; 
			  break ;
			case 63 : 
			$wave= " CHIFF"; 
			  break ;
			case 64 : 
			$wave= " THUMP"; 
			  break ;
			case 65 : 
			$wave= " LOGDRM"; 
			  break ;
			case 66 : 
			$wave= " KICK2"; 
			  break ;
			case 67 : 
			$wave= " SNARE"; 
			  break ;
			case 68 : 
			$wave= " TOMTOM"; 
			  break ;
			case 69 : 
			$wave= " HI-HAT"; 
			  break ;
			case 70 : 
			$wave= " DRUMS1"; 
			  break ;
			case 71 : 
			$wave= " DRUMS2"; 
			  break ;
			case 72 : 
			$wave= " DRUMS3"; 
			  break ;
			case 73 : 
			$wave= " DRUMS4"; 
			  break ;
			case 74 : 
			$wave= " DRUMS5"; 
				break ;
			case ($wave_loc > 74) :
				$wave = "WAVE " . $wave_loc;
				break;
			default:
				$wave =  "Bring lots of underwear!";
				break;  
		}
		
		echo "<font color=\"red\">";
		echo $wave;
		echo "</font>";
		echo "</td>";
		// end osc wave
		
		
			echo "</tr>\n<tr>"; 
	// Bottom Row
			echo "<td>&nbsp;</td>";		
		
		// Osc mod 1 source
		echo "<td> OSC" ;
		$osc_mod_1 = $pcb[(6+40+12+$osc*10+2)]; // 1 nyble = 16 mod source amounts
		$osc_mod_1 = ord($osc_mod_1) ;
		$osc_mod_1 =  $osc_mod_1  &0x0f;
		echo 	($osc+1) . " Mod1 Source: " ;
		echo "<font color=\"red\">";
		echo modulators($osc_mod_1);
		echo "</font>";
		echo "</td>";
		
		// OSC mod 1 amount
		$osc_mod_1_AMT = $pcb[(6+40+12+$osc*10+3)];
		$osc_mod_1_AMT = ord($osc_mod_1_AMT);
		echo "<td> OSC" . ($osc+1) . " Mod1 Level: " ;
		echo "<font color=\"red\">";
		echo neg63($osc_mod_1_AMT) ;
		echo "</font>";		
		echo "</td>";
		
		// OSC mod 2 source	
		echo "<td> OSC" ;
		$osc_mod_2 = $pcb[(6+40+12+$osc*10+2)]; // 1 nyble = 16 mod source amounts
		$osc_mod_2 = ord($osc_mod_2) ;
		$osc_mod_2 =  $osc_mod_2  >> 4;
		echo 	($osc+1) . " Mod2 Source: " ;
		echo "<font color=\"red\">";
		echo modulators($osc_mod_2) ;
		echo "</font>";	
		echo "</td>";
		
		// mod 2 amount
		$osc_mod_2_AMT = $pcb[(6+40+12+$osc*10+4)];
		$osc_mod_2_AMT = ord($osc_mod_2_AMT);
		echo "<td> OSC" . ($osc+1) . " Mod2 Level: " ;
		echo "<font color=\"red\">";
		echo neg63($osc_mod_2_AMT);
		echo "</font>";			
		// echo  " - position: " . (6+40+12+$osc*10+4); // for debugging current possition in PCB
		echo "</td>";
			
		echo "</TR>";
	}
	echo "</table>";	
	
// echo "<br>" ;

// DCA 1-3 values Table	
echo "<table border=\"1\" align=\"center\" width=\"80%\">";
	for ( $dca = 0; $dca <= 2; $dca++ ) {
		echo "<TR>";  // TOP ROW
		// Row Identification Column (DCA)
		echo "<th><b>DCA " 	. ($dca+1) . "</b></th>";
		// DCA Level
		$dca_level = $pcb[(6+40+12+$dca*10+6)];
		$dca_level = ord($dca_level);
		$dca_level = $dca_level >>1;  // shift over empty bit
		$dca_level = $dca_level &0x3f; // mask away (hide) left-most DCA enable "switch" bit					
		echo "<TD colspan=2>DCA" 	. ($dca+1) . " Volume Level: " ;
		echo "<font color=\"red\">";
		echo $dca_level ;
		echo "</font>";		
		echo  "</td>";
			
		// DCA Enable (ON/OFF)
		echo "<TD colspan=2>DCA" 	. ($dca+1) . " Enable: ";		
		$dca_enable = $pcb[(6+40+12+$dca*10+6)];
		$dca_enable = ord($dca_enable);
		$dca_enable = $dca_enable >>7;
		echo "<font color=\"red\">";
		echo ($dca_enable == 1) ? "ON" : "OFF";				
		echo "</font>";	
		echo  "</td>";
					
	echo "</tr><tr>";  // BOTTOM ROW
		// empty spacer cell
		echo "<td>&nbsp;</td>";
		// DCA Mod Source 1
		echo "<td>DCA" . ($dca+1) . " Mod Source 1: " ;
		$dca_mod_1 = $pcb[(6+40+12+$dca*10+7)]; // 1 nyble = 16 mod source amounts
		$dca_mod_1 = ord($dca_mod_1) ;
		$dca_mod_1 =  $dca_mod_1  &0x0f;
		echo "<font color=\"red\">";
		echo 	modulators($dca_mod_1) ;
		echo "</font>";	
		echo  "</td>";
		
		// dca mod amount 1
		$dca_Mod_Amt1 = $pcb[(6+40+12+$dca*10+8)];
		$dca_Mod_Amt1 = ord($dca_Mod_Amt1);
		$dca_Mod_Amt1 = neg63($dca_Mod_Amt1);
		echo "<td>DCA" . ($dca+1) . " Mod1 Amount: " ;
		echo "<font color=\"red\">";
		echo  $dca_Mod_Amt1 ;
		echo "</font>";
		echo "</td>";
		
		// DCA Mod Source 2
		echo "<td>DCA" . ($dca+1) . " Mod Source 2: " ;
		$dca_mod_2 = $pcb[(6+40+12+$dca*10+7)]; // 1 nyble = 16 mod source amounts
		$dca_mod_2 = ord($dca_mod_2) ;
		$dca_mod_2 =  $dca_mod_2  >> 4;
		echo "<font color=\"red\">";
		echo 	modulators($dca_mod_2) ;
		echo "</font>";
		echo  "</td>";
			
		// dca mod amount 2
		$dca_Mod_Amt2 = $pcb[(6+40+12+$dca*10+9)];
		$dca_Mod_Amt2 = ord($dca_Mod_Amt2);
		$dca_Mod_Amt2 = neg63($dca_Mod_Amt2);
		echo "<td>DCA" . ($dca+1) . " Mod2 Amount: " ;
		echo "<font color=\"red\">";
		echo $dca_Mod_Amt2 ;
		echo "</font>";	
		echo "</td>";
		
		echo "</TR>";
	
	} // end loop for cycling thru 3 DCAs
	echo "</table>";  
// end DCA values
	

// echo "<br>" ;


// filter
	// Misc - row top, col-1
	echo "<table border=\"1\" align=\"center\" width=\"80%\"><tr>";
	// Row Identification Column (FILTER)
		echo "<TH><b>FILTER </b></TH>";
		
		// filter freq - row top, col-2
		echo "<td>Filter Frequency: ";
		$filter_freq = $pcb[89];
		$filter_freq = ord($filter_freq);
		$filter_freq = $filter_freq & 0x3f;
		echo "<font color=\"red\">" .$filter_freq . "</font>";
		echo "</td>";
		
		// Filter Resonance (Q value)  - row top, col-3
		echo "<td colspan=\"2\">Filter Resonance (Q value): ";
		$filter_res = $pcb[90];
		$filter_res = ord($filter_res);
		$filter_res = $filter_res & 0x1f;
		echo "<font color=\"red\">";
		echo $filter_res;
		echo "</font>";	
		echo "</td>";
		
		// Keyboard Filter Tracking - row top, col-4
		echo "<td  colspan=2>Keyboard Filter Tracking: ";
		$filter_kbd = $pcb[94];
		$filter_kbd = ord($filter_kbd);
		$filter_kbd = $filter_kbd >> 1;
		$filter_kbd = $filter_kbd & 0x3f;
		echo "<font color=\"red\">";
		echo $filter_kbd;
		echo "</font>";	
		echo "</td>";
	
	echo "</tr><tr>";  // BOTTOM ROW
	
		// spacer bottom row collum "zero"
		echo "<td>&nbsp;</td>";
		//  Filter Mod1 Source		 - row bottom, col-1
		echo "<td>Filter Mod1 Source: " ;
		$filt_mod_1 = $pcb[91]; // 1 nyble = 16 mod source amounts
		$filt_mod_1 = ord($filt_mod_1) ;
		$filt_mod_1 =  $filt_mod_1  &0x0f;
		echo "<font color=\"red\">";
		echo 	modulators($filt_mod_1) ;
		echo "</font>";	
		echo "</td>";

		// Filter Mod1 Amount - row bottom, col-2
		echo "<td>Filter Mod1 Amount: " ;
		$filter_mod1_amt = $pcb[93];
		$filter_mod1_amt = ord($filter_mod1_amt);
		$filter_mod1_amt = $filter_mod1_amt &0x7f;
		$filter_mod1_amt = $filter_mod1_amt << 1; // shifts value over (adds zero to end bit) for "sign extended" value
		$filter_mod1_amt = neg63($filter_mod1_amt);
		echo "<font color=\"red\">";
		echo $filter_mod1_amt ;
		echo "</font>";	
		echo "</td>";

		//  Filter Mod2 Source - row bottom, col-3
		echo "<td>Filter Mod2 Source: " ;
		$filt_mod_2 = $pcb[91]; // 1 nyble = 16 mod source amounts
		$filt_mod_2 = ord($filt_mod_2) ;
		$filt_mod_2 =  $filt_mod_2  >> 4;
		echo "<font color=\"red\">";
		echo 	modulators($filt_mod_2) ;
		echo "</font>";	
		echo "</td>";	
		
		// Filter Mod2 Amount - row bottom, col-4
		echo "<td>Filter Mod2 Amount: " ;
		$filter_mod2_amt = $pcb[92];
		$filter_mod2_amt = ord($filter_mod2_amt);
		$filter_mod2_amt = $filter_mod2_amt &0x7f;
		$filter_mod2_amt = $filter_mod2_amt << 1; // shifts value over (adds zero to end bit) for "sign extended" value
		$filter_mod2_amt = neg63($filter_mod2_amt);
		echo "<font color=\"red\">";
		echo $filter_mod2_amt;
		echo "</font>";	
		echo "</td>";
		
	echo "</tr>";	
	echo "</table>";
// end filter


// echo "<br>" ;


// FINAL DCA (DCA 4) values Table	
	// Misc - row top, col-1
	echo "<table border=\"1\" align=\"center\" width=\"80%\"><tr>";
		// Row Identification Column (DCA4)
		echo "<TH><b>DCA 4 </b></TH>";
			// Misc - row top, col-2
		echo "<td>Final Volume Mod (ENV4) Depth: ";
		echo "<font color=\"red\">";
		$dca4_amt = $pcb[88];
		$dca4_amt = ord($dca4_amt);	
		echo $dca4_amt/2 ;		
		echo "</font>";	
		echo "</td>";
		// Misc - row top, col-3
		echo "<td>Pan: ";
		echo "<font color=\"red\">";
		$pan = $pcb[100];
		$pan =ord($pan);		
		$pan = $pan >> 4 ;
		echo $pan;
		echo "</font>";			
		echo "</td>";
		// Misc - row top, col-4
		echo "<td>Pan Modulator: ";
		echo "<font color=\"red\">";
		$panmod = $pcb[100];
		$panmod =ord($panmod);
		$panmod = $panmod &0x0f ;
		echo modulators($panmod);		
		echo "</font>";			
		echo "</td>";		
		// Misc - row top, col-5
		echo "<td>Pan Mod Amount: ";
		echo "<font color=\"red\">";
		$panmod_amt = $pcb[101];
		$panmod_amt =ord($panmod_amt);
		$panmod_amt = $panmod_amt &0x7f ;
		$panmod_amt = $panmod_amt  << 1; // shifts value over (adds zero to end bit) for "sign extended" value
		echo neg63($panmod_amt);		
		echo "</font>";			
		echo "</td>";			
		
	echo "</tr>";	
	echo "</table>";				
// end FINAL DCA (DCA 4) values Table	
		
		
// echo "<br>" ;


// ENVELOPES Value Table
echo "<table border=\"1\" align=\"center\" width=\"80%\">";
	for ( $env = 0; $env <= 3; $env++ ) {
		echo "<TR>";
		echo "<TH><b>ENVELOPE " . ($env+1) 	. "</b></TH>";
			echo "<td title=\"Envelope Volume Level 1\"> ENV" . ($env+1) . " L1 : ";
			echo "<font color=\"red\">";	
			echo  neg63(ord($pcb[6+$env*10  ])) ;
			echo "</font>";
			echo  "</td>";
			echo "<td title=\"Envelope Volume Level 2\"> ENV" . ($env+1) . " L2 : "  .  neg63(ord($pcb[6+$env*10+1]))  .  "</td>" ;
			echo "<td title=\"Envelope Volume Level 3\"> ENV" . ($env+1) . " L3 : "  .  ord($pcb[6+$env*10+2])       . "</td>" ;
			// *BEGIN* routine to display LV exp/lin values
			echo "<td title=\"Velocity Level Control (higher values increase velocity sensitivity)\"> ENV" . ($env+1) . " LV : "  ;
		/*	$lv_exp = $pcb[(6+$env*10+7)];
			echo "<font color=\"red\">";	
			echo ord($lv_exp) /4; // LV value steps by 4
			*/
			$exp = $pcb[(6+$env*10+7)];
			echo bindec($exp);
		//  $exp = ord($exp);
		//	$exp = $exp &0x01;
		// check for (and display) linear/exponential flag
			echo ($exp &1) ? "X <font color=\"blue\">(Exponential Curve)</font>" : "L <font color=\"blue\">(Linear Slope)</font>";			
		/*
			switch ($exp) { // check for (and display) linear/exponential flag
				case (($exp &0x01) == 1) :
					echo "X <font color=\"blue\">(Exponential Curve)</font>";
					break;
				case (($exp &0x01) == 0) :
					echo "L <font color=\"blue\">(Linear Slope)</font>";
					break;		
				}
			*/
			
			echo "</font>";
			echo  "</td>" ; 
				
		
			// *END* routine to display LV exp/lin values
			echo "<td title=\"T1 Attack Velocity Control (higher values shorten attack)\"> ENV" . ($env+1) . " T1V : " ;
			echo "<font color=\"red\">";	
			echo ord($pcb[6+$env*10+8]) ;
			echo "</font>";
			// echo "<font color=\"blue\">  (attack velocity)</font>";	 	
			echo "</td>" ;
		echo "</TR>";
		echo "<TR>";
			echo "<td>&nbsp;</td>";
			echo "<td> ENV " . ($env+1) . " T1 : " .  ord($pcb[6+$env*10+3]) . "</td>";
			echo "<td> ENV " . ($env+1) . " T2 : " .  ord($pcb[6+$env*10+4]) . "</td>";	
			echo "<td> ENV " . ($env+1) . " T3 : " .  ord($pcb[6+$env*10+5]) . "</td>";
			// *BEGIN* routine to handle ENV4 with reverb
			echo "<td> ENV " . ($env+1) . " T4 : " ;  
			$rev = ord($pcb[6+$env*10+6]);
//			$env4 = $rev; // this is not needed
				switch ($rev) {
				case ($rev < 128):
					$env4 = $rev;
					break;				
				case ($rev == 128):
					$env4 = 0;
					break;
				case ($rev > 128):
					$env4 = $rev -128 ;
					break;  
				}
			echo "<font color=\"red\">";				
			echo  $env4;
				if ($rev &0x01)
					echo "R <font color=\"blue\">(with Reverb)</font>";	
			//	else
			//		echo " <font color=\"blue\">Dry (no reverb)</font>";
			echo "</font>";
			echo "</td>";
			// *END* routine to handle ENV4 with reverb
			echo "<td> ENV" . ($env+1) . "_TK : " .  ord($pcb[6+$env*10+9]) . "</td>";
		echo "</TR>";
	}
echo "</table>";
// end ENVELOPE values table

// echo "<br><br>";




// LFO Values Table
echo "<table border=\"1\" align=\"center\" width=\"80%\">";
	for ( $lfo = 0; $lfo < 3; $lfo++ ) {
		echo "<TR>";
	// Top Row
		echo "<TH><b>LFO "  . ($lfo+1) . "</b></TH>";
			$freq = ord($pcb[(6+4*10+$lfo*4+0)])&0x3f ;
			echo "<td> LFO" . ($lfo+1) . "_Freq : "  . $freq  .  "<br />\n </td>" ;			
			$lfo_rst = $pcb[(6+4*10+$lfo*4+3)] ;
			$lfo_rst = ord($lfo_rst);
			$lfo_rst = $lfo_rst >> 7;
			echo "<td> LFO" . ($lfo+1) . "_Reset : ";
			echo "<font color=\"red\">";
			echo ($lfo_rst == 1) ? "ON" : "OFF";		
			echo "</font>";	
			echo   "</td>";

			/*			
			if ($lfo_rst != 1)
					$reset = "OFF";
				else
					$reset = "ON";				
*/	
			$lfo_hum = $pcb[(6+4*10+$lfo*4+3)] ;
			$lfo_hum = ord($lfo_hum) >> 6;
			$lfo_hum = $lfo_hum & 1;
				if ($lfo_hum != 1)
					$human = "OFF";
				else
					$human = "ON";
			echo "<td> LFO" . ($lfo+1) . "_Humanize : " . $human   	. "</td>";
			$lfo_wave = $pcb[(6+4*10+$lfo*4+0)];
			$lfo_wave = ord($lfo_wave);
			$lfo_wave = $lfo_wave >> 6;
				if ($lfo_wave == 0)
					$lfo_type = "TRI";
				elseif ($lfo_wave == 1)
					$lfo_type = "SAW";						
				elseif ($lfo_wave == 2)
					$lfo_type = "SQR";
				elseif ($lfo_wave == 3)
					$lfo_type = "NOI";				
		//	echo "<td> LFO" . ($lfo+1) . "_WaveNum: " . ($lfo_wave) . " _WAVE : " . $lfo_type  .  "</td>";			
			echo "<td> LFO" . ($lfo+1) .  " _Wave : " . $lfo_type  .  "</td>";			
			
			
			echo "</tr>\n<tr>"; 
	// Bottom Row
			echo "<td>&nbsp;</td>";
	// Level 1
			echo "<td> LFO" . ($lfo+1) . "_Level 1 : " ;
			$lfo_l1 = ($pcb[6+4*10+$lfo*4+1]);
			$lfo_l1 = ord($lfo_l1) & 0x3f  ;			
			echo  $lfo_l1;
			echo  "</td>";			
	// Delay
			echo "<td> LFO" . ($lfo+1) . "_Delay : " ;
			$lfo_dly =  $pcb[(6+4*10+$lfo*4+3)] ;
			$lfo_dly = ord($lfo_dly);
			$lfo_dly = $lfo_dly & 0x3f;			
			echo 	$lfo_dly 	. "</td>";
	// Level 2
			echo "<td> LFO" . ($lfo+1) . "_Level 2 : " ;
			$lfo_l2 =  ($pcb[6+4*10+$lfo*4+2]);
			$lfo_l2 =  ord($lfo_l2) ;
			$lfo_l2 = $lfo_l2 & 0x3f;
			echo $lfo_l2	. "</td>";
	// LFO Modulator

		echo "<td> LFO" . ($lfo+1) . "_Modulator : " ;

		$lfo_mod1=$pcb[6+4*10+$lfo*4+1] ;
		$lfo_mod1= ord($lfo_mod1);
		$lfo_mod1= $lfo_mod1 >> 6;
		
	//	echo " -modOne tiny bit: " . $lfo_mod1;
		
		$lfo_mod2=$pcb[6+4*10+$lfo*4+2];
		$lfo_mod2=ord($lfo_mod2);
		$lfo_mod2=$lfo_mod2 >> 6;
	
	//	echo " -modTwo tiny bit: " . $lfo_mod2 . " *END* " ;
		
		
		
		$lfo_mods = $lfo_mod1 . $lfo_mod2;
		$lfo_mods = (int)$lfo_mods;
		
		switch ($lfo_mods) {
			case 00:
			case 02:
			case 03:
			case 04:
			case 11 :
				$lfo_mods = 5;
				break;
			case 12 :
				$lfo_mods = 6;
				break;				
			case 13 :
				$lfo_mods = 7;
				break;
			case 20 :
				$lfo_mods = 8;
				break;	
			case 21 :
				$lfo_mods = 9;
				break;
			case 22 :
				$lfo_mods = 10;
				break;				
			case 23 :
				$lfo_mods = 11;
				break;
			case 30 :
				$lfo_mods = 12;
				break;	
			case 31 :
				$lfo_mods = 13;
				break;
			case 32 :
				$lfo_mods = 14;
				break;				
			case 33 :
				$lfo_mods = 15;
				break;
			
		}
		
		
		// echo " - both mods: " . ($lfo_mods) . " - equal: ";

		echo modulators($lfo_mods);
		
			echo  "</td>";

			
			
		echo "</TR>";
	}



	echo "</table>";
	

// echo "<br>" ;
	

	// MODES values Table	
	// MODES -  col-1
	echo "<table border=\"1\" align=\"center\" width=\"80%\"><tr>";
		// Row Identification Column 
		echo "<TH><b>MODES </b></TH>";
			// MODES - row top, col-2
		echo "<td title=\"OSC1 restarts OSC2 (OSC1=Master OSC2=Slave)\" >Sync: ";
		echo "<font color=\"red\">";
		$sync = $pcb[89];
		$sync = ord($sync);
		$sync = ($sync >> 7) & 1;
		echo ($sync == 1) ? "ON" : "OFF";		
		echo "</font>";	
		echo "</td>";
		
		// MODES - col-3
		echo "<td title=\"Amplitude of Oscillator 1 modulates the Amplitude of Oscillator 2\">AM: ";
		echo "<font color=\"red\">";
		$ampmod = $pcb[88];
		$ampmod =ord($ampmod);		
		$ampmod = ($ampmod >> 7)&1;
echo "FAULTY! " ; // AM param does not read correctly
		echo ($ampmod == 1) ? "ON" : "OFF";		
		echo "</font>";			
		echo "</td>";
		
		// MODES -  col-4
		echo "<td>Mono: ";
		echo "<font color=\"red\">";
		$mono = $pcb[93];
		$mono =ord($mono);
		$mono = ($mono >> 7) & 1;
		echo ($mono == 1) ? 'ON' : 'OFF';	
		echo "</font>";			
		echo "</td>";		
		
		// MODES -  col-5
		echo "<td>Glide: ";
		echo "<font color=\"red\">";
		$glide = $pcb[95];
		$glide =ord($glide);
		$glide = $glide &0x3f ;		
		echo $glide;
		echo "</font>";			
		echo "</td>";	
		
		// MODES - col-6
		echo "<td>Voice Restart (VC): ";
		echo "<font color=\"red\">";
		$vc = $pcb[92];
		$vc = ord($vc);
		$vc = ($vc >> 7) & 1;
		echo ($vc == 1) ? 'ON' : 'OFF';		
		echo "</font>";			
		echo "</td>";	

		// MODES - col-7
		echo "<td>ENV Restart: ";
		echo "<font color=\"red\">";
		$envRes = $pcb[94];
		$envRes = ord($envRes);
		$envRes = ($envRes >> 7) & 1;
		echo ($envRes == 1) ? 'ON' : 'OFF';			
		echo "</font>";			
		echo "</td>";

		// MODES - row 1, col-8
		echo "<td>OSC Restart: ";
		echo "<font color=\"red\">";
		$oscRes = $pcb[95];
		$oscRes = ord($oscRes);
		$oscRes = ($oscRes >> 7) & 1;
		echo ($oscRes == 1) ? 'ON' : 'OFF';		
		echo "</font>";			
		echo "</td>";

		// MODES - row 1, col-9
		echo "<td>CYC (Envelope Full Cycle): ";
		echo "<font color=\"red\">";
		$cyc= $pcb[101];
		$cyc = ord($cyc);
		$cyc = ($cyc >> 7) & 1;
		echo ($cyc == 1) ? 'ON' : 'OFF';			
		echo "</font>";			
		echo "</td>";
		
	echo "</tr>";	
	echo "</table>";				
// end MODES values Table	
	
	
// echo "<br>" ;
	

	// Split/Layer values Table	
	// Split/Layer -  col-1
	echo "<table border=\"1\" align=\"center\" width=\"80%\"><tr>";
		// Row Identification Column 
		echo "<TH><b>SPLIT/LAYER </b></TH>";
			// Split/Layer - row top, col-2
		echo "<td title=\"Activates Split/Layer mode. Use in conjunction with Split Select parameter (Upper or Lower) \" >";
		echo "Split/Layer: ";
		echo "<font color=\"red\">";
		$sply = $pcb[99];
		$sply = ord($sply);
		$sply = ($sply >> 7) & 1;
		echo ($sply == 1) ? "ON" : "OFF";		
		echo "</font>";	
		echo "</td>";
		
		// Split/Layer - col-3
		echo "<td title=\"Program Number\">S/L Prog #: ";
		echo "<font color=\"red\">";
		$slProg = $pcb[99];
		$slProg =ord($slProg);		
		$slProg = $slProg &0x7f;
		echo $slProg;
// here we could have a subroutine to display the prog name, or CART A / CART B	
		echo "</font>";			
		echo "</td>";
		
		// Split/Layer -  col-4
		echo "<td>Mono: ";
		echo "<font color=\"red\">";
		$mono = $pcb[93];
		$mono =ord($mono);
		$mono = ($mono >> 7) & 1;
		echo ($mono == 1) ? 'ON' : 'OFF';	
		echo "</font>";			
		echo "</td>";		
		
		// Split/Layer -  col-5
		echo "<td>Glide: ";
		echo "<font color=\"red\">";
		$glide = $pcb[95];
		$glide =ord($glide);
		$glide = $glide &0x3f ;		
		echo $glide;
		echo "</font>";			
		echo "</td>";	
		
		// Split/Layer - col-6
		echo "<td>Voice Restart (VC): ";
		echo "<font color=\"red\">";
		$vc = $pcb[92];
		$vc = ord($vc);
		$vc = ($vc >> 7) & 1;
		echo ($vc == 1) ? 'ON' : 'OFF';		
		echo "</font>";			
		echo "</td>";	

		// Split/Layer - col-7
		echo "<td>ENV Restart: ";
		echo "<font color=\"red\">";
		$envRes = $pcb[94];
		$envRes = ord($envRes);
		$envRes = ($envRes >> 7) & 1;
		echo ($envRes == 1) ? 'ON' : 'OFF';			
		echo "</font>";			
		echo "</td>";

		// Split/Layer - row 1, col-8
		echo "<td>OSC Restart: ";
		echo "<font color=\"red\">";
		$oscRes = $pcb[95];
		$oscRes = ord($oscRes);
		$oscRes = ($oscRes >> 7) & 1;
		echo ($oscRes == 1) ? 'ON' : 'OFF';		
		echo "</font>";			
		echo "</td>";

	echo "</tr>";	
	echo "</table>";				
// end Split/layer values Table	
	
	
	
include 'footer.html.php';	


?>
