<?php
/*--------------------------------------------------+
| SNAP 2 ASK TOP MENU BAR                           |
+===================================================+
| File: admin/ads.php                               |
| Serve as the menu and login field that            |
| propagates throughout all the main pages          |
| of the site.                                      |
+---------------------------------------------------+

+--------------------------[ Tue, Aug 02, 2008 ]---*/
?>
<html>
<head>
    <title>Snap 2 Ask Home Page</title>
	<meta content="text/html; charset=UTF-8" http-equiv="content-type" />
	<style type="text/css">
.hidelabel
{
display:none;
}
table
{
width: 100%;
}
#bigsearch
{
width: 30%;
height: 30px;
}
	</style>
</head>
<body>
<table border="1">
    <tbody>
		<tr>
			<td>
			<table border="1">
				<tbody>
					<tr>
						<td>
                        <a href="cproject1.zip">WEBSITE FILES</a>
                        <?php include("initvars.php"); include("topmenubar.php");?>
                        </td>
						<td>
                        <?include("loginfield.php");?>
						</td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
		<tr>
			<td>
                <?php include("searchbar.php"); ?>
			</td>
		</tr>
	</tbody>
</table>
