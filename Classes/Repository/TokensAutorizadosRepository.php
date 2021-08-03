<?php 

namespace Repository;

use DB\MySQL;
use InvalidArgumentException;
use Util\ConstantesGenericasUtil;

class TokensAutorizadosRepository 
{
    private object $MySQL;
    public const TABLE = "tokens_autorizados";

    public function __construct()
    {
        $this->MySQL = new MySQL();
    }

    public function validarToken($token){

        $token = str_replace([' ', 'Bearer'], '', $token);

        if($token){
            $consultaToken = 'SELECT id FROM ' .self::TABLE . ' WHERE token = :token AND status = :status';
            $stnt = $this->getMySQL()->getDb()->prepare($consultaToken);

            $stnt->bindValue(':token', $token);
            $stnt->bindValue(':status', ConstantesGenericasUtil::SIM);
            $stnt->execute();
            
            if($stnt->rowcount() !== 1){
                header('HTTP/1.1 401 Unauthorized');
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TOKEN_NAO_AUTORIZADO);
            }

        }else{
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TOKEN_VAZIO);
        }
    }

    public function getMySQL(){
        
        return $this->MySQL;
    }
}