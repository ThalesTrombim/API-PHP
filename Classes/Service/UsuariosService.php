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
    public const RECURSOS_POST = ['cadastrar'];
    public const RECURSOS_PUT = ['atualizar'];

    private array $dados;

    private array $dadosCorpoRequest = [];

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

    public function setDadosRequest($dadosRequest)
    {
        $this->dadosCorpoRequest = $dadosRequest;
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

    public function validarPut()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        if(in_array($recurso, self::RECURSOS_PUT, true)){
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

    public function validarPost()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        if(in_array($recurso, self::RECURSOS_POST, true)){
            $retorno = call_user_func(array($this, $recurso));
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

    private function cadastrar()
    {
        [$login, $senha] = [$this->dadosCorpoRequest['login'], $this->dadosCorpoRequest['senha']];

        if($login && $senha) {
            if($this->UsuariosRepository->insertUsuario($login, $senha) > 0){
                $idInserido = $this->UsuariosRepository->getMySQL()->getDb()->lastInsertId();
                $this->UsuariosRepository->getMySQL()->getDb()->commit();
                return ['id_inserido' => $idInserido];
            }
            $this->UsuariosRepository->getMySQL()->getDb()->rollBack();
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        } 
        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_LOGIN_SENHA_OBRIGATORIO);
    }

    private function atualizar()
    {
        if ($this->UsuariosRepository->updateUsuario($this->dados['id'], $this->dadosCorpoRequest) > 0) {
            $this->UsuariosRepository->getMySQL()->getDb()->commit();
            return ConstantesGenericasUtil::MSG_ATUALIZADO_SUCESSO;
        }
        $this->UsuariosRepository->getMySQL()->getDb()->rollBack();
        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_NAO_AFETADO); 
    }
}