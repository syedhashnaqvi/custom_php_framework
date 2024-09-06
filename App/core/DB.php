<?php
namespace Core;
use mysqli;
use Core\Config;
class DB {
    private static $obj;
    private $connection,$query;
    private static $table;
    public function __construct(){
        $this->getConnection();
    }

    private function getConnection(){
        $this->connection = new mysqli(Config::get('database.host'),Config::get('database.user'),Config::get('database.password'),Config::get('database.database'));
        if($this->connection->connect_error){
            die("Connection faild: ". mysqli_connect_error());
        }
    }

    public static function table($table=null){
        if(!$table) die("Error: Table name missing!");
        self::$table = $table;
        if(is_null(self::$obj)){
            self::$obj = new self();
        }
        return self::$obj;
    }

    public function select(...$cols){
        $colsToSelect = "*";
        if(count($cols)>0) $colsToSelect = implode(",",$cols);
        $this->query = "SELECT $colsToSelect FROM ".self::$table;
        return $this;
    }

    public function get($limit=null){
        if($limit && is_int($limit)){
            $this->query.=" limit $limit";
        }
        $result = $this->connection->query($this->query);
        if(!$result){
            die("Query: ".$this->query."<br>Database Error: ".$this->connection->error);
        }
        if($result->num_rows==1){
            return (object)$result->fetch_assoc();
        }else if($result->num_rows>1){
            $data = [];
            while($row = $result->fetch_assoc()){
                $data[] = (object)$row;
            }
            return $data;
        }else{
            return null;
        }
    }

    public function first(){
        return $this->get(1);
    }

    public function take($limit = null){
        return $this->get($limit);
    }

    public function orderBy($col,$order){
        $this->query.=" ORDER BY $col $order";
        return $this;
    }

    function __call($name,$arg){
        if($name == "where"){
            switch (count($arg)) {
                case 1:
                    $this->whereArr($arg[0]); break;
                case 2:
                    $this->where2($arg[0],$arg[1]); break;
                case 3:
                    $this->where3($arg[0],$arg[1],$arg[2]); break;
                default:
                    return null; break;
            }
        }
        return $this;
    }

    public function where2($col,$value){
        $this->query.=" WHERE $col = $value";
        return $this;
    }

    public function where3($col,$conditon,$value){
        $this->query.= " WHERE $col $conditon '".mysqli_real_escape_string($this->connection,$value)."'";
        return $this;
    }

    public function multiAndWhere($condtions){
        $where = " WHERE ";
        $and = " AND ";
        foreach ($condtions as $key => $condition) {
            if ($key === array_key_last($condtions)) {
                $and = '';
            }
            $where .=$condition[0]." ".$condition[1]." '". mysqli_real_escape_string($this->connection,$condition[2])."' ".$and;
        }
        $this->query.=$where;
        return $this;
    }

    public function whereArr($condtions){
        $multipleConditions = false;

        if(is_array($condtions[0])) $multipleConditions = true;
        if(!$multipleConditions){
            return $this->where3($condtions[0],$condtions[1],$condtions[2]);
        }else{
            if(count($condtions)==1){
                $condtions = $condtions[0];
                return $this->where3($condtions[0],$condtions[1],$condtions[2]);
            }else{
                return $this->multiAndWhere($condtions);
            }
        }
        $this->query.=" WHERE $col = $value";
        return $this;
    }

    public function find($id){
        return $this->where("id",$id)->first();
    }

    public function insert($data){
        $cols = implode(",",array_keys($data));
        $values = $this->sanitize(array_values($data));
        $this->query = "INSERT INTO ".self::$table." ($cols) VALUES ($values)";
        $result = $this->execute();
        return $this->select()->find($this->connection->insert_id);
    }

    public function update($data,$id = null){
        if(!$id) die("Identification Missing!");
        $updateColValues = [];
        foreach ($data as $key => $value) {
            $updateColValues[] = $key." = '".(mysqli_real_escape_string($this->connection,$value))."'";
        }
        $updateColValues = implode(",",$updateColValues);
        $this->query = "UPDATE ".self::$table." SET $updateColValues WHERE id=$id";
        return $this->execute();
    }

    public function sanitize($data){
        $values = [];
        foreach ($data as $key => $value) {
            $values[] = "'".mysqli_real_escape_string($this->connection,$value)."'";
        }
        return implode(",",$values);
    }


    public function delete($col,$value){
        if(!$this->select()->where($col,$value)->first()) return false;
        $this->query = "DELETE FROM ".self::$table." WHERE $col = '$value'";
        return $this->execute();
    }

    public function execute(){
        if(!$result = $this->connection->query($this->query)){
            die("Query: ".$this->query."Database Error: ".$this->connection->error);
        }
        return $result;
    }

    public function query($sql){
        $this->query = $sql;
        return $this;
    }

    
}