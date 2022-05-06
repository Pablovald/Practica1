<?php
namespace es\fdi\ucm\aw;
require __DIR__ . '/subidaImagenes.php';
class FormularioActualizarEntrada extends Form
{
    public function __construct()
    {
        parent::__construct('FormularioActualizarEntrada',);
    }
    protected function generaCamposFormulario($datos, $errores = array())
    {
        $id =$_GET['entrada'];
        $entrada = entradaBlog::buscaEntrada($id);
        $titulo = $entrada->getTitulo();
        $header1 = $entrada->getHeader1();
        $intro = $entrada->getIntro();
        $header2 = $entrada->getHeader2();
        $parrafo = $entrada->getParrafo();
        $imagen  = $entrada->getImagen();
        $video = $entrada->getVideo();

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
                    <label>Introducción:</label> <input class='control' type='text' name='intro' value='$intro' />$errorIntro
                </div>
                <div class='grupo-control'>
                    <label>Encabezado del párrafo:</label> <input class='control' type='text' name='header2' value='$header2' />$errorHeader2
                </div>
                <div class='grupo-control'>
                    <label>Texto del párrafo:</label> <input class='control' type='text' name='parrafo' value='$parrafo'/>$errorParrafo
                </div>
                <div class='seleccion'>
                    <label>Selecciona una imagen:</label> <input type='file' name='imagen' value='$imagen'/>$errorimagen
                </div>
                <div class='grupo-control'>
                    <label>Enlace al vídeo:</label> <input class='control' type='text' name='video' value='$video'/>$errorVideo
                </div>
                <div class='grupo-control'>
                    <input class='control' type='hidden' name='id' value='$id' />
                </div>
                <div class='submit'><button type='submit' name='subir'>Actualizar Entrada</button></div>
            </div>
            </div>";
        return $html;
    }

    protected function procesaFormulario($datos)
    {
        $result = array();
        $id =$datos['id'];
        $titulo = $datos['titulo'] ?? null;

        if (empty($titulo)) {
            $result['titulo'] = 'El titulo no puede estar vacío.';
        }

        $header1 = $datos['header1'] ?? null;
        if (empty($header1)) {
            $result['header1'] = 'El encabezado del artículo no puede estar vacío.';
        }
        $intro = $datos['intro'] ?? null;
        if (empty($intro)) {
            $result['intro'] = 'La introducción no puede estar vacía .';
        }

        $header2 = $datos['header2'];

        $parrafo = $datos['parrafo'] ?? null;
        if (empty($parrafo)) {
            $result['parrafo'] = 'El contenido del artículo no puede estar vacío.';
        }

        $imagen = subirImagen('img/') ?? null;
        if (empty($imagen)) {
            $result['imagen'] = 'Error al subir la imagen';
        }

        $video = substr($datos['video'],-11);
        if (empty($video)) {
            $result['video'] = 'Error al subir el video';
        }

        if (count($result) === 0) {
            $entrada = entradaBlog::actualizarEntrada(
                $id,
                $titulo,
                $header1,
                $intro,
                $header2,
                $parrafo,
                $imagen,
                $video
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
