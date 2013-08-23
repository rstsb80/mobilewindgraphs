<?php
// 2010-02-22 - testing simplexml
// 2010-05-26 - begin abstraction
// 2013-08-19 - seperated out monitoring locations

// setup variables

$debug   = 1;
$d_path  = "/var/www/redigital.org/wind/data";

require_once "/www/redigital.org/wind/lib/monitoring_locations.php";


// process the wind stats

foreach ($w_array as $location => $value) {
  $xml_url   = $value;

  $wind_string = xml_to_rrd($xml_url,$location);
  gen_cur_graph($location,$wind_string);
  gen_his_graph($location);
  gen_historical_graph($location,"-12month","now");
}



function gen_rrd_datafiles($location) {
  global $debug,$d_path;
  $gen_rrd_wind_cmd = "/usr/bin/rrdtool create ${d_path}/${location}.rrd \
        DS:wind:GAUGE:600:U:U \
        DS:gust:GAUGE:600:U:U \
        RRA:MIN:0.5:1:600 \
        RRA:MIN:0.5:6:700 \
        RRA:MIN:0.5:24:775 \
        RRA:MIN:0.5:288:797 \
        RRA:AVERAGE:0.5:1:600 \
        RRA:AVERAGE:0.5:6:700 \
        RRA:AVERAGE:0.5:24:775 \
        RRA:AVERAGE:0.5:288:797 \
        RRA:MAX:0.5:1:600 \
        RRA:MAX:0.5:6:700 \
        RRA:MAX:0.5:24:775 \
        RRA:MAX:0.5:288:797 ";

  $gen_rrd_dir_cmd = "/usr/bin/rrdtool create ${d_path}/${location}_dir.rrd \
        DS:wind_direction:GAUGE:600:0:360 \
        RRA:AVERAGE:0.5:1:600 ";

  $gen_rrd_wind = `$gen_rrd_wind_cmd`;
  $gen_rrd_dir  = `$gen_rrd_dir_cmd`;

} // gen_rrd_datafile

function xml_to_rrd($xml_url,$location) {
  global $d_path,$debug;

  $wind_data = simplexml_load_file($xml_url);

  $wind_mph       = $wind_data->wind_mph;
  $wind_gust_mph  = $wind_data->wind_gust_mph;
  $wind_direction = $wind_data->wind_direction;
  $wind_degrees   = $wind_data->wind_degrees;
  $wind_string    = $wind_data->wind_string;
  $temperature    = $wind_data->temperature_string;
  $neighborhood   = $wind_data->location->neighborhood;

  // fix wind gust if meter does not report value
  if ($wind_gust_mph == "0")
    $wind_gust_mph = $wind_mph;

  // update wind speed RRD
  if ( file_exists(${d_path}."/".${location}.".rrd" )) {
	echo "updating rrd";
    $update_rrd_speed_cmd = "/usr/bin/rrdtool update ${d_path}/${location}.rrd N:${wind_mph}:${wind_gust_mph}";
    $update_rrd_speed = `$update_rrd_speed_cmd`;
  } 
  else {
	echo "creating new rrd datafiles";
    gen_rrd_datafiles($location);
  }

  // update wind direction RRD
  if ( file_exists(${d_path}."/".${location}."_dir.rrd" )) {
	echo "updating rrd";
    $update_rrd_dir_cmd = "/usr/bin/rrdtool update ${d_path}/${location}_dir.rrd N:${wind_degrees}";
    $update_rrd_dir = `$update_rrd_dir_cmd`;
  }
  else {
	echo "creating new rrd datafiles";
    //gen_rrd_datafiles($location);
  }
      

  if ( $debug ) {
    echo "<p>\n";
    echo "<h2> location = ${location}</h2>\n";
    echo "<h3> url = ${xml_url}</h3>\n";
    echo "<img src=/wind/data/${location}_d.png><br>\n";
    echo "<pre>\n";
    //print_r($wind_data);
    echo $update_rrd_speed_cmd . "<br>";
    //echo $update_rrd_speed . "<br>";
    echo $update_rrd_dir_cmd . "<br>";
	//echo $update_rrd_dir . "<br>";
    echo "</pre></p>\n";
    echo "temperature = $temperature\n";
    }

  $wind_array["wind_string"]  = $wind_string;
  $wind_array["temperature"]  = $temperature;
  $wind_array["neighborhood"] = $neighborhood;
  return ($wind_array);
} // foreach

