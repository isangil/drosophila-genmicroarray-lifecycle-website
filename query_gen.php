<?php
function head($title){
?>
<html>
<head>
<title> <?echo $title ?></title>
<meta http-eqip="Content-Type" content=text/html;charset=iso-8859-1">
<style type="text/css">
 <!--
 BODY {font-family : Verdana, Arial, Helvetica;}
 H1, H2, H3, H4, H5, H6 {font-family : Arial, Helvetica, Verdana, sans-serif;}
 TD {font-family : Verdana, Arial, Helvetica;}
 PRE {font-family : Courier Fixed;}
 .date	{
		font-size : small;
		font-family : Verdana;
		font-style : italic;
	}
  -->
</style>
</head>
<body bgcolor="white">
<img src="flyarray.jpg" alt="fly array image" width=540 height=108>
<?php
}
head("Single Gene Query: Timecourse data");
?> 
<p>
<h4>Color coded time course</h4>
</p>
<?
$db = mysql_connect("localhost", $user, $pass)
	or die("could not connect mysql");
mysql_select_db("inigo_db", $db) 
	or die("could not select database");

// is a one-to-one between cg,fbgn, common_gene_name and raid?

if ($input1==""){
	die("you MUST enter a gene or a CG celera gene ID, or an FBgn value!");
}
elseif( preg_match("/cg/i",$input1) ){
//	$sql_element = "Select  * FROM red_element_table WHERE cg='$input1' AND status='confirmed'";
	$sql_element = "Select  * FROM red_element_table WHERE cg='$input1'";
}
elseif( preg_match("/fbgn/i",$input1) ){
//	$sql_element = "Select  * FROM red_element_table WHERE fbgn='$input1' AND status='confirmed'";
	$sql_element = "Select  * FROM red_element_table WHERE fbgn='$input1'";
}
else{
// 	$sql_element = "Select * FROM red_element_table WHERE com_gen_name='$input1' AND status='confirmed'";
 	$sql_element = "Select * FROM red_element_table WHERE com_gen_name='$input1'";
}

//print($sql_element); print("<br>"); 		// DBUG what we queried

$result_element = mysql_query($sql_element)
	or die("element query failed");

$row_element = mysql_fetch_array($result_element);

$raid = $row_element["raid"];
//print("raid is $raid\n");
if ($raid==""){
	print "<b>No match!</b></body></html>";
	exit;
}

$fbgn = $row_element["fbgn"];
$cg = $row_element["cg"];
$est = $row_element["est"];
$mixedan = $row_element["mixed_annot"];
$prot = $row_element["prot_domain"];
$chr = $row_element["chr_pos"];
$fct = $row_element["fct_clean"];
$status = $row_element["status"];
$cgn = $row_element["com_gen_name"];

preg_match("/^[0-9]+/i",$chr,$ban);
$band=$ban[0];
if(empty($cgn)){
	if ( empty($cg) ){
		if ( empty($fbgn) ){
			$cgn="unnamed gen";
		}
		else{
			$cgn=$fbgn;
		}
	}
	else{
		$cgn=$cg;
	}
}
//print strlen($cgn);//DBUG
//print ("cgn is $cgn\n, is empty $yes");//DBUG
//print "raid is $raid\n";//DBUG
//$sql_exp_table = "SELECT rep_average, exp_id FROM exp_table

$sql_exp_table = "SELECT rep_average, exp_id FROM red_exp_table
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
}
// <----------

?>

<table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=50%>

 <tr valign=top>
 <td width=7%>&nbsp;</td>
  <td width=97%>
	<font size=-1 >Yellow color represents high relative levels of expression 
	while blue represents low levels. The brightest color is three-fold or greater differential from 
	the reference black.</font>
  </td>
 </tr>

 <tr valign=top>
 <td width=7%>&nbsp;</td>
  <td width=93%>
    <table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=50%>
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
  </td>
 </tr>
