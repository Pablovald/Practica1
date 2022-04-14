<?php

require_once __DIR__. '/Aplicacion.php';

class entradaBlog{
    private $id;
    private $titulo;
    private $header1;
    private $intro;
    private $header2;
    private $parrafo;
    private $imagen;
    private $video;


    private function __construct($titulo, $header1, $intro, $header2,$parrafo,$imagen,$video)
    {
        $this->titulo= $titulo;
        $this->header1 = $header1;
        $this->intro = $intro;
        $this->header2 = $header2;
        $this->parrafo = $parrafo;
        $this->imagen = $imagen;
        $this->video = $video;
    }

    public static function crea($titulo,$header1,$intro,$header2,$parrafo,$imagen,$video){

        $entrada= new entradaBlog($titulo,$header1,$intro,$header2,$parrafo,$imagen,$video);
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
        $query=sprintf("INSERT INTO entradasBlog(titulo, header1, intro, header2, parrafo, rutaImagen, video) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s')"
        , $conn->real_escape_string($entrada->titulo)
        , $conn->real_escape_string($entrada->header1)
        , $conn->real_escape_string($entrada->intro)
        , $conn->real_escape_string($entrada->header2)
        , $conn->real_escape_string($entrada->parrafo)
        , $conn->real_escape_string($entrada->imagen)
        , $conn->real_escape_string($entrada->video));
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
        $query=sprintf("UPDATE entradasBlog E SET titulo = '%s', header1='%s', intro='%s', header2='%s', parrafo='%s', rutaImagen='%s', video='%s' WHERE E.id=%i"
        , $conn->real_escape_string($entrada->titulo)
        , $conn->real_escape_string($entrada->header1)
        , $conn->real_escape_string($entrada->intro)
        , $conn->real_escape_string($entrada->header2)
        , $conn->real_escape_string($entrada->parrafo)
        , $conn->real_escape_string($entrada->imagen)
        , $conn->real_escape_string($entrada->video)
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

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
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

    /**
     * Get the value of titulo
     */ 
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set the value of titulo
     *
     * @return  self
     */ 
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get the value of header1
     */ 
    public function getHeader1()
    {
        return $this->header1;
    }

    /**
     * Set the value of header1
     *
     * @return  self
     */ 
    public function setHeader1($header1)
    {
        $this->header1 = $header1;

        return $this;
    }

    /**
     * Get the value of intro
     */ 
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * Set the value of intro
     *
     * @return  self
     */ 
    public function setIntro($intro)
    {
        $this->intro = $intro;

        return $this;
    }

    /**
     * Get the value of header2
     */ 
    public function getHeader2()
    {
        return $this->header2;
    }

    /**
     * Set the value of header2
     *
     * @return  self
     */ 
    public function setHeader2($header2)
    {
        $this->header2 = $header2;

        return $this;
    }

    /**
     * Get the value of parrafo
     */ 
    public function getParrafo()
    {
        return $this->parrafo;
    }

    /**
     * Set the value of parrafo
     *
     * @return  self
     */ 
    public function setParrafo($parrafo)
    {
        $this->parrafo = $parrafo;

        return $this;
    }

    /**
     * Get the value of imagen
     */ 
    public function getRutaImagen()
    {
        return $this->imagen;
    }

    /**
     * Set the value of imagen
     *
     * @return  self
     */ 
    public function setRutaImagen($imagen)
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Get the value of video
     */ 
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set the value of video
     *
     * @return  self
     */ 
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }
}