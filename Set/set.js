var allCards = [];
//var allCardsAttributes = ["1SRS", "2SRS", "3SRS", "1SPS", "2SPS", "3SPS", "1SGS", "2SGS", "3SGS", "1SRD", "2SRD", "3SRD", "1SPD", "2SPD", "3SPD"];
var allCardsAttributes = ["1SRS", "2SRS", "3SRS", "1SPS", "2SPS", "3SPS", "1SGS", "2SGS", "3SGS", "1SRD", "2SRD", "3SRD", "1SPD", "2SPD", "3SPD", "1SGD", "2SGD", "3SGD", "1SRO", "2SRO", "3SRO", "1SPO", "2SPO", "3SPO", "1SGO", "2SGO", "3SGO", "1CRS", "2CRS", "3CRS", "1CPS", "2CPS", "3CPS", "1CGS", "2CGS", "3CGS", "1CRD", "2CRD", "3CRD", "1CPD", "2CPD", "3CPD", "1CGD", "2CGD", "3CGD", "1CRO", "2CRO", "3CRO", "1CPO", "2CPO", "3CPO", "1CGO", "2CGO", "3CGO", "1ORS", "2ORS", "3ORS", "1OPS", "2OPS", "3OPS", "1OGS", "2OGS", "3OGS", "1ORD", "2ORD", "3ORD", "1OPD", "2OPD", "3OPD", "1OGD", "2OGD", "3OGD", "1ORO", "2ORO", "3ORO", "1OPO", "2OPO", "3OPO", "1OGO", "2OGO", "3OGO"];
var boardCards = [];
var boardCardsAttributes = [];
var setCards = [];
var setCardsAttributes = [];
var completed = [];
var numSetsCompleted = 0;
var numCompSetsInFirstCol = 14;
var congratulatoryMessages = ["Nice!", "Good one!", "Keep it up!", "You're doing great!"];
var start;
var points = 0;

window.onload = addListeners;

function addListeners(){
	document.getElementById("sbutton").addEventListener("click", btnfunc, false);
	function btnfunc(){
		setUp();
	}
	document.getElementById("notaset").addEventListener("click", tfunc, false);
	function tfunc(){
		addCards();
	}
	document.getElementById("gameover").addEventListener("click", endfunc, false);
	function endfunc(){
		declareEndGame();
	}
}

function declareEndGame(){
	//document.getElementById("message").innerText = "Refresh or click the Restart button to play again!";
	//document.querySelector("#board").style.visibility = "hidden";
	var elem = document.getElementById("board");
	elem.parentNode.removeChild(elem);
	elem = document.getElementById("notaset");
	elem.parentNode.removeChild(elem);
	elem = document.getElementById("gameover");
	elem.parentNode.removeChild(elem);
	document.querySelector("#tryagain").style.visibility = "visible";
	document.getElementById("tryagain").setAttribute("onclick", "location.reload()");

	var elapsed = new Date() - start;
	//alert(elapsed);
	var minutes = parseInt((elapsed / (1000*60)) % 60, 10);
	var seconds = parseInt((elapsed / 1000) % 60, 10);
	minutes = minutes < 10 ? "0" + minutes : minutes;
	seconds = seconds < 10 ? "0" + seconds : seconds;

	if(points < 10){
		document.getElementById("message").innerText = "Try Again! You got " + points + " points in " + minutes + ":" + seconds + ". Click Restart to play again.";
	}
	else if(points < 50){
		document.getElementById("message").innerText = "Keep working at it! You got " + points + " points in " + minutes + ":" + seconds + ". Click Restart to play again.";
	}
	else if(points < 100){
		document.getElementById("message").innerText = "You're pretty good at this! You got " + points + " points in " + minutes + ":" + seconds + ". Click Restart to play again.";
	}
	else{
		document.getElementById("message").innerText = "Nice work! You got " + points + " points in " + minutes + ":" + seconds + ". Click Restart to play again.";
	}
}

