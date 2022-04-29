$(document).ready(function() {

	$("#correoOK").hide();
	$("#correoMal").hide();
	$("#userOK").hide();
	$("#userMal").hide();

	$("#campoEmail").change(function(){
		const campo = $("#campoEmail"); // referencia jquery al campo
		campo[0].setCustomValidity(""); // limpia validaciones previas

		// validación html5, porque el campo es <input type="email" ...>
		const esCorreoValido = campo[0].checkValidity();
		if (esCorreoValido && correoValidoComplu(campo.val())) {
			// el correo es válido y acaba por @ucm.es: marcamos y limpiamos quejas
		
			// tu código aquí: coloca la marca correcta
			$("#correoMal").hide();
			$("#correoOK").show();
			campo[0].setCustomValidity("");
		} else {			
			// correo invalido: ponemos una marca y nos quejamos

			// tu código aquí: coloca la marca correcta
			$("#correoOK").hide();
			$("#correoMal").show();
			campo[0].setCustomValidity(
				"El correo debe ser válido y acabar por @ucm.es");
		}
	});

	
	$("#campoUser").change(function(){
		var url = "comprobarUsuario.php?user=" + $("#campoUser").val();
		$.get(url,usuarioExiste);
  });


	function correoValidoComplu(correo) {
		// tu codigo aqui (devuelve true ó false)
		return correo.substr(-7)=="@ucm.es";
	}

	function usuarioExiste(data,status) {
		// tu codigo aqui
		if(data=="existe"){
			$("#userOK").hide();
			$("#userMal").show();
			$("#campoUser").focus();
			status="El usuario ya existe, escoge otro";
			alert(status);
		}
		else if(data=="disponible"){
			$("#userMal").hide();
			$("#userOK").show();
		}
	}
})