<?
if (isset($submit));
if (($user!="lifecycle" or $pass!="flyinfo"))
        {setcookie("user", "", time() - 3600);
        setcookie("pass", "", time() - 3600);
        die("WRONG PASSWORD OR USERNAME");}
else {setcookie("pass", $pass);
        setcookie("user", $user);}
?>
<html>
<head>
<title>drosophila entry page</title>
</head>

<body bgcolor="white">
<h2>Welcome to the LifeCycle Database!</h2>
<p>
please select a query type.
<p>
<a HREF="http://genome.med.yale.edu/Lifecycle/gen_query.html"><br>Single gene query </a><br> 

<a HREF="http://genome.med.yale.edu/Lifecycle/gen_query_basic.html"><br>Single basic gene query </a><p> 

<a HREF="http://genome.med.yale.edu/Lifecycle/Querylocal5.html">General query</a><br>

</body>
</html>
