<?php
namespace es\fdi\ucm\aw;
class FormularioEdicionValoracion extends Form{

    public function __construct()
    {
        parent::__construct('FormularioEdicionValoracion',);
    }

    protected function generaCamposFormulario($datos, $errores = array())
    {
        $id =$_POST['id'];
        $valoracion=Valoracion::buscaValoracionPorId($id);
        $titulo = $valoracion['titulo'];
        $texto = $valoracion['texto'];
        $nota = $valoracion['nota'];

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);
        $errorTitulo = self::createMensajeError($errores, 'titulo', 'span', array('class' => 'error'));
        $errorTexto = self::createMensajeError($errores, 'texto', 'span', array('class' => 'error'));
        $errorNota = self::createMensajeError($errores, 'nota', 'span', array('class' => 'error'));
        $html = <<<EOS
        $htmlErroresGlobales
        <div class="newComent">
        <h2> Modifica tu valoracion </h2>
        <p>Ten en cuenta que el resto de usuarios sabrán que lo has modificado</p>
        <div class="tituloComent">
            <input class="control" type="text" name="titulo" placeholder="Pon un título a tu valoracion" id="campoTitulo" value="$titulo" required/>$errorTitulo      
        </div>
        <div class="cajaComent">
            <textarea name="texto" id="campoTexto" required>$texto</textarea>$errorTexto
        </div>
        Puntuacion
        <div class='nota' value=$nota required>$errorNota 
                    <input type='radio' id='1estrella' name='nota' value='5' /><label class = 'full' for='1estrella'></label>
                    <input type='radio' id='2estrellas' name='nota' value='4' /><label class = 'full' for='2estrellas'></label>
                    <input type='radio' id='3estrellas' name='nota' value='3' /><label class = 'full' for='3estrellas'></label>
                    <input type='radio' id='4estrellas' name='nota' value='2' /><label class = 'full' for='4estrellas'></label>
                    <input type='radio' id='5estrellas' name='nota' value='1' /><label class = 'full' for='5estrellas'></label>
         </div> 
        <div class="buttonComent"><button type="submit" name="editar">Editar Valoracion</button></div>
        </div>
        <input type='hidden' name='id' value=$id>
    EOS;
        return $html;
    }
    protected function procesaFormulario($datos)
    {
        $result = array();
        $id =$datos['id'];
        $titulo = $datos['titulo'] ?? null;

        if (empty($titulo)) {
            $result['nombre'] = 'El titulo no puede estar vacío.';
        }
        $texto = $_POST['texto'] ?? null;
        if (empty($texto)) {
            $result['texto'] = 'La valoracion no puede estar vacía.';
        }
        $nota = $datos['nota'] ?? null;
        if (empty($nota)) {
            $result['nota'] = 'La nota no puede estar vacía.';
        }
        if (count($result) === 0) {
            $valor=Valoracion::buscaValoracionPorId($id);
            $Valoracion = new Valoracion($valor['idUsuario'], $valor['ubicacion'], $titulo, $texto, true,$nota);
            $Valoracion->setId($id);
            if (!Valoracion::guarda($Valoracion)) {
                $result[] = "Error al editar la valoracion";
            } else {
                $result[] = "Valoracion editada con éxito";
            }
        }
        return $result;
    }
  
}