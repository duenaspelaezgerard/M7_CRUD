<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CRUD - Create, Read, Update & Delete</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    .container {
        max-width: 1300px;
        margin: 0 auto;
        padding: 20px;
    }

    form {
        padding: 20px;
        max-width: 600px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    table {
        border: 1px solid black;
        text-align:center;
    }

    table th, td {
        border: 1px solid black;
    }

    table th {
        padding: 15px;
    }
    
    table td {
        padding: 10px;
    }
    #result {
        margin-top: 20px;
    }

    .input-group {
        margin-bottom: 10px;
    }
</style>
</head>
<body>

<div class="container">
    <h1>CRUD</h1>
    <form id="searchForm" method="POST" action="index.php">

        <div class="input-group">
            <label for="comando">Selecciona una acción:</label>
            <select style="margin-bottom: 10px;" name="comando" id="comando">
                <option value="">Escoge</option>
                <option value="create">CREATE TABLE</option>
                <option value="read_all">READ *</option>
                <option value="read_id">READ</option>
                <option value="update">UPDATE</option>
                <option value="delete">DELETE</option>
            </select>
        </div>

        <div id="inputTable" class="input-group" style="display: none;">
            <!-- <label for="tableName" >Nombre de la tabla:</label> -->
            <input type="text" name="tableName" id="tableName" placeholder="Nombre de la tabla">
        </div>

        <div id="inputColumn" class="input-group" style="display: none;">
            <div>
                <input type="text" style="margin: 10px 0;" name="columnName[]" class="columnName" placeholder="Nombre de la columna">
            </div>

            <div>
                <select name="columnType[]" class="columnType">
                    <option value="varchar">VARCHAR (255)</option>
                    <option value="int">INT</option>
                    <option value="float">FLOAT</option>
                    <option value="date">DATE </option>
                </select>
            </div>
        </div>

        <button type="button" style="margin: 10px 0; display: none;" id="addColumn">Añadir columna</button>

        <div style="display: none; margin: 10px 0;" id="tablas">
            <select name="tablas">
                <option value="0">Selecciona una tabla</option>
                <option value="address">Address</option>
                <option value="customers">Customers</option>
                <option value="userapp">User App</option>
            </select>
        </div>

        <div id="inputId" class="input-group" style="margin: 10px 0; display: none;">
            <label for="id">ID:</label>
            <input type="number" name="id" id="id">
        </div>

        <button type="submit" id="submitBtn">Enviar</button>
    </form>
    
    <div id="result">

        
    </div>
    <button type="button" style="margin: 10px 0; display:none" id="borrarFila">Confirmar</button>


<script>
    
    document.getElementById("searchForm").addEventListener("submit", function(e) {
        e.preventDefault();

        var formData = $('#searchForm').serialize();
        var confirmar = document.getElementById("borrarFila");
        var selectedOption = document.getElementById("comando").value
        
        $.ajax({
            type: 'POST',
            url: 'class.IntelForm.php', 
            data: formData,
            success: function(response){
                document.getElementById("result").innerHTML = response;
                if (selectedOption === "delete") {
                    confirmar.style.display = "block";
                }
            },
            error: function(xhr, status, error){
                console.error(xhr.responseText);
            }
        });
    });

    document.getElementById("comando").addEventListener("change", function() {
    
        var selectedOption = this.value;
        var inputTable = document.getElementById("inputTable");
        var inputColumn = document.getElementById("inputColumn");
        var inputId = document.getElementById("inputId");
        var addColumn = document.getElementById("addColumn");
        var tablas = document.getElementById("tablas");
        var borrar = document.getElementById("borrarFila");
        document.getElementById("result").innerHTML = "";
        if (selectedOption === "create") {
        
            inputTable.style.display = "block";
            inputColumn.style.display = "block";
            inputId.style.display = "none";
            addColumn.style.display = "block";
            tablas.style.display = "none";
            borrar.style.display = "none";

        }else{

            inputTable.style.display = "none";
            inputColumn.style.display = "none";
            addColumn.style.display = "none";
            tablas.style.display = "block"
            borrar.style.display = "none";
            
            if (selectedOption === "read_id" || selectedOption === "update" || selectedOption === "delete") {
                inputId.style.display = "block";
            } else {
                inputId.style.display = "none";
            }
        }
    });


    document.getElementById("borrarFila").addEventListener("click", function() {

        var formData = $('#searchForm').serialize(); 
        var confirmar = "confirmar"; 
        var esconder = document.getElementById("borrarFila");
        var comando = document.getElementById("comando").value

        var requestData = {
            formData: formData,
            comando: comando,
            confirmar: confirmar
        };

        $.ajax({
            type: 'POST',
            url: 'class.IntelForm.php', 
            data: requestData, 
            success: function(response){
                document.getElementById("result").innerHTML = response;
                esconder.style.display = "none";
            },
            error: function(xhr, status, error){
                console.error(xhr.responseText);
            }
        });
    });

    var columnCount = 1;

    document.getElementById("addColumn").addEventListener("click", function() {
        var select = document.createElement("div");
        select.innerHTML = `
            <div>
                <input type="text" style="margin: 10px 0;" name="columnName[${columnCount}]" class="columnName" placeholder="Nombre de la columna">
            </div>
            <div>
                <select name="columnType[${columnCount}]" class="columnType">
                    <option value="varchar">VARCHAR (255)</option>
                    <option value="int">INT</option>
                    <option value="float">FLOAT</option>
                    <option value="date">DATE</option>
                </select>
            </div>
        `;
        columnCount++; 
        var inputColumn = document.getElementById("inputColumn");
        inputColumn.parentNode.insertBefore(select, document.getElementById("addColumn"));
    });

    
    </script>
</body>
</html>