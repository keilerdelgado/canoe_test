## System Requirements

- PHP 8 or higher
- Composer
- Docker (docker-compose)
- Api Platform (Postman)

## Installation

1. Clone the repository:

```
git clone git@github.com:keilerdelgado/canoe_test.git

# move to the project folder
cd canoe_test

# install dependencies
composer install

# start services, migrations and seeds
docker-compose up

# start php server
php -S localhost:8080

```

## Usage

Valid enpoints:

#### Managers

- [GET] /managers
- [GET] /managers/{id}
- [POST] /managers
- [PUT] /managers/{id}
- [DELETE] /managers/{id}

#### Funds

- [GET] /funds
- [GET] /funds/{id}
- [POST] /funds
- [PUT] /funds/{id}
- [DELETE] /funds/{id}
- [GET] /duplicate_funds

#### Companies

- [GET] /companies
- [GET] /companies/{id}
- [POST] /companies
- [PUT] /companies/{id}
- [DELETE], /companies/{id}

#### Aliases

- [GET] /aliases
- [GET] /aliases/{id}
- [POST] /aliases
- [PUT] /aliases/{id}
- [DELETE] /aliases/{id}

#### Events

- [GET] /events

## Tasks

1. Design and create a data model to store data for the entities described above. Please document your ER diagram.
   ![ERD](./assets/canoe_erd.png?raw=true "Title")

2. Create a back-end service to support the following use cases:

- **a.** Display a list of funds optionally filtered by Name, Fund Manager, Year
- **How:** [GET] /managers?start_year=2010&manager_id=1?name=some
- **b.** An Update method to update a Fund and all related attributes.
- **How:** [PUT] /funds/{id} (every attribute in the body will be updated)

3. Create an event-driven back end process to support:

- **a.** If a new fund is created with a name and manager that matches the name or an alias of an existing fund with the same manager, throw a duplicate_fund_warning event.
- **How:** Implemented a queue system with RabbitMQ
- **b.** Write a process to Consume the duplicate_fund_warning event
- **How:** [GET] /events
- **c.** Bonus if time permitting: Add a method to the service created in #2 that will return a list of potentially duplicate funds
- **How:** [GET] /duplicate_funds
