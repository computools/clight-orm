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
use Computools\CLightORM\Tools\Pagination;

class DatabaseFindTest extends BaseTest
{
//	public function testOneToManyRelations()
//	{
//		$userRepository = $this->cligtORM->createRepository(UserRepository::class);
//
//		$users = $userRepository->testFind();
//
//		$this->assertInternalType('array', $users);
//		$this->assertInstanceOf(User::class, $users[0]);
//
//		$user = $users[0];
//
//		$this->assertInternalType('array', $user->getPostsAsAuthor());
//		$this->assertInternalType('array', $user->getPostsAsEditor());
//
//		$this->assertInstanceOf(Post::class, $user->getPostsAsEditor()[0]);
//		$this->assertInstanceOf(Post::class, $user->getPostsAsAuthor()[0]);
//	}
//
//	public function testManyToOneRelations()
//	{
//		$postRepository = $this->cligtORM->createRepository(PostRepository::class);
//		$posts = $postRepository->findBy([], null, ['author', 'editor'], (new Pagination())->setLimitOffset(2, 0));
//
//		$this->assertInternalType('array', $posts);
//		/**
//		 * @var Post $post
//		 */
//		$post = $posts[0];
//		$this->assertInstanceOf(Post::class, $post);
//		$this->assertInstanceOf(User::class, $post->getAuthor());
//		$this->assertInstanceOf(User::class, $post->getEditor());
//	}

	public function testManyToManyRelations()
	{
		$postRepository = $this->cligtORM->createRepository(PostRepository::class);
		$posts = $postRepository->findBy([], null, ['categories'], (new Pagination())->setLimitOffset(2, 0));

		$this->assertInternalType('array', $posts);

		$b = 1;
	}
}