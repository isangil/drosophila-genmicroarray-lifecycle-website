<?
include ("../jpgraph/jpgraph.php");
include ("../jpgraph/jpgraph_line.php");
//include ("../jpgraph/jpgraph_error.php");
//include ("../jpgraph/jpgraph_scatter.php");

$db = mysql_connect("localhost", $user, $pass)
	or die("could not connect mysql");
mysql_select_db("inigo_db", $db) 
	or die("could not select database");


//ADULT : 

$time_adult = array(230,302,350,470,590,710,830,950);	//8 elemtn 

// a) MALE

$sql_ad_male = "SELECT rep_average FROM red_exp_table
		 WHERE raid='$raid' AND exp_id>119  AND exp_id<136 ORDER BY exp_id";

$res_am = mysql_query($sql_ad_male)
	or die("adult male exp_table query failed");

$n_am=mysql_num_rows($res_am);

if($n_am!=8){
	die("male adult rows are not 8, but $n_am");
}
while($row_am = mysql_fetch_array($res_am) ){

		$val   = $row_am["rep_average"];
		if ( !is_null($val) ){
			$a_adultm[]=$val;
		}
		else{
			$a_adultm[]="-";
		}
///	}
}

//b) Female
// a) MALE
$sql_ad_female = "SELECT rep_average FROM red_exp_table
		 WHERE raid='$raid' AND exp_id>142  AND exp_id<159 ORDER BY exp_id";

$res_af = mysql_query($sql_ad_female)
	or die("adult female exp_table query failed");

$n_af=mysql_num_rows($res_af);

if($n_af!=8){
	die("female adult rows are not 8, but $n_af");
}
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

$ma_ti=950; $mi_ti=200;

$ma_adf = max($a_adultf); $ma_adm = max($a_adultm);
$mi_adm = min($a_adultm); $mi_adf = min($a_adultf);

$amax=max(array($ma_adf,$ma_adm));
$amin=min(array($mi_adf,$mi_adm));

$graph = new Graph(250,200);
$graph->img->SetMargin(15,15,20,40);	
$graph->img->SetAntiAliasing();
$graph->SetMarginColor("pink");

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

//$p4 = new ScatterPlot($a_adultm,$time_adult);
$p4 = new LinePlot($a_adultm,$time_adult);
$p4->mark->SetFillColor("aqua");
$p4->mark->SetType(MARK_FILLEDCIRCLE);
$p4->mark->SetWidth(3);
//$p4->SetLegend("Adult Male");

//$p5 = new ScatterPlot($a_adultf,$time_adult);
$p5 = new LinePlot($a_adultf,$time_adult);
$p5->mark->SetFillColor("beige");
$p5->mark->SetType(MARK_FILLEDCIRCLE);
$p5->mark->SetWidth(3);
//$p5->SetLegend("Adult Fem"); 

$graph->Add($p4);
$graph->Add($p5);

//$graph->legend->Pos(.03,.5,"right","center");
$graph->xaxis->title->Set("time");
$graph->xaxis->SetPos("min");
//$graph->yaxis->title->Set("log2 ratio");
$graph->Stroke();

?>
