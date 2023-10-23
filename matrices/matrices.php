<?php

// binomial product
function bp($a, $b) { 
	$p = array();
	for($i=0;$i<count($a)+count($b)-1;$i++) {
		$c = 0;
		for($j=0;$j<count($a);$j++) {
			if($i >= $j) $c += $a[$j] * @$b[$i-$j];
		}
		array_push($p, $c);
	}
	return $p;
}
// binomial sum and difference
function bs($a, $b) {
	$p = array();
	for($i=0;$i<max(count($a),count($b));$i++) {
		array_push($p, @$a[$i]+@$b[$i]);
	}
	return $p;
}
function bd($a, $b) {
	$p = array();
	for($i=0;$i<max(count($a),count($b));$i++) {
		array_push($p, @$a[$i]-@$b[$i]);
	}
	return $p;
}
// for square matrices only
function multiply($M, $N) {
	$O = array();
	for($i=0;$i<count($M);$i++) {
		array_push($O, array());
		for($j=0;$j<count($N);$j++) {
			$c = 0;
			for($i2=0;$i2<count($N);$i2++) {
				$c += $M[$i][$i2]*$N[$i2][$j];
			}
			array_push($O[$i], $c);
		}
	}
	return $O;
}

// decimal to fraction
function dtf($n) {
	if($n==0) return 0;
	$d = 1; $t = 1e6;
	while (strval(round($d*$n)) != strval($d*$n) && $d < $t) {
		$d += 1;
	}
	if($d==1) return $n;
	elseif (strval(round($d*$n)) != strval($d*$n)) return round($n*$t)/$t;
	else return '<u>'.($d*$n).'</u><br>'.$d;
}

function identity($n) {
	$M = array();
	for($i=0;$i<$n;$i++) {
		array_push($M, array());
		for($j=0;$j<$n;$j++) {
			if($i==$j) {
				array_push($M[$i], 1);
			} else {
				array_push($M[$i],0);
			}
		}
	}
	return $M;
}

// determinant
function det($M) {
	if(count($M)==2) {
		return $M[0][0] * $M[1][1] - $M[0][1] * $M[1][0];
	}
	$c = 0;
	for($j=0;$j<count($M);$j++) {
		$submatrix = array();
		for($i2=1;$i2<count($M);$i2++) {
			array_push($submatrix, array());
			for($j2=0;$j2<count($M);$j2++) {
				if($j2!=$j) array_push($submatrix[$i2-1], $M[$i2][$j2]);
			}
		}
		$c += pow(-1,$j) * $M[0][$j] * det($submatrix);
	}
	return $c;
}

// binomial determinant
function bdet($M) {
	if(count($M)==2) {
		return bd(bp($M[0][0],$M[1][1]), bp($M[0][1],$M[1][0]));
	}
	$c = [0];
	for($j=0;$j<count($M);$j++) {
		$submatrix = array();
		for($i2=1;$i2<count($M);$i2++) {
			array_push($submatrix, array());
			for($j2=0;$j2<count($M);$j2++) {
				if($j2!=$j) array_push($submatrix[$i2-1], $M[$i2][$j2]);
			}
		}
		$c = bs($c, bp(bp([pow(-1,$j)], $M[0][$j]), bdet($submatrix)));
	}
	return $c;
}

echo '
<style>
body {
background-color: #f0f0f0;
font-family: Helvetica;
padding: 10px;
}
table {
border-collapse: collapse;
}
td {
padding: 10px;
text-align: center;
font-size: 20px;
}
</style>
';

// your matrix
$A = array(
[1,2,-2],
[-4,-3,4],
[0,2,-1]
);
$A = [[5,4],[1,2]];

// left-right table
echo '
<table border="1" style="width:100%;">
<tr>
<td style="background-color:lightgreen;">
<h2>Row reduce</h2></td>
<td style="background-color:yellow;"
><h2>Facts</h2></td></tr>
<td style="width:50%;">
';

// row reduce table
echo '<table>
<tr>
<td colspan="2" style="text-align:right;">A</td>
<td>=</td>
<td>';
displayMatrix($A);
echo '</td></tr>';

function displayMatrix($M) {
	echo '<table border="1">';
	foreach($M as $r) {
		echo '<tr>';
		foreach($r as $e) {
			echo '<td>'.dtf($e).'</td>';
		}
		echo '</tr>';
	}
	echo '</table>';
}
echo '</td></tr>';

// start row reduce
$f = 1; // scale factor of determinant
$ema = array(); // array of elementary matrices

$O = $A;
for($j=0;$j<count($O);$j++) {
	echo '<tr>';
	
	// divide whole row by a scalar
	$E = identity(count($O));
	$s = $O[$j][$j];
	$f *= $s;
	if($s != 0) {
		$E[$j][$j] = 1/$s;
		array_push($ema, $E);
		echo '<td>';
		displayMatrix($E);
		echo '</td><td>';
		displayMatrix($O);
		$O = multiply($E, $O);
		echo '</td><td>=</td><td>';
		displayMatrix($O);
		echo '</td>';
	}
	
	echo '</tr>';
	
	// add a multiple fo one row to another
	$E = identity(count($O));
	for($i=0;$i<count($O);$i++) {
		if($i != $j) {
			$E[$i][$j] = - $O[$i][$j];
		}
	}
	array_push($ema, $E);
	echo '<td>';
	displayMatrix($E);
	echo '</td><td>';
	displayMatrix($O);
	$O = multiply($E, $O);
	echo '</td><td>=</td><td>';
	displayMatrix($O);
	echo '</td>';
	
	echo '</tr>';
}
echo '</table></td>';
// end row reduce table

