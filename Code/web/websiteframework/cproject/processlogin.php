<?php
if($_POST['sgnUp'])
    $xview= "sgnup";
else
{
    $xview= "loginok";
    include("helloname.php");
    include("initvars.php");
}
include("index.php");
?>