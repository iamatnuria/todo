<?php

namespace App;

//use PDO;
//use PDOException;

class DB extends \PDO
{
    static $instance;
    protected $config;

    static function singleton()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {

       /* $config = $this->loadConf();
        //determinar env pro o dev
        $strdbconf = 'conf_' . $this->env();

        $dbconf = (array) $config->$strdbconf;

        $dsn = $dbconf['driver'] . ':host=' . $dbconf['dbhost'] . ';dbname=' . $dbconf['dbname'];
        $usr = $dbconf['dbuser'];
        $pwd = $dbconf['dbpass'];*/
        $config=$this->loadConf();
        parent::__construct(DSN, USR, PWD);
    }

    private static function loadConf(){
        $file="config.json";
        $jsonStr=file_get_contents($file);
        $arrayJson=json_decode($jsonStr);
        return $arrayJson;
    }
    
    // Db functions
        function insert($table, $data):bool
    {
        if (is_array($data)) {
            $columns='';$bindv='';$value=null;
            foreach ($data as $column => $value) {
                $columns.='`'.$column.'`,';
                $bindv.='?,';
                $values[]=$value;
            }
            $columns = substr($columns, 0, -1);
            $bindv = substr($bindv, 0, -1);

            $sql = "INSERT INTO {$table}({$columns}) VALUES ({$bindv})";

            try {
                $stmt = self::$instance->prepare($sql);

                $stmt->execute($values);
            } catch (\PDOException $e) {
                echo $e->getMessage();
                return false;
            }

            return true;
        }
        return false;
    }

    function selectAll($table, array $fields = null): array
    {
        if (is_array($fields)) {
            $columns = implode(',', $fields);
        } else {
            $columns = "*";
        }

        $sql = "SELECT {$columns} FROM {$table}";

        $stmt = self::$instance->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $rows;
    }

    function selectAllWithJoin($table1, $table2, array $fields = null, string $join1, string $join2):array
    {
        if (is_array($fields)) {
            $columns = implode(',', $fields);
        } else {
            $columns = "*";
        }

        $inners = "{$table1}.{$join1} = {$table2}.{$join2}";

        $sql = "SELECT {$columns} FROM {$table1} INNER JOIN {$table2} ON {$inners}";

        $stmt = self::$instance->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $rows;
    }
    // nomes una condicio
    function selectWhereWithJoin($table1, $table2, array $fields = null, string $join1, string $join2, array $conditions): array
    {
        if (is_array($fields)) {
            $columns = implode(',', $fields);
        } else {
            $columns = "*";
        }

        $inners = "{$table1}.{$join1} = {$table2}.{$join2}";
        $cond = "{$conditions[0]}='{$conditions[1]}'";

        $sql = "SELECT {$columns} FROM {$table1} INNER JOIN {$table2} ON {$inners} WHERE {$cond}";

        $stmt = self::$instance->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $rows;
    }

    function update(string $table, array $data, array $conditions)
    {
        if ($data) {
            $keys = array_keys($data);
            $values = array_values($data);
            $changes = "";
            for ($i = 0; $i < count($keys); $i++) {
                $changes.=$keys[$i]."='".$values[$i]."',";
            }
            $changes = substr($changes, 0, -1);
            $cond = "{$conditions[0]}='{$conditions[1]}'";
            $sql="UPDATE {$table} SET {$changes} WHERE {$cond}";

            $stmt=self::$instance->prepare($sql);
            $res=$stmt->execute();
            if($res){
                return true;
            }
        } else {
            return false;
        }
    }

    /*
     * function update(string $table, array $data,array $conditions)
            {
                if ($data){
                    $keys=array_keys($data);
                    $values=array_values($data);
                    $changes="";
                    for($i=0;$i<count($keys);$i++){
                        $changes.=$keys[$i]."='".$values[$i]."',";
                    }
                    $changes=substr($changes,0,-1);
                    $cond="{$conditions[0]}='{$conditions[1]}'";
                    $sql="UPDATE {$table} SET {$changes} WHERE {$cond}";

                    $stmt=self::$instance->prepare($sql);
                    $res=$stmt->execute();
                    if($res){
                        return true;
                    }
                }else{
                    return false;
                }


            }
     * */

    function remove($tbl, $id)
    {
        $sql = "DELETE FROM {$tbl} WHERE id=$id";
        $stmt = self::$instance->prepare($sql);
        $res = $stmt->execute();
        if ($res) {
            return true;
        } else {
            return false;
        }
    }
}
