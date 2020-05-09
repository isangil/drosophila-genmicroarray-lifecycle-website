<?php
function head($title){
?>
<html>
<head>
<title> <? echo $title ?></title>
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
<table width="420" border="0" align="CENTER">
	<tr><td>&nbsp;
	<hr align="left" size="1" width="420" noshade>
	</td></tr>
</table>
<?php
}
//if (isset($submit)){
	$user="lifecycle";
	$pass="flyinfo";
	setcookie("pass", $pass);
        setcookie("user", $user);
	head("Welcome to the drosophila genome expression level website!");
//}
?>
<table width="420" border="0" align="CENTER">
<tr>
  <td>&nbsp; <hr align="left" size="1" width="210" noshade>
    <a HREF="http://genome.med.yale.edu/Lifecycle/gen_query.html">Single gene query </a><br> 
    <a HREF="http://genome.med.yale.edu/Lifecycle/gen_query.html"><img src="graph3.jpg" alt="graph icon"  width=182 height=107></a>
    <hr align="left" size="1" width="210" noshade>
  </td>

  <td>&nbsp; <hr align="left" size="1" width="210" noshade>
   <a HREF="http://genome.med.yale.edu/Lifecycle/correlation.html">View data by cluster </a><br> 
   <a HREF="http://genome.med.yale.edu/Lifecycle/correlation.html"><img src="treeview.jpg" alt="cluster tree icon" width=182 height=107></a>
   <hr align="left" size="1" width="210" noshade>
  </td>
</tr>
<tr>
 <td>&nbsp; <hr align="left" size="1" width="210" noshade>
  <a HREF="http://genome.med.yale.edu/Lifecycle/Querylocal.html">General query</a><br>
  <a HREF="http://genome.med.yale.edu/Lifecycle/Querylocal.html">
  <img src="correl.jpg" alt="colored expression icon" width=182 height=107></a><br>
  <hr align="left" size="1" width="210" noshade>
 </td>

 <td>&nbsp; <hr align="left" size="1" width="210" noshade>
  <a HREF="http://genome.med.yale.edu/Lifecycle/feedback.html">Feedback </a><br> 
  <a HREF="http://genome.med.yale.edu/Lifecycle/feedback.html">
  <img src="feedback.gif" alt="feedback" width=182 height=107></a><br> 
  <hr align="left" size="1" width="210" noshade>
 </td>
</tr>
</table>


<p>
<table width="420" border="0" align="CENTER">
   <tr>
      <td>&nbsp;<hr align="left" size="1" width="420" noshade></td>
   </tr>
   <tr>
      <td align=center>
	<font size=-2>
Send comments to <a href=mailto:kevin.white@yale.edu,inigo.sangil@yale.edu> 
Kevin White</a> and <a href=mailto:kevin.white@yale.edu,inigo.sangil@yale.edu> Inigo San Gil</a>
	</font>
      </td>
   </tr>
</table>

</body>
</html>
