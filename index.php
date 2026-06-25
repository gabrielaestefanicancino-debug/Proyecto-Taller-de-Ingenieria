<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca de Reseñas — Presentación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f4f4f9;
            color: #333;
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #007BFF;
            padding-bottom: 10px;
        }
        h2 {
            color: #007BFF;
            margin-top: 40px;
        }
        section {
            background: white;
            padding: 25px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-top: 20px;
            max-width: 800px;
        }
        ul {
            line-height: 2;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #007BFF;
            color: white;
            padding: 10px;
            text-align: left;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .letra { font-weight: bold; font-size: 1.1rem; }
        .create { color: #28a745; }
        .read   { color: #007BFF; }
        .update { color: #e0a800; }
        .delete { color: #dc3545; 
        }
        .imagen-app {
            display: block;
            max-width: 100%;
            height: auto;
            margin: 20px auto;
            border: 1px solid #ddd;
            border-radius: 5px;
}
        .app-frame {
            width: 100%;
            height: 900px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 15px;
        }
        .btn-app {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 18px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h1>📚 Biblioteca de Reseñas de Libros</h1>

    <!-- 1. INTEGRANTES -->
    <h2>1. Integrantes del Grupo</h2>
    <section>
        <ul>
            <li><strong>Bárbara Vargas</strong></li>
            <li><strong>Milaray Montecino</strong></li>
            <li><strong>Gabriela Cancino</strong></li>
        </ul>
    </section>

    <!-- 2. DESCRIPCIÓN DE LA APLICACIÓN -->
    <h2>2. Descripción de la Aplicación</h2>
    <section>
        <p>
            <strong>Biblioteca de Reseñas de Libros</strong> es una aplicación web que permite a los usuarios
            registrar y gestionar reseñas de libros. Por cada libro se almacena el título, el nombre del autor,
            una calificación de 1 a 5 estrellas y un comentario personal sobre la lectura.
        </p>
        <p style="margin-top: 12px;">
            La aplicación utiliza <strong>PHP</strong> con <strong>PDO</strong> para la conexión a una base de datos
            <strong>MySQL</strong>, y se despliega mediante <strong>Docker Compose</strong>, donde el contenedor
            de PHP se comunica con el contenedor de base de datos a través del servicio <code>db</code>.
            Los datos se persisten en la tabla <code>resenas</code> y se muestran ordenados por fecha de creación.
        </p>
    </section>

    <!-- 3. OPERACIONES CRUD -->
    <h2>3. Operaciones CRUD</h2>
    <section>
        <table>
            <thead>
                <tr>
                    <th>Operación</th>
                    <th>Descripción</th>
                    <th>Cómo se implementa</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="letra create">CREATE — Crear</span></td>
                    <td>El usuario completa el formulario con título, autor, calificación y comentario, y publica una nueva reseña.</td>
                    <td>Formulario POST con <code>action=create</code>. Se ejecuta <code>INSERT INTO resenas</code> usando un prepared statement.</td>
                </tr>
                <tr>
                    <td><span class="letra read">READ — Leer</span></td>
                    <td>Se muestran todas las reseñas registradas en una tabla, ordenadas de la más reciente a la más antigua.</td>
                    <td><code>SELECT * FROM resenas ORDER BY fecha_creacion DESC</code>. Los resultados se recorren con <code>foreach</code> en el HTML.</td>
                </tr>
                <tr>
                    <td><span class="letra update">UPDATE — Modificar</span></td>
                    <td>El usuario hace clic en "Editar" y el formulario se precarga con los datos actuales del registro para modificarlos.</td>
                    <td>GET con <code>edit=id</code> carga el registro. Formulario POST con <code>action=update</code> ejecuta <code>UPDATE resenas ... WHERE id = ?</code>.</td>
                </tr>
                <tr>
                    <td><span class="letra delete">DELETE — Borrar</span></td>
                    <td>El usuario hace clic en "Borrar", confirma la acción en una ventana emergente y el registro se elimina permanentemente.</td>
                    <td>Enlace GET con <code>delete=id</code>. Se ejecuta <code>DELETE FROM resenas WHERE id = ?</code> y se redirige a <code>index.php</code>.</td>
                </tr>
            </tbody>
        </table>
    </section>

    <!-- 4. INTERFAZ DE LA APLICACIÓN WEB -->
    <h2>4. Interfaz de la Aplicación Web</h2>
    <section>
        <p>
            A continuación se muestra la interfaz principal de la aplicación web
            Biblioteca de Reseñas de Libros.
        </p>

        <img src="mockup.png"
             alt="Interfaz de la aplicación web"
             class="imagen-app">
    </section>

    <!-- 5. APLICACIÓN FUNCIONANDO -->
    <h2>5. Aplicación Funcionando con su Base de Datos</h2>
    <section>
        <p>
            A continuación se presenta la aplicación <strong>en funcionamiento real</strong>,
            conectada a la base de datos MySQL. Puedes crear, leer, modificar y eliminar
            reseñas directamente desde aquí, o abrirla en una pestaña completa.
        </p>

        <a href="aplicacion.php" target="_blank" class="btn-app">🚀 Abrir la aplicación en una pestaña nueva</a>

        <iframe src="aplicacion.php" class="app-frame" title="Aplicación Biblioteca de Reseñas funcionando"></iframe>
    </section>

</body>
</html>
