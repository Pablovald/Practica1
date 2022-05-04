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

    public static function blog(){
        $app=Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $tablaBlog=sprintf("SELECT * FROM entradasBlog");
        $numEntradas = $conn->query($tablaBlog)->num_rows;
        $rs=$conn->query($tablaBlog);
        $tableCont="<tr>";
        $j=0;
        for($i=0;$i<$numEntradas;$i++){
            $aux=$rs->fetch_assoc();
            $row=$conn->query(sprintf("SELECT * FROM entradasBlog B WHERE B.id = '$aux[id]'"));
            $contenido=$row->fetch_assoc();
            $intro=explode(' ',$contenido['intro'],16);
            $intro[15]="...";
            $rowCont =  "<td>
            <div class = 'blog-contenedor'>
                <div class = 'blog-box'>
                    <div class = 'blog-img'>
                    <a href="."procesarEntradaBlog.php?entrada="."$contenido[id]"."><img src= '$contenido[rutaImagen]'></a>
                    </div>
                    <div class = 'blog-text'>
                    <h4>"."$contenido[titulo]"."</h4>
            <p>".implode(' ',$intro)."<a href="."procesarEntradaBlog.php?entrada="."$contenido[id]"."> Leer más</a></p>
                    </div>
                </div>
            </div>
            </td>";
            if($j<3){	
                $tableCont.=$rowCont;
                $j++;
            }
            else{
                $tableCont.="</tr>";
                $tableCont.="<tr>";
                $tableCont.=$rowCont;
                $j=1;
            }
        }

        $contenidoPrincipal = <<<EOS
        <div class='cabecera'>
            <p> En club Seawolf Deportes Naúticos os proporcionamos un blog con las noticias más extravagantes sobre deportes acuáticos </p>
        </div>
        <table align = "center">
            $tableCont
        </table>  
        EOS;

        $rs->free();
        $row->free();
        return $contenidoPrincipal;
    }

    public static function procesarEntradaBlog(&$tituloPagina, &$tituloCabecera){
        $entrada = htmlspecialchars($_GET["entrada"]);
        $app=Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $tablaEntrada=sprintf("SELECT * FROM entradasBlog E WHERE E.id = $entrada ");
        $row = $conn->query($tablaEntrada);
        $rs=$row->fetch_assoc();
        $tituloPagina=$rs['titulo'];
        $tituloCabecera = strtoupper($tituloPagina);
        $contenidoPrincipal = <<<EOS
            <div class='info-blog'>
                <h3>$rs[header1]</h3>
                <p>$rs[intro]</p>
                <img class='entr-img' src=$rs[rutaImagen] alt=""> </br> </br>
                <h3>$rs[header2]</h3>
                <p>$rs[parrafo]</p>
                <iframe class='iframe' src="https://www.youtube.com/embed/$rs[video]" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write;
                encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            EOS;
        $row->free();
        return $contenidoPrincipal;
    }
}