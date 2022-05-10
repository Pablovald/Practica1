<?php

namespace es\fdi\ucm\aw;


class FormularioValoracion extends Form
{
    public function __construct()
    {
        $sitio=array_key_first($_GET);
        $opciones['action'] = $_SERVER['PHP_SELF']."?" .$sitio."=". htmlspecialchars($_GET[$sitio]);
        parent::__construct('FormularioValoracion', $opciones);
    }

    protected function generaCamposFormulario($datos, $errores = array())
    {
        $titulo = $datos['titulo'] ?? '';
        $texto = $datos['texto'] ?? '';
        $nota = $datos['nota'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);
        $errorTitulo = self::createMensajeError($errores, 'titulo', 'span', array('class' => 'error'));
        $errorTexto = self::createMensajeError($errores, 'texto', 'span', array('class' => 'error'));
        $errorNota = self::createMensajeError($errores, 'nota', 'span', array('class' => 'error'));
        $html = <<<EOS
        $htmlErroresGlobales
        <div class="newComent">
        <h2> Déjenos un comentario </h2>
        <div class="tituloComent">
        <input class="control" type="text" name="titulo" placeholder="Pon un titulo a tu comentario" id="campoTitulo" value="$titulo" required/>$errorTitulo      
        </div>
        <div class="cajaComent">
            <textarea name="texto" placeholder="Comentario" id="campoTexto" required></textarea>$errorTexto
        </div>
        <div class = "puntos">
        Puntuacion
        <div class='nota' value="$nota" required>$errorNota 
                    <input type='radio' id='5estrellas' name='nota' value='5' /><label class = 'full' for='5estrellas'></label>
                    <input type='radio' id='45estrellas' name='nota' value='4.5' /><label class = 'half' for='45estrellas'></label>
                    <input type='radio' id='4estrellas' name='nota' value='4' /><label class = 'full' for='4estrellas'></label>
                    <input type='radio' id='35estrellas' name='nota' value='3.5' /><label class = 'half' for='35estrellas'></label>
                    <input type='radio' id='3estrellas' name='nota' value='3' /><label class = 'full' for='3estrellas'></label>
                    <input type='radio' id='25estrellas' name='nota' value='2.5' /><label class = 'half' for='25estrellas'></label>
                    <input type='radio' id='2estrellas' name='nota' value='2' /><label class = 'full' for='2estrellas'></label>
                    <input type='radio' id='15estrellas' name='nota' value='1.5' /><label class = 'half' for='15estrellas'></label>
                    <input type='radio' id='1estrellas' name='nota' value='1' /><label class = 'full' for='1estrellas'></label>
                    <input type='radio' id='05estrellas' name='nota' value='0.5' /><label class = 'half' for='05estrellas'></label>
         </div>    
        </div>
        <div class="buttonComent"><button type="submit" name="comentar">Publicar valoracion</button></div>
    EOS;
        return $html;
    }
    protected function procesaFormulario($datos)
    {
        $result = array();

        $titulo = $datos['titulo'] ?? null;

        if (empty($titulo)) {
            $result['nombre'] = 'El titulo no puede estar vacío.';
        }
        $texto = $_POST['texto'] ?? null;
        if (empty($texto)) {
            $result['texto'] = 'El comentario no puede estar vacío.';
        }
        $nota = $datos['nota'] ?? null;
        if (empty($nota)) {
            $result['nota'] = 'La nota no puede estar vacío.';
        }
        if (count($result) === 0) {
            $idUser = Usuario::buscaIdDelUsuario($_SESSION['nombreUsuario']);
            $sitio = array_key_first($_GET);
            $ubicacion = htmlspecialchars($_GET[$sitio]);
            $coment = Valoracion::creaV($idUser, $ubicacion, $titulo,$texto, false,$nota);
            if (!$sitio) {
                $result[] = "Error al publicar la valoracion";
            } else {
                $result[] = "Valoracion publicada con éxito";
            }
        }
        return $result;
    }
}
