<?php
namespace es\fdi\ucm\aw;
class FormularioEdicionComentario extends Form{

    public function __construct()
    {
        parent::__construct('FormularioEdicionComentario',);
    }

    protected function generaCamposFormulario($datos, $errores = array())
    {
        $id =$_POST['id'];
        $comentario=Comentario::buscaComentarioPorId($id);
        $titulo = $comentario['titulo'];
        $texto = $comentario['texto'];

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);
        $errorTitulo = self::createMensajeError($errores, 'titulo', 'span', array('class' => 'error'));
        $errorTexto = self::createMensajeError($errores, 'texto', 'span', array('class' => 'error'));
        $html = <<<EOS
        $htmlErroresGlobales
        <div class="newComent">
        <h2> Modifica tu comentario </h2>
        <p>Ten en cuenta que el resto de usuarios sabrán que lo has modificado</p>
        <div class="tituloComent">
            <input class="control" type="text" name="titulo" placeholder="Pon un título a tu comentario" id="campoTitulo" value="$titulo" required/>$errorTitulo      
        </div>
        <div class="cajaComent">
            <textarea name="texto" id="campoTexto" required>$texto</textarea>$errorTexto
        </div>
        <div class="buttonComent"><button type="submit" name="editar">Editar comentario</button></div>
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
            $result['texto'] = 'El comentario no puede estar vacío.';
        }
        if (count($result) === 0) {
            $coment=Comentario::buscaComentarioPorId($id);
            $comentario = new Comentario($coment['idUsuario'], $coment['ubicacion'], $titulo, $texto, true);
            $comentario->setId($id);
            if (!Comentario::guarda($comentario)) {
                $result[] = "Error al editar el comentario";
            } else {
                $result[] = "Comentario editado con éxito";
            }
        }
        return $result;
    }
  
}