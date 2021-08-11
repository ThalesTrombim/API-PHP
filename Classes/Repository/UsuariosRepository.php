<?php

namespace Repository;

use DB\MySQL;

class UsuariosRepository
{

    private object $MySQL;
    public const TABLE = "usuarios";

    public function __construct()
    {
        $this->MySQL = new MySQL;
    }

    public function getMySQL()
    {
        return $this->MySQL;
    }

    public function insertUsuario($login, $senha)
    {
        $consultaInsert = 'INSERT INTO ' . self::TABLE . '(login, senha) VALUES (:login, :senha)';
        $this->MySQL->getDB()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($consultaInsert);
        $stmt->bindParam(':login' , $login);
        $stmt->bindParam(':senha' , $senha);
        $stmt->execute();
        return $stmt->rowCount();
    }
}