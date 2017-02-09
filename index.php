<head>

<style type="text/css">


canvas,
img {
    image-rendering: crisp-edges;
    image-rendering: -moz-crisp-edges;
    image-rendering: -webkit-optimize-contrast;
    image-rendering: optimize-contrast;
    -ms-interpolation-mode: nearest-neighbor;
}

.noselect {
  -webkit-touch-callout: none; /* iOS Safari */
    -webkit-user-select: none; /* Safari */
     -khtml-user-select: none; /* Konqueror HTML */
       -moz-user-select: none; /* Firefox */
        -ms-user-select: none; /* Internet Explorer/Edge */
            user-select: none; /* Non-prefixed version, currently
                                  supported by Chrome and Opera */
}



canvas
{

margin: -9px;
overflow: hidden;
z-index: 100;
}


#canvas_div
{

overflow: hidden;
margin: 0px;
width: 100%;
height: 100%;

}


.dot
{
width: 7px;
height: 7px;
position: absolute;
background: white;
border: 2px solid black;
border-radius: 100px;
z-index: 1000;
}




#toggleDraw
{
background-image: url('https://maxcdn.icons8.com/Share/icon/Editing//delete1600.png');
background-size: 50px 50px;
background-position: 10px;
background-repeat: no-repeat;
background-color: #bfffec;
width: 70px;
height: 70px;
position: absolute;
border-radius: 100px;
right: 80px;
top: 50px;
transition: 0.5s;
cursor: pointer;
}

#toggleDraw:hover
{


background-color: #f9ffbf;

}








</style>

</head>


<body style="overflow: hidden;" class="noselect">


<div style="position: absolute;">
<h3 id="x-coord">X: 0</h3>
<h3 id="y-coord">Y: 0</h3>
</div>




<div id="toggleDraw" onclick="delete_everything();"></div>
<div style="top: 130px; background-image: url('http://www.endlessicons.com/wp-content/uploads/2012/12/back-icon-614x460.png')" id="toggleDraw" onclick="undo();"></div>



<div id="canvas_div">

<canvas id="canvas" width="3000" height="3000"></canvas>






<script type="text/javascript">


//Масив Координат Точок
var coordsX = [];
var coordsY = [];

//Миша
var mousePosition;
//Відступ Від Точки До Низа/Верху Экрана
var offset = [0,0];
//Перевіряє, чи миша зажата(для переміщення точок)
var isDown = false;
//Canvas Id потрібен для управління точками і їх відображені (z-index більше ніж у canvas)
//Canvas Id змінити на любий div, в який буде поміщенно canvas
var canvas_div = document.getElementById("canvas_div");
//Дістаємо Canvas
var canvas = document.getElementById("canvas");
var ctx=canvas.getContext("2d");

//Тут Можна Загрузити Любий Image
var img = new Image();
img.src = 'http://213.108.75.77:40404/pass/upload/schemas/1478792239Map-Anim.gif';

//Відображення Canvas
canvas.style.backgroundImage = "url('"+img.src+"')";
canvas.style.backgroundRepeat = "no-repeat";
canvas.style.cursor = "crosshair";

//Закріпленно ширину і висоту за зображенням (рядок 144)
canvas.width = img.width;
canvas.height = img.height;

//Переключається на true, коли малюнок закріпленно останьою точкою
var is_drawn = Boolean(false)

//Дозвіл на малювання точки(перемикається кнопкою)
var draw_permission = Boolean(true);








//Створення Точок

 
	 canvas_div.addEventListener('dblclick', dot_create, true);


	 