function addCards(){
	if(allCards.length == 0){
		document.getElementById("message").innerText = "Out of cards to add!";
	}
	else if(boardCards.length <= 12){
		for(i = 0; i < 3; i++){
			var randNum = Math.floor(Math.random() * allCards.length);
			boardCards.push(allCards[randNum]);
			boardCardsAttributes.push(allCardsAttributes[randNum]);
			allCards.splice(randNum, 1);
			allCardsAttributes.splice(randNum, 1);
		}

		var tableRef = document.getElementById("board");
		tableRef.deleteRow(0);
		tableRef.deleteRow(0);
		tableRef.deleteRow(0);
		var newRow;
		for(i = 0; i < 3; i++){
			newRow = tableRef.insertRow(tableRef.rows.length);
			for(j = 0; j < 5; j++){
				var newCell = newRow.insertCell(j);
				var img = document.createElement("IMG");
				img.setAttribute("src", boardCards[5*i+j]);
				img.setAttribute("width", 143);
				img.setAttribute("height", 93);
				img.id = 5*i+j + "";
				img.setAttribute("onclick", "updateSet(this.id)");
				img.setAttribute("class", "largetd");
				newCell.appendChild(img);
			}
		}
	}
	else{
		document.getElementById("message").innerText = "You already added cards!";
	}
}

function setUp(){
	start = new Date();
	removeStartButtonAndBr();
	document.querySelector("#board").style.visibility = "visible";
	document.querySelector("#notaset").style.visibility = "visible";
	document.querySelector("#smallheading").style.visibility = "visible";
	document.querySelector("#gameover").style.visibility = "visible";
	//document.querySelector("#tryagain").style.visibility = "visible";
	//document.getElementById("tryagain").setAttribute("onclick", "location.reload()");
	
	populateAllCards();
	populateBoardCards();
	
	var tableRef = document.getElementById("board");
	var newRow;
	for(i = 0; i < 3; i++){
		newRow = tableRef.insertRow(tableRef.rows.length);
		for(j = 0; j < 4; j++){
			var newCell = newRow.insertCell(j);
			var img = document.createElement("IMG");
			img.setAttribute("src", boardCards[4*i+j]);
			img.setAttribute("width", 143);
			img.setAttribute("height", 93);
			img.id = 4*i+j + "";
			img.setAttribute("onclick", "updateSet(this.id)");
			img.setAttribute("class", "largetd");
			newCell.appendChild(img);
		}
	}
}

function updateSet(clicked_id){
	if(setCards.indexOf(clicked_id) != -1){
		var i = setCards.indexOf(clicked_id);
		setCards.splice(i, 1);
		setCardsAttributes.splice(i, 1);
		document.getElementById(clicked_id).style.border = "";
	}
	else{
		setCards.push(clicked_id);
		setCardsAttributes.push(boardCardsAttributes[parseInt(clicked_id)]);
		document.getElementById(clicked_id).style.border = "2px solid red";
	}

	if(setCards.length == 3){
		var ifSet = checkForSet();
		if(ifSet == 1){
			completeSet();
		}
		else{
			for(i = 0; i < setCards.length; i++){
				document.getElementById(setCards[i]).style.border = "";
			}
			setCards = [];
			setCardsAttributes = [];
			points--;
		}
	}
}

function completeSet(){
	if(completed.length == 0){
		document.getElementById("left").style.width = "0px";
		document.getElementById("center").style.marginLeft = "0px";
	}

	console.log(setCards);
	console.log(boardCards);
	if(boardCards.length == 15){
		points += 3;
	}
	else{
		points += 6;
	}
	var randMsg = Math.floor(Math.random() * 4);
	document.getElementById("message").innerText = congratulatoryMessages[randMsg];
	numSetsCompleted = numSetsCompleted + 1;
	
	if(allCards.length == 0){
		updateBoardToMoveEndGame();
	}
	else{
		for(i = 0; i < setCards.length; i++){
			completed.push(boardCards[parseInt(setCards[i])]);
			if(boardCards.length <= 12){
				var randNum = Math.floor(Math.random() * allCards.length);
				boardCards[parseInt(setCards[i])] = allCards[randNum];
				boardCardsAttributes[parseInt(setCards[i])] = allCardsAttributes[randNum];
				allCards.splice(randNum, 1);
				allCardsAttributes.splice(randNum, 1);
			}
			else{
				boardCards[parseInt(setCards[i])] = "toremove";
			}
		}
		for(i = 0; i < setCards.length; i++){
			if(boardCards.indexOf("toremove") != -1){
				var index = boardCards.indexOf("toremove");
				boardCards.splice(index, 1);
				boardCardsAttributes.splice(index, 1);
			}
		}
		console.log(completed);
		
		document.querySelector("#completedboard").style.visibility = "visible";
		updateCompletedBoard();
	
		setCards = [];
		setCardsAttributes = [];
	
		updateBoardToMove();
	}
}

