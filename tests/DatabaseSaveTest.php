<?php

namespace Computools\CLightORM\Test;

use Computools\CLightORM\Test\Entity\Author;
use Computools\CLightORM\Test\Entity\Book;
use Computools\CLightORM\Test\Entity\Post;
use Computools\CLightORM\Test\Entity\Theme;
use Computools\CLightORM\Test\Entity\User;
use Computools\CLightORM\Test\Entity\UserProfile;
use Computools\CLightORM\Test\Repository\AuthorRepository;
use Computools\CLightORM\Test\Repository\BookRepository;
use Computools\CLightORM\Test\Repository\CategoryRepository;
use Computools\CLightORM\Test\Repository\PostRepository;
use Computools\CLightORM\Test\Repository\ThemeRepository;
use Computools\CLightORM\Test\Repository\UserProfileRepository;
use Computools\CLightORM\Test\Repository\UserRepository;

class DatabaseSaveTest extends BaseTest
{
	public function testOneToOne()
	{
		$userProfileRepository = $this->entityRepositoryFactory->create(UserProfileRepository::class);
		$user = $this->entityRepositoryFactory->create(UserRepository::class)->findFirst();

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

		$user = $this->entityRepositoryFactory->create(UserRepository::class)->save($user);
		$this->assertInstanceOf(User::class, $user);
		$this->assertEquals($user->getName(), $name);
		$this->assertInternalType('int', $user->getId());
	}

	public function testManyToOneSaveWithRelated()
	{
		$user = $this->entityRepositoryFactory->create(UserRepository::class)->findFirst();
		$postRepository = $this->entityRepositoryFactory->create(PostRepository::class);

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
		$user = $this->entityRepositoryFactory->create(UserRepository::class)->find(1);
		$postRepository = $this->entityRepositoryFactory->create(PostRepository::class);

		$post = new Post();
		$post->setAuthor($user);
		$post->setEditor($user);
		$post->setTitle('New post');
		$post->setDatePublished(new \DateTime());
		$post = $postRepository->save($post);

		$this->assertInstanceOf(Post::class, $post);
		$this->assertNull($post->getAuthor());
		$this->assertNull($post->getEditor());
	}

	public function testSaveManyToMany()
	{
		$category = $this->entityRepositoryFactory->create(CategoryRepository::class)->findFirst();
		$postRepository = $this->entityRepositoryFactory->create(PostRepository::class);
		$user = $this->entityRepositoryFactory->create(UserRepository::class)->findFirst();

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

	public function testUpdateManyToMany()
	{
		$category = $this->entityRepositoryFactory->create(CategoryRepository::class)->findFirst();
		$postRepository = $this->entityRepositoryFactory->create(PostRepository::class);

		$post = $postRepository->findLast();
		$post->addRelation($category);
		$post = $postRepository->save($post, ['categories'], false);

		$this->assertInstanceOf(Post::class, $post);
		$this->assertInternalType('array', $post->getCategories());
		$this->assertEquals(2, count($post->getCategories()));
	}

	public function testUpdateManyToManyWithUniqueCheck()
	{
		$category = $this->entityRepositoryFactory->create(CategoryRepository::class)->findFirst();
		$postRepository = $this->entityRepositoryFactory->create(PostRepository::class);

		$post = $postRepository->findLast();
		$post->addRelation($category);
		$post = $postRepository->save($post, ['categories'], true);

		$this->assertInstanceOf(Post::class, $post);
		$this->assertInternalType('array', $post->getCategories());
		$this->assertEquals(2, count($post->getCategories()));
	}

	public function testMultipleManyToManyConnections()
	{
		$author = $this->entityRepositoryFactory->create(AuthorRepository::class)->findFirst();
		$theme = $this->entityRepositoryFactory->create(ThemeRepository::class)->findFirst();

		$bookRepository = $this->entityRepositoryFactory->create(BookRepository::class);

		/**
		 * @var Book $book
		 */
		$book = $bookRepository->findLast();
		$book->setPrice(15.22);
		$book->addRelation($author);
		$book->addRelation($theme);
		$book = $bookRepository->save($book, ['themes', 'authors']);

		$this->assertInstanceOf(Book::class, $book);
		$this->assertInternalType('array', $book->getAuthors());
		$this->assertInstanceOf(Author::class, $book->getAuthors()[0]);
		$this->assertInternalType('array', $book->getThemes());
		$this->assertInstanceOf(Theme::class, $book->getThemes()[0]);
	}
}