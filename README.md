# CLight ORM

This library designed as usual ORM and based on
[LessQL library](https://github.com/morris/lessql).

Purpose for ORM development was to create fast and convenient tool for working with database and relation mapping.
ORM allows you to link entities with One-To-Many, One-To-One, Many-To-One, Many-To-Many relation types.

# Installation
    composer require computools/clight-orm

# Structural elements:

* **Entity** - representation of database table. This objects must have getters and setters for properties and extends *AbstractEntity* from library.
 
* **Mapper** - this objects maps entity to array and back. Must implement *getMapper()* method with field rules.

* **Repository** - based on Repository Pattern, this objects must have Entity objects definition and be extended from *AbstractRepository*.
Abstract repository contains all common (like find, findBy, etc) methods for data search and saving.


# Examples
For examples you may look at *tests* directory.

Lets have a look:
## Mapper 
    
    use Computools\LessqlORM\Mapper\Mapper;
    use Computools\LessqlORM\Mapper\Relations\ManyToMany;
    use Computools\LessqlORM\Mapper\Types\FloatType;
    use Computools\LessqlORM\Mapper\Types\IdType;
    use Computools\LessqlORM\Mapper\Types\StringType;
    use Computools\LessqlORM\Test\Entity\Author;
    use Computools\LessqlORM\Test\Entity\Theme;
    
    class BookMapper extends Mapper
    {
        public function getTable(): string
        {
            return 'books';
        }
    
        public function getFields(): array
        {
            return [
                'id' => new IdType(),
                'name' => new StringType('title'),
                'price' => new FloatType(),
                'authors' => new ManyToMany(new Author(), 'authors_books', 'book_id', 'author_id'),
                'themes' => new ManyToMany(new Theme(), 'books_theme', 'book_id', 'theme_id')
            ];
        }
    }
    
*Mapper* inheritance involves implementation of *getTable()* method, that will define database table name and *getFields()* method, that returns array of rules.

Keys must be specified as table column names.

No matter if it's camel case or underscore used for database columns and field map keys, entity fields supposed to be named in camel case.
 
### Allowed field types:
* **IdType** with optional parameter 'identifierField', that need to be specified if tables identifier column name is not 'id'
* **IntegerType** - integer, with optional parameter 'columnName', if map field is different than database field. 
* **StringType** - string or text, with optional parameter 'columnName', if map field is different than database field.
* **FloatType** - float or decimal, with optional parameter 'columnName', if map field is different than database field.
* **DateTimeType** - datetime with column name as first argument and  format as second.
* **CreatedAtType** - datetime, value will be defined automatically while entity creation. First parameter is columnName, second - datetime format, both are optional.
* **UpdatedAtType** - datetime, value will be defined automatically while entity is updating. First parameter is columnName, second - datetime format, both are optional.
* **BooleanType** - boolean, first parameter is column name if different than table field name, second parameter defines if field must be saved as int (default is true).

### Allowed relation types:
* **OneToOne**  - first parameter is related entity instance, second is main table field name
* **ManyToOne** - first parameter is related entity instance, second is main table field name.
* **OneToMany** - first parameter is related entity instance, second is related table field.
* **ManyToMany** - with parameters:
    * entity - related entity instance
    * table - many-to-many relation table name
    * columnName - relation table column name that corresponds main table
    * referencedColumnName - relation table column name that corresponds referenced table
    
    
## Entity


    use Computools\LessqlORM\Entity\AbstractEntity;
    use Computools\LessqlORM\Mapper\MapperInterface;
    use Computools\LessqlORM\Test\Mapper\BookMapper;
    
    class Book extends AbstractEntity
    {
        public function getMapper(): MapperInterface
        {
            return new BookMapper();
        }

        private $id;
    
        private $name;
    
        private $authors;
    
        private $themes;
    
        private $price;
    
        public function getId(): ?int
        {
            return $this->id;
        }
    
        public function setId(?int $id): void
        {
            $this->id = $id;
        }
    
        public function getName(): ?string
        {
            return $this->name;
        }
    
        public function setName(?string $name): void
        {
            $this->name = $name;
        }
    
        public function getAuthors(): ?array
        {
            return $this->authors;
        }
    
        public function setAuthors(?array $authors)
        {
            $this->authors = $authors;
        }
    
    
        public function getThemes(): ?array
        {
            return $this->themes;
        }
    
        public function setThemes(?array $themes): void
        {
            $this->themes = $themes;
        }
        
        public function getPrice(): ?float 
        {
            return $this->price;
        }
        
        public function setPrice(?float $price)
        {
            $this->price = $price;
        }
    }
    
Get mapper method is required.

Also if you want to use type hints, make sure that you always will set this parameters. Otherwise allow them to take and return null.


Id field must have ability to take null in case of new entity, that have not been saved to database yet.

Another option is using public properties instead of getters and setters. This library supports that kind of entities.

    use Computools\LessqlORM\Entity\AbstractEntity;
    use Computools\LessqlORM\Mapper\MapperInterface;
    use Computools\LessqlORM\Test\Mapper\BookMapper;
        
    class Book extends AbstractEntity
    {
        public function getMapper(): MapperInterface
        {
            return new BookMapper();
        }

        public $id;
    
        public $name;
    
        public $authors;
    
        public $themes;
    
        public $price; 
    }


## Repository
    use Computools\LessqlORM\Repository\AbstractRepository;
    use Computools\LessqlORM\Test\Entity\Book;
    
    class BookRepository extends AbstractRepository
    {
        public function getEntityClass(): string
        {
            return Book::class;
        }
        
        public function findByUser(User $user): ?Book
        {
            return $this->findBy(['user_id' => $user->getId()]);
        }
    }
    
This is the way how repository must be implemented. You can write your own methods or use existed.

To call the repository, you can use

***Computools\LessqlORM\Repository\EntityRepositoryFactory***

Class constructor requires ***LessQL\Database*** object as argument. Here is the example:

    $pdo = new \PDO(
                sprintf(
                    '%s:host=%s;port=%s;dbname=%s',
                    'mysql',
                    '127.0.0.1',
                    '3306',
                    'test'
                ),
            'user',
            'password'
            );
    
    $database = new \LessQL\Database($pdo);
    $entityRepositoryFactory = new EntityRepositoryFactory($database);
    
To get certain entity repository just call factory's create method with class string as argument:

    $repository = $entityRepositoryFactory->create(PostRepository::class);
    $repository->find(2);

### Repository methods
* find(int $id, array $with)
* findBy(array $citeria, array $with, ?Pagination $pagination)
* findOneBy(array $criteria, array $with)
* findFirst($with)
* findLast($with)
* save(EntityInterface $entity, $with)
* remove(EntityInterface $entity)

*With* parameter provides you possibility to include related entities into result. You may also get related entities of related entity etc.
For example:

    $book = $bookRepository->findLast(['themes', 'authors']);
    
This will find last book with related themes and authors (you must specify entity field name, that corresponds to relation)
    
    $book = $bookRepository->findLast(
        [
            'themes' => [
                'posts'
            ],
            'authors'
        ]
    );
This will find last book with themes and authors. Besides, related posts will be found for all the themes.
For save method, you also may define $with parameter, to get related entities in result.

Nesting level is not limited, so you can use constructions like this:

     $book = $bookRepository->findLast(
            [
                'themes' => [
                    'posts' => [
                        'editors' => [
                            'userProfile'
                        ]
                    ]
                ],
                'authors'
            ]
        );

First argument for repository's 'save' method takes a link to object, so you may not use method result to overwrite object variable.
   
    $post = new Post(); 
    $post->setUser($user);
    $postRepository->save($post);
    return $post;

But, of course, you can use return value:

    $post = new Post();
    $post->setUser($user);
    return $postRepository->save($post);

If there is a collection given as repository result, that would be an array of entities.

You can use Computools\LessqlORM\Tools\Pagination as third parameter for findBy to paginate result.

    $posts = $repository->findByUser($this->getUser(), ['theme'], (new Pagination())->setPagination(1, 20));
    
Or

    $posts = $repository->findByUser($this->getUser(), ['theme'], (new Pagination())->setLimitOffset(20, 0));
    
You can also use ***database*** object inside the repository class to make some custom queries, based on ***LessQL***.
For example:

    public function findByUser(User $user): ?Post
    {
        return $this->findBy(['user_id' => $user->getId()]);
    }
    
This construction can be changed like this:

    public function findByUser(User $user, array $with = [], ?Pagination $pagination = null): array
    {
        $query = $this->database->table(
            $this->entity->getMapper()->getTable()
        )
        ->where('user_id', $user->getId())
        ->limit($pagination->getLimit(), $pagination->getOffset());
        
        return $this->mapToEntities($query->fetchAll(), $query, $with);
    }
    
Original query **must** be provided as second argument to build relations (if needed). Also this way of implementation supports ***$with*** relations search.
If you want to find one entity but not array, you can call:
    
    $this->mapToEntity($query->fetch(), $query, $with)
    
Third way is to implement native query:


    public function findByAuthorNative(User $user): array
	{
            $query = $this->database->prepare('
                SELECT * FROM post WHERE author_id = :authorId
            ');
            $query->execute([
                'authorId' => $user->getId()
            ]);
    
            $result = [];
            foreach ($query->fetchAll() as $row) {
                $result[] = $this->entity->getMapper()->arrayToEntity(new Post(), $row);
            }
            return $result;
	}
	
This way is not support relations mapping, so you need to make it by yourself.