function dot_create(e) {
	

	
	if(draw_permission==true && is_drawn == false)
	{

		

		dot = document.createElement("div");
		dot.style.left = e.pageX-5;
		dot.style.top = e.pageY-5;
		dot.style.cursor = "pointer";
		dot.className = "dot";
		dot.id = coordsX.length;

		
		document.getElementById("canvas_div").appendChild(dot);



		//Якщо Координати поставленої точки і координати першої в діапазони 10 пікселів друг від друга, то закриваем(закріплюєм)

		  if(coordsX[0]-e.pageX<10 && coordsX[0]-e.pageX>-10 && coordsY[0]-e.pageY<10 && coordsY[0]-e.pageY>-10)
				  {

			  //Перемикаємо, щоб неможливо було ще ставити точку
			  is_drawn = true;

					  document.getElementById("canvas_div").removeChild(document.getElementById(coordsX.length));

					  var ctx=canvas.getContext("2d");
					  ctx.beginPath();


					  ctx.clearRect(0,0, canvas.width, canvas.height);
					  
					  for(var i=0; i<=coordsX.length; i++)
						{
							
							ctx.moveTo(coordsX[coordsX.length-i], coordsY[coordsY.length-i]);
							ctx.lineTo(coordsX[coordsX.length-(i+1)], coordsY[coordsY.length-(i+1)]);
							

							
						}


					  ctx.moveTo((document.getElementById(""+(coordsX.length-1)+"").offsetLeft + 7), (document.getElementById(""+(coordsY.length-1)+"").offsetTop + 7));
					  ctx.lineTo((document.getElementById("0").offsetLeft + 7), (document.getElementById("0").offsetTop + 7));


					  ctx.stroke();
					  
					  
				  }

		  else 
		  {

			  //Якщо точки не співпадають, то малюємо далі

			coordsX.push(document.getElementById(""+(coordsX.length)+"").offsetLeft + 7);
			coordsY.push(document.getElementById(""+(coordsY.length)+"").offsetTop + 7);


			var ctx=canvas.getContext("2d");
			ctx.clearRect(0,0, canvas.width, canvas.height);
			ctx.fillStyle = "rgba(0, 0, 200, 0.5)";
			ctx.beginPath();
			ctx.moveTo(coordsX[coordsX.length-2], coordsY[coordsY.length-2]);
			ctx.lineTo(coordsX[coordsX.length-1], coordsY[coordsY.length-1]);
			ctx.stroke();

			for(var i=0; i<coordsX.length; i++)
			{
				ctx.beginPath();
				ctx.moveTo(coordsX[coordsX.length-i], coordsY[coordsY.length-i]);
				ctx.lineTo(coordsX[coordsX.length-(i+1)], coordsY[coordsY.length-(i+1)]);
				ctx.stroke();
			}


		  }
			//Переміщення точки
			
			
			get_x_y();


			//Змінна для отримання id точки
		 var currant;

		  dot.addEventListener('mousedown', function(e) {
			    isDown = true;
			    currant = this.id;
			    offset = [
			        document.getElementById(currant).offsetLeft - e.clientX,
			        document.getElementById(currant).offsetTop - e.clientY
			    ];
			}, true);

		  document.addEventListener('mouseup', function(e) {
			    isDown = false;
			    

			    coordsX[currant] = e.clientX;
				coordsY[currant] = e.clientY;


			    currant = "";
			    
			}, true);




			//При переміщенні точки
			
			document.addEventListener('mousemove', function(event) {
			    event.preventDefault();

			    //Дані для запису в X i Y
			    get_x_y();
			    if (isDown) {
			        mousePosition = {

			            x : event.clientX,
			            y : event.clientY

			        };
			        document.getElementById(currant).style.left = (mousePosition.x + offset[0]) + 'px';
			        document.getElementById(currant).style.top  = (mousePosition.y + offset[1]) + 'px';

			        coordsX[currant] = document.getElementById(currant).offsetLeft + 6;
					coordsY[currant] = document.getElementById(currant).offsetTop + 6;

					var canvas = document.getElementById("canvas");
					var ctx=canvas.getContext("2d");
					ctx.clearRect(0,0, canvas.width, canvas.height);

					for(var i=0; i<coordsX.length; i++)
					{
						ctx.beginPath();
						ctx.moveTo(coordsX[coordsX.length-i], coordsY[coordsY.length-i]);
						ctx.lineTo(coordsX[coordsX.length-(i+1)], coordsY[coordsY.length-(i+1)]);
						ctx.stroke();
					}

					if(is_drawn==true)

					{

						ctx.beginPath();
						ctx.moveTo(coordsX[coordsX.length-1], coordsY[coordsY.length-1]);
						ctx.lineTo(coordsX[0], coordsY[0]);
						ctx.stroke();

					}

			        
			    }
			}, true);

			
		  

		  

	}

	
}



