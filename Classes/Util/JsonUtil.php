<?php

namespace Util;

use InvalidArgumentException;
use JsonException as JsonExceptionAlias;

class JsonUtil
{

    public function processarArray($retorno)
    {

        $dados = [];
        $dados[ConstantesGenericasUtil::TIPO] = ConstantesGenericasUtil::TIPO_ERRO;
        
        if((is_array($retorno) && count($retorno) > 0 || strlen($retorno) > 0)) {
            $dados[ConstantesGenericasUtil::TIPO] = ConstantesGenericasUtil::TIPO_SUCESSO;
            $dados[ConstantesGenericasUtil::RESPOSTA] = $retorno;
        }

        $this->retornarJson($dados);
    }

    private function retornarJson($json)
    {
        // echo '<pre>';
        // var_dump($json);exit;

        header('Content-Type: application/json');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Access-Control-Allow-Method: GET,POST,PUT,DELETE');
        echo json_encode($json);
        exit;
    }

    public static function tratarRequestJson(){

        try {
            $postJson = json_decode(file_get_contents('php://input'), true);
        }catch(JsonException $exception){  
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERR0_JSON_VAZIO);
        }

        if(is_array($postJson) && count($postJson) > 0){
            return $postJson;
        }
    }
}