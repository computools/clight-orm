<?php

namespace Computools\CLightORM\Test;

use Computools\CLightORM\Test\Entity\{
    Author,
    Book,
    Category,
    Post,
    Theme,
    User,
    UserProfile
};
use Computools\CLightORM\Test\Repository\{
    AuthorRepository,
    BookRepository,
    CategoryRepository,
    PostRepository,
    ThemeRepository,
    UserProfileRepository,
    UserRepository
};


class DatabaseSaveTest extends BaseTest
{
    public function testSave()
    {
        $user = new User();
        $user->setName('Test name');
        $userRepository = $this->cligtORM->createRepository(UserRepository::class);

        $userRepository->save($user);

        $this->assertInstanceOf(User::class, $user);
        $this->assertInternalType('integer', $user->getId());
    }

    public function testMultipleSave()
    {
        $theme = new Theme();
        $theme->setTitle('bunch saved theme');

        $theme1 = new Theme();
        $theme1->setTitle('bunch saved theme 1');

        $theme2 = new Theme();
        $theme2->setTitle('bunch saved theme 2');

        $userRepository = $this->cligtORM->createRepository(ThemeRepository::class);
        $themes = $userRepository->saveBunch([
            $theme, $theme1, $theme2
        ]);

        $this->assertEquals(count($themes), 3);

        $this->assertInstanceOf(Theme::class, $themes[0]);
        $this->assertNotNull($themes[0]->getId());

        $this->assertInstanceOf(Theme::class, $themes[1]);
        $this->assertNotNull($themes[1]->getId());

        $this->assertInstanceOf(Theme::class, $themes[2]);
        $this->assertNotNull($themes[2]->getId());
    }

    public function testJsonType()
    {
        $book = new Book();
        $book->price = 10;
        $book->name = 'Test';
        $book->data = [
            'test' => 'test',
            'new test' => 'new test'
        ];
        $book->dataBinary = [
            'binary test' => 'binary test',
            'new binary test' => 'new binary test'
        ];

        $bookRepository = $this->cligtORM->createRepository(BookRepository::class);
        $bookRepository->save($book);

        $this->assertInstanceOf(Book::class, $book);
        $this->assertInternalType('array', $book->data);
        $this->assertArrayHasKey('test', $book->data);
        $this->assertInternalType('array', $book->dataBinary);
        $this->assertArrayHasKey('binary test', $book->dataBinary);
    }

	public function testMassFill()
    {
        $title = 'some title';
        $price = 123;

        $book = new Book();
        $book->fill([
            'name' => $title,
            'price' => $price
        ]);
        $this->cligtORM->createRepository(BookRepository::class)->save($book);

        $this->assertInstanceOf(Book::class, $book);
        $this->assertEquals($title, $book->name);
        $this->assertEquals($price, $book->price);
        $this->assertNotNull($book->id);
    }

	public function testUpdate()
	{
		$userRepository = $this->cligtORM->createRepository(UserRepository::class);

		$testName = substr(md5(uniqid()), 0, 15);
		/**
		 * @var User $user
		 */
		$user = $userRepository->findLast();
		$user->setName($testName);
		$userRepository->save($user);

		$this->assertInstanceOf(User::class, $user);
		$this->assertEquals($testName, $user->getName());
		$this->assertInternalType('integer', $user->getId());
	}

	public function testOneToOne()
	{
		$userProfileRepository = $this->cligtORM->createRepository(UserProfileRepository::class);
		$user = $this->cligtORM->createRepository(UserRepository::class)->findFirst();

		$userProfile = new UserProfile();
		$userProfile->setUser($user);
		$userProfile->setFirstName('test');
		$userProfile->setLastName('test');

		$userProfile = $userProfileRepository->save($userProfile, ['user']);

		$this->assertInstanceOf(UserProfile::class, $userProfile);
		$this->assertInstanceOf(User::class, $userProfile->getUser());
	}

	public function testNewSaveSimple()
	{
		$user = new User();
		$name = 'New name';

		$user->setName($name);

		$user = $this->cligtORM->createRepository(UserRepository::class)->save($user);
		$this->assertInstanceOf(User::class, $user);
		$this->assertEquals($user->getName(), $name);
		$this->assertNotNull($user->getId());
	}

	public function testManyToOneSaveWithRelated()
	{
		$user = $this->cligtORM->createRepository(UserRepository::class)->findFirst();
		$postRepository = $this->cligtORM->createRepository(PostRepository::class);

		$post = new Post();
		$post->setAuthor($user);
		$post->setEditor($user);
		$post->setTitle('New post');
		$post->setDatePublished(new \DateTime());
		$post = $postRepository->save($post, ['author', 'editor']);

		$this->assertInstanceOf(Post::class, $post);
		$this->assertInstanceOf(User::class, $post->getAuthor());
		$this->assertInstanceOf(User::class, $post->getEditor());
	}

	public function testManyToOneSaveWithoutRelated()
	{
		$user = $this->cligtORM->createRepository(UserRepository::class)->find(1, []);
		$postRepository = $this->cligtORM->createRepository(PostRepository::class);

		$post = new Post();
		$post->setAuthor($user);
		$post->setEditor($user);
		$post->setTitle('New post');
		$post->setDatePublished(new \DateTime());
		$postRepository->save($post);

		$this->assertInstanceOf(Post::class, $post);
		$this->assertNull($post->getAuthor());
		$this->assertNull($post->getEditor());
	}