echo '<td valign="top" style="text-align:left; width: 50%;">';

$det = $f;
echo 'Determinant:<br>'.dtf($det).'<br><br>';

$tr = 0;
for($i=0;$i<count($A);$i++) {
	$tr += $A[$i][$i];
}
echo 'Trace:<br>'.dtf($tr).'<br><br>';

if($det==0) echo 'Matrix has no inverse.<br><br>';
else {
	echo 'Inverse:<br><table><tr><td style="padding:0">A<sup>-1</sup> =</td><td>';
	$inverse = $ema[count($ema)-1];
	for($i=count($ema)-1;$i>0;$i--) {
		$inverse = multiply($inverse, $ema[$i-1]);
	}
	displayMatrix($inverse);
	echo '</td></tr></table><br>';
}

// characterstic polynomial
echo 'Characteristic polynomial:<br>';
$N = $A;
for($i=0;$i<count($N);$i++) {
	for($j=0;$j<count($N);$j++) {
		if($i==$j) $N[$i][$j] = [-$N[$i][$j],1];
		else $N[$i][$j] = [-$N[$i][$j]];
	}
}
$cp = bdet($N);
for($k=count($cp)-1;$k>=0;$k--) {
	if($cp[$k] > 0 && $k < count($cp)-1)  echo '+';
	if($cp[$k] == -1) echo '-';
	if($cp[$k] < -1 || $cp[$k] > 1) echo $cp[$k];
	if(abs($cp[$k]) == 1 && $k == 0) echo '1';
	if($cp[$k] != 0) {
		if($k > 0) echo '&lambda;';
		if($k > 1) echo '<sup>'.$k.'</sup>';
	}
}
echo '<br><br>';

// eigenvalues (only for integers)
$eigenvalues = array();
if(strval(round($cp[0]))==strval($cp[0])) {
	$k = 0;
	while($cp[$k] == 0 && $k < count($cp)) $k++;
	$k = $cp[$k];
	for($i=-abs($k);$i<=abs($k);$i++) {
		if($i == 0 || $k % $i == 0) {
			$c = 0;
			for($j=0;$j<count($cp);$j++) {
				$c += $cp[$j] * pow($i, $j);
			}
			if($c == 0) array_push($eigenvalues, $i);
		}
	}
}

if(count($eigenvalues) < 1) echo '<font color=red>Eigenvalues are either irrational or complex.</font><br>';
else {
	echo 'Eigenvalue(s): '.join(', ', $eigenvalues).'<br>';
	if(count($eigenvalues) < count($cp)-1) echo '<font color=red>Other eigenvalues are irrational, repeated or complex.</font><br>';
}
echo '<br>';


if(count($eigenvalues) == count($cp)-1) {
	
	$P = array();
	for($i=0;$i<count($O);$i++) {
		array_push($P, array());
	}
	
	// eigenvectors (only for real diagonisable matrices)
	echo 'Eigenvector(s):<br>';
	foreach($eigenvalues as $e) {
		$O = $N;
		for($i=0;$i<count($O);$i++) {
			for($j=0;$j<count($O);$j++) {
				$c = 0;
				for($k=0;$k<count($O[$i][$j]);$k++) {
					$c += $O[$i][$j][$k] * pow($e, $k);
				}
				$O[$i][$j] = $c;
			}
		}
		// remove zero row
		$remove = -1;
		for($i=0;$i<count($O);$i++) {
			if(@array_count_values($O[$i])[0] == count($O)) $remove = $i;
		}
		if($remove >= 0) {
			array_push($O, $O[$remove]);
			array_splice($O, $remove, 1);
		}
		for($j=0;$j<count($O);$j++) {
			if($j < count($O)-1) $O[count($O)-1][$j] = 0;
			else $O[count($O)-1][$j] = 1;
		}
		// cramer rule
		$deto = det($O);
		if($deto == 0) {
			echo '<font color=red>When &lambda; = '.$e.', det = 0...</font><br>';
			exit();
		} else {
			echo '<table><tr><td style="padding:0">When &lambda; = '.$e.', v = (</td>';
			for($j=0;$j<count($O);$j++) {
				$K = $O;
				for($i=0;$i<count($O);$i++) {
					if($i < count($O)-1) $K[$i][$j] = 0;
					else $K[$i][$j] = 1;
				}
				$x = det($K)/$deto;
				array_push($P[$j], $x);
				echo '<td style="padding:0">'.dtf($x).'</td>';
				if($j<count($O)-1) echo '<td style="padding:0">, </td>';
				echo '</td>';
			}
			echo '<td style="padding:0">)</td></tr></table>';
		}
	}
	echo '<br>';
	
	// diagonalisation
	echo 'Diagonalisation:<br>';
	$D = identity(count($O));
	for($i=0;$i<count($D);$i++) {
		$D[$i][$i] = $eigenvalues[$i];
	}
	
	echo '<table><tr><td>A = </td><td>';
	displayMatrix($P);
	echo '</td><td>';
	displayMatrix($D);
	echo '</td><td>';
	displayMatrix($P);
	echo '</td>';
	echo '<td valign="top">-1</td></tr></table>';
	
}
echo '<br><br>';

echo '</td></tr></table>';


