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
	PostRepository,
	UserRepository
};
use Computools\CLightORM\Tools\Condition;
use Computools\CLightORM\Tools\Order;
use Computools\CLightORM\Tools\Pagination;
use Computools\CLightORM\Tools\RelatedData;
use Computools\CLightORM\Tools\RelatedDataSet;

class DatabaseFindTest extends BaseTest
{
    public function testHaving()
    {
        $queryBuilder = $this->cligtORM->createQuery();
        $queryBuilder->select('COUNT(*), category_id');
        $queryBuilder->from('categorization');
        $queryBuilder->where('category_id', 2);
        $queryBuilder->groupBy('category_id');
        $queryBuilder->having('category_id > :id');
        $queryBuilder->execute(['id' => 1]);
        $result = $queryBuilder->getResult();

        $this->assertInternalType('array', $result);
        $this->assertGreaterThan(0, count($result));
    }

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

	public function testRelatedDataSet()
    {
        $relations = [
            'author' => [
                'postsAsAuthor' => [
                    'editor'
                ]
            ],
            'editor'
        ];

        $relatedDataSet = new RelatedDataSet($relations);

        $this->assertInternalType('array', $relatedDataSet->getRelatedData());

        /**
         * @var RelatedData $firstRelatedData
         */
        $firstRelatedData = $relatedDataSet->getRelatedData()[0];
        /**
         * @var RelatedData $secondRelatedData
         */
        $secondRelatedData = $relatedDataSet->getRelatedData()[1];


        $this->assertInstanceOf(RelatedData::class, $firstRelatedData);
        $this->assertInstanceOf(RelatedData::class, $secondRelatedData);
        $this->assertEquals('editor', $secondRelatedData->getRelation());
        $this->assertNull($secondRelatedData->getRelatedDataSet());
        $this->assertEquals('author', $firstRelatedData->getRelation());
        $this->assertInstanceOf(RelatedDataSet::class, $firstRelatedData->getRelatedDataSet());
        $this->assertInternalType('array', $firstRelatedData->getRelatedDataSet()->getRelatedData());

        $postsAsAuthorRelatedData = $firstRelatedData->getRelatedDataSet()->getRelatedData()[0];
        $this->assertInstanceOf(RelatedData::class, $postsAsAuthorRelatedData);
        $this->assertEquals('postsAsAuthor', $postsAsAuthorRelatedData->getRelation());
        $this->assertInstanceOf(RelatedDataSet::class, $postsAsAuthorRelatedData->getRelatedDataSet());
        $this->assertInternalType('array', $postsAsAuthorRelatedData->getRelatedDataSet()->getRelatedData());

        $editorRelatedData = $postsAsAuthorRelatedData->getRelatedDataSet()->getRelatedData()[0];
        $this->assertInstanceOf(RelatedData::class, $editorRelatedData);
        $this->assertEquals('editor', $editorRelatedData->getRelation());
        $this->assertNull($editorRelatedData->getRelatedDataSet());
    }

    public function testManyToOneRelationsWithRelatedDataSet()
    {
        $postRepository = $this->cligtORM->createRepository(PostRepository::class);

        $posts = $postRepository->findBy(
            [],
            null,
            (new RelatedDataSet())
                ->addRelatedData(
                    (new RelatedData('author'))->setRelatedDataSet(
                        (new RelatedDataSet())->addRelatedData(
                            (new RelatedData('postsAsAuthor'))
                                ->setRelatedDataSet((new RelatedDataSet())->addRelatedData(new RelatedData('editor')))
                                ->setPagination((new Pagination())->setLimitOffset(10))
                                ->setOrder(new Order('id'))
                                ->addCondition((new Condition('id', '>', 20)))
                        )
                    )
                )
                ->addRelatedData(new RelatedData('editor')),
            (new Pagination())->setLimitOffset(2, 0)
        );

        $this->assertInternalType('array', $posts);
        /**
         * @var Post $post
         */
        $post = $posts[0];
        $this->assertInstanceOf(Post::class, $post);
        $this->assertInstanceOf(User::class, $post->getAuthor());
        $this->assertInstanceOf(User::class, $post->getEditor());

        $this->assertInternalType('array', $post->getAuthor()->getPostsAsAuthor());
        $this->assertCount(10, $post->getAuthor()->getPostsAsAuthor());
        $this->assertGreaterThan($post->getAuthor()->getPostsAsAuthor()[0]->getId(), $post->getAuthor()->getPostsAsAuthor()[9]->getId());
        $this->assertEquals(21, $post->getAuthor()->getPostsAsAuthor()[0]->getId());
    }

//    public function testManyToManyWithRelationDataSet()
//    {
//        $postRepository = $this->cligtORM->createRepository(PostRepository::class);
//        $posts = $postRepository->findBy(
//            [],
//            new Order('id'),
//            (new RelatedDataSet())->addRelatedData(
//                (new RelatedData('categories'))
//                    ->setOrder(new Order('id', Order::SORT_DESC))
//                    ->addCondition(new Condition('id', '>', 150))
//            ),
//            (new Pagination())->setLimitOffset(2, 0)
//        );
//
//        $this->assertInternalType('array', $posts);
//
//        /**
//         * @var Post $post1
//         */
//        $post1 = $posts[0];
//        $this->assertInstanceOf(Post::class, $post1);
//        $this->assertInternalType('array', $post1->getCategories());
//
//        /**
//         * @var Post $post2
//         */
//        $post2 = $posts[1];
//        $this->assertInstanceOf(Post::class, $post2);
//        $this->assertInternalType('array', $post2->getCategories());
//
//        /**
//         * @var Category $category1
//         * @var Category $category2
//         */
//        $category1 = $post1->getCategories()[0];
//        $category2 = $post1->getCategories()[1];
//        $category3 = $post2->getCategories()[0];
//        $category4 = $post2->getCategories()[1];
//
//
//        $this->assertInstanceOf(Category::class, $category1);
//        $this->assertInstanceOf(Category::class, $category2);
//        $this->assertInstanceOf(Category::class, $category3);
//        $this->assertInstanceOf(Category::class, $category4);
//    }

	public function testManyToOneRelations()
	{
		$postRepository = $this->cligtORM->createRepository(PostRepository::class);
		$posts = $postRepository->findBy([], null, [
		    'author',
            'editor'
        ], (new Pagination())->setLimitOffset(2, 0));

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
			'postsAsAuthor' => [
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

	public function testOptionalEntityFields()
    {
        /**
         * @var PostRepository $postRepository
         */
        $postRepository = $this->cligtORM->createRepository(PostRepository::class);

        $postsWithOptionalField = $postRepository->getPostsWithCategoriesCount();
        $postsWithoutOptionalField = $postRepository->findBy([]);

        $this->assertNotEquals(0, count($postsWithOptionalField));
        $this->assertNotEquals(0, count($postsWithoutOptionalField));
        $this->assertInstanceOf(Post::class, $postsWithOptionalField[0]);
        $this->assertInstanceOf(Post::class, $postsWithoutOptionalField[0]);
        $this->assertNotNull($postsWithOptionalField[0]->getCategoriesCount());
        $this->assertNull($postsWithoutOptionalField[0]->getCategoriesCount());
    }
}
