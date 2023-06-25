<?php

namespace App\Classes;

use Symfony\Component\Config\Loader\FileLoader;

class ConfiLoaderClass {
    // public function load($resource, $type = null)
    // {
    //     $path = $this->locator->locate($resource);
    //     $config = parse_ini_string($path, true);

    //     return $config;
    // }

    // public function supports($resource, $type = null)
    // {
    //     return is_string($resource) && 'conf' === pathinfo($resource, PATHINFO_EXTENSION);
    // }

    public function loadConfig(string $path){
        try{
            $arquivo = $path;
    
            // Variáveis para armazenar as configurações
            $configuracoes = array();
            $secao_atual = '';
    
            if ($linhas = file($arquivo)) {
                foreach ($linhas as $linha) {
                    // Remover espaços em branco no início e no final da linha
                    $linha = trim($linha);
                    
                    // Ignorar linhas vazias e comentários
                    if (empty($linha) || $linha[0] === ';' || $linha[0] === '#') {
                        continue;
                    }
                    
                    // Verificar se a linha é uma nova seção
                    if ($linha[0] === '['){
                        $linha = preg_replace('/\(.*\)/',"",$linha, 1);
    
                        if ($linha[0] === '[' && substr($linha, -1) === ']') {
                            $secao_atual = substr($linha, 1, -1);
                            continue;
                        }
                    }
                    
                    // Extrair a chave e o valor
                    if(str_contains($linha, "=>")){
                        $linha = str_replace("=>", "=", $linha);
                    }
                    
                    $posicao_separador = strpos($linha, '=');
                    $chave = trim(substr($linha, 0, $posicao_separador));
                    $valor = trim(substr($linha, $posicao_separador + 1));
                    
                    // Armazenar a configuração no array
                    if (!empty($secao_atual)) {
                        if(!empty($configuracoes[$secao_atual][$chave])){

                            if(gettype($configuracoes[$secao_atual][$chave]) === "string"){
                                $configuracoes[$secao_atual][$chave]   = [$configuracoes[$secao_atual][$chave], trim($valor, '"')] ;
                            }else{
                                $configuracoes[$secao_atual][$chave][] = trim($valor, '"') ;
                            }

                        }else{
                            $configuracoes[$secao_atual][$chave] = trim($valor, '"');
                        }
                    }
                }
            }
            return $configuracoes;
        }catch(\Exception $e){
            throw $e;
        }
    }
}

?>