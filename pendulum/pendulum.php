<?php

// mg sinθ = - m ℓ θ"
//  θ'' = - g/ℓ sinθ
//  θ(t=0) = π/2 (boundary)

// Euler
// θ(i+1) - 2θ(i) + θ(i-1) = - d² g/ℓ sin θ(i)
// θ(i+1) = k sin θ(i) + 2θ(i) - θ(i-1)

$n = 3000; // how many delta x's
$g = 9.81;
$l = 0.1775; // length of thread
$T = 2 * pi() * sqrt($l / $g); // period
$d = 2 / $n;
$k = - $d * $d * $g / $l;

$x = array(); // values of theta

$x[-1] = pi()/2;
$x[0] = pi()/2;
for($i=0;$i<$n;$i++) {
	$x[$i+1] = $k * sin($x[$i]) + 2 * $x[$i] - $x[$i-1];
}
// print_r($x);

// echo '<div style="width: 100%; height: 80%;">';
// echo '<style>';
// for($i=0;$i<=$n;$i++) {
// 	echo '
// 	#label'.$i.'{
// 		display:none;
// 	}
// 	#point'.$i.':hover #label'.$i.'{
// 		display: block;
// 	}
// 	';
// }
// echo '</style>';

function horizontalPi($a) {
	echo '<div style="position: absolute; top: '.(400-pi()*$a*150).'px; left: 0; width: 100%;">';
	echo '<span style="position: absolute; top: 0; left: 0; background-color: lightgray; height: 2px; width: 95%;"></span>';
	echo '<span style="position: absolute; top: 0; left: 95%;">&nbsp; &nbsp; ';
	if($a==0) {
		echo '0</span></div>';
	} else {
		echo ($a<0 && $a>-1 ? '-':'').(abs($a)>1 ? $a:'').'π'.(abs($a)<1 ? '/'.abs(1/$a):'').'</span></div>';
	}
}
function vertical($a) {
	echo '<div style="position: absolute; top: 0; left: '.($a/$GLOBALS['d']/$GLOBALS['n']*1300+10).'px; height: 100%;">';
	echo '<span style="position: absolute; top: 0; left: 0; background-color: lightgray; width: 2px; height: 95%;"></span>';
	echo '<span style="position: absolute; left: 0; top: 95%;">&nbsp; &nbsp;'.$a.'</span></div>';
}
horizontalPi(1/2);
horizontalPi(1/4);
horizontalPi(0);
horizontalPi(-1/4);
horizontalPi(-1/2);

for($i=0;$i<=$n;$i++) {
	$f = $x[$i];
	echo '
	<div id="point'.$i.'" style="position: absolute; background-color: green; width: 5px; height: 5px; border-radius: 2.5px; left: '.($i/$n*1300+10).'px; top: '.(400-$f*150).'px;"></div>
	';
// 	echo '
// 	<div id="label'.$i.'" style="position: absolute; border: 1px solid black; width: 200px; height: 100px; border-radius: 20px; right: 10px; top: 100px; padding: 10px;">('.$i.', '.$f.')</div>
// 	';
	
	$f = pi()/2*cos(2*pi()/1*$i*$d);
// 	echo '
// 	<div id="point'.$i.'" style="position: absolute; background-color: red; width: 5px; height: 5px; border-radius: 2.5px; left: '.($i/$n*1300+10).'px; top: '.(400-$f*150).'px;"></div>
// 	';
}
vertical(0);
vertical(1/2);
vertical(1);
vertical(3/2);
vertical(2);
// echo '</div>';

?>