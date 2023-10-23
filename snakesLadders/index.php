
<head>
<style>

body {
	font-family: Helvetica;
}
table {
	width: 98vh;
	border-collapse: collapse;
}
td {
	width: 9.8vh;
	height: 9.8vh;
	padding: 0.5vh;
}

</style>
</head>

<body>

<button style="position:absolute; width:98vh; height:98vh; background-color:transparent; border:1px solid red; top:10; z-index: 999" onclick="run()"></button><br />

<?php

function invertColor($value) {
	return (15-floor($value/16))*16+(15-$value%16);
}

$links = array('2'=>'97', '18'=>'37', '31'=>'28', '48'=>'67', '57'=>'36', '62'=>'79', '70'=>'89', '85'=>'63', '94'=>'100', '95'=>'21', '96'=>'37', '99'=>'4'); // change this only

echo '
<script>
alert(`Click on the board to play!`);
var gameLinks = {};
';
foreach ($links as $k=>$v) { echo 'gameLinks['.$k.'] = '.$v.";\n"; }
echo '</script>';

echo '<table style="position:relative; top:-15;" "border="0">';
for ($i=9;$i>=0;$i-=1) {
	echo '<tr>';
	for ($j=1;$j<=10;$j+=1) {
			$n = $i*10+$j;
			if ($i%2==1) $n = $i*10+11-$j;
			$r = rand(200,255); $g = rand(200,255); $b = rand(200,255);
			echo '<td valign="top" style="background-color:rgba('.$r.','.$g.','.$b.',1);">';
			echo '<b>'.$n.'</b>';
			if (!empty($links[$n])) echo '<br />Go to '.$links[$n];
			echo '</td>';
	}
	echo '</tr>';
}
echo '</table>';

?>

<script>

// prepare play;
var turn = 0;
let numberOfPlayers = 2; // don't change yet
if (numberOfPlayers > 6) {
	alert('Too many players!');
} else if (numberOfPlayers < 2) {
	alert('More players are needed...');
}
var players = new Array();
for (var i=0; i<numberOfPlayers; i++) {
	players.push(1);
}

function getRandomInt(max) {
	return Math.floor(Math.random() * Math.floor(max)) + 1;
}

var coord = new Object();
coord = getPos(document.getElementsByTagName('td')[90]);
var shifted = coord.x + Math.max(document.documentElement.clientHeight, window.innerHeight || 0)*(0.05);
console.log(shifted);

var chess0 = document.createElement('div');
chess0.setAttribute("id", "chess0");
chess0.innerHTML = `<span style="background-color:deeppink; color:white; width:4.9vh; height:4.9vh; position:absolute; z-index:9999; left:${coord.x}px; top:${coord.y}px; border-radius:3vh; text-align:center;"></span>`;
document.body.appendChild(chess0);

var chess1 = document.createElement('div');
chess1.setAttribute("id", "chess1");
chess1.innerHTML = `<span style="background-color:dodgerblue; color:white; width:4.9vh; height:4.9vh; position:absolute; z-index:9999; left:${shifted}px; top:${coord.y}px; border-radius:3vh; text-align:center;"></span>`;
document.body.appendChild(chess1);

function run() {
	
    //start game;
	if (!players.includes(100)) {
		let dice = getRandomInt(6);
		players[turn] += dice;
        if (players[turn] > 100) {
            players[turn] = 200-players[turn];
        }
        while (players[turn] in gameLinks) {
            players[turn] = gameLinks[players[turn]];
        }
//         console.log(players[turn] in gameLinks);
        console.log(turn, dice, players[turn]);
       	
        coord = new Object();
		if (Math.floor((players[turn]-1)/10)%2==0) {
			let index = (9-(Math.floor((players[turn]-1)/10)))*10 + (players[turn]-1)%10;
			coord = getPos(document.getElementsByTagName('td')[index]);
		} else {
			let index = 100-players[turn];
			coord = getPos(document.getElementsByTagName('td')[index]);
		}
		shifted = coord.x + Math.max(document.documentElement.clientHeight, window.innerHeight || 0)*(0.05);
		
		if (turn==0) {
		chess0.innerHTML = `<span style="background-color:deeppink; color:white; width:4.9vh; height:4.9vh; position:absolute; z-index:9999; left:${coord.x}px; top:${coord.y}px; border-radius:3vh; text-align:center;">${dice}</span>`;
		} else if (turn==1) {
		chess1.innerHTML = `<span style="background-color:dodgerblue; color:white; width:4.9vh; height:4.9vh; position:absolute; z-index:9999; left:${shifted}px; top:${coord.y}px; border-radius:3vh; text-align:center;">${dice}</span>`;
		}
        
        turn = (turn+1)%numberOfPlayers;
        
    } else {
    	if (turn == 0) { alert(`Blue wins!`); }
    	else if (turn == 1) { alert(`Pink wins!`); }
//         if (turn==0) { alert(`${numberOfPlayers-1} wins`); }
//         else { alert(`${turn-1} wins`); }
        window.location.reload();
    }
    
}

function getPos(el) {
	for (var lx=0, ly=0; el!=null; lx += el.offsetLeft, ly += el.offsetTop, el = el.offsetParent);
	return {x: lx, y: ly};
}



</script>


</body>