function updateBoardToMoveEndGame(){
	for(i = 0; i < setCards.length; i++){
		completed.push(boardCards[parseInt(setCards[i])]);
		boardCards[parseInt(setCards[i])] = "toremove";
	}
	for(i = 0; i < setCards.length; i++){
		if(boardCards.indexOf("toremove") != -1){
			var index = boardCards.indexOf("toremove");
			boardCards.splice(index, 1);
			boardCardsAttributes.splice(index, 1);
		}
	}
	console.log(boardCards);
	console.log(completed);
	document.querySelector("#completedboard").style.visibility = "visible";
	updateCompletedBoard();
	
	if(boardCards.length == 0){
		declareEndGame();
		//document.getElementById("message").innerText = "Congratulations! Refresh or click the Restart button to play again!";
		//document.querySelector("#board").style.visibility = "hidden";
		//var elem = document.getElementById("notaset");
		//elem.parentNode.removeChild(elem);
		//elem = document.getElementById("gameover");
		//elem.parentNode.removeChild(elem);
		//document.querySelector("#tryagain").style.visibility = "visible";
		//document.getElementById("tryagain").setAttribute("onclick", "location.reload()");
	}
	else if(boardCards.length >= 12){
		updateBoardToMove();
	}
	else{
		var tableRef = document.getElementById("board");
		var nRows = boardCards.length / 3;
		if(boardCards.length == 3 || boardCards.length == 6){
			for(i = 0; i < nRows+1; i++){
				tableRef.deleteRow(0);
			}
		}
		else{
			for(i = 0; i < nRows; i++){
				tableRef.deleteRow(0);
			}
		}
		var newRow;
		for(i = 0; i < nRows; i++){
			newRow = tableRef.insertRow(tableRef.rows.length);
			for(j = 0; j < 3; j++){
				var newCell = newRow.insertCell(j);
				var img = document.createElement("IMG");
				img.setAttribute("src", boardCards[3*i+j]);
				img.setAttribute("width", 143);
				img.setAttribute("height", 93);
				img.id = 3*i+j + "";
				img.setAttribute("onclick", "updateSet(this.id)"); 
				img.setAttribute("class", "largetd");
				newCell.appendChild(img);
			}
		}
	}
	setCards = [];
	setCardsAttributes = [];
}

function updateCompletedBoard(){
	var numRows = numSetsCompleted;
	if(numRows <= numCompSetsInFirstCol){
		var tableRef = document.getElementById("completedboard");
		for(i = 0; i < numRows-1; i++){
			tableRef.deleteRow(0);
		}
		var newRow;
		for(i = 0; i < numRows; i++){
			newRow = tableRef.insertRow(tableRef.rows.length);
			for(j = 0; j < 3; j++){
				var newCell = newRow.insertCell(j);
				var img = document.createElement("IMG");
				img.setAttribute("src", completed[3*i+j]);
				img.setAttribute("width", 50);
				img.setAttribute("height", 33);
				img.setAttribute("class", "smalltd");
				newCell.appendChild(img);
			}
		}
	}
	else{
		document.querySelector("#completedboardpt2").style.visibility = "visible";
		var tableRef = document.getElementById("completedboardpt2");
		numRows = numSetsCompleted - numCompSetsInFirstCol;
		for(i = 0; i < numRows-1; i++){
			tableRef.deleteRow(0);
		}
		var newRow;
		for(i = 0; i < numRows; i++){
			newRow = tableRef.insertRow(tableRef.rows.length);
			for(j = 0; j < 3; j++){
				var newCell = newRow.insertCell(j);
				var img = document.createElement("IMG");
				img.setAttribute("src", completed[numCompSetsInFirstCol*3+3*i+j]);
				img.setAttribute("width", 50);
				img.setAttribute("height", 33);
				img.setAttribute("class", "smalltd");
				newCell.appendChild(img);
			}
		}
	}
}

