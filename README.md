# Galería de Arte - API REST (TPE Parte 3)

API REST que expone los recursos del proyecto "Galería de Arte" para permitir su integración con sistemas de terceros. Comparte la base de datos con la primera entrega del trabajo.

## Pasos para consumir la API
1. Cloná el repositorio en la carpeta htdocs de Xampp
2. Iniciá, desde Xampp, Apache y MySQL
3. Utilizá cualquier plataforma/herramienta para probar los endpoints (por ejemplo Postman). 

### URL base

http://localhost/web2/GaleriaDeArte_P3/api

## Autenticación | Uso del JWT

Para realizar modificaciones se requiere un token. Para obtener este token se deben seguir los siguientes pasos:
En Postman (o similar): accede a http://localhost/web2/GaleriaDeArte_P3/api/login (con método POST).
En el body ingresá el siguiente formato con los datos:

{
    "email" : "webadmin@admin.com",
    "password" : "admin"
}

Esto te va a devolver una respuesta de tipo {"token": "..."}. Copia el token y arma un pedido nuevo accediendo a: http://localhost/web2/GaleriaDeArte_P3/api/obras (con métodos POST o PUT). En la pestaña Authorization, selecciona "Bearer Token" y pegá el token. Esto te va a permitir hacer modificaciones por el lapso de 1 hora (duración del token). 

## Formato de respuesta

Todas las respuestas se devuelven en formato `JSON`. Los recursos de error devuelven un mensaje descriptivo junto con el código de estado HTTP correspondiente.

## Códigos de respuesta utilizados

200 OK:  La operación se realizó con éxito (lectura, actualización, eliminación) 
201 Created: Se creó un nuevo recurso correctamente (POST) 
400 Bad Request:  Faltan datos obligatorios o un parámetro enviado es inválido 
404 Not Found: El recurso solicitado no existe, o la ruta pedida no coincide con ningún endpoint 

## Recurso: Obras

### Listar todas las obras

GET /obras

Devuelve la colección completa de obras. Soporta los siguientes parámetros opcionales por **query string**, que se pueden combinar entre sí (salvo lo indicado):

orderBy=string (campo por el cual ordenar). Acepta cualquier columna de la tabla obras (id_obra, nombre, año_creacion, corriente_artistica, tecnica, soporte, descripcion, id_artista). Si se envía un valor inválido, se ignora y se ordena por id_obra de forma ascendente. 

order=string (dirección del orden: `ASC` o `DESC`) No distingue mayúsculas/minúsculas. Cualquier otro valor cae en `ASC`.

page=int (número de página para paginar resultados. Por defecto es 1. 

limit=int (cantidad de resultados por página). Por defecto devuelve la totalidad de los elementos. 

id_artista=int. Filtra la colección y devuelve solo las obras de ese artista. No se puede combinar con "nombre".

nombre=string. Filtra la colección por nombre de obra (coincidencia parcial, no exacta). No se puede combinar con "id_artista".

Si no se envía ningún parámetro, el endpoint devuelve el comportamiento por defecto: todas las obras, ordenadas por id_obra ascendente.

**Ejemplo de pedido:**

GET /obras?orderBy=nombre&order=desc&page=1&limit=5

### Obtener una obra por ID

GET /obras/:id

Devuelve una obra puntual junto con el nombre completo del artista correspondiente (se resuelve internamente con un `JOIN`).

**Ejemplo de pedido:**

GET /obras/15

### Crear una obra

POST /obras


**Body (JSON):**
```json
{
  "nombre": "Atardecer en el río",
  "año_creacion": 2019,
  "tecnica": "Óleo",
  "soporte": "Tela",
  "corriente_artistica": "Impresionismo",
  "descripcion": "Una breve descripción de la obra",
  "imagen": "https://miservidor.com/imagenes/obra12.jpg",
  "id_artista": 3
}
```

Todos los campos son obligatorios. Es importante que id_obra no se incluya dado que se genera de manera auto-incremental por la base de datos. 

### Actualizar una obra (reemplazo completo)

PUT /obras/:id

En el body en formato JSON, mismos campos que en el POST, todos obligatorios (reemplaza por completo los datos existentes).

**Ejemplo de pedido:**

PUT /obras/15

### Actualizar una obra parcialmente

PATCH /obras/:id

En el body en formato JSON, acepta cualquier subconjunto de los campos de la obra; los que no se envíen mantienen su valor actual.

**Ejemplo de pedido:**

PATCH /obras/15


### Eliminar una obra

DELETE /obras/:id

**Ejemplo de pedido:**

DELETE /obras/15

