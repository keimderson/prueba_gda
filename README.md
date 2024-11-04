# prueba_gda

#Version PHP = 8.2.12

#version laravel = 11.30.0

#version composer = 2.8.2

#version Npm = 10.9.0

#version DB = 10.4.32-MariaDB 

##################
# Base de datos #
#################

Siguiendo con los parametros dados y no afectar con las tablas basicas proporcionadas, se debe ejecutar como: 

primer paso el comando php artisan migrate

segundo paso el codigo SQL proporcionado, se encuentra en el archivo codigo_sql en la raiz del proyecto, donde bdapi sera el nombre del schema. 

configurar un usuario desde laravel para ejecutar pruebas del los servicios. esto usando el comando:

php artisan tinker 

seguido de la siguiente estructura sin los caracteres (#)
############################################################
use App\Models\User;

User::create(
    'name' => 'tester',
    'email' => 'tester@tester.com',
    'password' => bcrypt('tester'),
]);
############################################################

se puede dar los datos que se deseen

################################
#configuración del archivo .env#
################################

valores clave a configurar:
APP_DEBUG=false //segun los requerimientos 

DB_CONNECTION= tipo de conexion (mysql)
DB_HOST= host de la base de datos
DB_PORT= puerto de la base de datos
DB_DATABASE= nombre de la base de datos
DB_USERNAME= usuario de la base de datos
DB_PASSWORD= contraseña de la base de datos

LOGGING_ENABLED=true //variable de control para log True no graba log de salida, False graba log de salida

###############################
#configuracion del archivo log#
###############################

se debe asegurar que en el archivo /config/loggin.php este agregado el nuevo canal para el registro del log.
....otros canales 

        'apirest' => [
            'driver' => 'single',
            'path' => storage_path('logs/apirest.log'),
            'level' => 'info',
        ],
Log guardado en archivo de texto plano en la ruta storage/log/apirest.log
##################################################################################################################


#########################
#Estructura del proyecto#
#########################

Controllers:
AuthController.php -> controlador para validar y generar el token con timepo de vida de 1 hora

Api/customerController.php -> creación -> createCustomer, consulta -> getCustomer($id), delete -> deleteCustomer($dni), manejo de los datos de los customer registrados y ha registrar con validaciones. 

Middleware:
checkToken.php -> validaciones para la correcta generación del token, utilizado de manera obligatoria para consumir los demas servicios
checkGetCustomer -> valida las solitudes tipo get o delete
registerCustomer -> validaciones necesarias para el correcto registro de customers
logRequest -> encargado de la logica para el registro de los log entra y salida o solo entrada en caso de la variable LOGGING_ENABLED se ecuentre true

Models: 
Communes.php -> tabla communes, asegura datos de la BD 
Customer.php -> tabla customers, asegura datos de la BD 
Region.php -> tabla regions, asegura datos de la BD 
Token.php -> tabla tokens, asegura datos de la BD 
User.php -> tabla users, asegura datos de la BD 

Registro de Middleware 
/bootstrap/app -> se registran los middleware creados para las validaciones necesarias y son agreagdas en routes bajo su alias

Routes: 
routes/api -> se crean las rutas o grupo de rutas con los middleware necesarios para cada caso

Consumo de la Api

ejemplos:

######################
Generación de token:
metodo POST
http://127.0.0.1:8000/api/login

Request
{
    "email": "tester@tester.com",
    "password": "tester"
}

Response
{
    "token": "b7d618321697e15119594264794d13af4d2726c6",
    "expires_at": "2024-11-04 02:39:25"
}

es necesario haber creado el usuario como se indico anteriormente

NOTA: el token es obligatorio para el consumo del resto de los servicios,
configurar en el header la varibale de auth: 'Authorization' => 'b7d618321697e15119594264794d13af4d2726c6' para todos los casos

######################
Registro de customer.
Metodo POST
http://127.0.0.1:8000/api/customer/create

Request
{
    "dni": "25845078",
    "id_reg": 1,
    "id_com": 1,
    "email": "juan@customer.com",
    "name": "Juan",
    "last_name": "perez",
    "address": "caracas"
}

response
{
    "customer": {
        "dni": 0,
        "id_reg": 1,
        "id_com": 1,
        "email": "juan@customer.com",
        "name": "Juan",
        "last_name": "perez",
        "address": "caracas",
        "date_reg": "2024-11-04T01:39:55.279830Z"
    },
    "success": true,
    "status": 200
}

####################

Consulta de customer
Metodo GET

request
http://127.0.0.1:8000/api/customer/getcustomer/25845078

response
{
    "customer": {
        "dni": 25845078,
        "email": "juan@customer.com",
        "name": "Juan",
        "last_name": "perez",
        "region_description": "region 1",
        "commune_description": "commune 1",
        "estatus": null
    },
    "success": true,
    "status": 200
}
####################################################

eliminación logica de customer

metodo DELETE
request
http://127.0.0.1:8000/api/customer/deletecustomer/25845078

Response
{
    "message": "Registro eliminado exitosamente",
    "success": true,
    "status": 200
}
#####################################################
