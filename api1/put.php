<!DOCTYPE html>
<html>
<head>
    <title>Actualizar Entrada de Diario</title>
</head>
<body>
    <h1>Actualizar Entrada de Diario</h1>
    
    <form id="updateForm">
        <label for="id_mae">ID de Usuario:</label>
        <input type="text" id="id_mae" name="id_mae" required><br>

        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" required><br>

        <label for="apodo">Apodo</label>
        <input type="text" id="apodo" name="apodo"><br>

        <label for="tel">tel:</label>
        <input type="text" id="tel" name="tel"><br>

        <label for="foto">foto:</label>
        <input type="text" id="foto" name="foto"><br>

        <button type="button" id="putButton">Actualizar con PUT</button>
        <button type="button" id="patchButton">Actualizar con PATCH</button>
    </form>

    <div id="response"></div>

    <script>
        document.getElementById('putButton').addEventListener('click', function () {
            actualizarEntrada('PUT');
        });

        document.getElementById('patchButton').addEventListener('click', function () {
            actualizarEntrada('PATCH');
        });

        function actualizarEntrada(metodo) {
            var id_mae = document.getElementById('id_mae').value;
            var nombre = document.getElementById('nombre').value;
            var apodo = document.getElementById('apodo').value;
            var tel = document.getElementById('tel').value;
            var foto = document.getElementById('foto').value;

            var data = new URLSearchParams();
            data.append('id_mae', id_mae);
            data.append('nombre', nombre);
            data.append('apodo', apodo);
            data.append('tel', tel);
            data.append('foto', foto);

            fetch('method.php', {
                method: metodo,
                body: data
            })
            .then(function(response) {
                return response.text();
            })
            .then(function(data) {
                document.getElementById('response').textContent = data;
            })
            .catch(function(error) {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>
