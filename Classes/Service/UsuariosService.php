<?php

namespace Service;

use InvalidArgumentException;
use Repository\UsuariosRepository;
use Util\ConstantesGenericasUtil;

class UsuariosService 
{
    public const TABLE = 'usuarios';
    public const RECURSOS_GET = ['listar'];
    public const RECURSOS_DELETE = ['deletar'];

    private array $dados;

    private object $UsuariosRepository; 

    public function __construct($dados = [])
    {
        $this->dados = $dados;
        $this->UsuariosRepository = new UsuariosRepository;
    }

    public function validarGET()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        if(in_array($recurso, self::RECURSOS_GET, true)){
            $retorno = $this->dados['id'] > 0 ? $this->getOneByKey() : $this->$recurso();
            // $retorno = ($this->data['id'] > 0 ? $this->getOneByKey() : call_user_func(
            //     array ($this, $recurso)));
        }else {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        if ($retorno == null){
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }

        return $retorno;
    }

    public function validarDelete()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        if(in_array($recurso, self::RECURSOS_DELETE, true)){
            if($this->dados['id'] > 0) {
                $retorno = call_user_func(array($this, $recurso));
            } else {
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_ID_OBRIGATORIO);
            }
        }else {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        if ($retorno == null){
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }

        return $retorno;
    }

    private function getOneByKey()
    {
        return $this->UsuariosRepository->getMySQL()->getOneByKey(self::TABLE, $this->dados['id']);  
    }

    private function listar()
    {
        return $this->UsuariosRepository->getMySQL()->getAll(self::TABLE); 
    }

    private function deletar()
    {
        return $this->UsuariosRepository->getMySQL()->delete(self::TABLE, $this->dados['id']);
    }
}