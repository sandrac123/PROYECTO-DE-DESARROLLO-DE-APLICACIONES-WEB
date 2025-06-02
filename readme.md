# 📊 Proyecto de Encuestas Web

Este proyecto tiene como objetivo el desarrollo de aplicaciones web modernas utilizando buenas prácticas de programación, diseño responsivo y tecnologías actuales.

## Características

- Arquitectura modular y escalable.
- Interfaz de usuario intuitiva y adaptable a dispositivos móviles.
- Integración con bases de datos y servicios externos.
- Seguridad y autenticación de usuarios.
- Documentación clara y detallada.

## Tecnologías utilizadas

- HTML5, CSS3, JavaScript (ES6+)
- Node.js y Express para el backend
- Base de datos (Mysql)
- Control de versiones Git
- phpMyAdmin
- BootStrap
- PHP

## Instalación

1. Clona el repositorio:

   git clone https://github.com/sandrac123/PROYECTO-DE-DESARROLLO-DE-APLICACIONES-WEB.git

2. Instala las dependencias:
   ```
   npm install
   ```
3. Configura las variables de entorno según el archivo `.env.example`.
4. Inicia la aplicación:
   ```
   npm start
   ```

## Contribución

Las contribuciones son bienvenidas. Por favor, abre un issue o realiza un pull request para sugerir mejoras o reportar errores.

## Licencia

Este proyecto está bajo la licencia MIT.

## Estructura del proyecto

```
├── backend/                  # Lógica del servidor y API
│   ├── controllers/         # Controladores de rutas
│   ├── models/              # Modelos de base de datos
│   ├── routes/              # Definición de rutas
│   ├── middlewares/         # Middlewares personalizados
│   ├── config/              # Configuración y variables de entorno
│   ├── app.js               # Archivo principal del backend
│   └── package.json         # Dependencias del backend
│
├── frontend/                 # Aplicación cliente (interfaz de usuario)
│   ├── public/              # Archivos estáticos
│   ├── src/                 # Código fuente del frontend
│   │   ├── components/      # Componentes reutilizables
│   │   ├── pages/           # Vistas o páginas principales
│   │   ├── App.jsx          # Componente principal
│   │   └── index.js         # Punto de entrada
│   └── package.json         # Dependencias del frontend
│
├── database/                 # Scripts y archivos de base de datos
│   └── schema.sql           # Esquema de la base de datos
│
├── .env.example              # Ejemplo de variables de entorno
├── .gitignore                # Archivos y carpetas ignorados por Git
├── README.md                 # Documentación principal del proyecto
└── LICENSE                   # Licencia del proyecto
```

## configuración

Puedes crear un archivo .env en la raiz del sistema


## FUNCIONAMIENTO
1. El proyecto consiste en una página principal a través de la cual pinchando en un enlace puedes acceder a las tres enquisas

- Enquisa Premium
- Enquisa De Eventos
- Enquisa de Valoración del curso

2. El usuario entrara en la página del enlace y rellenara los datos de forma que los resultados se guarden en una base de datos. 
3. Mediiante un gráfico se observaran los resultados de la encuesta


