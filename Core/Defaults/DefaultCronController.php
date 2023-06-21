<?php

namespace Core\Defaults;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


global $_ID_USUARIO;

$_ID_USUARIO = 2;


abstract class DefaultCronController{

    public $flag_salva_log = 1;
    public $array_ignorar_log =  [
    ];

    private $masterMysqli;
    private $id_usuario;

    function __construct(){
        try{

            $this->masterMysqli = $GLOBALS['_DB_MYSQLI'];
            $this->id_usuario   = $GLOBALS['_ID_USUARIO'];

            $this->iniciaModels();
            // $this->salvaLogController();
        
        }catch(Exception $e){
            throw $e;
        }
    }

    /**
    * Inicia todos os models. Define spl_autoload nas classes e api's
    * 
    * @access private
    * @link construct
    */
    private function iniciaModels(){
        try{
            // Inicia Models / Daos
            $all_model = array_slice(scandir(ROOT_PATH.'/'. MODEL_PATH),2);
            foreach($all_model as $model) {
                $model = ucfirst($model); 
                $model = str_replace('.php','',$model);
                $modelWithNamespace  = MODEL_NAMESPACE . $model;

                $this->$model = new $modelWithNamespace($this->masterMysqli);
            }
            spl_autoload_register( array($this,"pathsToAutoload") );
        }catch(Exception $e){
            throw $e;
        }
    }



    /**
    * Salvar log em arquivo
    * 
    * @access public 
    * @param nome_arquivo (de log)
    * @param texto_log (a ser salvo no log)
    */
    public function salvaLogArquivo($nome_arquivo,$texto_log){
        try {
            if(!empty($nome_arquivo) && !empty($texto_log)){
                if(getenv('APPLICATION_ENV') === 'local'){
                    $caminho_logs = $_SERVER['DOCUMENT_ROOT']."/logs/";
                }else{
                    $caminho_logs = '/var/log/webhook_portal/';
                }
                $arquivo = fopen($caminho_logs.$nome_arquivo, "a");
                fwrite($arquivo, date('Y-m-d H:i:s').' - '.$texto_log.PHP_EOL);
                fclose($arquivo);
            }
        } catch (Exception $e) {
        //silence
        }
    }
    /**
    * Aqui ficam os caminhos para o spl_autoload_register puxar automaticamente os daos e classes e apis (e subdiretorios).
    * 
    * @access protected 
    * @param qualquerClass (string): classe/api a ser importada
    */
    protected function pathsToAutoload () {
        try{
            $models = glob(ROOT_PATH.'/'. MODEL_PATH.'/*');
            foreach ($models as  $model) {
                include_once $model;
            }
        }catch(\Exception $e){
            throw $e;
        }
    }


    /**
    * Metodo para filtrar possiveis problemas no insert do alerta
    * 
    * @access private
    * @param string a ser filtrada
    */  
    protected function filtroMaster($string){
        return $this->masterMysqli->real_escape_string(iconv(mb_detect_encoding($string, mb_detect_order(), true), "UTF-8//IGNORE", $string));
    }

    
}
?>