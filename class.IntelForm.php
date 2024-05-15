<?php
require("class.pdofactory.php");

$strDSN = "pgsql:dbname=usuaris;host=localhost;port=5432";
$objPDO = PDOFactory::GetPDO($strDSN, "postgres", "postgres", array());
$objPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$comando = $_POST['comando'];

switch ($comando) {
    case 'create':

        $tableName = $_POST['tableName'];
        $columnNames = $_POST['columnName'];
        $columnTypes = $_POST['columnType'];
        
        $query = "CREATE TABLE $tableName (";

        $numColumns = count($columnNames);
        for ($i = 0; $i < $numColumns; $i++) {
            $columnName = $columnNames[$i];
            $columnType = $columnTypes[$i];
            $query .= "$columnName $columnType";
        
            if ($i < $numColumns - 1) {
                $query .= ", ";
            }
        }
        
        $query .= ");";       

        try {
            $result = $objPDO->query($query);
            if ($result) {
                echo "La tabla $tableName se ha creado correctamente.";
            } else {
                echo "Error al crear la tabla: " . pg_last_error($conexion);
            }
        } catch (PDOException $e) {
            die("Error al ejecutar la consulta: " . $e->getMessage());
        }

    break;
        
    case 'read_all':
        $query = "SELECT * FROM " . $_POST['tablas'];
    
        try {
            $result = $objPDO->query($query);
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            
            $html = '<table>';
        
            $html .= '<tr>';
            foreach ($rows[0] as $columnName => $value) {
                $html .= "<th>$columnName</th>";
            }
            $html .= '</tr>';
    
            foreach ($rows as $row) {
                $html .= '<tr>';
                foreach ($row as $value) {
                    $html .= "<td>$value</td>";
                }
                $html .= '</tr>';
            }
    
            $html .= '</table>';
        
            echo $html;
        } catch (PDOException $e) {
            die("Error al ejecutar la consulta: " . $e->getMessage());
        }
        
        break;
    case 'read_id':

        $query = "SELECT * FROM " . $_POST['tablas'] . " WHERE id = " . $_POST['id'];

        try {
            $result = $objPDO->query($query);
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            
            $html = '<table>';
        
            $html .= '<tr>';
            foreach ($rows[0] as $columnName => $value) {
                $html .= "<th>$columnName</th>";
            }
            $html .= '</tr>';
    
            foreach ($rows as $row) {
                $html .= '<tr>';
                foreach ($row as $value) {
                    $html .= "<td>$value</td>";
                }
                $html .= '</tr>';
            }
    
            $html .= '</table>';
        
            echo $html;
        } catch (PDOException $e) {
            die("Error al ejecutar la consulta: " . $e->getMessage());
        }
              
        break;
    case 'update':
        if (isset($_POST['confirmar'])) {
            $formData = $_POST['formData'];
        
            parse_str($formData, $formDataArray);
        
            $tablas = $formDataArray['tablas'];
            $id = $formDataArray['id'];
        
            $query = "DELETE FROM $tablas WHERE id = $id";
        
            try {
                $result = $objPDO->query($query);
        
                if ($result) {
                    echo "La fila con ID $id ha sido eliminada correctamente.";
                } else {
                    echo "Error al eliminar la fila.";
                }
            } catch (PDOException $e) {
                die("Error al ejecutar la consulta: " . $e->getMessage());
            }
        } else {
            $query = "SELECT * FROM " . $_POST['tablas'] . " WHERE id = " . $_POST['id'];

            try {
                $result = $objPDO->query($query);
                $row = $result->fetch(PDO::FETCH_ASSOC);
                
                echo '<form id="updateForm">';
                foreach ($row as $columnName => $value) {
                    echo "<div class='input-group'>";
                    echo "<label for='$columnName'>$columnName:</label>";
                    echo "<input type='text' name='$columnName' id='$columnName' value='$value'>";
                    echo "</div>";
                }
                echo "<button type='button' id='submitUpdateBtn'>Actualizar</button>";
                echo '</form>';
            } catch (PDOException $e) {
                die("Error al ejecutar la consulta: " . $e->getMessage());
            }
        }

    break;
    case 'delete':
        if (isset($_POST['confirmar'])) {

            $formData = $_POST['formData'];
        
            parse_str($formData, $formDataArray);
        
            $tablas = $formDataArray['tablas'];
            $id = $formDataArray['id'];
        
            $query = "DELETE FROM $tablas WHERE id = $id";
        
            try {
                $result = $objPDO->query($query);
        
                if ($result) {
                    echo "La fila con ID $id ha sido eliminada correctamente.";
                } else {
                    echo "Error al eliminar la fila.";
                }
            } catch (PDOException $e) {
                die("Error al ejecutar la consulta: " . $e->getMessage());
            }
        } else {
            $query = "SELECT * FROM " . $_POST['tablas'] . " WHERE id = " . $_POST['id'];

            try {
                $result = $objPDO->query($query);
                $rows = $result->fetchAll(PDO::FETCH_ASSOC);
                
                $html = '<table border="1">';
            
                $html .= '<tr>';
                foreach ($rows[0] as $columnName => $value) {
                    $html .= "<th>$columnName</th>";
                }
                $html .= '</tr>';
        
                foreach ($rows as $row) {
                    $html .= '<tr>';
                    foreach ($row as $value) {
                        $html .= "<td>$value</td>";
                    }
                    $html .= '</tr>';
                }
        
                $html .= '</table>';
    
                echo $html;
            } catch (PDOException $e) {
                die("Error al ejecutar la consulta: " . $e->getMessage());
            }
        }

        break;
    default:
        echo "ESCOGE ALGO TETE ";
        break;
}

