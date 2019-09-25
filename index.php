<?php 
	$memoria = "memoria.json";
	$pregunta = null;

	if(isset($_POST['pregunta'])){
		$recuerdos = json_decode(file_get_contents("./memoria.json"));
		$r = neurona($recuerdos,$_POST['pregunta']);
		if($r != $_POST['pregunta']){
			responder($_POST['pregunta'],$r);
		}else{
			$pregunta = $r;
		}
	}

	if(isset($_POST['recordar'])){
		$recuerdos = (array)json_decode(file_get_contents("./memoria.json"));
		$r = neurona($recuerdos,$_POST['pregunta']);
		$f = explode(',',$_POST['recordar']);
		$recuerdos[$_POST['pregunta']] = $f;
		aprender($recuerdos,$memoria);
		$pregunta = null;
	}

	function neurona($dato,$llave,$n = 0){
		if(isset($dato->$llave)){
			if(is_array($dato->$llave)){
				$cant = count($dato->$llave);
				$resp = rand(0,$cant - 1 );
				return  $dato->$llave[$resp];
			}else{
				return $dato->$llave;
			}
		}else{
			responder(null,'Disculpa apenas estoy aprendiendo.<br> ¿serias tan amable de enseñarme como deberia desponder a esa pregunta?<br>');
			return $llave;
		}
	}

	function responder($a = null,$b = null){
		if($a != null){
			print_r("Tu: ".$a."<br>");
		}
		if ($b != null) {
			print_r("Bot: ".$b."<br>");
		}
	}

	function aprender($dato,$m){
		$path = "./".$m;
		if(!file_exists($path)){
			exit("No tengo memoria. no recuerdo nada");
		}
		
		if(file_put_contents($path,json_encode($dato))){
			responder(null,"Gracias");
		}else{
			responder(null,"No lo he aprendido<br>");
		}
	}


 ?>


 <!DOCTYPE html>
 <html lang="es">
 <head>
 	<meta charset="UTF-8">
 	<title>IA Chat</title>
 </head>
 <body>
	<form action="index.php" method="post">
		<?php if ($pregunta != null): ?>
			<label for="">Posibles respuestas:</label>
			<input type="text" name="recordar" placeholder="Respuestas"><button>Enseñar</button><br>
			<span>Separa las respuestas por , </span>
		<?php endif ?>
		<input type="<?php echo ($pregunta == null)?'text':'hidden'?>" name="pregunta" placeholder="Pregunta cualquier cosa" value="<?= $pregunta;?>" required>
		<?php if ($pregunta == null): ?>
			<button>Preguntar</button>
		<?php endif ?>
	</form>
 </body>
 </html>