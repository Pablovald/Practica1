<?php
namespace es\fdi\ucm\aw;
require __DIR__ . '/subidaImagenes.php';
class FormularioBlog extends Form
{
    public function __construct()
    {
        parent::__construct('formEntrada',);
    }
    protected function generaCamposFormulario($datos, $errores = array())
    {
        $titulo = $datos['titulo'] ?? '';
        $header1 = $datos['header1'] ?? '';
        $intro = $datos['intro'] ?? '';
        $header2 = $datos['header2'] ?? '';
        $parrafo = $datos['parrafo'] ?? '';
        $imagen  = $datos['imagen'] ?? '';
        $video = $datos['video'] ?? '';
        $idAutor = Usuario::buscaIdDelUsuario($_SESSION['nombreUsuario']);

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);
        $errorTitulo = self::createMensajeError($errores, 'titulo', 'span', array('class' => 'error'));
        $errorHeader1 = self::createMensajeError($errores, 'header1', 'span', array('class' => 'error'));
        $errorIntro = self::createMensajeError($errores, 'intro', 'span', array('class' => 'error'));
        $errorHeader2 = self::createMensajeError($errores, 'header2', 'span', array('class' => 'error'));
        $errorParrafo = self::createMensajeError($errores, 'parrafo', 'span', array('class' => 'error'));
        $errorimagen  = self::createMensajeError($errores, 'imagen', 'span', array('class' => 'error'));
        $errorVideo = self::createMensajeError($errores, 'video', 'span', array('class' => 'error'));
    
        $html ="
            <div class = 'content'>
            <legend> Editor <span> Publicación </span></legend></br>
            <div class = 'formulario'>
                $htmlErroresGlobales
                <div class='grupo-control'>
                    <label>Titulo del artículo:</label> <input class='control' type='text' name='titulo' value='$titulo' />$errorTitulo
                </div>
                <div class='grupo-control'>
                    <label>Encabezado de la introducción:</label> <input class='control' type='text' name='header1' value='$header1' />$errorHeader1
                </div>
                <div class='grupo-control'>
                    <label>Introducción:</label> <textarea class='control' type='text' name='intro' required/></textarea>$errorIntro
                </div>
                <div class='grupo-control'>
                    <label>Encabezado del párrafo:</label> <input class='control' type='text' name='header2' value='$header2' />$errorHeader2
                </div>
                <div class='grupo-control'>
                    <label>Texto del párrafo:</label> <textarea class='control' type='text' name='parrafo' required/></textarea>$errorParrafo
                </div>
                <div class='seleccion'>
                    <label>Selecciona una imagen:</label> <input type='file' name='imagen' value='$imagen'/>$errorimagen
                </div>
                <div class='grupo-control'>
                    <label>Enlace al vídeo de YouTube:</label> <input class='control' type='text' name='video' value='$video' required/>$errorVideo
                </div>
                <div class='grupo-control'>
                    <input class='control' type='hidden' name='idAutor' value='$idAutor' />
                </div>
                <div class='submit'><button type='submit' name='subir'>Subir artículo al blog</button></div>
            </div>
            </div>";
        return $html;
    }

    protected function procesaFormulario($datos)
    {
        $result = array();

        $titulo = $datos['titulo'] ?? null;
        $idAutor = $datos['idAutor']??null;
        if (empty($titulo)) {
            $result['titulo'] = 'El titulo no puede estar vacío.';
        }

        $header1 = $datos['header1'] ?? null;
        if (empty($header1)) {
            $result['header1'] = 'El encabezado del artículo no puede estar vacío.';
        }
        $intro = $_POST['intro'] ?? null;
        if (empty($intro)) {
            $result['intro'] = 'La introducción no puede estar vacía .';
        }

        $header2 = $datos['header2'];

        $parrafo = $_POST['parrafo'] ?? null;
        if (empty($parrafo)) {
            $result['parrafo'] = 'El contenido del artículo no puede estar vacío.';
        }

        $imagen = subirImagen('img/') ?? null;
        if (empty($imagen)) {
            $result['imagen'] = 'Error al subir la imagen';
        }

        $video = substr($datos['video'],-11);
        if (empty($video)) {
            $result['video'] = 'El enlace al vídeo no puede estar vacío';
        }

        if (count($result) === 0) {
            $entrada = entradaBlog::crea(
                $titulo,
                $header1,
                $intro,
                $header2,
                $parrafo,
                $imagen,
                $video,
                $idAutor
            );
            if (!$entrada) {
                $result[] = 'No se ha podido crear la entrada de Blog';
            }
            else{
                $result='Blog.php';
            }
        }
        return $result;
    }
}
