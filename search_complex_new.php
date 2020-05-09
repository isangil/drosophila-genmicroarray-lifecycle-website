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
<?
}
head("complex query");
 
$db = mysql_connect("localhost", $user, $pass)
        or die("could not connect mysql");
mysql_select_db("inigo_db", $db) 
        or die("could not select database");

if ($pass!="flyinfo"){
	die("ACCESS DENIED: INVALID PASSWORD/USER");
}

// fct split query output  (page navigation)
function pagenav() {
                     
global $limit,$offset,$numpage,$where;

if ($where) {
	$safewhere=urlencode($where);
}
echo "<TABLE CELLPADDING=0 BORDER=0 CELLSPACING=5 WIDTH=60%>
<TR>
<TD ALIGN=RIGHT>";
	
print "<font size=-1>";
if ($offset>=$limit) {
	$newoff=$offset-$limit;
	echo "<A HREF=\"search_complex_navigation.php?offset=$newoff&where=$safewhere\">&lt;-- 
PREV</A> </font></TD>"; } else {
	echo "&lt;-- PREV";
}

echo "<TD ALIGN=CENTER> &nbsp; ";

for ($i=1;$i<=$numpage;$i++) {
	if ((($i-1)*$limit)==$offset) {
		print "$i |";
	} else {
		$newoff=($i-1)*$limit;
//		echo "<A HREF=\"$PHP_SELF?offset=$newoff&where=$safewhere\">$i</A>|";	
		echo "<A HREF=\"search_complex_navigation.php?offset=$newoff&where=$safewhere\">$i</A>|";	
	}
}
                         
echo "&nbsp; </TD><TD ALIGN=LEFT>";

if ($offset!=$limit*($numpage-1)) {
	$newoff=$offset+$limit;
//	echo "<font size=-1> <A HREF=\"$PHP_SELF?offset=$newoff&where=$safewhere\">NEXT--&gt;</A></font></TD>";
	echo "<font size=-1> <A HREF=\"search_complex_navigation.php?offset=$newoff&where=$safewhere\">NEXT--&gt;</A></font></TD>";
}else{
	echo "<font size=-1>NEXT--&gt;</font></TD>";
}
echo "</TR></TABLE>";

} // END FUNCTION

//number of results per page: about 25
$limit=25;

//if no offset has been passed, should be zero
if(!$offset) $offset=0;

if ($OP1== "eq") 
	{$OPC1="=";}
elseif($OP1=="gt")
	{$OPC1=">";}
elseif($OP1== "gte")
	{$OPC1=">=";}
elseif($OP1== "lt")
	{$OPC1="<";}
elseif($OP1== "lte")
	{$OPC1="<=";}
elseif($OP1== "neq")
	{$OPC1="!=";}

if ($OP2== "eq") 
	{$OPC2="=";}
elseif($OP2=="gt")
	{$OPC2=">";}
elseif($OP2== "gte")
	{$OPC2=">=";}
elseif($OP2== "lt")
	{$OPC2="<";}
elseif($OP2== "lte")
	{$OPC2="<=";}
elseif($OP2== "neq")
	{$OPC2="!=";}

flush();

/*this part converts the operators so that html does not get confused with > signs.  Drawback is that < doesn't seem to work right.  So I wrote it  when user selects < he really gets <=.*/

$field1="rep_average";$field2="rep_average";
$field3="rep_average";$field4="rep_average";


// print($OPC1); print($OPC2); print($OPC3); print($OPC4); print($input1);
/*This part keeps the user from screwing up the search by leaving buttons clicked with blank fields or entering data without clicking buttons*/

if ($Radio==""){
	$field2="";
	$OPC2="";
	$input2="";
}
if ($input1=="")
	{die("YOU MUST ENTER AT LEAST ONE EXPRESSION VALUE IN THE FIRST FIELD");
}


if ($input2==""){
	$field2="";
	$OPC2="";
	$Radio="";
}
/* this part converts "not" into "and" for the element and experiment selections.  downstream of this, chromosome= is converted to chromosome!= for the not statement */

/* this part converts "not" into "and " NOTE THE SPACE!! when $AND7="AND "  it can be distinguished from "AND" so a != statement can then be used.  This is convoluted but seems to work */

if ($and0r7=="NOT")
	{$AND7="AND ";}
if ($and0r7=="AND")
	{$AND7="AND";}
if ($and0r7=="OR")
	{$AND7="OR";}
if ($and0r7=="NONE")
	{$AND7="";}


if ($and0r9=="NOT")
	{$AND9="AND ";}
if ($and0r9=="AND")
	{$AND9="AND";}
if ($and0r9=="OR")
	{$AND9="OR";}

if ($and0r10=="NOT")
	{$AND10="AND NOT";}
if ($and0r10=="AND")
	{$AND10="AND";}
if ($and0r10=="OR")
	{$AND10="OR";}

/* this part lets user enter "All" for chromosome and adds the ' marks around the chromosome entered only if something other than "All" was selected.*/

