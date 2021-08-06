<?php

use Util\ConstantesGenericasUtil;
use Util\RotasUtil;
use Util\JsonUtil;
use Validator\RequestValidator;

include "bootstrap.php";

try {
    $RequestValidator = new RequestValidator(RotasUtil::getRotas());
    $retorno = $RequestValidator->processarRequest();

    $JsonUtil = new JsonUtil();
    $JsonUtil->processarArray($retorno);

} catch (Exception $exception) {
    echo json_encode([
        ConstantesGenericasUtil::TIPO => ConstantesGenericasUtil::TIPO_ERRO,
        ConstantesGenericasUtil::RESPOSTA =>utf8_encode($exception->getMessage())
    ]);
    exit;
}