</table>
<p>
<table BORDER=0 CELLSPACING=0 CELLPADDING=0>
<tr>
	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td  BGCOLOR=#FF9978 COLSPAN=30>&nbsp;</td>
	<td  BGCOLOR=#00FF00 COLSPAN=10>&nbsp;</td>
	<td  BGCOLOR=#EE83EE COLSPAN=18 ALIGN="CENTER">Metamorphosis</td>
	<td  BGCOLOR="aqua" COLSPAN=18 ALIGN="CENTER">Adult </td>
	<td  COLSPAN=18 ALIGN="CENTER">Flybase |</td>
	<td  COLSPAN=18 ALIGN="CENTER">Map</td>
</tr>
<tr>
	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td  BGCOLOR=#FF9978 COLSPAN=30  ALIGN="CENTER">Embryo</td>
	<td  BGCOLOR=#00FF00 COLSPAN=10 ALIGN="CENTER">Larvae</td>
	<td  BGCOLOR=#EE83EE COLSPAN=7 ALIGN="CENTER">Prepupae</td>
	<td  BGCOLOR=#F7FF90 COLSPAN=10 ALIGN="CENTER">Pupae</td>
	<td  BGCOLOR=#99FFFF COLSPAN=9 ALIGN="CENTER">Male</td>
	<td  BGCOLOR="beige" COLSPAN=9 ALIGN="CENTER">Female</td>
</tr>
<tr>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

<?php


/* 0  .5  1  1.5  2  2.5  3  3.5  4  4.5  5  5.5  6-23 (30)		embryo
   24  33 43 49   57 67   72 84   96 105               (10)		larvae
   120 :  2  :    132 					(7)		prepupae
   136 :  4  :14  150 : 6 :  168 180 192  200 216	(11)		pupae
   230 ~-~> 950						(8)		adult */

$color=array("1000" => "888888",
	"1.584963"  => "FFFF00",
	"1.459432"  => "DDDD00",
	"1.321928"  => "BBBB00",
	"1.169925"  => "999900",
	"1" 	    => "777700",
	"0.807355"  => "555500",
	"0.584963"  => "333300",
	"-0.584963" => "000000",
	"-0.807355" => "000033",
	"-1" 	    => "000055",
	"-1.169925" => "000077",
	"-1.321928" => "000099",
	"-1.459432" => "0000BB",
	"-1.584963" => "0000DD",
	"-1000.0"   => "0000FF"  );

$numeric_color=array_keys($color);

/*  DBUG
$i=0;
while ($i<=14){
	$num=$numeric_color[$i];
	print("i: $i, numeric:$num, $color[$num]  \n");
	$i++; 
}
*/

//$embryo=array(); $larvae=array();$metam=array();$adult=array();//other strategy

$exp_id=0;  		
while ($exp_id<=158){		// exceptions not included (unordered/sex defined larvaes & on)

	$row_exp_table = mysql_fetch_array($result_exp_table);

///	$junk = mysql_fetch_array($result_exp_table);        //ignore following repeat
///						do not ignore in red_exp_table
	$exp_id   = $row_exp_table["exp_id"];
	settype($exp_id,"integer");
	$val   = $row_exp_table["rep_average"];
	if ( !is_null($val) ){
		$beteta=1;
///		settype($exp_id,"integer");	
///		$val = log($val)/log(2.0);//not taken in red_exp_table

//		print " val is $val";//DBUG

		if (($exp_id>=117 & $exp_id<=119) || ($exp_id>=140 & $exp_id<142)){	
				//ignore these NOW
		}
		elseif (($exp_id>=136 & $exp_id<=139) ){	
				//ignore these NOW
		}
		else{
			$i=0;			// get color
			while ($val < $numeric_color[$i]){
				$i++;
			}
			$col = $color[$numeric_color[$i]];
//			print "color is $col\n val is $val";//DBUG
			print ("<td WIDTH=5 BGCOLOR=#$col>&nbsp;</td>\n");
			$average["$exp_id"]=$val;
			$tim["$exp_id"]=$timey["exp_id"];
		}	
	}
	else{
//		is null...do gray
		print ("<td rowspan=2 WIDTH=5 BGCOLOR=\"gray\">&nbsp;</td>\n");
	}
}

