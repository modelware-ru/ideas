<?php

class DB
{
    private static $_Manager = null;

    private $_dsn;
    private $_user;
    private $_password;
    private $_driverOpts = [
        PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        // PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
    ];

    private $_pdo;

    public static function GetConnection()
    {
        global $G_SETTING;

        if (!isset(self::$_Manager)) {

            self::$_Manager = new DB();

            self::$_Manager->_dsn = $G_SETTING['db']['dsn'];
            self::$_Manager->_user = $G_SETTING['db']['user'];
            self::$_Manager->_password = $G_SETTING['db']['password'];

            self::$_Manager->_pdo = new PDO(self::$_Manager->_dsn, self::$_Manager->_user, self::$_Manager->_password, self::$_Manager->_driverOpts);
            // self::$_Manager->_pdo->beginTransaction();
        }

        return self::$_Manager;
    }

    public static function Commit(array $keys = [])
    {
        self::$_Manager->_pdo->commit();
    }

    public static function Rollback(array $keys = [])
    {
        self::$_Manager->_pdo->rollback();
    }

    public function select(string $stmt, array $vars = [])
    {
        $q = $this->_pdo->prepare($stmt);
        $q->execute($vars);

        return $q->fetchAll();
    }

    public function insert(string $stmt, array $vars = [], array $constVars = [], bool $skipDuplicateError = false)
    {
        $ids = [];
        $q = $this->_pdo->prepare($stmt);

        foreach ($vars as $index => $var) {
            if ($index === 0) {

                foreach ($constVars as $k => $v) {
                    $q->bindValue(':' . $k, $v);
                }

                $data = [];
                $i = 0;
                foreach ($vars[0] as $k => $v) {
                    $data[$i] = $v;
                    $q->bindParam(':' . $k, $data[$i]);
                    $i++;
                }
            } else {
                $i = 0;
                foreach ($var as $v) {
                    $data[$i++] = $v;
                }
            }

            $q->execute();

            $ids[] = $this->_pdo->lastInsertId();
        }
        return $ids;
    }

    public function delete(string $stmt, array $vars = [])
    {
        $q = $this->_pdo->prepare($stmt);
        $q->execute($vars);

        return $q->rowCount();
    }

    public function deleteEx(string $stmt, array $vars, array $constVars = [])
    {
        $count = 0;
        $q = $this->_pdo->prepare($stmt);

        foreach ($vars as $index => $var) {
            if ($index === 0) {

                foreach ($constVars as $k => $v) {
                    $q->bindValue(':' . $k, $v);
                }

                $data = [];
                $i = 0;
                foreach ($vars[0] as $k => $v) {
                    $data[$i] = $v;
                    $q->bindParam(':' . $k, $data[$i]);
                    $i++;
                }
            } else {
                $i = 0;
                foreach ($var as $v) {
                    $data[$i++] = $v;
                }
            }

            $q->execute();

            $count = $count + $q->rowCount();
        }

        return $count;
    }

    public function insertUpdate(string $stmt, array $vars = [])
    {
        $q = $this->_pdo->prepare($stmt);
        $q->execute($vars);

        return $q->rowCount();
    }

    public function update(string $stmt, array $vars, array $constVars = [])
    {
        $count = 0;
        $q = $this->_pdo->prepare($stmt);

        foreach ($vars as $index => $var) {
            if ($index === 0) {

                foreach ($constVars as $k => $v) {
                    $q->bindValue(':' . $k, $v);
                }

                $data = [];
                $i = 0;
                foreach ($vars[0] as $k => $v) {
                    $data[$i] = $v;
                    $q->bindParam(':' . $k, $data[$i]);
                    $i++;
                }
            } else {
                $i = 0;
                foreach ($var as $v) {
                    $data[$i++] = $v;
                }
            }

            $q->execute();

            $count = $count + $q->rowCount();
        }

        return $count;
    }

    public function exec(string $stmt)
    {
        $res = $this->_pdo->exec($stmt);

        return $res;
    }
}