/*EXPERIMENT TABLE STUFF.  This first part is for stage of development*/

if (($AND7!=="") and ($stage=="")){
	die("YOU MUST SELECT AT LEAST ONE STAGE OR UNSELECT THIS PART OF THE FORM");
}

$parenstg="'";
$tickstg="')";

if ($AND7=="AND "){
	$stage_logop="!=";
	$stage_eq="(stage!=";
	$stagesql = implode($stage, "' and stage != '");
}
elseif ($AND7==""){     	//nothing doing, then.
	$stage_logop="";
	$stage_eq="";
	$stagesql="";
	$parenstg="";
	$tickstg="";
}
else{
	$stage_logop="=";
	$stage_eq="(stage=";
	$stagesql = implode($stage, "' or stage = '");
}

/*SELECT based on sex */

if ($AND9=="AND "){
	$sexsql = "sex!='$sex'";
	$sex_op = "!=";
}else{
	$sexsql="sex='$sex'";
	$sex_op = "=";
}

if ($sex==""){
	$sex_op="";
	$AND9="";
	$sexsql="";
}

/* tudor code */

if ($description!=="")	{
	$expsql="description='$description'";
}
if ($description==""){
	$AND10="";
	$expsql="";
}

if(!$where) {
	if(empty($input1)){
//		error handling
		die("must enter at least one value!");
	}	
	$where = "$OPC1|$input1|$Radio|$OPC2|$input2|";
	$where = $where."$sex_op|$sex|";
	$where = $where."$AND10|$description|";
	$where = $where."$AND7|";
 
	if (!empty($stage)){
		foreach ($stage as $elemt){
			$where=$where."$elemt|";
		}
	}else{
		$where=$where."|";
	}
}
$da = explode('|',$where);
$cels = count($da);
$query_where = "WHERE ( (rep_average $da[0] $da[1] ";
if(preg_match ("/[0-9]+/", $da[4]) ){
	$query_where=$query_where." $da[2]  rep_average  $da[3] $da[4])";
}else{$query_where=$query_where.")";}
   
if(preg_match ("/[A-Z]+/", $da[6]) ){
	$query_where=$query_where."AND (sex $da[5] '$da[6]')";
}
if(preg_match ("/[a-z]+/", $da[8]) ){
	$query_where=$query_where."$da[7] (description='$da[8]')";
}
if(preg_match ("/[a-z]+/", $da[10]) ){  //DONT WORK
	$query_where =$query_where."$da[9](";
	for ($i=10;$i<=$cels-3;$i++){
		$query_where =$query_where."stage='$da[$i]' OR ";
	}
	$query_where =$query_where."stage='$da[$i]')";
}
$query_where=$query_where.")";

flush();


$db = mysql_connect("localhost", $user, $pass);
mysql_select_db("inigo_db", $db);

 $sql = "Select DISTINCT raid FROM  red_exp_table, experiment_description1
 WHERE ($field1 $OPC1 $input1 $Radio $field2 $OPC2 $input2  
 $AND7  $stage_eq $parenstg $stagesql $tickstg $AND9 $sexsql $AND10 
$expsql)";

// print($sql); print("<br>"); 		// what we queried

$result = mysql_query($sql)  
	or die("query failed!");

$ns = mysql_num_rows($result);    // print ($ns);
// calc num pages:
$numpage = intval($ns/$limit); 

if ($ns%$limit){
	$numpage++ ; //add one page if there's a leftover
}

tableheaders();

flush();


$sql0 = "Select DISTINCT raid FROM  red_exp_table, experiment_description1
$query_where  LIMIT $offset,$limit";

//print($sql0); print("<br>"); 		// what we queried

$result0 = mysql_query($sql0)  
	or die("query failed!");