	public function testSaveManyToMany()
	{
		$category = $this->cligtORM->createRepository(CategoryRepository::class)->findFirst();
		$postRepository = $this->cligtORM->createRepository(PostRepository::class);
		$user = $this->cligtORM->createRepository(UserRepository::class)->findFirst();

		$post = new Post();
		$post->setDatePublished(new \DateTime());
		$post->setTitle('some title');
		$post->setEditor($user);
		$post->setAuthor($user);
		$post->setIsPublished(true);
		$post->addRelation($category);
		$post = $postRepository->save($post, ['categories']);

		$this->assertInstanceOf(Post::class, $post);
		$this->assertInternalType('array', $post->getCategories());
		$this->assertEquals(1, count($post->getCategories()));
	}

	public function testDeleteManyToMany()
	{
		$postRepository = $this->cligtORM->createRepository(PostRepository::class);

		/**
		 * @var Post $post
		 */
		$post = $postRepository->findLast(['categories']);
		foreach ($post->getCategories() as $category) {
			$post->removeRelation($category);
		}

		$postRepository->save($post, ['categories']);

		$this->assertInstanceOf(Post::class, $post);
		$this->assertInternalType('array', $post->getCategories());
		$this->assertEquals(0, count($post->getCategories()));
	}

	public function testUpdateManyToMany()
	{
		$category1 = $this->cligtORM->createRepository(CategoryRepository::class)->findFirst();
		$category2 = $this->cligtORM->createRepository(CategoryRepository::class)->findLast();

		$postRepository = $this->cligtORM->createRepository(PostRepository::class);

		$post = $postRepository->findLast();
		$post->addRelation($category1);
		$post->addRelation($category2);
		$post = $postRepository->save($post, ['categories'], false);

		$this->assertInstanceOf(Post::class, $post);
		$this->assertInternalType('array', $post->getCategories());
		$this->assertEquals(2, count($post->getCategories()));
		$category = $post->getCategories()[0];
		$this->assertInstanceOf(Category::class, $category);
	}

	public function testUpdateManyToManyWithUniqueCheck()
	{
	    $categoryRepository = $this->cligtORM->createRepository(CategoryRepository::class);
		$category = new Category();
		$category->setTitle('test new');
		$categoryRepository->save($category);

		$postRepository = $this->cligtORM->createRepository(PostRepository::class);

		/**
         * @var Post $post
         */
		$post = $postRepository->findFirst(['categories']);

		$existedCategory = $post->getCategories()[0];
		$existedCategoriesCount = count($post->getCategories());

		$post->addRelation($category);
        $post->addRelation($existedCategory);

		$postRepository->save($post, ['categories'], true);

		$this->assertEquals($existedCategoriesCount + 1, count($post->getCategories()));
	}

	public function testMultipleManyToManyConnections()
	{
		$author = $this->cligtORM->createRepository(AuthorRepository::class)->findFirst();
		$theme = $this->cligtORM->createRepository(ThemeRepository::class)->findFirst();

		$bookRepository = $this->cligtORM->createRepository(BookRepository::class);

		/**
		 * @var Book $book
		 */
		$book = $bookRepository->findLast();
		$book->price = 15.22;
		$book->addRelation($author);
		$book->addRelation($theme);
		$book = $bookRepository->save($book, ['themes', 'authors']);

		$this->assertInstanceOf(Book::class, $book);
		$this->assertInternalType('array', $book->authors);
		$this->assertInstanceOf(Author::class, $book->authors[0]);
		$this->assertInternalType('array', $book->themes);
		$this->assertInstanceOf(Theme::class, $book->themes[0]);
	}

	public function testDelete()
	{
		$postRepository = $this->cligtORM->createRepository(PostRepository::class);
		$post = $postRepository->findLast();

		$this->assertInstanceOf(Post::class, $post);
		$this->assertInternalType('int', $post->getIdValue());

		$id = $post->getIdValue();

		$postRepository->remove($post);

		$post = $postRepository->find($id);

		$this->assertNull($post);
	}

    public function testRelationSetNull()
    {
        /**
         * @var User $user
         */
        $user = $this->cligtORM->createRepository(UserRepository::class)->findLast();

        $postRepository = $this->cligtORM->createRepository(PostRepository::class);
        /**
         * @var Post $post
         */
        $post = $postRepository->findLast();
        $post->destroyToOneRelation('author');

        $postRepository->save($post, ['author']);

        $this->assertNull($post->getAuthor());

        $post->setAuthor($user);
        $postRepository->save($post);
    }

    public function testRelationSetNullWith()
    {
        /**
         * @var User $user
         */
        $user = $this->cligtORM->createRepository(UserRepository::class)->findLast();

        $postRepository = $this->cligtORM->createRepository(PostRepository::class);

        $post = new Post();
        $post->setAuthor($user);
        $post->setTitle('test title');
        $post->setDatePublished(new \DateTime());
        $postRepository->save($post);

        /**
         * @var Post $post
         */
        $post = $postRepository->findLast(['author']);
        $post->destroyToOneRelation('author');

        $postRepository->save($post, ['author']);

        $this->assertNull($post->getAuthor());

        $post->setAuthor($user);
        $postRepository->save($post);
    }
}