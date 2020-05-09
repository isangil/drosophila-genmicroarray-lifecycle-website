<?
include ("../jpgraph/jpgraph.php");
include ("../jpgraph/jpgraph_line.php");
//include ("../jpgraph/jpgraph_error.php");
//include ("../jpgraph/jpgraph_scatter.php");

$db = mysql_connect("localhost", $user, $pass)
	or die("could not connect mysql");
mysql_select_db("inigo_db", $db) 
	or die("could not select database");

//EMBRYO

///$sql_embryo = "SELECT rep_average, exp_id FROM red_exp_table
$sql_embryo = "SELECT rep_average FROM red_exp_table
		 WHERE raid='$raid' and exp_id<=60 ORDER BY exp_id";

$result_embryo = mysql_query($sql_embryo)
	or die("embryo exp_table query failed");

///$exp_id=0;  		
///while ($exp_id<=60){		// exceptions not included 
while(	$row_em = mysql_fetch_array($result_embryo) ){
///	$exp_id   = $row_em["exp_id"];
///	settype($exp_id,"integer");
	$val   = $row_em["rep_average"];
	if ( !is_null($val) ){
///		settype($exp_id,"double");
///		$a_embryo["$exp_id"]=$val;
		$a_embryo[]=$val;
		$a_larvae[]="";
		$a_metam[]="";
		$a_adultm[]="";
		$a_adultf[]="";
	}
	else{
		$a_embryo[]="-";
		$a_larvae[]="";
		$a_metam[]="";
		$a_adultm[]="";
		$a_adultf[]="";
	}
}

//LARVAE   117,118 & 141, 140 are larvae with sex. ignore'm for now.
///$sql_larvae = "SELECT rep_average, exp_id FROM red_exp_table

$sql_larvae = "SELECT rep_average FROM red_exp_table
		 WHERE raid='$raid' AND exp_id>60 AND exp_id<=80 ORDER BY exp_id";

$result_larvae = mysql_query($sql_larvae)
	or die("exp_table query failed");

///while ($exp_id<=80){		// exceptions not included 
while(	$row_l = mysql_fetch_array($result_larvae) ){
///	$exp_id   = $row_l["exp_id"];
///	settype($exp_id,"integer");
	$val   = $row_l["rep_average"];
	if ( !is_null($val) ){
///		settype($exp_id,"double");
///		$a_larvae["$exp_id"]=$val;
		$a_larvae[]=$val;
		$a_metam[]="";
		$a_adultm[]="";
		$a_adultf[]="";
	}
	else{
		$a_larvae[]="-";
		$a_metam[]="";
		$a_adultm[]="";
		$a_adultf[]="";
	}
}

//METAM
///$sql_met = "SELECT rep_average, exp_id FROM red_exp_table
$sql_met = "SELECT rep_average FROM red_exp_table
		 WHERE raid='$raid' AND exp_id>81 AND exp_id<=116 ORDER BY exp_id";

$result_met = mysql_query($sql_met)
	or die("metam exp_table query failed");

///while ($exp_id<=116){		// exceptions 119 142 not included 
while(	$row_m = mysql_fetch_array($result_met) ){
///	$exp_id   = $row_m["exp_id"];
///	settype($exp_id,"integer");
	$val   = $row_m["rep_average"];
	if ( !is_null($val) ){
///		settype($exp_id,"double");
///		$a_metam["$exp_id"]=$val;
		$a_metam[]=$val;
		$a_adultf[]="";
		$a_adultm[]="";
	}
	else{
		$a_metam[]="-";
		$a_adultf[]="";
		$a_adultm[]="";
	}
}


//ADULT : 
// a) MALE
///$sql_exp_table = "SELECT rep_average, exp_id FROM red_exp_table
$sql_ad_male = "SELECT rep_average FROM red_exp_table
		 WHERE raid='$raid' AND exp_id>119  AND exp_id<136 ORDER BY exp_id";

$res_am = mysql_query($sql_ad_male)
	or die("adult male exp_table query failed");

///while ($exp_id<=170){		// exceptions not included 136-139 tudor, 
///				//  DRILL:  120-135 AM    143-158 AF 
///	$row_exp_table = mysql_fetch_array($result_exp_table);

