<?php

namespace Actuallymab\LaravelComment\Tests;

use Actuallymab\LaravelComment\Tests\Models\Product;
use Actuallymab\LaravelComment\Tests\Models\User;
use Faker\Generator;
use Faker\Provider\Lorem;

/** actuallymab | 12.06.2016 - 13:52 */
class CommentTest extends TestCase
{
    private $faker;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->faker = new Generator();
        $this->faker->addProvider(Lorem::class);
    }

    /** @test */
    public function it_belongs_to_a_can_comment_object()
    {
        $user = $this->createUser();
        $product = $this->createProduct();

        $user->comment($product, $this->faker->sentence);
        $this->assertEquals(1, $product->comments()->count());

        $user->comment($product, $this->faker->sentence);
        $this->assertEquals(2, $product->comments()->count());
    }

    /** @test */
    public function it_must_be_unapproved_as_default_if_must_be_approved_property_is_true()
    {
        $user = $this->createUser();
        $product = $this->createProduct(false, true);

        $user->comment($product, $this->faker->sentence);
        $this->assertFalse($user->comments[0]->approved);
    }

    /** @test */
    public function it_must_be_approved_as_default_if_commented_by_an_admin()
    {
        $user = $this->createUser(true);
        $product = $this->createProduct(false, true);

        $user->comment($product, $this->faker->sentence);
        $this->assertTrue($user->comments[0]->approved);
    }

    /** @test */
    public function it_can_be_approved()
    {
        $user = $this->createUser();
        $product = $this->createProduct(false, true);

        $user->comment($product, $this->faker->sentence);
        $user->comments[0]->approve();
        $this->assertTrue($product->comments[0]->approved);
    }

    /** @test */
    public function it_can_be_rated()
    {
        $user = $this->createUser();
        $product = $this->createProduct(true);

        $user->comment($product, $this->faker->sentence, 3);
        $this->assertEquals(3, $user->comments[0]->rate);
    }

    /** @test */
    public function it_can_calculate_average_rating_of_all_comments()
    {
        $user = $this->createUser();
        $product = $this->createProduct(true);

        $user->comment($product, $this->faker->sentence, 3);
        $user->comment($product, $this->faker->sentence, 5);

        $this->assertEquals(4, $product->averageRate());
    }

    /** @test */
    public function it_must_calculate_average_rating_only_from_approved_comments_if_approve_option_enabled()
    {
        $user = $this->createUser();
        $product = $this->createProduct(true, true);

        $user->comment($product, $this->faker->sentence, 3);
        $user->comment($product, $this->faker->sentence, 5);

        $this->assertEquals(0, $product->averageRate());

        $product->comments[0]->approve();
        $this->assertEquals(2, $product->comments()->count());
        $this->assertEquals(3, $product->averageRate());

        $product->comments[1]->approve();
        $this->assertEquals(2, $product->comments()->count());
        $this->assertEquals(4, $product->averageRate());

        $user = $this->createUser(true);
        $user->comment($product, $this->faker->sentence, 1);
        $this->assertEquals(3, $product->comments()->count());
        $this->assertEquals(3, $product->averageRate());
    }

    /** @test */
    public function it_can_calculate_total_comments_count()
    {
        $user = $this->createUser();
        $product = $this->createProduct();

        $user->comment($product, $this->faker->sentence);
        $user->comment($product, $this->faker->sentence);

        $this->assertEquals(2, $product->totalCommentCount());
    }

    /** @test */
    public function it_must_calculate_only_approved_comments_as_total_comment_if_approve_option_enabled()
    {
        $user = $this->createUser();
        $product = $this->createProduct(true, true);

        $user->comment($product, $this->faker->sentence);
        $user->comment($product, $this->faker->sentence);

        $this->assertEquals(0, $product->totalCommentCount());
        $product->comments[0]->approve();

        $this->assertEquals(1, $product->totalCommentCount());

        $product->comments[1]->approve();
        $this->assertEquals(2, $product->totalCommentCount());
    }

    /** @test */
    public function comments_also_can_be_commentable()
    {
        $user = $this->createUser();
        $product = $this->createProduct();

        $user->comment($product, $this->faker->sentence);
        $comment = $product->comments->first();
        $user->comment($comment, $this->faker->sentence);

        $this->assertEquals(1, $comment->totalCommentCount());

        $comment = $product->comments->first()->comments->first();
        $user->comment($comment, $this->faker->sentence);
        $this->assertEquals(1, $comment->totalCommentCount());
    }

    /**
     * @param bool $isAdmin
     * @return User
     */
    protected function createUser($isAdmin = false)
    {
        $user = User::create([
            'name' => $this->faker->word
        ]);

        $user->isAdmin = $isAdmin;
        return $user;
    }

    /**
     * @param bool $canBeRated
     * @param bool $mustBeApproved
     * @return Product
     */
    protected function createProduct($canBeRated = false, $mustBeApproved = false)
    {
        return Product::create([
            'name' => $this->faker->word
        ])->setCanBeRated($canBeRated)->setMustBeApproved($mustBeApproved);
    }
}
