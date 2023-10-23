<?php



echo '
<!DOCTYPE html>
<html>
<head>
<style>
#m {
	height: 60vh;
	width: 100%;
}
#x {
background-color: red;
}
body {
	background-color: #f0f0f0;
	text-color: black;
	font-family: Helvetica;
	padding: 10px;
}
th {
	padding: 5px;
	text-align: left;
}
td {
	padding: 5px;
}
a {
	text-decoration: none;
}
button:hover {
	opacity: 70%;
}
</style>
</head>

<body>

<table>
<tr><td style="width:49vw; position: fixed; height:95vh; left: 10px; overflow-x: hidden; overflow-y: scroll; top: 10px;">
<form action="bus4.php" method="GET" style="width: 50vw;">
';

$colours = array('red', 'green', 'blue', 'yellow', 'purple', 'pink', 'orange');
$colours = array('magenta', 'chartreuse', 'aqua', 'lemonchiffon', 'plum', 'pink', 'coral');

for($t=1;$t<=7;$t++) {
	echo '
	<h3 style="width: 47vw; background-color:'.$colours[$t-1].'">Route '.$t.'</h3>
	<table>
	<tr><th>Company:</th>
	<td><input type="radio" name="co'.$t.'" value="kmb" '.($t==1?'required':'').' '.($_GET['co'.$t]=='kmb'?'checked':'').'> KMB / LWB</td>
	<td><input type="radio" name="co'.$t.'" value="ctb" '.($_GET['co'.$t]=='ctb'?'checked':'').'> Citybus</td>
	<td><input type="radio" name="co'.$t.'" value="nwfb" '.($_GET['co'.$t]=='nwfb'?'checked':'').'> NWFB</td>
	</tr>
	<tr><th>Bus Route Number:</th>
	<td colspan=3><input type="text" name="routeNo'.$t.'" style="text-transform:uppercase" '.($t==1?'required':'').' value="'.$_GET['routeNo'.$t].'"></td>
	</tr>
	<tr><th>Outbound/Inbound:</th>
	<td><input type="radio" name="dir'.$t.'" value="o" '.($_GET['dir'.$t]==''?'checked':($_GET['dir'.$t]=='o'?'checked':'')).'> Outbound</td>
	<td><input type="radio" name="dir'.$t.'" value="i" '.($_GET['dir'.$t]=='i'?'checked':'').'> Inbound</td>
	</tr>
	<tr><th>Service type:</th>
	<td colspan=3><input type="number" name="st'.$t.'" value="'.($_GET['st'.$t]!=''?$_GET['st'.$t]:'1').'"></td>
	</tr>
	</table>
	<br><br>
	';
}
echo '
<input type="submit" value="Submit">
</form>
</td>
';

if(!isset($_GET)) exit();

echo '
<td style="width: 49vw; margin: 10px 0 0 50vw; overflow-y: scroll; position: fixed; top: 0; left: 0; height: 95vh;">
<div id="m"></div>
';

$busList = array();
$list = array();

for($i=1;$i<=7;$i++) {
	if(isset($_GET['routeNo'.$i])) {
		array_push($busList,
			array('co' => $_GET['co'.$i], 'routeNo' => strtoupper($_GET['routeNo'.$i]), 'dir' => strtoupper($_GET['dir'.$i]), 'st' => $_GET['st'.$i])
		);
		array_push($list, $_GET['routeNo'.$i]);
	}
}
// print_r($busList);

function json($url) {
	$json = file_get_contents($url);
	$arr = json_decode($json, true);
	return $arr;
}
$stops = json('https://data.etabus.gov.hk/v1/transport/kmb/stop')['data'];
$routeStop = json('https://data.etabus.gov.hk/v1/transport/kmb/route-stop')['data'];
$latList = array();
$lngList = array();
$key = array();
$index = 0;
foreach($busList as $route) {
	if($route['co'] == 'kmb') { // kmb
		foreach($routeStop as $stop) {
			if($stop['route'] == $route['routeNo'] && $stop['bound'] == $route['dir'] && $stop['service_type'] == $route['st']) {
				foreach($stops as $s) {
					if($stop['stop'] == $s['stop']) {
						$lat = $s['lat']; $long = $s['long'];
						if(in_array($lat, $latList) && in_array($long, $lngList)) {
							$long += 0.00005*array_search($route, $list);
						}
						array_push($latList, $lat);
						array_push($lngList, $long);
						array_push($key, $route);
						break;
					}
				}
			}
		}
	} elseif($route['co'] == 'nwfb' || $route['co'] == 'ctb') { // nwfb / ctb
		$bravoStops = json('https://rt.data.gov.hk/v1.1/transport/citybus-nwfb/route-stop/'.$route['co'].'/'.$route['routeNo'].'/'.($route['dir']=='O'?'outbound':'inbound'))['data'];
		foreach($bravoStops as $s) {
			$stop = json('https://rt.data.gov.hk/v1.1/transport/citybus-nwfb/stop/'.$s['stop'])['data'];
			$lat = $stop['lat']; $long = $stop['long'];
			if($lat !== null && $long !== null) {
				if(in_array($lat, $latList) && in_array($long, $lngList)) {
					$long += 0.00005*array_search($route, $list);
				}
				array_push($latList, $lat);
				array_push($lngList, $long);
				}
			array_push($key, $route);
		}
		
	}
}

