<?php
namespace es\fdi\ucm\aw;

class entradaBlog{
    private $id;
    private $titulo;
    private $header1;
    private $intro;
    private $header2;
    private $parrafo;
    private $imagen;
    private $video;
    private $idAutor;


    public function __construct($titulo, $header1, $intro, $header2,$parrafo,$imagen,$video,$idAutor)
    {
        $this->titulo= $titulo;
        $this->header1 = $header1;
        $this->intro = $intro;
        $this->header2 = $header2;
        $this->parrafo = $parrafo;
        $this->imagen = $imagen;
        $this->video = $video;
        $this->idAutor = $idAutor;
    }

    public static function crea($titulo,$header1,$intro,$header2,$parrafo,$imagen,$video,$idAutor){

        $entrada= new entradaBlog($titulo,$header1,$intro,$header2,$parrafo,$imagen,$video,$idAutor);
        return self::guarda($entrada);
    }
    public static function guarda($entrada){
        if ($entrada->id !== null) {
            return self::actualiza($entrada);
        }
        return self::inserta($entrada);
    }
    private static function inserta($entrada){
        $app=Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query=sprintf("INSERT INTO entradasBlog(titulo, header1, intro, header2, parrafo, rutaImagen, video, idAutor) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d')"
        , $conn->real_escape_string($entrada->titulo)
        , $conn->real_escape_string($entrada->header1)
        , $conn->real_escape_string($entrada->intro)
        , $conn->real_escape_string($entrada->header2)
        , $conn->real_escape_string($entrada->parrafo)
        , $conn->real_escape_string($entrada->imagen)
        , $conn->real_escape_string($entrada->video)
        , $conn->real_escape_string($entrada->idAutor));
        if ( $conn->query($query) ) {
            $entrada->id = $conn->insert_id;
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $entrada;
    }
    private static function actualiza($entrada){
        $actualizado=false;
        $app=Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query=sprintf("UPDATE entradasBlog E SET titulo = '%s', header1='%s', intro='%s', header2='%s', parrafo='%s', rutaImagen='%s', video='%s', idAutor='%d' WHERE E.id=%d"
        , $conn->real_escape_string($entrada->titulo)
        , $conn->real_escape_string($entrada->header1)
        , $conn->real_escape_string($entrada->intro)
        , $conn->real_escape_string($entrada->header2)
        , $conn->real_escape_string($entrada->parrafo)
        , $conn->real_escape_string($entrada->imagen)
        , $conn->real_escape_string($entrada->video)
        , $conn->real_escape_string($entrada->idAutor)
        , $entrada->id);
    if ( $conn->query($query) ) {
        if ( $conn->affected_rows != 1) {
            echo "No se ha podido actualizar la entrada: " . $entrada->id;
        }
        else{
            $actualizado = $entrada;
        }
    } else {
        echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
    }
    
    return $actualizado;

    }
    public static function getEntradaPorId($id){
        $app=Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $row=$conn->query(sprintf("SELECT B.* FROM entradasBlog B WHERE B.id = $id"));
        $rs=$row->fetch_assoc();
        $contenido=new entradaBlog($rs['titulo'],$rs['header1'],$rs['intro'],$rs['header2'],$rs['parrafo'],$rs['rutaImagen'],$rs['video'],$rs['idAutor']);
        $contenido->id=$id;
        $row->free();
        return $contenido;
    }

    public static function borrarEntrada($nombre){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query=(sprintf("DELETE FROM entradasBlog WHERE titulo= '%s'",$conn->real_escape_string($nombre)));

        if ($conn->query($query)) {
            if ( $conn->affected_rows != 1) {
                header("Location: Blog_Admin.php?estado=error&nombre=".$nombre."");
            }
            else{

                header("Location: Blog_Admin.php?estado=eliminado&nombre=".$nombre."");
            }
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        }

    }
    public static function buscaEntrada($id){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query = sprintf("SELECT * FROM entradasBlog WHERE id = $id");
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            if ( $rs->num_rows == 1) {
                $fila = $rs->fetch_assoc();
                $entrada = new entradaBlog($fila['titulo'], $fila['header1'], $fila['intro'], $fila['header2'], $fila['parrafo'],$fila['rutaImagen'],$fila['video'],$fila['idAutor']);  
                $entrada->id = $fila['id'];
                $result = $entrada;
            }
            $rs->free();
        } else {
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $result;
    }


    public function getTitulo()
    {
        return $this->titulo;
    }

    public function getHeader1()
    {
        return $this->header1;
    }

    public function getIntro()
    {
        return $this->intro;
    }

    public function getHeader2()
    {
        return $this->header2;
    }

    public function getParrafo()
    {
        return $this->parrafo;
    }

    public function getImagen()
    {
        return $this->imagen;
    }

    public function getVideo()
    {
        return $this->video;
    }


    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of idAutor
     */ 
    public function getIdAutor()
    {
        return $this->idAutor;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}