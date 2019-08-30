CMH Practical Test
==================

**Horses REST API**  
by *Michael Hengstmann*  

**********

### Local Installation

- Check out or download the repository
- Copy `.env` to `.env.local`, change values to match your setup (mainly database) and delete values you don't want/need to change
- Then run these console commands:

      composer install
      php bin/console doctrine:migrations:migrate
_(the migrations also add some initial test-data to the tables)_
- add a simple vhost to your Apache pointing to the `public` directory or run the Symfony internal webserver via  
`php bin/console server:run`

You should now be able to call the "Horses API" using the `/horses` endpoint on the local server.

### Accessing the demo API

You can access the API via `/horses` on the webserver or vhost you set up for it.

Three calls are available:

- `GET /horses` - returns the details of all horses in the database
- `GET /horses/id` - returns the details of the horse with the ID "id", or a 404 error if no horse with the give ID could be found
- `POST /horses` - accepts a JSON in the request body and adds the horse to the database.  
The JSON looks as follows:

      {
          "name": "Twilight Sparkle",
          "picture": "http://cdn.ponies.com/twilight.jpg"
      }

You _can_ supply the "id" field, but it will be ignored and an automitic ID will always be generated for the new entry.

The fields of the JSON are validated as follows:

- "name" - must not be empty
- "picture" - must be a properly formatted URL (i.e. `http://...` or `https://...`)

#### Authentication

All requests need an API token for authentication.

The token needs to be supplied in the `X-API-Token` HTTP header.

The sample data, put into the database when executing the migrations, already offers two valid API tokens that can be used:

- `TEST00000`  
_(this one's for easy typing w/o the need for long copy-pastes ;-)_
- `a3d27374d33385ec076d530684e6c51d0dc8a29cb1ad55139e3c5bcb5dd4dce7`  
_(this is a more proper one)_

#### Using the API-DOC frontend

This sample application also offers a swagger style APIDOC frontend.

It can be accessed under

      /api/doc

All API endpoints are listed with brief documentations and can be tested/executed right from the page.

**You need to first click the *AUTHORIZE* button and enter an API key, or the API calls will fail due to missing authentication**.  
After entering an API token you can "on the fly" try out all endpoints.

### Exception Handling

The application has a global exception handler that takes care to always return a proper, processable JSON response in any event of something going wrong,

The business logic of the application also relies on this, as in certain situations there are exceptions thrown that are - intentionally - not cought and handled by the calling controller, as the exception handler is supposed to take care of it,

### Backgrounds

**Just FYI:**  
How certain things were implemented:

- Database access via Doctrine/ORM
- Abstraction via data models, keeping domains decoupled (models can be changed w/o the need to change the entities and vice versa)
- JSON parsing and generating via JMS Serializer
- Validation via Symfony validator
- API token authentication via custom user-provider and default Symfony Guard functionality
- Global exception handler via event subscriber hooking to kernel-exception events

## That's it

I hope I didn't forget to mention anything.

If you have any further questions, feel free to contact me via email at every time.

I hope the application is to your likings.

_-Michael Hengstmann_