print "<td COLSPAN=18 BGCOLOR=\"#CCCCCC\"><A HREF=\"http://flybase.bio.indiana.edu/.bin/fbidq.html?$fbgn\">&nbsp;$cgn</A>\n";
print "&nbsp;</td>\n";
print "<td COLSPAN=18 BGCOLOR=\"#CCCCCC\"><A HREF=\"http://flybase.bio.indiana.edu/.bin/fbgrmap?fbgene$band&id=$fbgn\"><img 
src=\"map.gif\" width=20 height=20 alt=\"map\"></A>\n"; print "</tr>\n";
print "</table>\n";

if( empty($beteta) ){
	print "no match<br></body></html>";
	exit;
}
/*         file section commented out COMMENTED OUT--IGNORE

else{


	$fname="/tmp/timey".$cg.".txt";
	if(!($pid = fopen($fname,"w") ) ){
		print("Error: could not open $fname\n");
		exit;
	}
	foreach ($timey as $value){
		fputs($pid,$value);
	}
	fclose($pid);
	
	$fname="/tmp/average".$cg.".txt";
	if(!($pid = fopen($fname,"w") ) ){
		print("Error: could not open $fname\n");
		exit;
	}
	foreach($average as $value){
		fputs($pid,$value);
	}
	fclose($pid);
}
 
WENT DOWN..-v
<img src="query_gen_equalspacing.php?raid=<? echo $raid; ?>">   
*/
?>

<p>
<h4>Graph: time course</h4>
</p>
<table width=100% cellpadding=0 cellspacing=0 border=0>
 <tr valign=top>
  <td COLSPAN=2 width=25%>
  <img src="query_gen_equalspacing3.php?raid=<? echo $raid; ?>">   
  </td>
  <td width=50%>
   <table width=100% cellpadding=0 cellspacing=0 border=0>
    <tr valign=top>
     <td><h4>Gene info</h4></td>
    </tr>
    <tr valign=top>
     <td width=34%> <font size=-1 color="blue">Gene name:</font><font size=-1><? print $cgn ?></font></td>
    </tr><tr>
     <td width=33%> <font size=-1 color="blue">Celera gen:</font><font size=-1><? print $cg ?></font></td>
    </tr><tr>
     <td width=33%> <font size=-1 color="blue">Protein domain:</font>&nbsp;<font size=-1><? print $prot ?></font></td> </tr>
    </tr><tr>
     <td width=33%> <font size=-1 color="blue">Status:</font>&nbsp;<font size=-1><? print $status ?></font></td>
    </tr>  <tr>
     <td width=50%> <font size=-1 color="blue">Chromosome band:</font>&nbsp;<font size=-1><? print $chr ?></font></td>
    </tr><tr>
     <td width=50%> <font size=-1 color="blue">Fbgn:</font>&nbsp;<font size=-1><? print $fbgn ?></font></td>
    </tr> <tr>
     <td width=50%> <font size=-1 color="blue">EST:</font>&nbsp;<font size=-1><? print $est ?></font></td>
    </tr><tr>
     <td width=50%> <font size=-1 color="blue"> Function:</font>&nbsp;<font size=-1><? print $fct ?></font></td>
    </tr><tr valign=top>
     <td width=30%><font size=-1 color="blue">Mixed annotation:</font>&nbsp;<font size=-1><? print $mixedan ?></font></td> 
    </tr>
   </table>
  </td>
 </tr>
 <tr valign=top>
  <td width=25%>
  <img src="query_gen_vstimepoint2a.php?raid=<? echo $raid; ?>">   
  </td>
  <td width=25%>
  <img src="query_gen_vstimepoint2b.php?raid=<? echo $raid; ?>">   
  </td>
  <td width=50%></td>

 </tr>

</table>
<p></p>
<table width="420" border="0" align="CENTER">
   <tr>
      <td>&nbsp;<hr align="left" size="1" width="420" noshade></td>
   </tr><tr>
      <td align=center>Send comments to <a href=mailto:kevin.white@yale.edu> 
Kevin White</a> or/and <a href=mailto:inigo.sangil@yale.edu> Inigo San Gil</a>
   </tr>
</table>
</body>
</html>

