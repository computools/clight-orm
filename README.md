# CLight ORM

This library designed as usual ORM.

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
    
    use Computools\CLightORM\Mapper\Mapper;
    use Computools\CLightORM\Mapper\Relations\ManyToMany;
    use Computools\CLightORM\Mapper\Types\FloatType;
    use Computools\CLightORM\Mapper\Types\IdType;
    use Computools\CLightORM\Mapper\Types\StringType;
    use Computools\CLightORM\Test\Entity\Author;
    use Computools\CLightORM\Test\Entity\Theme;
    
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

 
### Allowed field types:
* **IdType** with optional parameter 'identifierField', that need to be specified if tables identifier column name is not 'id'
* **IntegerType** - integer, with optional parameter 'columnName', if map field is different than database field. 
* **StringType** - string or text, with optional parameter 'columnName', if map field is different than database field.
* **FloatType** - float or decimal, with optional parameter 'columnName', if map field is different than database field.
* **DateTimeType** - datetime with column name as first argument and  format as second.
* **CreatedAtType** - datetime, value will be defined automatically while entity creation. First parameter is columnName, second - datetime format, both are optional.
* **UpdatedAtType** - datetime, value will be defined automatically while entity is updating. First parameter is columnName, second - datetime format, both are optional.
* **BooleanType** - boolean, first parameter is column name if different than table field name, second parameter defines if field must be saved as int (default is true).
* **JsonType** - json, saves json to database and convert to array for entity. First parameter - table field name.

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


    use Computools\CLightORM\Entity\AbstractEntity;
    use Computools\CLightORM\Mapper\MapperInterface;
    use Computools\CLightORM\Test\Mapper\BookMapper;
    
    class Book extends AbstractEntity
    {
        public function getMapper(): MapperInterface
        {
            return new BookMapper();
        }

        private $id;
    
        private $name;
    
        private $authors = [];
    
        private $themes = [];
    
        private $price;
    
        public function getId(): ?int
        {
            return $this->id;
        }
    
        public function getName(): ?tring
        {
            return $this->name;
        }
    
        public function setName(?string $name): void
        {
            $this->name = $name;
        }
    
        public function getAuthors(): array
        {
            return $this->authors;
        }
    
        public function getThemes(): array
        {
            return $this->themes;
        }
        
        public function getPrice(): ?float 
        {
            return $this->price;
        }
        
        public function setPrice(float $price)
        {
            $this->price = $price;
        }
    }
    
Get mapper method is required.

Id field must have ability to take null in case of new entity, that have not been saved to database yet.

Another option is using public properties instead of getters and setters. This library supports that kind of entities.

    use Computools\CLightORM\Entity\AbstractEntity;
    use Computools\CLightORM\Mapper\MapperInterface;
    use Computools\CLightORM\Test\Mapper\BookMapper;
        
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
    
If you want to set null to one-to-one or many-to-one relation you can use *destroyToOneRelation(string $field)* method:
    
    $post->destroyToOneRelation('author');
    $postRepository->save();
    
This operation will save null to author_id field for post record. This can be used regardless method that was used to receive entity.
So both will work:

    $postRepository->find(1, ['author']);
    $post->destroyToOneRelation('author');
    
    $postRepository->find(1);
    $post->destroyToOneRelation('author');
    
If you want to add many-to-many relation for two entities, you can call *addRelation(EntityInterface $entity)* method and *removeRelation(EntityInterface $entity)* to remove.

    $post->addRelation($user);
    
    $post->removeRelation($user);

ORM has ability to execute mass assignment for entity. So you can use this construction
    
    $book = new Book();
    $book->fill([
        'name' => 'Book name',
        'price' => 10.99
    ]);
    $bookRepository->save($book);
    
instead of

    $book = new Book();
    $book->setName('Book name');
    $book->setPrice(10.99);
    $bookRepository->save($book);

That kind of action will be allowed if $allowedFields property was set for entity.
So it will looks like

    class Book
    {
        protected $allowedFields = [
            'name',
            'price'
        ];
        
        public $name;
        
        public $price;
    }

This is list of fields, that can be set with fill() method. If specified field is not presents in the list - it will be skipped.

## Repository
    use Computools\CLightORM\Repository\AbstractRepository;
    use Computools\CLightORM\Test\Entity\Book;
    
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

***Computools\CLightORM\CLightORM***