function gen_cur_graph($location, $wind_array) {
  global $debug,$d_path;

  $wind_string  = $wind_array["wind_string"];
  $temperature  = $wind_array["temperature"];
  $neighborhood = $wind_array["neighborhood"];

  // generate current graph
  $gen_graph_cmd = "/usr/bin/rrdtool graph ${d_path}/${location}_d.png \
      -M -v '\nmph - ${location}' -t '${wind_string}' \
      --font 'WATERMARK:7:' -w 320 --height=150 -E\
      -s 06:00 -e 20:00 --alt-autoscale-max --lower-limit=0  \
	DEF:a=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE  \
	DEF:b=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE \
	DEF:c=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE  \
	DEF:d=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE \
	DEF:e=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE \
	DEF:f=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE \
	DEF:g=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE \
	DEF:h=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE \
	DEF:i=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE  \
	DEF:j=${d_path}/${location}.rrd:wind:AVERAGE \
	DEF:ba=${d_path}/${location}.rrd:gust:AVERAGE \
	DEF:bb=${d_path}/${location}.rrd:wind:AVERAGE \
	CDEF:cdefa=i,0,GE,i,22.5,GE,UNKN,INF,IF,UNKN,IF  \
	CDEF:cdefb=i,22.6,GE,i,67.5,GE,UNKN,INF,IF,UNKN,IF  \
	CDEF:cdefc=i,67.6,GE,i,112.5,GE,UNKN,INF,IF,UNKN,IF \
	CDEF:cdefd=i,112.6,GE,i,157.5,GE,UNKN,INF,IF,UNKN,IF \
	CDEF:cdefe=i,157.6,GE,i,202.5,GE,UNKN,INF,IF,UNKN,IF \
	CDEF:cdeff=i,202.6,GE,i,247.5,GE,UNKN,INF,IF,UNKN,IF \
	CDEF:cdefg=i,247.6,GE,i,292.5,GE,UNKN,INF,IF,UNKN,IF  \
	CDEF:cdefh=i,292.6,GE,i,337.5,GE,UNKN,INF,IF,UNKN,IF  \
	CDEF:cdefi=i,337.6,GT,i,360.99,GT,UNKN,INF,IF,UNKN,IF \
	AREA:cdefb#9FA4EE:\"North East\t\"  \
	AREA:cdefa#8D85F3:\"North\t\" \
	AREA:cdefh#6557D0:\"North West\t\" \
	AREA:cdefc#F9FD5F:\"East\\n\" \
	AREA:cdefd#EACC00:\"South East\t\"  \
	AREA:cdefe#EAAF00:\"South\\t\"  \
	AREA:cdeff#EA8F00:\"South West\t\"  \
	AREA:cdefg#00CF00:\"West\\n\" \
	AREA:cdefi#8D85F3:\"\" \
	GPRINT:i:LAST:\"Current Direction in Degrees\:%8.2lf %s\\n\" \
    COMMENT:\"Location\: ${neighborhood}\" \
    COMMENT:\"Temperature\: ${temperature}\" \
    COMMENT:\"\n\" \
	AREA:bb#C0C0C0:\"\" \
	LINE2:ba#562B29:\"\" \
	LINE1:bb#000000:\"\"
  ";

  $gen_graph = `$gen_graph_cmd`;
  if ($debug) {
    echo "<p>\n";
    echo "<pre>\n";
    echo $gen_graph_cmd;
    echo "</pre>\n";
  }

} // end xml_to_rrd

