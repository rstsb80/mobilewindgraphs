<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php

#20100524 - randall s. ehren <randall@redigital.org>

require_once "/www/redigital.org/wind/lib/monitoring_locations.php"

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <!-- render the page for iphones well -->
 <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
 <!-- full screen iphone -->
 <meta name="apple-mobile-web-app-capable" content="yes" />
 <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />

 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 <meta name="description" content="iphone wind graphs for santa barbara,ca and sedona,az"/>
 <meta name="keywords" content="santa barbara wind iphone,wind graphs,iphone wind graphs,santa barbara,santa barbara wind,sedona,sedona wind,wind meter,rrdtool,santa barbara wind graph,iphone wind graph"/>
 
 <title>iphone wind graph</title>
 <style type="text/css">
@media screen and (max-device-width: 480px) {
/*--- iPhone only CSS here ---*/
body {
  -webkit-text-size-adjust:none;
  /* font-family:Helvetica, Arial, Verdana, sans-serif; */
   padding: 0 0 0 0;
   margin: 0 0 0 0;
}

}

#menubar ul {
   list-style: none;
   padding: 0 0 0 0;
   margin: 0 0 0 0;
}

#menubar li {
  float: left;
   padding: 0 0 0 0;
   margin: 0 0 0 0;
}

#menubar li a {
  background: url(images/css-nav.gif) #fff bottom left repeat-x;
  height: 2em;
  line-height: 2em;
  float: left;
  width: 3.9em;
  /* width: 20%; */
  display: block;
  border: 0.1em solid #dcdce9;
  color: #0d2474;
  text-decoration: none;
  text-align: center;
}

#content {

}
</style>
</head>
<body>
<div id="header">
  <div id="menubar">
  <ul>

<?
foreach ($w_array as $location => $value)
{
    echo "<li><a href=\"$_SERVER[PHP_SELF]?l=$location\">$location</a></li>";
}

?>

<br>
<br>
<div id="content">

<?php

function display_wind_graphs($location)
{
      echo "<br /><br />\n";
      echo "<img src=\"/wind/data/${location}_d.png\" width=\"320\" alt=\"${location}\" />";
      echo "<br />\n";
      echo "<img src=\"/wind/data/${location}_h.png\" width=\"320\" alt=\"${location}\" />";
      echo "<br />\n";
      echo "<img src=\"/wind/data/${location}_historical.png\" width=\"320\" alt=\"${location}\" />";
}

$location = $_GET["l"];
if ( $location )
{
    if ( $location == "light" )
    {
        echo "<br /><br />\n";
        echo '<iframe align="top" src="http://widgets.windalert.com/widgets/web/currentConditions?spot_id=90902&units_wind=mph&units_temp=F&width=320&height=480&color=870100" width="320" height="480" frameborder="0" scrolling="no" allowtransparency="no"></iframe>';
    }
    else 
    {
        display_wind_graphs($location);
    }

}
else
{
    display_wind_graphs("lacumbre");
}

?>
</div>
<script type="text/javascript">
<!-- hide iphone toolbar -->
window.scrollTo(0, 1);

var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-5727235-1");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>
