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
use Computools\CLightORM\Tools\Order;
use Computools\CLightORM\Tools\Pagination;

class DatabaseFindTest extends BaseTest
{
	public function testOneToManyRelations()
	{
		$userRepository = $this->cligtORM->createRepository(UserRepository::class);

		$users = $userRepository->testFind();

		$this->assertInternalType('array', $users);
		$this->assertInstanceOf(User::class, $users[0]);

		$user = $users[0];

		$this->assertInternalType('array', $user->getPostsAsAuthor());
		$this->assertInternalType('array', $user->getPostsAsEditor());

		$this->assertInstanceOf(Post::class, $user->getPostsAsEditor()[0]);
		$this->assertInstanceOf(Post::class, $user->getPostsAsAuthor()[0]);

	}

	public function testManyToOneRelations()
	{
		$postRepository = $this->cligtORM->createRepository(PostRepository::class);
		$posts = $postRepository->findBy([], null, ['author', 'editor'], (new Pagination())->setLimitOffset(2, 0));

		$this->assertInternalType('array', $posts);
		/**
		 * @var Post $post
		 */
		$post = $posts[0];
		$this->assertInstanceOf(Post::class, $post);
		$this->assertInstanceOf(User::class, $post->getAuthor());
		$this->assertInstanceOf(User::class, $post->getEditor());
	}

	public function testManyToManyRelations()
	{
		$postRepository = $this->cligtORM->createRepository(PostRepository::class);
		$posts = $postRepository->findBy([], new Order('id'), ['categories'], (new Pagination())->setLimitOffset(2, 0));

		$this->assertInternalType('array', $posts);

		/**
		 * @var Post $post1
		 */
		$post1 = $posts[0];
		$this->assertInstanceOf(Post::class, $post1);
		$this->assertInternalType('array', $post1->getCategories());

		/**
		 * @var Post $post2
		 */
		$post2 = $posts[1];
		$this->assertInstanceOf(Post::class, $post2);
		$this->assertInternalType('array', $post2->getCategories());

		/**
		 * @var Category $category1
		 * @var Category $category2
		 */
		$category1 = $post1->getCategories()[0];
		$category2 = $post1->getCategories()[1];
		$category3 = $post2->getCategories()[0];
		$category4 = $post2->getCategories()[1];


		$this->assertInstanceOf(Category::class, $category1);
		$this->assertInstanceOf(Category::class, $category2);
		$this->assertInstanceOf(Category::class, $category3);
		$this->assertInstanceOf(Category::class, $category4);
	}

	public function testOneToOneRelation()
	{
		$userRepository = $this->cligtORM->createRepository(UserRepository::class);

		$users = $userRepository->findBy([], new Order('id'), ['profile'], (new Pagination())->setLimitOffset(2, 0));

		$this->assertInternalType('array', $users);

		/**
		 * @var User $user1
		 * @var User $user2
		 */
		$user1 = $users[0];
		$user2 = $users[1];

		$this->assertInstanceOf(User::class, $user1);
		$this->assertInstanceOf(User::class, $user2);

		$this->assertInstanceOf(UserProfile::class, $user1->getProfile());
		$this->assertInstanceOf(UserProfile::class, $user2->getProfile());
	}

	public function testNestedEntitiesFind()
	{
		$authorRepository = $this->cligtORM->createRepository(AuthorRepository::class);

			/**
			 * @var Author $author
			 */
		$author = $authorRepository->findFirst([
			'books' => [
				'themes'
			]
		]);

		$this->assertInstanceOf(Author::class, $author);
		$this->assertInternalType('array', $author->getBooks());
		$this->assertNotEquals(0, count($author->getBooks()));
		$this->assertInstanceOf(Book::class, $author->getBooks()[0]);
		$this->assertInternalType('array', $author->getBooks()[0]->themes);
		$this->assertNotEquals(0, count($author->getBooks()[0]->themes));
		$this->assertInstanceOf(Theme::class, $author->getBooks()[0]->themes[0]);

		$userRepository = $this->cligtORM->createRepository(UserRepository::class);

			/**
			 * @var User $user
			 */
		$user = $userRepository->findFirst([
			'posts_as_author' => [
				'categories' => [
					'posts'
				]
			]
		]);
		$this->assertInstanceOf(User::class, $user);
		$this->assertInternalType('array', $user->getPostsAsAuthor());
		$this->assertNotEquals(0, count($user->getPostsAsAuthor()));
		$this->assertInstanceOf(Post::class, $user->getPostsAsAuthor()[0]);
		$this->assertInternalType('array', $user->getPostsAsAuthor()[0]->getCategories());
		$this->assertNotEquals(0, count($user->getPostsAsAuthor()[0]->getCategories()));
		$this->assertInstanceOf(Category::class, $user->getPostsAsAuthor()[0]->getCategories()[0]);
		$this->assertInternalType('array', $user->getPostsAsAuthor()[0]->getCategories()[0]->getPosts());
		$this->assertNotEquals(0, count($user->getPostsAsAuthor()[0]->getCategories()[0]->getPosts()));
		$this->assertInstanceOf(Post::class, $user->getPostsAsAuthor()[0]->getCategories()[0]->getPosts()[0]);

		$postRepository = $this->cligtORM->createRepository(PostRepository::class);

		/**
		 * @var Post $post
		 */
		$post = $postRepository->findFirst([
			'author' => [
				'profile'
			]
		]);

		$this->assertInstanceOf(Post::class, $post);
		$this->assertInstanceOf(User::class, $post->getAuthor());
		$this->assertInstanceOf(UserProfile::class, $post->getAuthor()->getProfile());
	}

	public function testFindLastNative()
	{
		/**
		 * @var PostRepository $postRepository
		 */
		$postRepository = $this->cligtORM->createRepository(PostRepository::class);

		/**
		 * @var Post $post
		 */
		$post = $postRepository->findLastNative();

		$this->assertInstanceOf(Post::class, $post);
		$this->assertInstanceOf(User::class, $post->getAuthor());
		$this->assertInstanceOf(User::class, $post->getEditor());
	}

	public function testFirstNativeExpression()
	{
		/**
		 * @var PostRepository $postRepository
		 */
		$postRepository = $this->cligtORM->createRepository(PostRepository::class);

		/**
		 * @var Post $post
		 */
		$post = $postRepository->findFirstNative();

		$this->assertInstanceOf(Post::class, $post);
		$this->assertInstanceOf(User::class, $post->getAuthor());
		$this->assertNull($post->getEditor());
	}

	public function testNativeQuery()
	{
		/**
		 * @var PostRepository $postRepository
		 */
		$postRepository = $this->cligtORM->createRepository(PostRepository::class);
		/**
		 * @var Post $post
		 */
		$post = $postRepository->findByNativeQuery();

		$this->assertInstanceOf(Post::class, $post);
		$this->assertInstanceOf(User::class, $post->getAuthor());
	}
}