<?php
declare(strict_types=1);

namespace Core;

use mysqli;
use Core\Config;
use Core\Sessions;

class DB {
    private static ?DB $obj = null;
    private $connection;
    private string $query = "";
    private static ?string $table = null;

    public function __construct(){
        $this->getConnection();
    }

    private function getConnection(): void{
        $this->connection = new mysqli(Config::get('database.host'),Config::get('database.user'),Config::get('database.password'),Config::get('database.database'));
        if($this->connection->connect_error){
            die("Connection faild: ". mysqli_connect_error());
        }
    }

    public static function table(?string $table=null): self{
        if(!$table) die("Error: Table name missing!");
        self::$table = $table;
        if(is_null(self::$obj)){
            self::$obj = new self();
        }
        return self::$obj;
    }

    public function select(string ...$cols): self{
        $colsToSelect = "*";
        if(count($cols)>0) $colsToSelect = implode(",",$cols);
        $this->query = "SELECT $colsToSelect FROM ".self::$table;
        return $this;
    }

    public function leftJoin(string $joiningTable, string $forignKey, string $primaryKey): self{
        $this->query .= " LEFT JOIN $joiningTable on $forignKey = $primaryKey ";
        return $this;
    }

    public function get(?int $limit=null, int $offset=0): ?array{
        if($limit && is_int($limit)){
            $this->query.=" limit $limit offset $offset";
        }
        $result = $this->connection->query($this->query);
        if(!$result){
            die("Query: ".$this->query."<br>Database Error: ".$this->connection->error);
        }
        if($result->num_rows>0){
            $data = [];
            while($row = $result->fetch_assoc()){
                $data[] = (object)$row;
            }
            return $data;
        }else{
            return null;
        }
    }

    public function paginate(int $perPageLimit): ?array{
        $totalRecords = count($this->get());
        $totalPages = ceil($totalRecords/$perPageLimit);
        $activePage = 1;
        if(isset($_GET["page"]) && !empty($_GET["page"])){
            $activePage = (int)$_GET["page"];
        }
        $pagination = [
            "number_of_pages" => $totalPages,
            "active_page" => $activePage,
        ];
        Sessions::set("pagination",$pagination);
        $offset = ($activePage-1)*$perPageLimit;
        return $this->get($perPageLimit,$offset);
    }

    public function first(): ?object{
        $result = $this->get(1);
        return $result ? $result[0] : null;
    }

    public function take(?int $limit = null): ?array{
        return $this->get($limit);
    }

    public function orderBy(string $col, string $order): self{
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

    public function where2(string $col, string $value): self{
        $this->query.=" WHERE $col = '$value'";
        return $this;
    }

    public function where3(string $col, string $conditon, string $value): self{
        $this->query.= " WHERE $col $conditon '".mysqli_real_escape_string($this->connection,$value)."'";
        return $this;
    }

    public function multiAndWhere(array $condtions): self{
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

    public function whereArr(array $condtions): self{
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
    }

    public function find(string|int $id): ?object{
        return $this->where("id",$id)->first();
    }

    public function insert(array $data): ?object{
        $cols = implode(",",array_keys($data));
        $values = $this->sanitize(array_values($data));
        $this->query = "INSERT INTO ".self::$table." ($cols) VALUES ($values)";
        $result = $this->execute();
        return $this->select()->find($this->connection->insert_id);
    }

    public function update(array $data, int|string $id = null){
        if(!$id) die("Identification Missing!");
        $updateColValues = [];
        foreach ($data as $key => $value) {
            $updateColValues[] = $key." = '".(mysqli_real_escape_string($this->connection,$value))."'";
        }
        $updateColValues = implode(",",$updateColValues);
        $this->query = "UPDATE ".self::$table." SET $updateColValues WHERE id=$id";
        return $this->execute();
    }

    public function sanitize(array $data): string{
        $values = [];
        foreach ($data as $key => $value) {
            $values[] = "'".mysqli_real_escape_string($this->connection,$value)."'";
        }
        return implode(",",$values);
    }


    public function delete(string $col, string $value){
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

    public function query(string $sql): self{
        $this->query = $sql;
        return $this;
    }

    
}