<?php
$file = tables.php;
?>
<div id="admintables_nico">

<form action="<?php echo $file; ?>" method="post" id="global_table">
</form>

<form action="<?php echo $file; ?>tables" method="post" id="selected_table">
</form>

<script type="text/javascript">
$( document ).ready(function() {
    $( ".php_js_echo" ).remove();
});
</script>

</div>

<?php
require("server/mysqli_connector.php");

class adminTables {
    private $result;
    private $code;
    private $onlyget;
    public $iterable;
    public $type;
    public function __construct() {
        try {
            createClass();
            $this->mainTable();
            $this->getSelectedTable(false);
        } catch (Exception $e) {
            echo("<h1>Fallo en el sistema</h1>");
            echo("<p>Codigo de error: <strong>" . $e->getMessage(). " </strong></p>");
            die;
        }
    }
    private function mainTable() {
        $this->query = "SHOW TABLES;";
        $this->globalTableArray = $GLOBALS["mClass"]->fetchArray($this->query, "Tables_in_cattusmanga");
        $code =
        "<h1>Elige un una tabla</h1><select name='tablas' id='admin_table_1'><option hidden>";
        if (($this->getSelectedTable(true)) === true) {
            $code .= $this->selectedTable;
        } else {
            $code .= "Elige una tabla";
        }
        $code .= "</option>";
        foreach ($this->globalTableArray as $v) {
            $code .= ("<option value=" . $v . ">" . $v . "</option> ");
        }
        $code .= 
        "</select><input type='submit' value='Submit'><hr/>";
        $this->javascriptPrinter("global_table", $code);
    }
    private function getSelectedTable($onlyget) {
        if (isset($_POST["tablas"])) {
            foreach ($this->globalTableArray as $v) {
                if ($_POST["tablas"] === $v) {
                    $this->selectedTable = $v;
                }
            }
            if (isset($this->selectedTable) !== true ) { throw new Exception('adminTable.undefined_table');}
            else if ($onlyget === false) { $this->constructSelectedTable(); }
            else { return true; }
        }
    }
    private function constructSelectedTable() {
        $this->query = "SHOW COLUMNS FROM " . $this->selectedTable . ";";
        $this->selectedTableArray = $GLOBALS["mClass"]->fetchArray($this->query, "Field");
        $code = "<h2>Tabla seleccionada</h1><table border='1' class='table table-bordered'><thead>";
        $code = $this->for_each($this->selectedTableArray, 1, $code);
        $code .= "</thead><tbody>";
        $this->query = "SELECT * FROM " . $this->selectedTable . ";";
        $code = $this->table_fetch_array($this->query, $code);
        $code .= "</tbody></table>";
        $this->javascriptPrinter("selected_table", $code);
    }
    public function table_fetch_array($query, $code) {
        $result = $GLOBALS["mysqli"]->query($this->query);
        while ($row = $result->fetch_array(MYSQLI_NUM)) {
            $code = $this->for_each($row, 2, $code);
        }
        return $code;
    }
    public function for_each($iterable, $type, $code) {
        if ($type === 1) {
            $internal_type = "<th> ";
            $internal_type2 =  "</th>";
        } else {
            $internal_type = "<td>";
            $internal_type2 = "</td>";
        }
        $code .= "<tr>";
        foreach ($iterable as $v) {
            $code .= ($internal_type . $v . $internal_type2);
        }
        $code .= "</tr>";
        return $code;
    }
    public function javascriptPrinter($id, $code) {
        echo("<script type='text/javascript' class='php_js_echo'>document.getElementById('" . $id . "').innerHTML = \"" . $code . "\";</script>");
    }
}
$e = new adminTables;
?>