$ns = mysql_num_rows($result0);
//print ($ns);
//	headers
	$n=0;  
	$oldraid="crap";
	while ($row = mysql_fetch_array($result0)){ 
		$newraid = $row["raid"];
	 	if($oldraid != $newraid){
			//new query		
			$sql2 = "Select rep_average
			FROM red_exp_table  WHERE raid='$newraid' 
	                ORDER BY  exp_id";
//			print("$sql2 \n");
			$res2 = mysql_query($sql2) 
				or die("2nd query failed!");

			$sql3 ="Select fbgn, com_gen_name, chr_pos
				FROM red_element_table
				WHERE raid='$newraid'";

			$res3 = mysql_query($sql3) 
				or die("3rd query failed!");

			$ns2 = mysql_num_rows($res2);	//DBUG--v
//			print("rows 2nd query $ns2 \n");
			$ns3 = mysql_num_rows($res3);
//			print("rows 3rd query $ns3\n");
//			print("$sql3 \n");

			$annot_row = mysql_fetch_array($res3);
			$fbgn = $annot_row["fbgn"];
			$cgn = $annot_row["com_gen_name"];
			$chr = $annot_row["chr_pos"];
			preg_match("/^[0-9]+/i",$chr,$ban);
			$band=$ban[0];

			while ($nrow = mysql_fetch_array($res2)){ 

			$lr = $nrow["rep_average"];

			if  ($lr > 1.584963 and $lr < 1000 ) {			
				print ("<td WIDTH=5 BGCOLOR=#FFFF00>&nbsp;</td>\n");
			}
			elseif ($lr > 1.459432 and $lr <  1.584963) {
				print ("<td WIDTH=5 BGCOLOR=#DDDD00>&nbsp;</td>\n");
			}
			elseif ( $lr > 1.321928 and $lr <  1.459432) {
				print ("<td WIDTH=5 BGCOLOR=#BBBB00>&nbsp;</td>\n");
			}
			elseif ( $lr > 1.169925 and $lr <  1.321928) {
				print ("<td WIDTH=5 BGCOLOR=#999900>&nbsp;</td>\n");
			}
			elseif ( $lr > 1 and $lr <  1.169925) {
				print ("<td WIDTH=5 BGCOLOR=#777700>&nbsp;</td>\n");
			}
			elseif ( $lr > 0.807355  and $lr <  1) {
				print ("<td WIDTH=5 BGCOLOR=#555500>&nbsp;</td>\n");
			}
			elseif ( $lr > 0.584963  and $lr < 0.807355) {
				print ("<td WIDTH=5 BGCOLOR=#333300>&nbsp;</td>\n");
			}
			elseif ( $lr < 0.584963 and  $lr > -0.584963) {
				print ("<td WIDTH=5 BGCOLOR=#000000>&nbsp;</td>\n");
			}
			elseif (-1.584963 > $lr) {
				print ("<td WIDTH=5 BGCOLOR=#0000FF>&nbsp;</td>\n");
			}
			elseif (-1.584963 < $lr and $lr < -1.459432) {
				print ("<td WIDTH=5 BGCOLOR=#0000DD>&nbsp;</td>\n");
			}
			elseif ( -1.459432 < $lr and $lr < -1.321928) {
				print ("<td WIDTH=5 BGCOLOR=#0000BB>&nbsp;</td>\n");
			}
			elseif ( -1.321928 < $lr and $lr < -1.169925) {
				print ("<td WIDTH=5 BGCOLOR=#000099>&nbsp;</td>\n");
			}
			elseif ( -1.169925 < $lr and $lr < -1) {
				print ("<td WIDTH=5 BGCOLOR=#000077>&nbsp;</td>\n");
			}
			elseif ( -1 < $lr and $lr < -0.807355) {
				print ("<td WIDTH=5 BGCOLOR=#000055>&nbsp;</td>\n");
			}
			elseif ( -0.807355 < $lr and $lr < -0.584963) {
				print ("<td WIDTH=5 BGCOLOR=#000033>&nbsp;</td>\n");
			}
			elseif ($lr >999) {
				print ("<td WIDTH=5 BGCOLOR=#888888>&nbsp;</td>\n");
			}
			else {
				print ("ERROR");
			}

			}//endwhile
		
			print ("<td><A HREF=\"http://flybase.bio.indiana.edu/.bin/fbidq.html?$fbgn\">&nbsp;$cgn</A>\n");
			print ("&nbsp;</td>\n");
 			print "<td COLSPAN=18><A HREF=\"http://flybase.bio.indiana.edu/.bin/fbgrmap?fbgene$band&id=$fbgn\"><img src=\"map.gif\" width=20 height=20 alt=\"map\"></A>\n"; 
			print ("&nbsp;</td>\n</tr>\n");
			print ("<tr>\n");

		}
	        $n++;

	}
	print ("</tr></table>\n");
	if($numpage>1) {
		pagenav();
		print "<p>";
	}
	
	if($n == 0){
		print "no match<br></body></html>";
		exit;
	}
//}  // end of for loop

function tableheaders(){
?>

<blockquote>
<font size=-1 >Yellow color represents high relative levels of expression while blue represents 
low levels. The brightest color is three-fold or greater differential from the reference 
black.</font>
<font size= +1></font>;

<table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=332>
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

<table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=85%>
<tr NOSAVE>
<td  BGCOLOR=#FF9978 COLSPAN=30>Embryo</td>
<td  BGCOLOR=#00FF00 COLSPAN=10>Larvae</td>
<td  BGCOLOR=#EE83EE COLSPAN=7>Prepupae</td>
<td  BGCOLOR=#F7FF90 COLSPAN=10>Pupae</td>
<td  BGCOLOR=#99FFFF COLSPAN=9>Adult M</td>
<td  BGCOLOR=#99FFFF COLSPAN=9>Adult F</td>
<td  BGCOLOR=#FF6699 COLSPAN=18>Fly Annotation</td>
<td  BGCOLOR=#FF6699 COLSPAN=18>Map</td>
</tr>
<tr>

<?
}
?>
<p>
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
