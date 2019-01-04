<?php
declare(strict_types=1);

namespace Actuallymab\LaravelComment\Tests;

use Actuallymab\LaravelComment\Models\Comment;
use Actuallymab\LaravelComment\Tests\Models\Product;
use Actuallymab\LaravelComment\Tests\Models\User;
use Illuminate\Foundation\Testing\WithFaker;

class CommentTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_belongs_to_a_can_comment_object()
    {
        $user = $this->createUser();
        $product = $this->createProduct();

        $user->comment($product, $this->faker->sentence);
        $this->assertEquals(1, $product->comments()->count());

        $user->comment($product, $this->faker->sentence);
        $this->assertEquals(2, $product->comments()->count());

        $this->assertTrue($product->comments()->first()->commentable->is($product));
        $this->assertTrue($product->comments()->first()->commented->is($user));
    }

    /** @test */
    public function comment_object_is_returned_after_comment_operation()
    {
        $user = $this->createUser();
        $product = $this->createProduct();

        $this->assertInstanceOf(Comment::class, $user->comment($product, $this->faker->sentence));
    }

    /** @test */
    public function comment_can_be_checked()
    {
        $user = $this->createUser();
        $product = $this->createProduct();

        $user->comment($product, $this->faker->sentence);

        $this->assertTrue($user->hasCommentsOn($product));
    }

    /** @test */
    public function it_must_be_unapproved_as_default_if_must_be_approved_method_returns_true()
    {
        $user = $this->createUser();
        $product = $this->createProductWithCommentsMustBeApproved();

        $user->comment($product, $this->faker->sentence);
        $this->assertFalse($user->comments[0]->approved);
    }

    /** @test */
    public function it_must_be_approved_as_default_if_a_product_was_commented_by_an_admin()
    {
        $admin = $this->createAdmin();
        $product = $this->createProductWithCommentsMustBeApproved();

        $admin->comment($product, $this->faker->sentence);
        $this->assertTrue($admin->comments[0]->approved);
    }

    /** @test */
    public function it_can_be_approved()
    {
        $user = $this->createUser();
        $product = $this->createProductWithCommentsMustBeApproved();

        $user->comment($product, $this->faker->sentence);
        $this->assertFalse($product->comments[0]->approved);
        $user->comments[0]->approve();
        $this->assertTrue($product->comments[0]->fresh()->approved);
    }

    /** @test */
    public function it_can_be_rated()
    {
        $user = $this->createUser();
        $product = $this->createRateableProduct();

        $user->comment($product, $this->faker->sentence, 3);
        $this->assertEquals(3, $user->comments[0]->rate);
    }

    /** @test */
    public function rate_does_not_make_sense_when_its_not_rateable_product()
    {
        $user = $this->createUser();
        $product = $this->createProduct();

        $user->comment($product, $this->faker->sentence, 3);
        $this->assertEquals(0, $product->averageRate());
    }

    /** @test */
    public function it_can_calculate_average_rating_of_all_comments()
    {
        $user = $this->createUser();
        $product = $this->createRateableProduct();

        $this->assertEquals(0, $product->averageRate());

        $user->comment($product, $this->faker->sentence, 3);
        $this->assertEquals(3, $product->averageRate());

        $user->comment($product, $this->faker->sentence, 5);
        $this->assertEquals(4, $product->averageRate());

        $user->comment($product, $this->faker->sentence, 3);
        $this->assertEquals(3.67, $product->averageRate());
    }

    /** @test */
    public function it_must_calculate_average_rating_only_from_approved_comments_if_approve_option_enabled()
    {
        $admin = $this->createUser();
        $product = $this->createRateableProductWithCommentsMustBeApproved();

        $admin->comment($product, $this->faker->sentence, 3);
        $admin->comment($product, $this->faker->sentence, 5);

        $this->assertEquals(0, $product->averageRate());

        $product->comments[0]->approve();
        $this->assertEquals(2, $product->comments()->count());
        $this->assertEquals(3, $product->averageRate());

        $product->comments[1]->approve();
        $this->assertEquals(2, $product->comments()->count());
        $this->assertEquals(4, $product->averageRate());

        $admin = $this->createAdmin();
        $admin->comment($product, $this->faker->sentence, 1);
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

        $this->assertEquals(2, $product->totalCommentsCount());
    }

    /** @test */
    public function it_must_calculate_only_approved_comments_as_total_comment_if_approve_option_enabled()
    {
        $user = $this->createUser();
        $product = $this->createProductWithCommentsMustBeApproved();

        $user->comment($product, $this->faker->sentence);
        $user->comment($product, $this->faker->sentence);

        $this->assertEquals(0, $product->totalCommentsCount());
        $product->comments[0]->approve();

        $this->assertEquals(1, $product->totalCommentsCount());

        $product->comments[1]->approve();
        $this->assertEquals(2, $product->totalCommentsCount());

        $admin = $this->createAdmin();
        $admin->comment($product, $this->faker->sentence);
        $this->assertEquals(3, $product->totalCommentsCount());
    }

    /** @test */
    public function comments_also_can_be_commentable()
    {
        $user = $this->createUser();
        $product = $this->createProduct();

        $user->comment($product, $this->faker->sentence);
        $comment = $product->comments->first();
        $user->comment($comment, $this->faker->sentence);

        $this->assertEquals(1, $comment->comments()->count());

        $comment = $product->comments->first()->comments->first();
        $user->comment($comment, $this->faker->sentence);
        $this->assertEquals(1, $comment->comments()->count());
    }

    protected function createUser(bool $isAdmin = false): User
    {
        return User::create(['name' => $this->faker->name, 'is_admin' => $isAdmin]);
    }

    protected function createAdmin(): User
    {
        return $this->createUser(true);
    }

    protected function createProduct(bool $canBeRated = false, bool $mustBeApproved = false): Product
    {
        return Product::create([
            'name'             => $this->faker->word,
            'can_be_rated'     => $canBeRated,
            'must_be_approved' => $mustBeApproved
        ]);
    }

    protected function createRateableProduct(): Product
    {
        return $this->createProduct(true);
    }

    protected function createProductWithCommentsMustBeApproved(): Product
    {
        return $this->createProduct(false, true);
    }

    protected function createRateableProductWithCommentsMustBeApproved(): Product
    {
        return $this->createProduct(true, true);
    }
}
