<?php
namespace es\fdi\ucm\aw;


class FormularioComentario extends Form{
    public function __construct(){
        parent::__construct('FormularioComentario');
    }

    protected function generaCamposFormulario($datos, $errores = array()){ 
        $titulo = $datos['titulo'] ?? '';
        $texto = $datos['texto'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);
        $errorTitulo = self::createMensajeError($errores,'titulo','span',array('class' => 'error'));
        $errorTexto = self::createMensajeError($errores,'texto','span',array('class' => 'error'));
        $html = <<<EOS
        $htmlErroresGlobales
        <div class="grupo-control">
            <input class="control" type="text" name="titulo" placeholder="Titulo" id="campoTitulo" value="$titulo" required/>$errorTitulo      
        </div>
        <div class="grupo-control">
            <textarea" name="texto" placeholder="Comentario" id="campoTexto" required/>$errorTexto
        </div>
        <div class="grupo-control"><button type="submit" name="comentar">Publicar comentario</button></div>
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
            $result['texto'] = 'La texto no puede estar vacía.';
        }
        if (count($result) === 0) {
            $entrada = htmlspecialchars($_GET["entrada"]);
           $result[] = 'procesarEntradaBlog.php?entrada='.$entrada;
        }
        return $result;
    }
}