function gen_his_graph($location) {
  global $debug, $d_path;

  // generate current graph
  $gen_his_graph_cmd = "/usr/bin/rrdtool graph ${d_path}/${location}_h.png \
	-M -v mph -t ${location}:yesterday -w 320 -s 6am-1day -e 20:00-1day \
	--alt-autoscale-max --lower-limit=0 \
	DEF:a=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE  \
	DEF:b=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE \
	DEF:c=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE  \
	DEF:d=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE \
	DEF:e=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE \
	DEF:f=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE \
	DEF:g=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE \
	DEF:h=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE \
	DEF:i=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE  \
	DEF:j=${d_path}/${location}.rrd:wind:AVERAGE \
	DEF:ba=${d_path}/${location}.rrd:gust:AVERAGE \
	DEF:bb=${d_path}/${location}.rrd:wind:AVERAGE \
	CDEF:cdefa=i,0,GE,i,22.5,GE,UNKN,INF,IF,UNKN,IF  \
	CDEF:cdefb=i,22.6,GE,i,67.5,GE,UNKN,INF,IF,UNKN,IF  \
	CDEF:cdefc=i,67.6,GE,i,112.5,GE,UNKN,INF,IF,UNKN,IF \
	CDEF:cdefd=i,112.6,GE,i,157.5,GE,UNKN,INF,IF,UNKN,IF \
	CDEF:cdefe=i,157.6,GE,i,202.5,GE,UNKN,INF,IF,UNKN,IF \
	CDEF:cdeff=i,202.6,GE,i,247.5,GE,UNKN,INF,IF,UNKN,IF \
	CDEF:cdefg=i,247.6,GE,i,292.5,GE,UNKN,INF,IF,UNKN,IF  \
	CDEF:cdefh=i,292.6,GE,i,337.5,GE,UNKN,INF,IF,UNKN,IF  \
	CDEF:cdefi=i,337.6,GT,i,360.99,GT,UNKN,INF,IF,UNKN,IF \
	AREA:cdefa#8D85F3:\"North\" \
	AREA:cdefb#9FA4EE:\"North East\"  \
	AREA:cdefc#F9FD5F:\"East\" \
	AREA:cdefd#EACC00:\"South East\\n\"  \
	AREA:cdefe#EAAF00:\"South\"  \
	AREA:cdeff#EA8F00:\"South West\"  \
	AREA:cdefg#00CF00:\"West\" \
	AREA:cdefh#6557D0:\"North West\\n\" \
	AREA:cdefi#8D85F3:\"\" \
	AREA:bb#C0C0C0:\"\" \
	LINE2:ba#562B29:\"\" \
	LINE1:bb#000000:\"\"
  ";

  $gen_his_graph = `$gen_his_graph_cmd`;

  if ( $debug ) {
    echo "<pre>\n";
    echo $gen_his_graph_cmd;
    echo "</pre>\n";
  }

} // gen_his_graph

function gen_historical_graph($location,$start_time,$end_time) {
  global $debug, $d_path;
  // notes
  // -E/--slope-mode = organic look

  // generate 1 month historical graph
  $gen_his_graph_cmd = "/usr/bin/rrdtool graph ${d_path}/${location}_historical.png \
	-M -v mph -t ${location}:historical -w 320 -s ${start_time} -e ${end_time} \
	--alt-autoscale-max --lower-limit=0 \
	DEF:a=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE  \
	DEF:b=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE \
	DEF:c=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE  \
	DEF:d=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE \
	DEF:e=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE \
	DEF:f=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE \
	DEF:g=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE \
	DEF:h=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE \
	DEF:i=${d_path}/${location}_dir.rrd:wind_direction:AVERAGE  \
	DEF:j=${d_path}/${location}.rrd:wind:AVERAGE \
	DEF:ba=${d_path}/${location}.rrd:gust:AVERAGE \
	DEF:bb=${d_path}/${location}.rrd:wind:AVERAGE \
	CDEF:cdefa=i,0,GE,i,22.5,GE,UNKN,INF,IF,UNKN,IF  \
	CDEF:cdefb=i,22.6,GE,i,67.5,GE,UNKN,INF,IF,UNKN,IF  \
	CDEF:cdefc=i,67.6,GE,i,112.5,GE,UNKN,INF,IF,UNKN,IF \
	CDEF:cdefd=i,112.6,GE,i,157.5,GE,UNKN,INF,IF,UNKN,IF \
	CDEF:cdefe=i,157.6,GE,i,202.5,GE,UNKN,INF,IF,UNKN,IF \
	CDEF:cdeff=i,202.6,GE,i,247.5,GE,UNKN,INF,IF,UNKN,IF \
	CDEF:cdefg=i,247.6,GE,i,292.5,GE,UNKN,INF,IF,UNKN,IF  \
	CDEF:cdefh=i,292.6,GE,i,337.5,GE,UNKN,INF,IF,UNKN,IF  \
	CDEF:cdefi=i,337.6,GT,i,360.99,GT,UNKN,INF,IF,UNKN,IF \
	HRULE:8#FF0000::dashes \
	AREA:cdefa#8D85F3:\"North\" \
	AREA:cdefb#9FA4EE:\"North East\"  \
	AREA:cdefc#F9FD5F:\"East\" \
	AREA:cdefd#EACC00:\"South East\\n\"  \
	AREA:cdefe#EAAF00:\"South\"  \
	AREA:cdeff#EA8F00:\"South West\"  \
	AREA:cdefg#00CF00:\"West\" \
	AREA:cdefh#6557D0:\"North West\\n\" \
	AREA:cdefi#8D85F3:\"\" \
	AREA:bb#C0C0C0:\"\" \
	LINE2:ba#562B29:\"\" \
	LINE1:bb#000000:\"\"
  ";

  $gen_his_graph = `$gen_his_graph_cmd`;

  if ( $debug ) {
    echo "<pre>\n";
    echo $gen_his_graph_cmd;
    echo "</pre>\n";
  }

} // gen_historical_graph

?>
