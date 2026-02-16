<?php session_start();

// Si no hay idUsuario en la sesión, redirige al login
if (!isset($_SESSION["idUsuario"])) {
    header("Location: index.php");
    exit();
}

$usuario = $_SESSION["usuario"];
$idUsuario = $_SESSION["idUsuario"];
?>
<!DOCTYPE html>
<html>
<head>
	<title>Juego</title>
	<link rel="stylesheet" type="text/css" href="estilojuego.css">
</head>
<body>
	<div class="bloquesJuego">
		<div class="barraPuntuacion">
			<div id="btnAtras"><b>←</b></div>
			<div class="vidas">
				<p><b>VIDAS</b></p>
				<img id="vida1" src="./media/corazon.png">
				<img id="vida2" src="./media/corazon.png">
				<img id="vida3" src="./media/corazon.png">
			</div>
			<div class="puntuacion">
				<p><b>PUNTUACION</b></p>
				<p id="puntos" class="puntos"><b>0</b></p>
			</div>
			<div class="tiempo">
				<p><b>TIEMPO</b></p>
				<p class="reloj"><b id="reloj">00:00</b></p>
			</div>
		</div>
		<canvas id="juego"></canvas>
	</div>
	<div id="hasPerdido">
        <img class="salir" id="cerrar" src="./media/x.png" alt="icono de x para salir">
        <h2>¡HAS PERDIDO!</h2>
        <div class="botones">
	        <button id="reiniciarBtn">Reiniciar</button>
	        <button id="volverBtn">Volver</button>
	    </div>
    </div>
    <div id="hasGanado">
        <img class="salir" id="cerrar2" src="./media/x.png" alt="icono de x para salir">
        <h2>¡HAS GANADO!</h2>
        <div class="estadisticas">
	        <p>🧡 Vidas restantes: <span id="vidasFinal"></span></p>
	        <p>⭐ Puntuación: <span id="puntosFinal"></span></p>
	        <p>⏱ Tiempo: <span id="tiempoFinal"></span></p>
	    </div>
        <div class="botones">
	        <button id="reiniciarBtnGanado">Reiniciar</button>
	        <button id="volverBtnGanado">Volver</button>
	    </div>
    </div>
	<img hidden id="bloques" src="./media/marron.jpeg"/>
	<script type="text/javascript">
		const canvas =document.getElementById('juego');
		const vida1 =document.getElementById('vida1');
		const vida2 =document.getElementById('vida2');
		const vida3 =document.getElementById('vida3');
		const hasPerdido =document.getElementById('hasPerdido');
		const cerrar = document.getElementById("cerrar");
		const cerrar2 = document.getElementById("cerrar2");
		const reiniciar = document.getElementById("reiniciarBtn");
		const volver = document.getElementById("volverBtn");
		const reiniciarBtnGanado = document.getElementById("reiniciarBtnGanado");
		const volverBtnGanado = document.getElementById("volverBtnGanado");
		const puntos = document.getElementById("puntos");
		const reloj = document.getElementById("reloj");
		const hasGanado = document.getElementById("hasGanado");
		const vidasFinal = document.getElementById("vidasFinal");
		const puntosFinal = document.getElementById("puntosFinal");
		const tiempoFinal = document.getElementById("tiempoFinal");
		const ctx= canvas.getContext('2d');
		const $bloques= document.querySelector('#bloques');
		const rect =canvas.getBoundingClientRect();

		canvas.width=700;
		canvas.height=500;
		let pelotaEnJuego = false;
		let estasVivo=true;
		let vidasTotales=3;
		let cantidadPuntos=0;
		let tiempoInicio = null;
		let tiempoIntervalo = null;
		let puntuacionGuardada = false;
		const inicioRandom= Math.random() * (10 - (-10)) + (-10);

		cerrar.addEventListener("click", ()=>{
            hasPerdido.style.display='none';
        });

        cerrar2.addEventListener("click", ()=>{
            hasGanado.style.display='none';
        });


		//funciones objetos

		//variables de la pelota
		const pel={
			//posicion pelota inicial
			x: canvas.width/2,
		 	y: canvas.height -50,
		 	//radiopelota
			radius: 8,
			//velocidades
			vx: 0,
   			vy: 0
		};

		//variables de la barra
		const bar={
			//posicion de la barra
			x: 275,
			y: canvas.height -30,
			//ancho y alto
			ancho: 150,
			alto: 10,
			//velocidad barra
			vx: 10
		};

		function pelota(){
			ctx.beginPath();
			ctx.arc(pel.x, pel.y, pel.radius, 0, Math.PI * 2);
			ctx.fillStyle = "#4c370f";
			ctx.fill();
			ctx.closePath();
		};

		function movimientoPelota(){
			pel.x += pel.vx;
			pel.y += pel.vy;
		}

		function barra(){
			ctx.fillRect(bar.x,bar.y,bar.ancho,bar.alto);
			ctx.fillStyle = "#4c370f";
		};

		/* VARIABLES DE LOS LADRILLOS */
		  const numeroLadrillosFilas = 6;
		  const numeroLadrillosColumnas = 30;
		  const anchoLadrillo = 20;
		  const altoLadrillo = 20;
		  const separacionLadrillos = 2;
		  const separacionArriba = 50;
		  const separacionIzquierda = 26;
		  const ladrillos = [];

		  const ESTADO_LADRILLO = {
		    VIVO: 1,
		    DESTRUIDO: 0
		  }

		  
		  for (let c = 0; c < numeroLadrillosColumnas; c++) {
		    ladrillos[c] = [] // inicializamos con un array vacio
		    for (let r = 0; r < numeroLadrillosFilas; r++) {
		      // calculamos la posicion del ladrillo en la pantalla
		      const ladrilloX = c * (anchoLadrillo + separacionLadrillos) + separacionIzquierda
		      const ladrilloY = r * (altoLadrillo + separacionLadrillos) + separacionArriba
		      // Asignar un color aleatorio a cada ladrillo
		      const random = Math.floor(Math.random() * 8)
		      // Guardamos la información de cada ladrillo
		      ladrillos[c][r] = {
		        x: ladrilloX,
		        y: ladrilloY,
		        status: ESTADO_LADRILLO.VIVO,
		        color: random
		      }
		    }
		  }
		function bloques() {
		    for (let c = 0; c < numeroLadrillosColumnas; c++) {
		      for (let r = 0; r < numeroLadrillosFilas; r++) {
		        const ladrilloActual = ladrillos[c][r]
		        if (ladrilloActual.status === ESTADO_LADRILLO.DESTRUIDO) continue;

		        const clipX = ladrilloActual.color * 32  //borrar esto era de cuando teniamos bloques de color

		        ctx.drawImage(
		          $bloques,
		          clipX,
		          0,
		          anchoLadrillo,
		          altoLadrillo,
		          ladrilloActual.x,
		          ladrilloActual.y,
		          anchoLadrillo,
		          altoLadrillo
		        )
		      }
		    }
		  }

		function limpiarPantalla() {
		    ctx.clearRect(0, 0, canvas.width, canvas.height);
		}

		function guardarPuntuacion() {
		    fetch("guardarPuntuacion.php", {
		        method: "POST",
		        headers: {
		            "Content-Type": "application/x-www-form-urlencoded"
		        },
		        body: "puntuacion=" + cantidadPuntos + "&idnivel=1"
		    })
		    .then(res => res.text())
		    .then(data => {
		        console.log("Guardado:", data);
		    });
		}

		function movimientoBarra() {
		  if (!movimientoBarra.iniciado) {

		    document.addEventListener("keydown", (e) => {
		      if (e.key === "ArrowRight") {
		        bar.direccion = 1;
		      } else if (e.key === "ArrowLeft") {
		        bar.direccion = -1;
		      }
		    });

		    document.addEventListener("keyup", (e) => {
		      if (e.key === "ArrowRight" || e.key === "ArrowLeft") {
		        bar.direccion = 0;
		      }
		    });

		    document.addEventListener("mousemove", (e) => {
			    const rect = canvas.getBoundingClientRect(); //recogemos el tamaño y la posicion del canvas

			    const mouseX = e.clientX - rect.left;

			    bar.x = mouseX - bar.ancho / 2;

			    // Limitamos el listener a dentro del canvas
			    if (bar.x < 0) bar.x = 0;
			    if (bar.x + bar.ancho > canvas.width) {
			        bar.x = canvas.width - bar.ancho;
			    }
			});

		    bar.direccion = 0;
		    movimientoBarra.iniciado = true;

		  }

		  bar.x += bar.direccion * bar.vx;
		}

		function colision(){
			//colision con paredes
			if (pel.x + pel.radius > canvas.width || pel.x - pel.radius < 0) {
			    pel.vx *= -1;
			  }

			  if (pel.y - pel.radius < 0) {
			    pel.vy *= -1;
			  }
		    // Colisión con barra
		    if (
		        pel.x + pel.radius > bar.x &&
		        pel.x - pel.radius < bar.x + bar.ancho &&
		        pel.y + pel.radius > bar.y &&
		        pel.y - pel.radius < bar.y + bar.alto
		    ) {
		    	//encontramos el centro de la barra sumando el extremo izquierdo y sumandole la mitad del ancho
		        let centroBarra = bar.x + bar.ancho / 2;

		    	//variable de la distancia que hay entre la pelota y el centro de la barra al chocar
			    let distancia = pel.x - centroBarra;

			    // Normalizamos entre -1 y 1
			    let porcentaje = distancia / (bar.ancho / 2);

			    // Limitamos para evitar valores extremos
			    porcentaje = Math.max(-1, Math.min(1, porcentaje));

			    let velocidad = Math.sqrt(pel.vx * pel.vx + pel.vy * pel.vy);

			    // Ángulo máximo de rebote (60 grados)
			    let anguloMax = Math.PI / 3;

			    let angulo = porcentaje * anguloMax;

			    pel.vx = velocidad * Math.sin(angulo);
			    pel.vy = -velocidad * Math.cos(angulo);
		    }
		}

		function choqueLadrillos(){
			// Colisión con ladrillos
			for (let c = 0; c < numeroLadrillosColumnas; c++) {
			  for (let r = 0; r < numeroLadrillosFilas; r++) {

			    const ladrillo = ladrillos[c][r];

			    if (ladrillo.status === ESTADO_LADRILLO.VIVO) {

			      if (
			        pel.x + pel.radius > ladrillo.x &&
			        pel.x - pel.radius < ladrillo.x + anchoLadrillo &&
			        pel.y + pel.radius > ladrillo.y &&
			        pel.y - pel.radius < ladrillo.y + altoLadrillo
			      ) {
			        pel.vy *= -1; // rebote
			        ladrillo.status = ESTADO_LADRILLO.DESTRUIDO;
			        cantidadPuntos=cantidadPuntos+50;
			        puntos.textContent=cantidadPuntos;
			      }
			    }
			  }
			}

		}


		//vida
		
		function restarVidas(){
			if (pel.y + pel.radius > canvas.height) {
		        vidasTotales--;
		        if (vidasTotales === 2) vida3.style.display = "none";
		        else if (vidasTotales === 1) vida2.style.display = "none";
		        else if (vidasTotales === 0 && !puntuacionGuardada) {
		        	puntuacionGuardada = true;
		        	vida1.style.display = "none";
		        	estasVivo=false;
		        	hasPerdido.style.display= "block";
		        	detenerCronometro();
		        	guardarPuntuacion();
		        }

		        // Detenemos la pelota
		        pel.vx = 0;
		        pel.vy = 0;
		        pel.x = canvas.width / 2;
		        pel.y = canvas.height - 50;
		        pelotaEnJuego = false;
		    }
		}

		// Listener para iniciar pelota
		document.addEventListener("click", () => {
		    if (!pelotaEnJuego) {
		        reiniciarPelota();
		    }
		});

		// Listener para iniciar pelota
		document.addEventListener("keydown", (e) => {
		    if (e.code === "Space" && !pelotaEnJuego) {
		        reiniciarPelota();
		    }
		});

		
		//funcion reiniciar pelota
		function reiniciarPelota() {
			if (vidasTotales <= 0) return;
		    pel.x = canvas.width / 2;
		    pel.y = canvas.height - 50;
		    pel.vx = Math.random() * 10 - 5;
		    pel.vy = -2;
		    pelotaEnJuego = true;

		    if (!tiempoIntervalo) {
		        iniciarCronometro();
		    }

		}

		//function reiniciar partida
		function reiniciarPartida(){
			vidasTotales = 3;
		    vida1.style.display = "inline";
		    vida2.style.display = "inline";
		    vida3.style.display = "inline";

		    estasVivo = true;
		    cantidadPuntos = 0;
		    puntos.textContent = 0;

		    reloj.textContent = "00:00";
		    detenerCronometro();

		    // Resetear ladrillos
		    for (let c = 0; c < numeroLadrillosColumnas; c++) {
		        for (let r = 0; r < numeroLadrillosFilas; r++) {
		            ladrillos[c][r].status = ESTADO_LADRILLO.VIVO;
		        }
		    }

		    pel.vx = 0;
		    pel.vy = 0;
		    pel.x = canvas.width / 2;
		    pel.y = canvas.height - 50;
		    pelotaEnJuego = false;
		    puntuacionGuardada = false;
		    hasPerdido.style.display = "none";
		    hasGanado.style.display = "none";
			
		}

		reiniciar.addEventListener("click", reiniciarPartida);
		reiniciarBtnGanado.addEventListener("click", reiniciarPartida);

		function detenerCronometro() {
		    clearInterval(tiempoIntervalo);
		    tiempoIntervalo = null;
		}


		//cronometro
		function iniciarCronometro() {
		    tiempoInicio = Date.now();

		    tiempoIntervalo = setInterval(() => {
		        const tiempoActual = Math.floor((Date.now() - tiempoInicio) / 1000);

		        let min = Math.floor(tiempoActual / 60);
		        let seg = tiempoActual % 60;

		        reloj.textContent =
		            `${min.toString().padStart(2, "0")}:${seg.toString().padStart(2, "0")}`;
		    }, 1000);
		}

		//victoria
		function mostrarEstadisticasFinales() {
		    vidasFinal.textContent = vidasTotales;
		    puntosFinal.textContent = cantidadPuntos;
		    tiempoFinal.textContent = reloj.textContent;
		}
		function comprobarVictoria() {
		    let ladrillosRestantes = 0;

		    for (let c = 0; c < numeroLadrillosColumnas; c++) {
		        for (let r = 0; r < numeroLadrillosFilas; r++) {
		            if (ladrillos[c][r].status === ESTADO_LADRILLO.VIVO) {
		                ladrillosRestantes++;
		            }
		        }
		    }

		    if (ladrillosRestantes === 0) {
		    	puntuacionGuardada = true;
		        detenerCronometro();
		        mostrarEstadisticasFinales();
		        guardarPuntuacion();
		        hasGanado.style.display = "block";
		        pel.vx = 0;
		        pel.vy = 0;
		        pel.x = canvas.width / 2;
		        pel.y = canvas.height - 50;
		        pelotaEnJuego = false;
		    }
		}



		  

		//dentro de esta funcion tendremos todas las funciones que necesitaremos para el juego
		function pintar(){
			//estos son los diferentes objetos que tendremos que pueden interactuar
			limpiarPantalla();
			pelota();
			bloques();
			barra();
			choqueLadrillos();
			//otro tipo de funciones
			colision();
			//desaparecerbloque()
			//rebote()
			//puntos()
			//evento()
			movimientoPelota();
			movimientoBarra();
			restarVidas();
			//reiniciarPartida();
			comprobarVictoria();
			window.requestAnimationFrame(pintar);
		};
		pintar();


	</script>
</body>
</html>