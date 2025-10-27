# Prueba Técnica – Nextcaddy

¡Hola!  
Nos alegra que hayas avanzado hasta esta fase del proceso. Con esta prueba intentaremos hacernos una idea de cómo programas. Este ejercicio no es real y no va a tener un uso más allá de valorar tus habilidades como programador, pero debes ejecutarlo como si fuera un caso de uso real de una aplicación con un gran volumen de datos y de uso. Hazlo lo más cercano posible a como lo harías en tu día a día.

## Contexto

Nextcaddy es una plataforma de Golf que acerca jugadores y clubs, donde se juegan competiciones federativas.

---

## Descripción

En la prueba se te entrega una serie de herramientas necesarias para su desarrollo:

- Hay ficheros con datos que queremos que uses para la prueba. Se encuentran en la carpeta `necessaryData` en la raíz del proyecto. Usa los datos con total libertad y como consideres más conveniente.

    - **clubs.json**: contiene un listado de clubs de golf con los siguientes campos:
        - `name`: nombre ficticio del club.
        - `clubCode`: código federativo y único del club.

    - **competitions.json**: contiene un listado de competiciones de golf con los siguientes campos:
        - `name`: nombre ficticio de la competición.
        - `club`: código federativo del club al que pertenece.
        - `startDate`: fecha de inicio.
        - `places`: número de plazas disponibles para inscripciones.

    - **counter.json**: contiene un registro de contadores con datos para la aplicación:
        - `numCompetitions`: número de competiciones que hay en la aplicación.
        - `numRegistrations`: número de inscritos actuales que hay en la aplicación. Este número es ficticio puesto que la prueba se entrega sin ningún inscrito, pero asume que sea un número real de inscritos a competiciones antiguas que no existen en los datos que se entregan.

---

## API de la Federación

Para poder realizar la prueba, también te proporcionamos una API que tenemos desplegada y que te devolverá datos de jugadores inventados, haciéndose pasar por endpoints reales de la Federación de Golf.

Los endpoints ofrecidos son los siguientes:

- **Listado de jugadores**
    - Endpoint: `https://fakefederation.nextcaddy.com/players`
    - Devolverá los datos de todos los jugadores que están federados.

- **Datos de jugador en concreto**
    - Endpoint: `https://fakefederation.nextcaddy.com/players/100002`  
      (el parámetro corresponde a la licencia del jugador en la federación).
    - Recibe la licencia del jugador y devuelve todos los datos del mismo en la Federación.
    - Si no existe un jugador con esa licencia, devolverá una respuesta 404.

- **Jugadores de un club**
    - Endpoint: `https://fakefederation.nextcaddy.com/players?club=CB29`
    - Recibe el código del club y devuelve el listado de jugadores que pertenecen a dicho club.
    - Si no existe ningún jugador para ese club, devolverá una respuesta 200 con un JSON body vacío.

> **NOTA:** El campo `federatedCode` de los endpoints de la Federación corresponde a la licencia del jugador.  
> **IMPORTANTE:** Trata este servicio como si fuera un API real de la Federación Española de Golf ajena por completo a nuestra aplicación.

---

## Tarea principal

La tarea principal consiste en crear una API, idealmente con PHP y Symfony, que exponga 2 endpoints:

1. **Endpoint de inscripción a una competición**
    - Inscribirá a un jugador en una competición.
    - Recibirá como parámetro tanto el `id` de la competición, como la licencia del jugador.
    - Aumentará el número de inscritos en el contador (campo `numRegistrations`).

2. **Endpoint para listar jugadores**
    - Debe listar los jugadores que alguna vez han usado nuestra aplicación, es decir, que hayan realizado alguna acción de persistencia en la misma.
    - Debe devolver los siguientes datos del jugador: **sólo los que son invariables** desde la Federación.  
      Los variables son `active` y `clubCode`.

---

## Consideraciones para desarrollar los endpoints

- La licencia de un jugador se compone de 6 caracteres.
- La licencia es única por jugador.
- La licencia de un jugador no puede cambiar.
- Un jugador pertenece a un club.
- Un jugador sólo puede inscribirse en una competición del club al que pertenece.
- Un jugador no podrá inscribirse en una competición si no está federado.
- La inscripción sólo se realizará si hay plazas disponibles.
- Cuando un usuario se inscriba a una competición, se debe sumar esa inscripción al contador `numRegistrations`, que contiene el número de jugadores inscritos.
- Hay acciones que puede hacer un jugador directamente en la Federación sin pasar por nuestra aplicación (por ejemplo, imagina que el jugador llame por teléfono a la Federación y realice dicho trámite).  
  Las únicas posibles acciones son:
    - Darse de baja: el jugador deja de estar federado (`active = 0`).
    - Cambiar de club.

---

## Entrega

Entrega el proyecto de la manera que creas más conveniente, teniendo en cuenta que tenemos que ser capaces de poder montarlo y usarlo de una forma sencilla. Necesitamos ver y analizar tanto el código como el funcionamiento real.

Documenta las decisiones y mejoras realizadas en el proyecto y envíalas en el email de respuesta.

Si consideras que hay algo que mejorarías y no has llegado a aplicar por falta de tiempo o cualquier otro motivo, coméntalo en el email de respuesta.

---

## Dudas

Cualquier duda técnica sobre la prueba puedes comentarla directamente con: `juan.cabrera@slope.es`

# Cómo poner en marcha el proyecto tras clonarlo
## Arrancar contenedores Docker
```
docker-compose up -d
```
## Instalar dependencias
```
docker exec tt_php php composer.phar install
```
## Pasar los tests
```
docker exec tt_php php bin/phpunit
```