<html> 
<head><title>Single Gene Query: Timecourse data</title></head>
<body bgcolor="white">
<h3>Search Result:</h3>

<?
$db = mysql_connect("localhost", $user, $pass)
	or die("could not connect mysql");
mysql_select_db("locals", $db) 
	or die("could not select database");

// is a one-to-one between cg,fbgn, common_gene_name and raid?

if ($input1==""){
	die("you MUST enter a gene or a CG celera gene ID, or an FBgn value!");
}
elseif( preg_match("/cg/i",$input1) ){
	$sql_element = "Select  * FROM element_table WHERE cg='$input1' AND status='confirmed'";
}
elseif( preg_match("/fbgn/i",$input1) ){
	$sql_element = "Select  * FROM element_table WHERE fbgn='$input1' AND status='confirmed'";
}
else{
 	$sql_element = "Select * FROM element_table WHERE common_gen_name='$input1' and status='confirmed'";
}



//print($sql_element); print("<br>"); 		// DBUG what we queried

$result_element = mysql_query($sql_element)
	or die("element query failed");

$row_element = mysql_fetch_array($result_element);

$raid = $row_element["raid"];
if ($raid==""){
	print "<b>No match!</b></body></html>";
	exit;
}
$fbgn = $row_element["fbgn"];
$cg = $row_element["cg"];
$status = $row_element["status"];
$cgn = $row_element["common_gene_name"];

//print "raid is $raid\n";//DBUG

$sql_exp_table = "SELECT rep_average, exp_id FROM exp_table
		 WHERE raid='$raid' ORDER BY exp_id";

//print($sql_exp_table); print("<br>"); 	// DBUG what we query

$result_exp_table = mysql_query($sql_exp_table)
	or die("exp_table query failed");


// these are the associative expids and timepoints--->

$sql_experim_desc = "Select exp_id, sex, time_point_young FROM experiment_description1";
$result_exp_desc = mysql_query($sql_experim_desc)
	or die("experiment description query failed");

while($row_des = mysql_fetch_assoc($result_exp_desc) ){
	$timepoint=$row_des["time_point_young"];
	$eid=$row_des["exp_id"];
	settype($timepoint,"double");
	$timey["$eid"]=$timepoint;
	$etmp=$timey["$eid"];
	print (" timepoint is $etmp");//DBUG
	print (" exp_id is $eid");//DBUG
}

//<----------

?>

<blockquote>
<font size=-1 >Yellow color represents high relative levels of expression 
while blue represents low levels. The brightest color is three-fold or greater differential from 
the reference black.</font>
<font size= +1></font>;

<table BORDER=1 CELLSPACING=0 CELLPADDING=0 WIDTH=332>
<tr NOSAVE>
<td WIDTH=3 BGCOLOR=#FFFF00>&nbsp;</td>
<td WIDTH=3 BGCOLOR=#DDDD00>&nbsp;</td>
<td WIDTH=3 BGCOLOR=#BBBB00>&nbsp;</td>
<td WIDTH=3 BGCOLOR=#999900>&nbsp;</td>
<td WIDTH=3 BGCOLOR=#777700>&nbsp;</td>
<td WIDTH=3 BGCOLOR=#555500>&nbsp;</td>
<td WIDTH=3 BGCOLOR=#333300>&nbsp;</td>
<td WIDTH=3 BGCOLOR=#000000>&nbsp;</td>
<td WIDTH=3 BGCOLOR=#000033>&nbsp;</td>
<td WIDTH=3 BGCOLOR=#000055>&nbsp;</td>                
<td WIDTH=3 BGCOLOR=#000077>&nbsp;</td>
<td WIDTH=3 BGCOLOR=#000099>&nbsp;</td>                
<td WIDTH=3 BGCOLOR=#0000BB>&nbsp;</td>
<td WIDTH=3 BGCOLOR=#0000DD>&nbsp;</td>                
<td WIDTH=3 BGCOLOR=#0000FF>&nbsp;</td>              
</tr>
</table>
</blockquote>
<p>
<table BORDER=0 CELLSPACING=0 CELLPADDING=0>
<tr>
	<td  BGCOLOR=#FF9978 COLSPAN=31>Embryo</td>
	<td  BGCOLOR=#00FF00 COLSPAN=10>Larvae</td>
	<td  BGCOLOR=#EE83EE COLSPAN=8>Prepupae</td>
	<td  BGCOLOR=#F7FF90 COLSPAN=11>Pupae</td>
	<td  BGCOLOR=#99FFFF COLSPAN=9>Adult M</td>
	<td  BGCOLOR=#55FFFF COLSPAN=10>Adult F</td>
</tr>
<tr>

<?php


/* 0  .5  1  1.5  2  2.5  3  3.5  4  4.5  5  5.5  6-23 (30)		embryo
   24  33 43 49   57 67   72 84   96 105               (10)		larvae
   120 :  2  :    132 					(7)		prepupae
   136 :  4  :14  150 : 6 :  168 180 192  200 216	(11)		pupae
   230 ~-~> 950						(8)		adult */

$color=array("1000" => "888888","1.584963" => "FFFF00","1.459432" => "DDDD00",
"1.321928" => "BBBB00","1.169925" => "999900","1" => "777700",
"0.807355" => "555500","0.584963" => "333300","-0.584963" => "000000",
"-0.807355" => "000033","-1" => "000055","-1.169925" => "000077",
"-1.321928" => "000099","-1.459432" => "0000DD","-1.584963" => "0000FF");

$numeric_color=array_keys($color);

$embryo=array(); $larvae=array();$metam=array();$adult=array();
$exp_id=0;  		
while ($exp_id<=162){		// exceptions not included (unordered/sex defined larvaes & on)

	$row_exp_table = mysql_fetch_array($result_exp_table);
	$junk = mysql_fetch_array($result_exp_table);        //ignore following repeat
	$exp_id   = $row_exp_table["exp_id"];
	settype($exp_id,"integer");
	$val   = $row_exp_table["rep_average"];
	if ( !is_null($val) ){
		settype($exp_id,"double");
		if ($val<=0){
			$val=-1000;
		}
		else{	
			$val = log($val)/log(2.0);
		}
		print (" val is $val");//DBUG

		if (($exp_id>=117 & $exp_id<=119) || ($exp_id>=140 & $exp_id<=142)){	
				//ignore these NOW
		}
		else{
			$i=0;			// get color
			while ($val < $numeric_color[$i]){
				$i++;
			}
			$col = $color[$numeric_color[$i-1]];
			print ("<td WIDTH=7 BGCOLOR=#$col>&nbsp;</td>\n");
			$average["$exp_id"]=$val;
			$tim[]=$timey["$exp_id"];
			if ($exp_id<20){  //DBUG
				print ("tim is $tim[$exp_id]");
			}
		}
	}
}

print "<td BGCOLOR=\"#CCCCCC\"><A HREF=\"http://flybase.bio.indiana.edu/.bin/fbidq.html?$fbgn\">&nbsp;$fbgn</A>\n";
print "&nbsp;</td>\n";
print "</tr>\n";
print "</table>\n";

if( !is_array($embryo) ){
	print ("no match<br></body></html>");
	exit;
}
?>
<p>
<table>
<tr>
<?
   foreach($average as $value){
	print("<td> $value</td>");
    }
?>
<p>
<? 
   foreach($tim as $value){
	print("<td> $value</td>");
    } 
?>
 </tr>

</table>


</body>
</html>