while($row_am = mysql_fetch_array($res_am) ){

///	$exp_id   = $row_exp_table["exp_id"];
///	settype($exp_id,"integer");   // 136->139 is "tudor"...
///	if($exp_id>139 & $exp_id<143){		//ignore NOT adults
///	}
///	elseif($exp_id>162 & $exp_id<167){	//ignore NOT adults
///	}
///	else{
		$val   = $row_am["rep_average"];
		if ( !is_null($val) ){
///			settype($exp_id,"double");
///			$a_adult["$exp_id"]=$val;
			$a_adultm[]=$val;
/////			$a_adultf[]="";  //same time points as adult male, overlap!! (=comment out)
		}
		else{
			$a_adultm[]="-";
////			a...
		}
///	}
}

//b) Female
// a) MALE
$sql_ad_female = "SELECT rep_average FROM red_exp_table
		 WHERE raid='$raid' AND exp_id>142  AND exp_id<159 ORDER BY exp_id";

$res_af = mysql_query($sql_ad_female)
	or die("adult female exp_table query failed");

while($row_af = mysql_fetch_array($res_af) ){

	$val   = $row_af["rep_average"];
	if ( !is_null($val) ){
		$a_adultf[]=$val;
	}
	else{
		$a_adultf[]="-";
	}
}

mysql_close($db);

$ma_em = max($a_embryo);$ma_la = max($a_larvae);
$ma_adf = max($a_adultf); $ma_me = max($a_metam);$ma_adm = max($a_adultm);

$mi_em  = min($a_embryo); $mi_la  = min($a_larvae);
$mi_adm = min($a_adultm); $mi_adf = min($a_adultf); $mi_me = min($a_metam);

$amax=max(array($ma_em,$ma_la,$ma_me,$ma_adf,$ma_adm));
$amin=min(array($mi_em,$mi_la,$mi_me,$mi_adf,$mi_adm));

$graph = new Graph(500,200);
$graph->img->SetMargin(40,100,20,40);	
$graph->img->SetAntiAliasing();
$graph->SetMarginColor("pink");
//$graph->SetMarginColor("navy");

$graph->SetScale("linlin");
//$graph->SetScale("textlin");

///$graph->SetScale("linlin",$ymin=$amin,$ymax=$amax);
//$graph->yscale->ticks->set(1,10);  //what's these values??
///$graph->yscale->ticks->set(1,2);  //what's these values??
$graph->SetShadow();
$graph->title->Set("Time course data");
$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yscale->SetGrace(40,50);
//$graph->xscale->SetGrace(1,1);

$p1 = new LinePlot($a_embryo);
$p1->mark->SetFillColor("sandybrown");
$p1->mark->SetType(MARK_FILLEDCIRCLE);
$p1->mark->SetWidth(3);
$p1->SetLegend("Embryo");

$p2 = new LinePlot($a_larvae);
$p2->mark->SetFillColor("green");
$p2->mark->SetType(MARK_FILLEDCIRCLE);
$p2->mark->SetWidth(3);
$p2->SetLegend("Larvae");

$p3 = new LinePlot($a_metam);
$p3->mark->SetFillColor("violet");
$p3->mark->SetType(MARK_FILLEDCIRCLE);
$p3->mark->SetWidth(3);
$p3->SetLegend("Metamorph");

$p4 = new LinePlot($a_adultm);
$p4->mark->SetFillColor("aqua");
$p4->mark->SetType(MARK_FILLEDCIRCLE);
$p4->mark->SetWidth(3);
$p4->SetLegend("Adult Male");

$p5 = new LinePlot($a_adultf);
$p5->mark->SetFillColor("beige");
$p5->mark->SetType(MARK_FILLEDCIRCLE);
$p5->mark->SetWidth(3);
$p5->SetLegend("Adult Fem");

$graph->Add($p1);
$graph->Add($p2);
$graph->Add($p3);
$graph->Add($p4);
$graph->Add($p5);

$graph->legend->Pos(.03,.5,"right","center");
$graph->xaxis->title->Set("sample");
$graph->xaxis->SetPos("min");
$graph->yaxis->title->Set("log2 ratio");
$graph->Stroke();

?>
