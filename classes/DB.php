<?php
define('mainFOLDER', dirname(__DIR__));
class DB {
    private $path = mainFOLDER . "\db";
    private $db_name = "db.json";

    public $db;

    public function __construct() {
        $this->db = $this->getDataBase();
    }

    private function getDataBase()
    {
        $file = file_get_contents($this->path . "\\" . $this->db_name,  FILE_USE_INCLUDE_PATH);
        $this->db = json_decode($file, true);

        return json_decode($file, true);
    }

    public function saveChange($data)
    {
        file_put_contents($this->path . "/" . $this->db_name, json_encode($data, JSON_UNESCAPED_UNICODE)); // последний параметр - некодировать многобайтные символы, чтобы кириллица отображалась верно
    }
}
?>