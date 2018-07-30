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
	UserRepository
};

class DatabaseFindTest extends BaseTest
{
//	public function testFind()
//	{
//		$user = $this->cligtORM->create(UserRepository::class)->find(1);
//
//		$this->assertInstanceOf(User::class, $user);
//		$this->assertInternalType('int', $user->getId());
//	}
//
//	public function testFindFirst()
//	{
//		$category = $this->cligtORM->create(CategoryRepository::class)->findFirst();
//
//		$this->assertInstanceOf(Category::class, $category);
//		$this->assertInternalType('int', $category->getId());
//	}
//
//	public function testFindLast()
//	{
//		$category = $this->cligtORM->create(CategoryRepository::class)->findLast();
//
//		$this->assertInstanceOf(Category::class, $category);
//		$this->assertInternalType('int', $category->getId());
//	}
//
//	public function testFindBy()
//	{
//		$postRepository = $this->cligtORM->create(PostRepository::class);
//		$posts = $postRepository->findBy([]);
//
//		$this->assertInternalType('array', $posts);
//		$this->assertInstanceOf(Post::class, $posts[0]);
//		$this->assertInternalType('boolean', $posts[0]->getIsPublished());
//	}
//
//	public function testCustomRepositoryFind()
//	{
//		$user = $this->cligtORM->create(UserRepository::class)->findFirst();
//
//		$postRepository = $this->cligtORM->create(PostRepository::class);
//		$posts = $postRepository->findByAuthor($user, ['author', 'editor']);
//
//		$this->assertInternalType('array', $posts);
//
//		/**
//		 * @var Post $post
//		 */
//		$post = $posts[0];
//		$this->assertInstanceOf(Post::class, $post);
//		$this->assertInstanceOf(User::class, $post->getAuthor());
//		$this->assertInstanceOf(User::class, $post->getEditor());
//		$this->assertEquals($post->getAuthor()->getId(), $user->getId());
//	}
//
//	public function testNativeQueryFind()
//	{
//		$user = $this->cligtORM->create(UserRepository::class)->findFirst();
//
//		$postRepository = $this->cligtORM->create(PostRepository::class);
//		$posts = $postRepository->findByAuthorNative($user, ['author', 'editor']);
//
//		$this->assertInternalType('array', $posts);
//
//		/**
//		 * @var Post $post
//		 */
//		$post = $posts[0];
//		$this->assertInstanceOf(Post::class, $post);
//	}
//
//	public function testMultipleManyToManyRelations()
//	{
//		$bookRepository = $this->cligtORM->create(BookRepository::class);
//
//		/**
//		 * @var Book $book
//		 */
//		$book = $bookRepository->findLast(['themes', 'authors']);
//
//		$this->assertInstanceOf(Book::class, $book);
//		$this->assertInternalType('array', $book->themes);
//		$this->assertInstanceOf(Theme::class, $book->themes[0]);
//		$this->assertInternalType('array', $book->authors);
//		$this->assertInstanceOf(Author::class, $book->authors[0]);
//	}
//
	public function testOneToManyRelations()
	{
		$userRepository = $this->cligtORM->create(UserRepository::class);

		//$user = $userRepository->findFirst(['posts_as_author', 'posts_as_editor']);

		$users = $userRepository->testFind();

		$b = 1;

		$this->assertInstanceOf(User::class, $user);

		$this->assertInternalType('array', $user->getPostsAsAuthor());
		$this->assertInternalType('array', $user->getPostsAsEditor());

		$this->assertInstanceOf(Post::class, $user->getPostsAsEditor()[0]);
		$this->assertInstanceOf(Post::class, $user->getPostsAsAuthor()[0]);
	}
//
//	public function testNestedRelationFind()
//	{
//		$authorRepository = $this->cligtORM->create(AuthorRepository::class);
//
//		/**
//		 * @var Author $author
//		 */
//		$author = $authorRepository->findFirst([
//			'books' => [
//				'themes'
//			]
//		]);
//
//		$this->assertInstanceOf(Author::class, $author);
//		$this->assertInternalType('array', $author->getBooks());
//		$this->assertNotEquals(0, count($author->getBooks()));
//		$this->assertInstanceOf(Book::class, $author->getBooks()[0]);
//		$this->assertInternalType('array', $author->getBooks()[0]->themes);
//		$this->assertNotEquals(0, count($author->getBooks()[0]->themes));
//		$this->assertInstanceOf(Theme::class, $author->getBooks()[0]->themes[0]);
//
//		$userRepository = $this->cligtORM->create(UserRepository::class);
//
//		/**
//		 * @var User $user
//		 */
//		$user = $userRepository->findFirst([
//			'posts_as_author' => [
//				'categories' => [
//					'posts'
//				]
//			]
//		]);
//		$this->assertInstanceOf(User::class, $user);
//		$this->assertInternalType('array', $user->getPostsAsAuthor());
//		$this->assertNotEquals(0, count($user->getPostsAsAuthor()));
//		$this->assertInstanceOf(Post::class, $user->getPostsAsAuthor()[0]);
//		$this->assertInternalType('array', $user->getPostsAsAuthor()[0]->getCategories());
//		$this->assertNotEquals(0, count($user->getPostsAsAuthor()[0]->getCategories()));
//		$this->assertInstanceOf(Category::class, $user->getPostsAsAuthor()[0]->getCategories()[0]);
//		$this->assertInternalType('array', $user->getPostsAsAuthor()[0]->getCategories()[0]->getPosts());
//		$this->assertNotEquals(0, count($user->getPostsAsAuthor()[0]->getCategories()[0]->getPosts()));
//		$this->assertInstanceOf(Post::class, $user->getPostsAsAuthor()[0]->getCategories()[0]->getPosts()[0]);
//
//		$postRepository = $this->cligtORM->create(PostRepository::class);
//
//		/**
//		 * @var Post $post
//		 */
//		$post = $postRepository->findFirst([
//			'author' => [
//				'profile'
//			]
//		]);
//
//		$this->assertInstanceOf(Post::class, $post);
//		$this->assertInstanceOf(User::class, $post->getAuthor());
//		$this->assertInstanceOf(UserProfile::class, $post->getAuthor()->getProfile());
//	}
//
//	public function testCache()
//	{
//		$userRepository = $this->cligtORM->create(UserRepository::class);
//		$user = $userRepository->find(1, ['posts_as_author', 'posts_as_editor'], 60);
//
//		$this->assertInstanceOf(User::class, $user);
//		$this->assertInternalType('array', $user->getPostsAsAuthor());
//		$this->assertInternalType('array', $user->getPostsAsEditor());
//
//		$this->assertInstanceOf(Post::class, $user->getPostsAsEditor()[0]);
//		$this->assertInstanceOf(Post::class, $user->getPostsAsAuthor()[0]);
//	}
//
//	public function testCacheRetrieve()
//	{
//		$userRepository = $this->cligtORM->create(UserRepository::class);
//		$user = $userRepository->find(1, ['posts_as_author', 'posts_as_editor'], 60);
//
//		$this->assertInstanceOf(User::class, $user);
//		$this->assertInternalType('array', $user->getPostsAsAuthor());
//		$this->assertInternalType('array', $user->getPostsAsEditor());
//
//		$this->assertInstanceOf(Post::class, $user->getPostsAsEditor()[0]);
//		$this->assertInstanceOf(Post::class, $user->getPostsAsAuthor()[0]);
//	}
}