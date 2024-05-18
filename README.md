```
   __|                                  _ \           |   _)              
  (_ |   _` |   _| _` |   _` |   -_)    __/ _` |   _| | /  |    \    _` | 
 \___| \__,_| _| \__,_| \__, | \___|   _| \__,_| _|  _\_\ _| _| _| \__, | 
                        ____/                                      ____/                      
```

## The requirements
Implement a software solution that will enable various types of vehicle to park in a parking garage.
- When a vehicle arrives at the parking garage, and if there is space available, we should see a message on the screen saying "Welcome, please go in".
- When a vehicle arrives at the entrance to the parking garage, and it's full, we should see a message saying "Sorry, no spaces left".
- Must accept multiple types of vehicles: cars, vans, motorcycles
- Each type will have a different size (e.g. 2 motorcycles can park in 1 car spot, a van  takes 1.5 car spots).
- Must have 3 floors, each floor has different capacity.
- A Van can only park on the ground floor.

## Instructions
1. Start the Docker containers
```
docker compose up -d
```

2. Install the vendor folder
```
docker compose exec app composer install
```

Or enter the container and install the vendor folder
```
docker exec -it parking-garage-assignment /bin/bash
```

```
composer install
```

3. Run your tests
```
docker compose exec app vendor/bin/phpunit src/Tests/
```

4. Run the console
```
docker compose exec app php src/console.php
```

5. Stop the docker containers
```
docker compose down
```

## Assumptions and design decisions

### Dynamic Floor Capacity
There's no predefined size for each floor of the parking garage, meaning that parking spaces on each floor can change dynamically. This assumption suggests that my parking garage implementation is flexible enough to handle varying capacities for each floor.

### Vehicle Parking and Exit Support
I've implemented a leave method to support both vehicle parking (adding to capacity) and vehicle exit (freeing up space). This will be essential for ensuring that the system can adapt to vehicles entering and leaving in real-time.

### Error Handling and Edge Cases
I can assume the system is relatively straightforward without complex error scenarios. However, I considered basic error handling as per requirements, like preventing parking when the garage is full, or when a Van try to park (considering only the ground floor). 

### Preferred Coding Standards and Conventions
By following PSR12, I am adopting a widely accepted coding standard for PHP. This will ensure that my code is consistent, readable, and maintainable according to best practices.

### File Structure
```
src/
├── Domain/            # Domain-related code
│   ├── Entities/      # Entity classes
│   ├── Interfaces/    # Entities-related Interfaces 
│   └── Exceptions/    # Entities-related Exceptions
├── Application/       # Application layer for orchestration
│   └── Commands/      # Commands folder
└── Tests/             # Unit and integration tests
```

### Trade-offs Document

#### Interface vs Abstract Class
I choose to use an Interface rather than an abstract class for vehicles because:
- The vehicles only share a method (like getSize()) and there's no common state or default behavior.
- The Sizable interface is more general and not specific to vehicles.
- This approach is ideal if you prioritize flexibility and anticipate potential changes in our vehicle structures.

For example: What if we want to let our users to parking a container (or other various objects)? Is not vehicle, but it's Sizable.

#### Utilization of PHP 8 Constructor Property Promotion
- Pros: Simplifies class definitions, reducing boilerplate code and enhancing code readability.
- Cons: Limits backward compatibility with PHP versions prior to 8, potentially restricting compatibility with certain environments.

#### Enforcement of Domain-Specific Rules
Where is the best place to enforce domain-specific rules like "Special handling for Vans - can only park on the ground floor"?
- It depends on the architecture and expected growth.
- If the rules are simple, keeping them within the domain entity (ParkingGarage) can be straightforward and easy to maintain.
- If the rules are complex, or you expect them to evolve, using a domain service to encapsulate business logic can provide more flexibility and scalability.

*I decided to Keep It Stupidly Simple (KISS), and keep it in the domain entity - ParkingGarage.*

#### Validation

##### ParkingGarage->park():
- Ensures the system identifies the right floor with sufficient space before attempting to park.
- Can apply business rules, like restricting certain vehicles to specific floors.

##### ParkingFloor->park():
- Serves as an additional safety check at the floor level.
- Confirms the floor's capacity before changing its internal state (like updating the current load).
- A defensive measure to prevent overloading or parking in unintended areas.

#### PHP-CS-Fixer

##### What is PHP-CS-Fixer?
- The PHP Coding Standards Fixer (PHP CS Fixer) tool fixes your code to follow standards.
- It can modernize your code (like converting the pow function to the ** operator on PHP 5.6) and (micro) optimize it.

##### Usage:
1. Install PHP-CS-Fixer:
```
composer require --dev friendsofphp/php-cs-fixer
```
2. Run PHP-CS-Fixer:
```
docker compose exec app ./vendor/bin/php-cs-fixer fix src
```

Or alternatively (inside the container):
```
./vendor/bin/php-cs-fixer fix src
```

#### Laravel Pint

##### What is Laravel Pint?
- Laravel Pint is an opinionated PHP code style fixer for minimalists.
- It is a code quality tool built on top of PHP CS Fixer for detecting coding style issues in PHP projects.

*I configured the pint.json file with basic rules to enforce PSR-12 compliance.*

##### Usage:
1. Install Laravel Pint:
```
composer require laravel/pint --dev
```
2. Run Laravel Pint:
```
docker compose exec app ./vendor/bin/pint
```

Or alternatively (inside the container):
```
./vendor/bin/pint
```

#### PHPStan

##### What is PHPStan?
- PHPStan finds bugs in your code without writing tests. It's open-source and free.
- PHPStan scans your whole codebase and looks for both obvious & tricky bugs.

##### Usage:
1. Install PHPStan:
```
composer require --dev phpstan/phpstan
```
2. Run PHPStan:
```
docker compose exec app ./vendor/bin/phpstan analyse src
```

Or alternatively (inside the container):
```
./vendor/bin/phpstan analyse src
```

3. Run PHPStan with level (e.g. level 6):
```
docker compose exec app ./vendor/bin/phpstan analyse -l 6 src
```

#### Additional Actions

##### Create Feature Test for Testing the Garage Feature
```
docker compose exec app ./vendor/bin/phpunit src/Tests/
```

##### Create Units Test only for Car
I created a Unit Test only for Car and not for other vehicles for the sake of time.

##### Implement a leave method
I implemented a leave method, tested but not used in the console.

##### Implement a console command (TODO)
I would like to implement an interactive command, using the Symfony Console Component.
```
composer require symfony/console
```