function undo(id)
{
	
	if(is_drawn==false)
	{

	document.getElementById("canvas_div").removeChild(document.getElementById(coordsX.length-1));
	coordsX.pop();
	coordsY.pop();


	var c=document.getElementById("canvas");
	c.width = window.innerWidth;
    c.height = window.innerHeight;
	var ctx=c.getContext("2d");

	for(var i=0; i<coordsX.length; i++)
	{
		ctx.beginPath();
		ctx.moveTo(coordsX[coordsX.length-i], coordsY[coordsY.length-i]);
		ctx.lineTo(coordsX[coordsX.length-(i+1)], coordsY[coordsY.length-(i+1)]);
		ctx.stroke();
	}

	}

	else
	{

		is_drawn=false

		var c=document.getElementById("canvas");
		c.width = window.innerWidth;
	    c.height = window.innerHeight;
		var ctx=c.getContext("2d");

		for(var i=0; i<coordsX.length; i++)
		{
			ctx.beginPath();
			ctx.moveTo(coordsX[coordsX.length-i], coordsY[coordsY.length-i]);
			ctx.lineTo(coordsX[coordsX.length-(i+1)], coordsY[coordsY.length-(i+1)]);
			ctx.stroke();
		}

	}

	get_x_y();

	
}

















function delete_everything()
{
	is_drawn = false;

	document.getElementById("x-coord").innerText="X: 0";
	document.getElementById("y-coord").innerText="Y: 0";

	var c=document.getElementById("canvas");
	c.width = window.innerWidth;
    c.height = window.innerHeight;
	var ctx=c.getContext("2d");

	ctx.clearRect(0,0,canvas.width,canvas.height);
coordsX.length = 0;
coordsY.length = 0;
	for(var i=0; i<=1000; i++)
	{
		document.getElementById("canvas_div").removeChild(document.getElementById(i));

		
	}


	

	

	
}





document.onkeypress = function(e) {
    e = e || window.event;
    var charCode = (typeof e.which == "number") ? e.which : e.keyCode;
    if (String.fromCharCode(charCode)=='z') {
    	undo();
    }
    else if(charCode==45) delete_everything();
};





















function get_x_y() {
	


function smallestX(){
	  if(coordsX[0] instanceof Array)
		  coordsX = coordsX[0];

	  return Math.min.apply( Math, coordsX );
	}
	function biggestX(){
	  if(coordsX[0] instanceof Array)
		  coordsX = coordsX[0];

	  return Math.max.apply( Math, coordsX );
	}

	var smalestX = smallestX(10, 11, 12, 13);
	var bigestX = biggestX(10, 11, 12, 13);
	

	var x = bigestX-smalestX;
	
	document.getElementById("x-coord").innerText="X: "+x;




	function smallestY(){
  	  if(coordsY[0] instanceof Array)
  		  coordsY = coordsY[0];

  	  return Math.min.apply( Math, coordsY );
  	}
  	function biggestY(){
  	  if(coordsY[0] instanceof Array)
  		  coordsY = coordsY[0];

  	  return Math.max.apply( Math, coordsY );
  	}

  	var smalestY = smallestY(10, 11, 12, 13);
  	var bigestY = biggestY(10, 11, 12, 13);
  	

  	var y = bigestY-smalestY;
  	
  	
  	document.getElementById("y-coord").innerText="Y: "+y;

}

  

  


</script>


</div>


</body>