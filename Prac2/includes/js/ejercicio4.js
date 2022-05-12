$(document).ready(function() {

	$("#correoOK").hide();
	$("#correoMal").hide();
	$("#userOK").hide();
	$("#userMal").hide();
	$("#userNameOK").hide();
	$("#userNameMal").hide();
	$("#telefonoOK").hide();
	$("#telefonoMal").hide();
	$("#DNIOK").hide();
	$("#DNIMal").hide();
	$("#insertarNombreActividadOK").hide();
	$("#insertarNombreActividadMal").hide();
	$("#campoNombreCursoOK").hide();
	$("#campoNombreCursoMal").hide();

	$("#campoEmail").change(function(){
		const campo = $("#campoEmail"); // referencia jquery al campo
		campo[0].setCustomValidity(""); // limpia validaciones previas

		// validación html5, porque el campo es <input type="email" ...>
		const esCorreoValido = campo[0].checkValidity();
		if (esCorreoValido && correoValido(campo.val())) {
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
				"El correo debe ser válido.Ejemplo: ejemplo@elemplo.xxx");
		}
	});

	
	$("#campoUser").change(function(){
		const campo = $("#campoUser"); // referencia jquery al campo
		campo[0].setCustomValidity(""); // limpia validaciones previas

		// validación html5, porque el campo es <input type="email" ...>
		const esUsuarioValido = campo[0].checkValidity();
		if (esUsuarioValido && tamValido(campo.val())) {
			// el correo es válido y acaba por @ucm.es: marcamos y limpiamos quejas
		
			// tu código aquí: coloca la marca correcta
			var url = "jsFuncion.php?user=" + $("#campoUser").val();
			$.get(url,usuarioExiste);
			campo[0].setCustomValidity("");
		} else {			
			// correo invalido: ponemos una marca y nos quejamos

			// tu código aquí: coloca la marca correcta
			$("#userOK").hide();
			$("#userMal").show();
			campo[0].setCustomValidity(
				"El nombre de usuario debe tener por lo menos 5 catacteres");
		}
	});

	$("#campoUserName").change(function(){
		const campo = $("#campoUserName"); // referencia jquery al campo
		campo[0].setCustomValidity(""); // limpia validaciones previas

		// validación html5, porque el campo es <input type="email" ...>
		const esNombreValido = campo[0].checkValidity();
		if (esNombreValido && tamValido(campo.val())) {
			// el correo es válido y acaba por @ucm.es: marcamos y limpiamos quejas
		
			// tu código aquí: coloca la marca correcta
			$("#userNameOK").show();
			$("#userNameMal").hide();
			campo[0].setCustomValidity("");
		} else {			
			// correo invalido: ponemos una marca y nos quejamos

			// tu código aquí: coloca la marca correcta
			$("#userNameOK").hide();
			$("#userNameMal").show();
			campo[0].setCustomValidity(
				"El nombre completo debe tener por lo menos 5 catacteres");
		}
	});

	$("#campoPass1").change(function(){
		const campo = $("#campoPass1"); // referencia jquery al campo
		campo[0].setCustomValidity(""); // limpia validaciones previas

		// validación html5, porque el campo es <input type="email" ...>
		const esContraseniaValido = campo[0].checkValidity();
		if (esContraseniaValido && tamValido(campo.val())) {
			// el correo es válido y acaba por @ucm.es: marcamos y limpiamos quejas
		
			// tu código aquí: coloca la marca correcta
			campo[0].setCustomValidity("");
		} else {			
			// correo invalido: ponemos una marca y nos quejamos

			// tu código aquí: coloca la marca correcta
			campo[0].setCustomValidity(
				"La contraseña debe tener por lo menos 5 catacteres");
		}
	});

	$("#campoPass2").change(function(){
		const con1 = $("#campoPass1");
		const con2 = $("#campoPass2"); // referencia jquery al campo
		con2[0].setCustomValidity(""); // limpia validaciones previas

		// validación html5, porque el campo es <input type="email" ...>
		const esContraseniaValido = con2[0].checkValidity();
		if (esContraseniaValido && con1.val().toUpperCase() == con2.val().toUpperCase()) {
			// el correo es válido y acaba por @ucm.es: marcamos y limpiamos quejas
		
			// tu código aquí: coloca la marca correcta
			con2[0].setCustomValidity("");
		} else {			
			// correo invalido: ponemos una marca y nos quejamos

			// tu código aquí: coloca la marca correcta
			con2[0].setCustomValidity(
				"Las contraseñas deben coincidirse");
		}
	});

	$("#campoTelefono").change(function(){
		const campo = $("#campoTelefono"); // referencia jquery al campo
		campo[0].setCustomValidity(""); // limpia validaciones previas

		// validación html5, porque el campo es <input type="email" ...>
		const esCorreoValido = campo[0].checkValidity();
		if (esCorreoValido && telefonoValido(campo.val().toString())) {
			// el correo es válido y acaba por @ucm.es: marcamos y limpiamos quejas
		
			// tu código aquí: coloca la marca correcta
			$("#telefonoOK").show();
			$("#telefonoMal").hide();
			campo[0].setCustomValidity("");
		} else {			
			// correo invalido: ponemos una marca y nos quejamos

			// tu código aquí: coloca la marca correcta
			$("#telefonoOK").hide();
			$("#telefonoMal").show();
			campo[0].setCustomValidity(
				"El telefono tiene que tener 9 digitos");
		}
	});

	$("#campoDNI").change(function(){
		const campo = $("#campoDNI"); // referencia jquery al campo
		campo[0].setCustomValidity(""); // limpia validaciones previas

		// validación html5, porque el campo es <input type="email" ...>
		const esCorreoValido = campo[0].checkValidity();
		if (esCorreoValido && dniValido(campo.val())) {
			// el correo es válido y acaba por @ucm.es: marcamos y limpiamos quejas
		
			// tu código aquí: coloca la marca correcta
			$("#DNIOK").show();
			$("#DNIMal").hide();
			campo[0].setCustomValidity("");
		} else {			
			// correo invalido: ponemos una marca y nos quejamos

			// tu código aquí: coloca la marca correcta
			$("#DNIOK").hide();
			$("#DNIMal").show();
			campo[0].setCustomValidity(
				"El DNI/NIE no es valido");
		}
	});

	$("#campoActividad").change(function(){
		const campoActividad = $("#campoActividad"); // referencia jquery al campo
		var url = "jsFuncion.php?capacidad=" + $("#campoActividad").val();
		$.get( url , function( data ) {
			var o = new Option(data, data);
			var $el = $("#campoCurso");
			$el.empty();
			/// jquerify the DOM object 'o' so we can use the html method
			$(o).html(data);
			$("#campoCurso").append(data);
		  });

	});
	
	//Comprueba si el nombre de la actividad ya existe en la BD o no
	$("#insertarNombreActividad").change(function(){
		const campo = $("#insertarNombreActividad"); // referencia jquery al campo
		campo[0].setCustomValidity(""); // limpia validaciones previas

		// validación html5, porque el campo es <input type="email" ...>
		const esNombreValido = campo[0].checkValidity();
		var url = "jsFuncion.php?actividad=" + $("#insertarNombreActividad").val();
		$.get( url , function( data ) {
			if (esNombreValido && data == "disponible") {
				// el correo es válido y acaba por @ucm.es: marcamos y limpiamos quejas
			
				// tu código aquí: coloca la marca correcta
				$("#insertarNombreActividadMal").hide();
				$("#insertarNombreActividadOK").show();
				campo[0].setCustomValidity("");
			} else {			
				// correo invalido: ponemos una marca y nos quejamos
	
				// tu código aquí: coloca la marca correcta
				$("#insertarNombreActividadOK").hide();
				$("#insertarNombreActividadMal").show();
				campo[0].setCustomValidity(
					"¡La actividad ya existe!");
			}
		  });
	});
	
	$("#campoNombreCurso").change(function(){
		const campo = $("#campoNombreCurso"); // referencia jquery al campo
		campo[0].setCustomValidity(""); // limpia validaciones previas

		// validación html5, porque el campo es <input type="email" ...>
		const esNombreValido = campo[0].checkValidity();
		var url = "jsFuncion.php?actividad=" + $("#campoNombreCursoActividad").val() +"&curso=" + $("#campoNombreCurso").val() + "&usuario=admin";
		$.get( url , function( data ) {
			if (esNombreValido && data == "disponible") {
				// el correo es válido y acaba por @ucm.es: marcamos y limpiamos quejas
			
				// tu código aquí: coloca la marca correcta
				$("#campoNombreCursoMal").hide();
				$("#campoNombreCursoOK").show();
				campo[0].setCustomValidity("");
			} else {			
				// correo invalido: ponemos una marca y nos quejamos
	
				// tu código aquí: coloca la marca correcta
				$("#campoNombreCursoOK").hide();
				$("#campoNombreCursoMal").show();
				campo[0].setCustomValidity(
					"¡El curso ya existe!");
			}
		  });
	});

	//El precio y la hora se cambia segun el curso
	$("#campoCurso").change(function(){
		var url = "jsFuncion.php?actividad=" + $("#campoActividad").val() + "&curso=" +  $("#campoCurso").val();
		$.get( url , function( data ) {
			var arrayDeNum = data.split(' ');
			$("#campoHora").val(arrayDeNum[0]);
			$("#campoPrecio").val(arrayDeNum[1]);
		  });
	});

	//Borrar una actividad
	$("#borrarActividad").click(function(){
		var opcion = confirm("¿Deseas eliminar la actividad?");
		if (opcion == true) {
			var url = "jsFuncion.php?nombre=" + $("#nombreActividad").val() + "&estado=borrarActividad";
			$.get( url , function( data ) {
				if(data == "exito"){
					alert("¡La actividad: '" + $("#nombreActividad").val() + "' se borro correctamente!");
					window.location.href = "Actividades_Main.php";
				}
			  });
		}
		else{
			alert("La operacion fue cancelada");
		}
		console.log(opcion)
	});

	// Comprueba si es un DNI correcto (entre 5 y 8 letras seguidas de la letra que corresponda).

	// Acepta NIEs (Extranjeros con X, Y o Z al principio)
	function dniValido(dni) {
		var numero, let, letra;
		var expresion_regular_dni = /^[XYZ]?\d{5,8}[A-Z]$/;
	
		dni = dni.toUpperCase();
	
		if(expresion_regular_dni.test(dni) === true){
			numero = dni.substr(0,dni.length-1);
			numero = numero.replace('X', 0);
			numero = numero.replace('Y', 1);
			numero = numero.replace('Z', 2);
			let = dni.substr(dni.length-1, 1);
			numero = numero % 23;
			letra = 'TRWAGMYFPDXBNJZSQVHLCKET';
			letra = letra.substring(numero, numero+1);
			if (letra != let) {
				//alert('Dni erroneo, la letra del NIF no se corresponde');
				return false;
			}else{
				//alert('Dni correcto');
				return true;
			}
		}else{
			//alert('Dni erroneo, formato no válido');
			return false;
		}
	  }

	function telefonoValido(telefono) {
		// tu codigo aqui (devuelve true ó false)
    	return telefono.length == 9;
	}

	function correoValido(correo) {
		// tu codigo aqui (devuelve true ó false)
		var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    	return regex.test(correo) ? true : false;
	}

	function tamValido(variable) {
		// tu codigo aqui (devuelve true ó false)
		return variable.length > 4;
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