function updateBoardToMove(){
        var tableRef = document.getElementById("board");
        tableRef.deleteRow(0);
	tableRef.deleteRow(0);
	tableRef.deleteRow(0);
	var newRow;
        for(i = 0; i < 3; i++){
                newRow = tableRef.insertRow(tableRef.rows.length);
                for(j = 0; j < 4; j++){
                        var newCell = newRow.insertCell(j);
                        var img = document.createElement("IMG");
                        img.setAttribute("src", boardCards[4*i+j]);
                        img.setAttribute("width", 143);
                        img.setAttribute("height", 93);
                        img.id = 4*i+j + "";
                        img.setAttribute("onclick", "updateSet(this.id)");
			img.setAttribute("class", "largetd");
                        newCell.appendChild(img);
                }
        }
}

function checkForSet(){
	var set = 1;
	var failElements = "";
	if(notAllSameOrAllDifferent(setCardsAttributes[0].charAt(0), setCardsAttributes[1].charAt(0), setCardsAttributes[2].charAt(0))){
		failElements = failElements + "number ";
		set = 0;
	}
	if(notAllSameOrAllDifferent(setCardsAttributes[0].charAt(1), setCardsAttributes[1].charAt(1), setCardsAttributes[2].charAt(1))){
		failElements = failElements + "shade ";
		set = 0;
	}
	if(notAllSameOrAllDifferent(setCardsAttributes[0].charAt(2), setCardsAttributes[1].charAt(2), setCardsAttributes[2].charAt(2))){
		failElements = failElements + "color ";
		set = 0;
	}
	if(notAllSameOrAllDifferent(setCardsAttributes[0].charAt(3), setCardsAttributes[1].charAt(3), setCardsAttributes[2].charAt(3))){
		failElements = failElements + "shape ";
		set = 0;
	}
	document.getElementById("message").innerText = "Not a set, failed on: " + failElements;
	return set;
}

function notAllSameOrAllDifferent(c1, c2, c3){
	if(c1 == c2 && c1 == c3 && c2 == c3){
		return false;
	}
	if(c1 != c2 && c1 != c3 && c2 != c3){
		return false;
	}
	return true;
}

function populateBoardCards(){
	for(i = 0; i < 12; i++){
		var randNum = Math.floor(Math.random() * allCards.length);
		boardCards.push(allCards[randNum]);
		boardCardsAttributes.push(allCardsAttributes[randNum]);
		allCards.splice(randNum, 1);
		allCardsAttributes.splice(randNum, 1);
	}
}

function populateAllCards(){
	for(i = 1; i < allCardsAttributes.length+1; i++){
		var cardImg = "SetCards/" + i + ".gif";
		allCards.push(cardImg);
	}
}


function removeStartButtonAndBr(){
	var elem = document.getElementById("sbuttondiv");
	elem.parentNode.removeChild(elem);
	elem = document.getElementById("toberemovedbr1");
	elem.parentNode.removeChild(elem);
	elem = document.getElementById("toberemovedbr2");
	elem.parentNode.removeChild(elem);
	elem = document.getElementById("toberemovedbr3");
	elem.parentNode.removeChild(elem);
	elem = document.getElementById("headdirections");
	elem.parentNode.removeChild(elem);
	elem = document.getElementById("directions");
	elem.parentNode.removeChild(elem);
	elem = document.getElementById("scoringdirections");
	elem.parentNode.removeChild(elem);
	elem = document.getElementById("headscoring");
	elem.parentNode.removeChild(elem);
}