$latC = (min($latList)+max($latList))/2;
$lngC = (min($lngList)+max($lngList))/2;
$latR = (max($latList)-min($latList));
$lngR = (max($lngList)-min($lngList));
$z = 11 + floor(0.2/max($latR, $lngR))/2;

echo '
<script>
function initMap() {
var center = {lat:'.$latC.', lng:'.$lngC.'};
var m1 = new google.maps.Map(
document.getElementById("m"), {zoom: '.$z.', center: center});
';


for($i=0;$i<count($latList);$i++) {
	$colourIndex = array_search($key[$i], $busList);
	$colour = $colours[$colourIndex];
	echo '
	var loc = {lat:'.$latList[$i].', lng:'.$lngList[$i].'};
	var mar'.$i.' = new google.maps.Marker({
		position: loc,
		map: m1,
		icon: {
//			url: "https://maps.google.com/mapfiles/ms/icons/'.$colour.'-dot.png",
			path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
			scale: 2.5,
			strokeWeight: 1.5,
			rotation: '.(pow(-1,$colourIndex)*floor(($colourIndex+1)/2)*20).',
			strokeColor: "'.$colour.'"
		},
		opacity: 0.85,
	});
	';
	
// 	echo '
// 	 google.maps.event.addListener(mar'.$i.', "mouseover", function(event) {
//           console.log("mouseover");
//       });
//       google.maps.event.addListener(mar'.$i.', "mouseout", function(event) {
//           console.log("mouseout");
//       });
//       ';

}
echo '}';
$apiKey = 'AIzaSyDZXVM7_rvx_QCodrrLn2g9pYCDami0OKw';
echo '
</script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key='.$apiKey.'&callback=initMap">
</script>
<br>
';

echo '<b>Key</b><br>';
$k=0; $c=0; $n=0;
$kmbRoutes = array(); $ctbRoutes = array(); $nwfbRoutes = array();
for($i=0;$i<count($list);$i++) {
	if($busList[$i]['co'] == 'kmb') $k=1;
	if($busList[$i]['co'] == 'ctb') $c=1;
	if($buslIst[$i]['co'] == 'nwfb') $n=1;
}
if($k==1) $kmbRoutes = json('https://data.etabus.gov.hk/v1/transport/kmb/route/')['data'];
// if($c==1) $ctbRoutes =  json('https://rt.data.gov.hk/v1.1/transport/citybus-nwfb/route/ctb/')['data'];
// if($n==1) $nwfbRoutes =  json('https://rt.data.gov.hk/v1.1/transport/citybus-nwfb/route/nwfb/')['data'];
echo '<table>';
for($i=0;$i<count($list);$i++) {
	$x = array();
	echo '<tr><td><a href="';
	if($busList[$i]['co'] == 'kmb') {
		foreach($kmbRoutes as $route) {
			if($busList[$i]['routeNo'] == $route['route'] && $busList[$i]['dir'] == $route['bound'] && $busList[$i]['st'] == $route['service_type']) {
				$x = array('orig_tc' => $route['orig_tc'], 'dest_tc' => $route['dest_tc']);
				break;
			}
		}
		echo 'http://search.kmb.hk/KMBWebSite/?action=routesearch&route='.$list[$i].'&lang=zh-hk';
	} elseif($busList[$i]['co'] == 'nwfb' || $busList[$i]['co'] == 'ctb') {
		$x = json('https://rt.data.gov.hk/v1.1/transport/citybus-nwfb/route/'.$busList[$i]['co'].'/'.$busList[$i]['routeNo'])['data'];
		$orig = $x['orig_tc'];
		if($busList[$i]['dir'] == 'I') {
			$x['orig_tc'] = $x['dest_tc'];
			$x['dest_tc'] = $orig;
		}
		echo 'https://mobile.bravobus.com.hk/nwp3/?f=1&ds='.$list[$i].'&dsmode=1&l=1';
	}
	if($list[$i] != '') echo '" target="_blank" style="background-color:'.$colours[$i].'">&nbsp;'.$busList[$i]['routeNo'].'&nbsp;</a></td><td style="text-align: right;">'.$x['orig_tc'].'</td><td>→</td><td>'.$x['dest_tc'].'</td></tr>';
}
echo '
</table>
</td></tr></table>
';

echo '
</body>
</html>
';

?>