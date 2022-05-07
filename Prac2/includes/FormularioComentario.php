<?php

namespace es\fdi\ucm\aw;


class FormularioComentario extends Form
{
    public function __construct()
    {
        $opciones['action'] = "procesarEntradaBlog.php?entrada=" . htmlspecialchars($_GET['entrada']);
        parent::__construct('FormularioComentario', $opciones);
    }

    protected function generaCamposFormulario($datos, $errores = array())
    {
        $titulo = $datos['titulo'] ?? '';
        $texto = $datos['texto'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);
        $errorTitulo = self::createMensajeError($errores, 'titulo', 'span', array('class' => 'error'));
        $errorTexto = self::createMensajeError($errores, 'texto', 'span', array('class' => 'error'));
        $html = <<<EOS
        $htmlErroresGlobales
        <div class="newComent">
        <h2> Déjenos un comentario </h2>
        <div class="tituloComent">
            <input class="control" type="text" name="titulo" placeholder="Pon un titulo a tu comentario" id="campoTitulo" value="$titulo" required/>$errorTitulo      
        </div>
        <div class="cajaComent">
            <textarea name="texto" placeholder="Valora tu experiencia con nosotros" id="campoTexto" required></textarea>$errorTexto
        </div>
        <div class="buttonComent"><button type="submit" name="comentar">Publicar comentario</button></div>
        </div>
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
        if (count($result) === 0) {
            $idUser = Usuario::buscaIdDelUsuario($_SESSION['nombreUsuario']);
            $entrada = htmlspecialchars($_GET['entrada']);
            $ubicacion = entradaBlog::nombreEntrada($entrada);
            Comentario::crea($idUser, $ubicacion, $titulo, $texto, false);
            if (!$entrada) {
                $result[] = "Error al publicar el comentario";
            } else {
                $result[] = "Comentario publicado con éxito";
            }
        }
        return $result;
    }
}