Here is the example:

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
    
    $clightORM = new CLightORM($pdo);
    
To get certain entity repository just call create method with class string as argument:

    $repository = $clightORM->createRepository(PostRepository::class);
    $repository->find(2);

### Repository methods
* find(int $id, array $with, $expiration = 0)
* findBy(array $citeria, ?Order $order, array $with, ?Pagination $pagination, $expiration = 0)
* findOneBy(array $criteria, ?Order $order = null, array $with, $expiration = 0)
* findFirst($with)
* findLast($with)
* save(EntityInterface $entity, array $with, $relationExistsCheck = false)
* remove(EntityInterface $entity)

*Computools\CLightORM\Tools\Order* object can be used to sort query result.
    
    $repository->findBy([
            'name' => 'test'
        ],
        new Order('name', 'DESC')
    )
    
*expiration* parameter can be used to store search result to cache. If isn't equals 0 than first call result will be stored to cache. Then method call will return data from cache, until expires.
For detailed description see *Cache* part.

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

You can use Computools\CLightORM\Tools\Pagination as third parameter for findBy to paginate result.

    $posts = $repository->findByUser($this->getUser(), ['theme'], (new Pagination())->setPagination(1, 20));
    
Or

    $posts = $repository->findByUser($this->getUser(), ['theme'], (new Pagination())->setLimitOffset(20, 0));
    
You can also use **orm** object inside the repository class to make some custom queries.


## Query

You can use built-in queries objects to do some custom logic.

CLightORM object can create any type of queries. This object is accessable inside any repository:

    class PostRepository extends AstractRepository
    {
        public function getEntityClass(): string
        {
            return Post::class;
        }
        
        public function findPostsCustom()
        {
            $query = $this->orm->createQuery();
            
            $query
                ->select('id, name')
                ->from($this->table)
                ->where('title', 'test')
                ->where('type', 'test')
                ->whereExpr('id < 5')
                ->whereExpr('id > 2')
                ->whereArray([
                    'title' => 'test',
                    'type' => 'test'
                ])
                ->groupBy('id')
                ->order('id', 'DESC')
                ->limit(10, 5)
                ->execute();
                
            return $query->getResult();
        }
    }

Method above demonstrates possible methods for query. It returns array as result. If you want to map result to some entity, you must not specify select fields, and call *mapToEntity* or *mapToEntities* method:

    $query
        ->from($this->table);
        ->whereExpr('id < 5')
        ->whereExpr('id > 2')
        ->execute();
    return $this->mapToEntities($query, ['author', 'editor']);
    
To use JOIN command, you can use Computools\CLightORM\Database\Query\Structure\Join.
Constructor arguments are:
* string $type (LEFT, RIGHT, INNER)
* string $table (table name)
* string $condition (on p.user_id = u.id for example)
* string $alias = null


    $query
        ->from('post', 'p')
        ->join(new Join('INNER', 'user', 'ON p.author_id = u.id', 'u'))
        ->where('p.id', 5)
        ->execute()
    
**Many-to-Many** relations saving can do checks for already existed relations. So if you want relations to not duplicate, you can provide third parameter as **true**:

    $post = $postRepository->find(1);
    $user = $userRepository->find(1);
    
    $post->addRelation($user);
    $postRepository->save($post, [], true);
    
This call will also call duplicates check and just will not add same relation. If you provide third argument as false, than check will not be executed, and will throw exception if database table has unique indexes for relations.

## Cache

Cache mechanism can be used to store some search results. To use it, you need to specify cache type while creating CLightORM instance.
There is two different options to store results - memcached and filesystem.

*Computools\CLightORM\Cache\Memcache* takes two parameters:

* host(string) - memcached server host, default is 'localhost',
* port(int) - memcached server port, default is '11211'

*Computools\CLightORM\Cache\Filecache* takes cache dir as parameter, default is 'cache'.

So, to use cache you need to write something like that:

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
        
    $clightORM = new CLightORM($pdo, new Filecache('response/cache'));
    
Or

    $clightORM = new CLightORM($pdo, new Memcache('localhost', 11211));
    
Than all your repos will be created with cache as private property.
You can provide *expiration* parameter for findBy etc.

    $repository->findBy(array $criteria, null, array $with = [], null, $expiration = 3600)
    
If expiration = 0, than cache will not be used. If not - data will be taken from cache if not expired yet.