<?php 
session_start();

// Si no existe idUsuario, crear usuario invitado temporal
if (!isset($_SESSION["idUsuario"])) {
    $_SESSION["usuario"] = "Usuario invitado";
    $_SESSION["idUsuario"] = "invitado_" . uniqid();
}

$usuario = $_SESSION["usuario"];
$idUsuario = $_SESSION["idUsuario"];
?>
<!DOCTYPE html>
<html>
<head>
	<title>Juego</title>
	<link rel="icon" href="../media/W.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="../css/estilojuego.css">
	<!-- Fuente de google fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Doto:wght@100..900&display=swap" rel="stylesheet">
</head>
<body>
	<div class="bloquesJuego">
		<div class="barraPuntuacion">
			<div id="btnAtras"><b>←</b></div>
			<div class="vidas">
				<p><b>VIDAS</b></p>
				<img id="vida1" src="../media/corazon.png">
				<img id="vida2" src="../media/corazon.png">
				<img id="vida3" src="../media/corazon.png">
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
        <img class="salir" id="cerrar" src="../media/x.png" alt="icono de x para salir">
        <h2>¡HAS PERDIDO!</h2>
		<img id="skull" src="../media/skull.png" alt="una calavera">
        <div class="botones">
	        <button id="reiniciarBtn">Reiniciar</button>
	        <button id="volverBtn">Volver</button>
	    </div>
    </div>
    <div id="hasGanado">
        <img class="salir" id="cerrar2" src="../media/x.png" alt="icono de x para salir">
        <h2>¡HAS GANADO!</h2>
		<img id="trofeo" src="../media/rectangulo.png" alt="trofeo">
        <div class="estadisticas">
	        <p>Vidas restantes: <span id="vidasFinal"></span></p>
	        <p>Puntuación: <span id="puntosFinal"></span></p>
	        <p>Tiempo: <span id="tiempoFinal"></span></p>
	    </div>
        <div class="botones">
	        <button id="volverBtnGanado">Volver</button>
			<button id="reiniciarBtnGanado">Reiniciar</button>
	    </div>
    </div>
	<img hidden id="bloques" src="../media/marron.jpeg"/>
	<img hidden id="powerCorazon" src="../media/europa2.png">
	<img hidden id="powerLinea" src="../media/Asia2.png">
	<img hidden id="powerBarra" src="../media/Oceania2.png">
	<img hidden id="powerPelotasRapidas" src="../media/Africa2.png">
	<img hidden id="powerExplosion" src="../media/America2.png">
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
		const btnAtras = document.getElementById("btnAtras");
		const ctx= canvas.getContext('2d');
		const $bloques= document.querySelector('#bloques');
		const rect =canvas.getBoundingClientRect();

		//power ups
		const powerCorazon = document.getElementById("powerCorazon");
		const powerLinea = document.getElementById("powerLinea");
		const powerBarra = document.getElementById("powerBarra");
		const powerPelotasRapidas = document.getElementById("powerPelotasRapidas");
		const powerExplosion = document.getElementById("powerExplosion");


		canvas.width=700;
		canvas.height=500;
		let pelotaEnJuego = false;
		let estasVivo=true;
		let vidasTotales=3;
		let cantidadPuntos=0;
		let tiempoInicio = null;
		let tiempoIntervalo = null;
		let puntuacionGuardada = false;
		let velocidadMultiplicador = 1; // 1 = velocidad normal, 2 = rápida
		const inicioRandom= Math.random() * (10 - (-10)) + (-10);


		//variables power ups
		const powerUps = [];
		const PROBABILIDAD_POWERUP = 0.2; // 20% de probabilidad

		const TIPOS_POWERUP = {
			VIDA_EXTRA: "vida",
			//MAS_PELOTAS: "maspelotas", power up futuro
			LINEA: "linea",
			EXPLOSION: "explosion",
			GRAVEDAD: "gravedad"
		};



		cerrar.addEventListener("click", ()=>{
            hasPerdido.style.display='none';
        });

        cerrar2.addEventListener("click", ()=>{
            hasGanado.style.display='none';
        });

        volver.addEventListener("click", () => {
	        window.location.href = "../index.php";
	    });
	    volverBtnGanado.addEventListener("click", () => {
	        window.location.href = "../index.php";
	    });

	    btnAtras.addEventListener("click", () => {
	        window.location.href = "../index.php";
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
   			vy: 0,
   			velocidadBase: 3
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
			vx: 10,
			activarLinea: false,
			activarExplosion: false
		};

		function pelota(){
			ctx.beginPath();
			ctx.arc(pel.x, pel.y, pel.radius, 0, Math.PI * 2);
			ctx.fillStyle = "#4c370f";
			ctx.fill();
			ctx.closePath();
		};

		function movimientoPelota(){
			pel.x += pel.vx * velocidadMultiplicador;
    		pel.y += pel.vy * velocidadMultiplicador;
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
			let tiempoTexto = reloj.textContent;
			let partes = tiempoTexto.split(":");
			let tiempoMySQL = `00:${partes[0]}:${partes[1]}`;
		    fetch("../controladores/guardarPuntuacion.php", {
		        method: "POST",
		        headers: {
		            "Content-Type": "application/x-www-form-urlencoded"
		        },
		        body: "puntuacion=" + cantidadPuntos + "&tiempo=" + tiempoMySQL
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

		function dibujarPowerUps() {
		    powerUps.forEach(p => {

		        if (p.tipo === TIPOS_POWERUP.VIDA_EXTRA) {
		            ctx.drawImage(
				        powerCorazon,
				        p.x - p.size,
				        p.y - p.size,
				        p.size * 2,
				        p.size * 2
				    );

				} else {

				    if (p.tipo === TIPOS_POWERUP.GRAVEDAD) {
					    const size = p.size;
					    ctx.drawImage(
					        powerBarra,
					        p.x - size,
					        p.y - size,
					        size * 2,
					        size * 2
					    );
					}
				    if (p.tipo === TIPOS_POWERUP.LINEA){
					    	ctx.drawImage(
					        powerLinea,
					        p.x - p.size,
					        p.y - p.size,
					        p.size * 2,
					        p.size * 2
					    );
				    } 
				    if (p.tipo === TIPOS_POWERUP.EXPLOSION) {
					    const anchoExplosion = 32;
					    const altoExplosion = 32;
					    ctx.drawImage(
					        powerExplosion,
					        p.x - anchoExplosion / 2,
					        p.y - altoExplosion / 2,
					        anchoExplosion,
					        altoExplosion
					    );
					}


				    ctx.fill();
				    ctx.closePath();
		        }
		    });
		}

		function moverPowerUps() {
		    powerUps.forEach(p => {
		        p.y += p.velocidad;
		    });
		}

		function detectarPowerUpBarra() {
		    for (let i = powerUps.length - 1; i >= 0; i--) {
		        const p = powerUps[i];

		        // Si toca barra
		        if (
		            p.x > bar.x &&
		            p.x < bar.x + bar.ancho &&
		            p.y + p.size > bar.y &&
		            p.y - p.size < bar.y + bar.alto
		        ) {
		            if (p.tipo === TIPOS_POWERUP.LINEA) {
		                bar.activarLinea = true;
		            } else if (p.tipo === TIPOS_POWERUP.EXPLOSION) {
		                pel.activarExplosion = true; // <-- bandera en la pelota
		            } else if (p.tipo === TIPOS_POWERUP.PELOTA_RAPIDA) {
				        activarPowerUp(p.tipo); // <-- velocidad
				    } else {
		                activarPowerUp(p.tipo);
		            }

		            powerUps.splice(i, 1); // eliminar power-up del array
		        }

		        // Si cae fuera del canvas
		        else if (p.y > canvas.height) {
		            powerUps.splice(i, 1);
		        }
		    }
		}


		function activarPowerUp(tipo) {

		    if (tipo === TIPOS_POWERUP.VIDA_EXTRA) {

			    if (vidasTotales < 3) {

			        vidasTotales++;

			        if (vidasTotales === 1) {
			            vida1.style.display = "inline";
			        }

			        if (vidasTotales === 2) {
			            vida2.style.display = "inline";
			        }

			        if (vidasTotales === 3) {
			            vida3.style.display = "inline";
			        }

			    }


		    } else if (tipo === TIPOS_POWERUP.BARRA_GRANDE) {

		        bar.ancho += 50;

		        setTimeout(() => {
		            bar.ancho -= 50;
		        }, 8000); // dura 8 segundos

		    } else if (tipo === TIPOS_POWERUP.GRAVEDAD) {
			    velocidadMultiplicador = 2; // aumenta velocidad
			    setTimeout(() => {
			        velocidadMultiplicador = 1; // vuelve a normal tras 15s
			    }, 15000);
			

		    }else if (tipo === TIPOS_POWERUP.LINEA) {
			    // Activar power-up línea
			    bar.activarLinea = true;  // marcamos que la próxima colisión destruirá la línea
			}else if (tipo === TIPOS_POWERUP.EXPLOSION) {
			    bar.activarLinea = true;
			    const radioExplosion = 60; // radio de destrucción en píxeles

			    for (let c = 0; c < numeroLadrillosColumnas; c++) {
			        for (let r = 0; r < numeroLadrillosFilas; r++) {
			            const ladrillo = ladrillos[c][r];

			            if (ladrillo.status === ESTADO_LADRILLO.VIVO) {
			                // Calculamos el centro del ladrillo
			                const centroX = ladrillo.x + anchoLadrillo / 2;
			                const centroY = ladrillo.y + altoLadrillo / 2;

			                const distancia = Math.hypot(centroX - bar.x - bar.ancho/2, centroY - bar.y - bar.alto/2);

			                if (distancia <= radioExplosion) {
			                    ladrillo.status = ESTADO_LADRILLO.DESTRUIDO;
			                    cantidadPuntos += 50;
			                }
			            }
			        }
			    }

			    puntos.textContent = cantidadPuntos;
			}


		}




		function choqueLadrillos(){
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

		                    if (bar.activarLinea) {
		                        // destruir toda la fila
		                        for (let c2 = 0; c2 < numeroLadrillosColumnas; c2++) {
		                            if (ladrillos[c2][r].status === ESTADO_LADRILLO.VIVO) {
		                                ladrillos[c2][r].status = ESTADO_LADRILLO.DESTRUIDO;
		                                cantidadPuntos += 50;
		                            }
		                        }
		                        bar.activarLinea = false;
		                    } 
		                    else if (pel.activarExplosion) {
		                        const radioExplosion = 60; // píxeles
		                        const centroX = ladrillo.x + anchoLadrillo / 2;
		                        const centroY = ladrillo.y + altoLadrillo / 2;

		                        for (let c2 = 0; c2 < numeroLadrillosColumnas; c2++) {
		                            for (let r2 = 0; r2 < numeroLadrillosFilas; r2++) {
		                                const l = ladrillos[c2][r2];
		                                if (l.status === ESTADO_LADRILLO.VIVO) {
		                                    const lCentroX = l.x + anchoLadrillo / 2;
		                                    const lCentroY = l.y + altoLadrillo / 2;
		                                    const distancia = Math.hypot(lCentroX - centroX, lCentroY - centroY);
		                                    if (distancia <= radioExplosion) {
		                                        l.status = ESTADO_LADRILLO.DESTRUIDO;
		                                        cantidadPuntos += 50;
		                                    }
		                                }
		                            }
		                        }

		                        pel.activarExplosion = false; // desactivamos después de usar
		                    } 
		                    else {
		                        // destruir solo este ladrillo
		                        ladrillo.status = ESTADO_LADRILLO.DESTRUIDO;
		                        cantidadPuntos += 50;
		                    }

		                    puntos.textContent = cantidadPuntos;

		                    // generar power-up aleatoriamente
		                    if (Math.random() < PROBABILIDAD_POWERUP) {
		                        const tipos = Object.values(TIPOS_POWERUP);
		                        const tipoRandom = tipos[Math.floor(Math.random() * tipos.length)];
		                        powerUps.push({
		                            x: ladrillo.x + anchoLadrillo / 2,
		                            y: ladrillo.y,
		                            tipo: tipoRandom,
		                            velocidad: 2,
		                            size: 15
		                        });
		                    }
		                }
		            }
		        }
		    }
		}




		//vida
		
		function restarVidas(){
			if (puntuacionGuardada) return;
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
		    let velocidadBase = 3; // velocidad base para que se note
		    pel.vx = (Math.random() * 2 - 1) * velocidadBase; // -3 a 3
		    pel.vy = -velocidadBase; // siempre hacia arriba al inicio
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
			if (puntuacionGuardada) return;
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
			dibujarPowerUps();
			moverPowerUps();
			detectarPowerUpBarra();
			comprobarVictoria();
			window.requestAnimationFrame(pintar);
		};
		pintar();


	</script>
</body>
</html>
