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

$sql_embryo = "SELECT rep_average FROM red_exp_table
		 WHERE raid='$raid' and exp_id<=60 ORDER BY exp_id";

$result_embryo = mysql_query($sql_embryo)
	or die("embryo exp_table query failed");

$time_embryo = array(0,.5,1,1.5,2,2.5,3,3.5,4,4.5,5,5.5,6,7,8,9,10,
		11,12,13,14,15,16,17,18,19,20,21,22,23); //30 elements

$e_count=0 ;//DBUG
$n_row=mysql_num_rows($result_embryo);
if($n_row!=30){		//DBUG
	die("n embryo count is not 30, is $n_row");
}

while(	$row_em = mysql_fetch_array($result_embryo) ){

	$e_count++;//DBUG
	$val   = $row_em["rep_average"];

	if ( !is_null($val) ){

		$a_embryo[]=$val;
	}
	else{
		$a_embryo[]="-";
	}
}

if($e_count!=30){		//DBUG
	die("embryo count is not 30, is $e_count");
}

//LARVAE   117,118 & 141, 140 are larvae with sex. ignore'm for now.
///$sql_larvae = "SELECT rep_average, exp_id FROM red_exp_table

$sql_larvae = "SELECT rep_average FROM red_exp_table
		 WHERE raid='$raid' AND exp_id>60 AND exp_id<=80 ORDER BY exp_id";

$result_larvae = mysql_query($sql_larvae)
	or die("exp_table query failed");

$time_larvae = array(24,33,43,49,57,67,72,84,96,105); // 10 elements

$l_count=0;//DBUG
while(	$row_l = mysql_fetch_array($result_larvae) ){

	$l_count++;//DBUG

	$val   = $row_l["rep_average"];
	if ( !is_null($val) ){
		$a_larvae[]=$val;
	}
	else{
		$a_larvae[]="-";
	}
}

if($l_count!=10){		//DBUG
	die("larvae count is not 10, is $l_count");
}

//METAM
$sql_met = "SELECT rep_average FROM red_exp_table
		 WHERE raid='$raid' AND exp_id>80 AND exp_id<=116 ORDER BY exp_id";

$result_met = mysql_query($sql_met)
	or die("metam exp_table query failed");

$time_metam = array(120,122,124,126,128,130,132,
	            136,140,144,150,156,162,168,180,192,200,216);//18 elementos

$m_count=0;//DBUG
while(	$row_m = mysql_fetch_array($result_met) ){
	$m_count++;//DBUG

	$val   = $row_m["rep_average"];
	if ( !is_null($val) ){
		$a_metam[]=$val;
	}
	else{
		$a_metam[]="-";
	}
}

if($m_count!=18){		//DBUG
	die("metamorfosis count is not 18, is $m_count");
}

mysql_close($db);

$ma_ti=950; $mi_ti=0;

$ma_em = max($a_embryo);$ma_la = max($a_larvae);$ma_me = max($a_metam);

$mi_em  = min($a_embryo); $mi_la  = min($a_larvae); $mi_me = min($a_metam);

$amax=max(array($ma_em,$ma_la,$ma_me));
$amin=min(array($mi_em,$mi_la,$mi_me));

$graph = new Graph(240,200);
$graph->img->SetMargin(40,10,20,40);	
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
//$graph->yscale->SetGrace(40,50);
$graph->yscale->SetGrace(20);
//$graph->xscale->SetGrace(1,1);

$p1 = new LinePlot($a_embryo,$time_embryo);
//$p1 = new ScatterPlot($a_embryo,$time_embryo);
$p1->mark->SetFillColor("sandybrown");
$p1->mark->SetType(MARK_FILLEDCIRCLE);
$p1->mark->SetWidth(3);
//$p1->SetLegend("Embryo");

//$p2 = new ScatterPlot($a_larvae,$time_larvae);
$p2 = new LinePlot($a_larvae,$time_larvae);
$p2->mark->SetFillColor("green");
$p2->mark->SetType(MARK_FILLEDCIRCLE);
$p2->mark->SetWidth(3);
//$p2->SetLegend("Larvae");

//$p3 = new ScatterPlot($a_metam,$time_metam);
$p3 = new LinePlot($a_metam,$time_metam);
$p3->mark->SetFillColor("violet");
$p3->mark->SetType(MARK_FILLEDCIRCLE);
$p3->mark->SetWidth(3);
//$p3->SetLegend("Metamorph");

$graph->Add($p1);
$graph->Add($p2);
$graph->Add($p3);

//$graph->legend->Pos(.03,.5,"right","center");
$graph->xaxis->title->Set("time");
$graph->xaxis->SetPos("min");
$graph->yaxis->title->Set("log2 ratio");
$graph->Stroke();

?>
