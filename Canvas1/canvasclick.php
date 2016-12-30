<!-- START OF PROGRAM -->

<html>
	<head>
		<title>Canvas Lab</title>
		<style type="text/css">
			html{
				background-color: #CCFFCC;
			}

			canvas{
				background-color: white;
			}

			h1{
				font-family: 'Iceberg';
				color: #666699;
				font-size: 72px;
			}
		</style>
		<script type="text/javascript">
			var canvas;
			var context;
			var x1;
			var y1;
			var x2, lastX;
			var y2, lastY;
			var dotsX = [];
			var dotsY = [];
			var dotColor = [];
			var dotSizes = [];

			var isMouseDown = false;
			var drawing = false;
			var movingdot = false;
			var indexHitDot = 0;
			var actuallyMoved = false;
			//var answered = false;
			var keepborder = true;
			var ctrlpressed = false;
			var toggle = false;
			var dotsize = 15;
			var farthestdistancetraveled; 

			var actionType = []; //C- create, M- movement
			var arrayActions = []; 

			//Finish Undo/Replay buttons

			shiftKey = false;
			altKey = false;
			
			function init(){
				canvas = document.getElementById("myCanvas");
				context = canvas.getContext("2d");
				canvas.addEventListener("mousedown", setUp, false);
				canvas.addEventListener("mousemove", draw, false);
				canvas.addEventListener("mouseup", execute, false);
				document.getElementById("clear").addEventListener("click", btnfunc, false);
				document.getElementById("undo").addEventListener("click", undofunc, false);
				document.getElementById("replay").addEventListener("click", replayfunc, false);

				document.getElementById("save").addEventListener("click", savefunc, false);
				document.getElementById("load").addEventListener("click", loadfunc, false);

				function savefunc(){
					//alert("clicked");
					document.getElementById("xposvals").value = dotsX;
					document.getElementById("yposvals").value = dotsY;
					document.getElementById("dotcolorvals").value = dotColor;
					document.getElementById("dotsizevals").value = dotSizes;

					document.getElementById("mform").submit();
				}

				function loadfunc(){
					document.getElementById("mform2").submit();
				}

				function btnfunc(){
					context.clearRect(0, 0, canvas.width, canvas.height);
					dotsX = [];
					dotsY = [];
					dotColor = [];
					isMouseDown = false;
					drawing = false;

					actionType = [];
					arrayActions = [];

				}
				function undofunc(){
					var indexOfActionToUndo = actionType.length - 1;
					if(actionType[indexOfActionToUndo] == "C"){
						var dotIndexToUndo = arrayActions[indexOfActionToUndo][0];
						dotsX.splice(dotIndexToUndo, 1);
						dotsY.splice(dotIndexToUndo, 1);
						dotColor.splice(dotIndexToUndo, 1);
						actionType.splice(indexOfActionToUndo, 1);
						arrayActions.splice(indexOfActionToUndo, 1);
						context.clearRect(0, 0, canvas.width, canvas.height);
						for(var i = 0; i < dotColor.length; i++){
							context.beginPath();
							context.fillStyle = dotColor[i];
							context.arc(dotsX[i], dotsY[i], dotSizes[i], 0, 2*Math.PI, true);
							context.fill();
							context.closePath();
						}
					}
					else{
						var dotsToChange = arrayActions[indexOfActionToUndo][0];
						for(var i = 0; i < dotColor.length; i++){
							dotsX[dotsToChange[i]] = dotsX[dotsToChange[i]] - arrayActions[indexOfActionToUndo][1];
							dotsY[dotsToChange[i]] = dotsY[dotsToChange[i]] - arrayActions[indexOfActionToUndo][2];
						}
						actionType.splice(indexOfActionToUndo, 1);
						arrayActions.splice(indexOfActionToUndo, 1);

						context.clearRect(0, 0, canvas.width, canvas.height);
						for(var i = 0; i < dotColor.length; i++){
							context.beginPath();
							context.fillStyle = dotColor[i];
							context.arc(dotsX[i], dotsY[i], dotSizes[i], 0, 2*Math.PI, true);
							context.fill();
							context.closePath();
						}
					}

				}
				function replayfunc(){
					var localDotsX = [];
					var localDotsY = [];
					var i = 0;
					
					context.clearRect(0, 0, canvas.width, canvas.height);
					replayingDelay(localDotsX, localDotsY, i);

				}

				document.onkeyup = function(event){
					if(event.keyCode == 16){
						shiftKey = false;
					}
					if(event.keyCode == 18){
						altKey = false;
					}
				}

				document.onkeydown = function(event){
					if(event.keyCode == 27){
						executeEscapeKey();
					}
					if(event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40){
						fineTune(event.keyCode);
					}
					if(event.keyCode == 16){
						shiftKey = true;
					}
					if(event.keyCode == 18){
						altKey = true;
					}
					if(event.keyCode == 187){
						if(altKey && shiftKey){
							dotsize = dotsize + 1;
							console.log("alt plus");
						}
						else if(shiftKey){
							for(var i = 0; i < dotColor.length; i++){
								if(dotColor[i] == "#FF0000"){
									dotSizes[i] = dotSizes[i] + 1;
								}
							}
							context.clearRect(0, 0, canvas.width, canvas.height);
							for(var i = 0; i < dotColor.length; i++){
								context.beginPath();
								context.fillStyle = dotColor[i];
								context.arc(dotsX[i], dotsY[i], dotSizes[i], 0, 2*Math.PI, true);
								context.fill();
								context.closePath();
							}
							console.log("plus");
						}
					}
					if(event.keyCode == 189){
						if(altKey){
							dotsize = dotsize - 1;
							console.log("alt minus");
						}
						else{
							for(var i = 0; i < dotColor.length; i++){
								if(dotColor[i] == "#FF0000"){
									dotSizes[i] = dotSizes[i] - 1;
								}
							}
							context.clearRect(0, 0, canvas.width, canvas.height);
							for(var i = 0; i < dotColor.length; i++){
								context.beginPath();
								context.fillStyle = dotColor[i];
								context.arc(dotsX[i], dotsY[i], dotSizes[i], 0, 2*Math.PI, true);
								context.fill();
								context.closePath();
							}
							console.log("minus");
						}
					}
				};
			}

			function replayingDelay(localDotsX, localDotsY, i){
				context.clearRect(0, 0, canvas.width, canvas.height);
				if(actionType[i] == "C"){
					localDotsX.push(arrayActions[i][1]);
					localDotsY.push(arrayActions[i][2]);
					console.log("localDotsX: " + localDotsX);
					console.log("localDotsY: " + localDotsY);
				}
				else{
					for(var j = 0; j < arrayActions[i][0].length; j++){
						localDotsX[arrayActions[i][0][j]] = localDotsX[arrayActions[i][0][j]] + arrayActions[i][1];
						localDotsY[arrayActions[i][0][j]] = localDotsY[arrayActions[i][0][j]] + arrayActions[i][2];
					}
				
				}
				for(var k = 0; k < localDotsX.length; k++){
					context.beginPath();
					context.fillStyle = "#000000";
					context.arc(localDotsX[k], localDotsY[k], dotsize, 0, 2*Math.PI, true);
					console.log("drawn: " + localDotsX[k] + " " + localDotsY[k]);
					context.fill();
					context.closePath();
				}
				console.log("replaying step: " + i);
				i = i + 1;
				if(i < actionType.length){
					window.setTimeout(function(){ replayingDelay(localDotsX, localDotsY, i) }, 500);
				}
				else{
					context.clearRect(0, 0, canvas.width, canvas.height);
					for(var j = 0; j < dotColor.length; j++){
						context.beginPath();
						context.fillStyle = dotColor[j];
						context.arc(dotsX[j], dotsY[j], dotsize, 0, 2*Math.PI, true);
						context.fill();
						context.closePath();
					}
					console.log("Finished redraw");
				}
			}

			function fineTune(direction){
				//movement
					
				var tempMoveType;
				context.clearRect(0, 0, canvas.width, canvas.height);
				for(var i = 0; i < dotColor.length; i++){
					context.beginPath();
					if(dotColor[i] == "#FF0000"){
						if(direction == 37){
							dotsX[i]--;
							tempMoveType = 37;
						}
						else if(direction == 38){
							dotsY[i]--;
							tempMoveType = 38;
						}
						else if(direction == 39){
							dotsX[i]++;
							tempMoveType = 39;
						}
						else{
							dotsY[i]++;
							tempMoveType = 40;
						}
					}
					else{
					}
					context.fillStyle = dotColor[i];
					context.arc(dotsX[i], dotsY[i], dotSizes[i], 0, 2*Math.PI, true);
					context.fill();
					context.closePath();
				}

				actionType.push("M");
				if(tempMoveType == 37){
					arrayActions.push([[], -1, 0]);
				}
				else if(tempMoveType == 38){
					arrayActions.push([[], 0, -1]);
				}
				else if(tempMoveType == 39){
					arrayActions.push([[], 1, 0]);
				}
				else{
					arrayActions.push([[], 0, 1]);
				}
				
				for(var i = 0; i < dotColor.length; i++){
					if(dotColor[i] == "#FF0000"){
						arrayActions[arrayActions.length - 1][0].push(i);
					}
				}

				console.log("action type: " + actionType);
				console.log("array actions: " + arrayActions);
			}

			function executeEscapeKey(){
				context.clearRect(0, 0, canvas.width, canvas.height);
				for(var i = 0; i < dotColor.length; i++){
					context.beginPath();
					dotColor[i] = "#000000";
					context.fillStyle = dotColor[i];
					context.arc(dotsX[i], dotsY[i], dotSizes[i], 0, 2*Math.PI, true);
					context.fill();
					context.closePath();
				}
			}
			
			//getMousePos function from HTML5 Canvas Tutorials, url: http://www.html5canvastutorials.com/advanced/html5-canvas-mouse-coordinates/
			function getMousePos(event){
				var rect = canvas.getBoundingClientRect();
				return{
					x: event.clientX - rect.left,
					y: event.clientY - rect.top
				};
			}
			HTMLCanvasElement.prototype.getMousePos = getMousePos;
			
			function setUp(event){
				if(event.ctrlKey){
					ctrlpressed = true;
					//alert("ctr key pressed during click");
				}
				var pos = canvas.getMousePos(event);
				var posX = pos.x;
				var posY = pos.y;
				x1 = parseInt(posX);
				y1 = parseInt(posY);
				isMouseDown = true;
				if(isThereHit()){
					if(ctrlpressed){
						toggle = true;
					}
					else{
						movingdot = true;
						indexHitDot = findHitOnTop();
					}
					//alert(indexHitDot);
				}
			}

			function isThereHit(){
				for(var i = 0; i < dotColor.length; i++){
					var tempDistance = Math.sqrt((dotsX[i] - x1)*(dotsX[i]-x1) + (dotsY[i] - y1)*(dotsY[i]-y1));
					if(tempDistance <= dotSizes[i]){
						return true;
					}
				}
				return false;
			}

			function findHitOnTop(){
				var indexOfHits = [];
				for(var i = 0; i < dotColor.length; i++){
					var tempDistance = Math.sqrt((dotsX[i]-x1)*(dotsX[i]-x1) + (dotsY[i] - y1)*(dotsY[i] - y1));
					if(tempDistance <= dotSizes[i]){
						indexOfHits.push(i);
					}
				}
				return indexOfHits[indexOfHits.length - 1];
			}

			function draw(){
				if(isMouseDown && !movingdot){
					drawing = true;
					canvas.style.cursor = "crosshair";
					var pos2 = canvas.getMousePos(event);
					var posX = pos2.x;
					var posY = pos2.y;
					x2 = parseInt(posX);
					y2 = parseInt(posY);
					if(Math.sqrt((x2-x1)*(x2-x1) + (y2-y1)*(y2-y1)) > farthestdistancetraveled){
						farthestdistancetraveled = Math.sqrt((x2-x1)*(x2-x1) + (y2-y1)*(y2-y1));
					}
					//console.log(farthestdistancetraveled);
					context.clearRect(0, 0, canvas.width, canvas.height);
					context.beginPath();
					context.setLineDash([5]);
					context.rect(x1, y1, x2-x1, y2-y1);
					context.stroke();

					for(var i = 0; i < dotColor.length; i++){
						context.beginPath();
						context.fillStyle = dotColor[i];
						context.arc(dotsX[i], dotsY[i], dotSizes[i], 0, 2*Math.PI, true);
						context.fill();
					}
				}
				else if(isMouseDown && movingdot){
					var pos2 = canvas.getMousePos(event);
					var posX = pos2.x;
					var posY = pos2.y;

					lastX = posX;
					lastY = posY;

					var startMovingDotX = dotsX[indexHitDot];
					var startMovingDotY = dotsY[indexHitDot];
					var movePosX = posX - startMovingDotX;
					var movePosY = posY - startMovingDotY;
					if(movePosX != 0 || movePosY != 0){
						actuallyMoved = true;
					}
					context.clearRect(0, 0, canvas.width, canvas.height);
					if(dotColor[indexHitDot] == "#000000"){
						for(var i = 0; i < dotColor.length; i++){
							context.beginPath();
							if(i == indexHitDot){
								dotsX[i] = posX;
								dotsY[i] = posY;
								dotColor[i] = "#FF0000";
							}
							else{
								dotColor[i] = "#000000";
							}
							context.fillStyle = dotColor[i];
							context.arc(dotsX[i], dotsY[i], dotSizes[i], 0, 2*Math.PI, true);
							context.fill();
							context.closePath();
						}
					}
					else{
						for(var i = 0; i < dotColor.length; i++){
							context.beginPath();
							if(i == indexHitDot){
								dotsX[i] = posX;
								dotsY[i] = posY;
								dotColor[i] = "#FF0000";
							}
							else if(dotColor[i] == "#FF0000"){
								var newXRedDot = dotsX[i] + movePosX;
								var newYRedDot = dotsY[i] + movePosY;
								dotsX[i] = newXRedDot;
								dotsY[i] = newYRedDot;
							}
							else{
							}
							context.fillStyle = dotColor[i];
							context.arc(dotsX[i], dotsY[i], dotSizes[i], 0, 2*Math.PI, true);
							context.fill();
							context.closePath();
						}
					}
				}
				else{
				}
			}

			function execute(){
				//console.log("bounds: " + x1 + " " + y1 + " " + x2 + " " + y2);
				if(movingdot){
					movingdot = false;
					context.clearRect(0, 0, canvas.width, canvas.height);
					for(var i = 0; i < dotColor.length; i++){
						if(i == indexHitDot){
							dotColor[i] = "#FF0000";
						}
						else if(!actuallyMoved){
							dotColor[i] = "#000000";
						}
						context.beginPath();
						context.fillStyle = dotColor[i];
						context.arc(dotsX[i], dotsY[i], dotSizes[i], 0, 2*Math.PI, true);
						context.fill();
						context.closePath();
					}
					actuallyMoved = false;
					
					actionType.push("M");
					arrayActions.push([[], lastX - x1, lastY - y1]);
					for(var i = 0; i < dotColor.length; i++){
						if(dotColor[i] == "#FF0000"){
							arrayActions[arrayActions.length - 1][0].push(i);
						}
					}
					console.log("action type: " + actionType);
					console.log("array actions: " + arrayActions);

				}
				else if(drawing == false || farthestdistancetraveled < 5){
					if(ctrlpressed == false){
						context.clearRect(0, 0, canvas.width, canvas.height);
						for(var i = 0; i < dotColor.length; i++){
							if(dotColor[i] == "#FF0000"){
								dotColor[i] = "#000000";
							}
							context.beginPath();
							context.fillStyle = dotColor[i];
							context.arc(dotsX[i], dotsY[i], dotSizes[i], 0, 2*Math.PI, true);
							context.fill();
						}
						context.beginPath();
						context.fillStyle = "#FF0000";
						context.arc(x1, y1, dotsize, 0, 2*Math.PI, true);
						context.fill();
						context.closePath();
						dotsX.push(x1);
						dotsY.push(y1);
						dotColor.push("#FF0000");
						dotSizes.push(dotsize);
						//create dot
						actionType.push("C");
						arrayActions.push([dotColor.length - 1, x1, y1]);
						console.log("action type: " + actionType);
						console.log("array actions: " + arrayActions);
					}
					else{
						ctrlpressed = false;
						//console.log("toggle: " + toggle);
						if(toggle){
							indexHitDot = findHitOnTop();
							context.clearRect(0, 0, canvas.width, canvas.height);
							for(var i = 0; i < dotColor.length; i++){
								if(i == indexHitDot){
									if(dotColor[i] == "#000000"){
										dotColor[i] = "#FF0000";
									}
									else{
										dotColor[i] = "#000000";
									}
								}
								context.beginPath();
								context.fillStyle = dotColor[i];
								context.arc(dotsX[i], dotsY[i], dotSizes[i], 0, 2*Math.PI, true);
								context.fill();
								context.closePath();
							}
						}
						else{
							context.beginPath();
							context.fillStyle = "#FF0000";
							context.arc(x1, y1, dotsize, 0, 2*Math.PI, true);
							context.fill();
							context.closePath();

							dotsX.push(x1);
							dotsY.push(y1);
							dotColor.push("#FF0000");
							dotSizes.push(dotsize);
							//create dot
							actionType.push("C");
							arrayActions.push([dotColor.length - 1, x1, y1]);
							console.log("action type: " + actionType);
							console.log("array actions: " + arrayActions);
						}
					}
				}
				else{
					drawing = false;
					context.clearRect(0, 0, canvas.width, canvas.height);
					for(var i = 0; i < dotColor.length; i++){
						context.beginPath();
						var lowerXBound, upperXBound, lowerYBound, upperYBound;
						if(x1 >= x2){
							upperXBound = x1 + dotsize;
							lowerXBound = x2 - dotsize;
						}
						else{
							upperXBound = x2 + dotsize;
							lowerXBound = x1 - dotsize;
						}
						if(y1 >= y2){
							upperYBound = y1 + dotsize;
							lowerYBound = y2 - dotsize;
						}
						else{
							upperYBound = y2 + dotsize;
							lowerYBound = y1 - dotsize;
						}

						if(dotsX[i] < upperXBound && dotsX[i] > lowerXBound && dotsY[i] < upperYBound && dotsY[i] > lowerYBound){
							//console.log("(" + dotsX[i] + " " + dotsY[i] + ")");
							//console.log("inside");
							if(dotColor[i] == "#000000"){
								dotColor[i] = "#FF0000";
								context.fillStyle = "#FF0000";
							}
							else{
								context.fillStyle = dotColor[i];
							}
							context.arc(dotsX[i], dotsY[i], dotSizes[i], 0, 2*Math.PI, true);
						}
						else{
							//console.log("outside");
							if(ctrlpressed){
								context.fillStyle = dotColor[i];
							}
							else{
								dotColor[i] = "#000000";
								context.fillStyle = dotColor[i];
							}
							context.arc(dotsX[i], dotsY[i], dotSizes[i], 0, 2*Math.PI, true);
						}
						context.fill();
						context.closePath();
					}
				}
				isMouseDown = false;
				ctrlpressed = false;
				toggle = false;
				farthestdistancetraveled = 0;
				canvas.style.cursor = "default";
				context.fillStyle = "black";
			}

			function sleep(milliseconds){
				var startTime = new Date().getTime();
				for(var i = 0; i < 1e7; i++){
					if((new Date().getTime() - startTime) > milliseconds){
						break;
					}
				}
			}

			function dealWithLoading(loadedString){
				context.clearRect(0, 0, canvas.width, canvas.height);
	
				//alert("loaded: " + loadedString);
				var indexInLine = 0;
				var individualLines = [];
				for(var i = 0; i < 4; i++){
					var currLine = [];
					var currStr = "";
					while(loadedString.charAt(indexInLine) != '\n'){
						if(loadedString.charAt(indexInLine) == ','){
							currLine.push(currStr);
							currStr = "";
						}
						else{
							currStr = currStr + loadedString.charAt(indexInLine);
						}
						indexInLine++;
					}
					currLine.push(currStr);
					indexInLine++;
					individualLines.push(currLine);
				}
				//alert("dotsX: " + individualLines[0]);
				//alert("dotsY: " + individualLines[1]);
				//alert("dotColors: " + individualLines[2]);
				//alert("dotSizes: " + individualLines[3]);
				
				dotsX = [];
				dotsY = [];
				dotColor = [];
				dotSizes = [];
				for(var i = 0; i < individualLines[0].length; i++){
					dotsX.push(individualLines[0][i]);
					dotsY.push(individualLines[1][i]);
					dotColor.push(individualLines[2][i]);
					dotSizes.push(individualLines[3][i]);
				}
				
				//alert("should be here");
				//alert("updated: " + dotsX);
				//alert("updated: " + dotsY);
				//alert("updated: " + dotColor);
				//alert("updated: " + dotSizes);

				for(var i = 0; i < dotColor.length; i++){
					context.beginPath();
					context.fillStyle = dotColor[i];
					context.arc(dotsX[i], dotsY[i], dotSizes[i], 0, 2*Math.PI, true);
					context.fill();
				}
			}
		</script>
	</head>
	<body onload=init()>
		<center>
			<h1> <em>Your Masterpiece<em> </h1>
			<canvas id="myCanvas" style="border:1px solid black" width="1000px" height="500px"></canvas>
			<br><br>
			<button type=button accessKey=c id="clear"><u>C</u>lear</button>
			<button type=button accessKey=u id="undo" style="display:none"><u>U</u>ndo</button>
			<button type=button accessKey=r id="replay" style="display:none"><u>R</u>eplay</button>
			<button type=button accessKey=s id="save"><u>S</u>ave</button>
			<button type=button accessKey=l id="load"><u>L</u>oad</button>
			<br><br>
			<iframe name="myframe" style="display:none"></iframe>



			<form target="myframe" action="save.php" method="POST" id="mform">
				<input type="hidden" name="xvals" id="xposvals"/>
				<input type="hidden" name="yvals" id="yposvals"/>
				<input type="hidden" name="colorvals" id="dotcolorvals"/>
				<input type="hidden" name="sizevals" id="dotsizevals"/>
			</form>
			<form target="myframe" action="load.php" method="POST" id="mform2">
			</form>
		</center>
	</body>
</html>
