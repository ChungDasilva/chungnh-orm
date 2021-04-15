<?php declare(strict_types=1);
namespace ORM\Tests;

use PHPUnit\Framework\TestCase;
use ORM\Models\Blog;
use ORM\Models\Comment;

final class ORMTest extends TestCase
{
    use CustomAssertTrait;
    
    // function setUp() { echo "setUp";}

    // function tearDown() { echo "tearDown";}

    public function testAll(): void
    {
        $blogs = Blog::all();
        $this->assertArrayHasObjectOfType('ORM\Models\Blog', $blogs);
    }

    public function testFind(): void
    {
        $blog = Blog::find(1);
        $this->assertInstanceOf('ORM\Models\Blog', $blog);
    }

    public function testWhere(): void
    {
        $blog = Blog::Where('id', 2)->get();
        $this->assertInstanceOf('ORM\Models\Blog', $blog);

        $blogs = Blog::Where('id', '>', 8)->get();
        $this->assertArrayHasObjectOfType('ORM\Models\Blog', $blogs);
        
        $comment = Comment::Where([
            ['id', '=', 6075],
            ['target_table', '=', 'blogs'],
            ['target_id', '=', 100]
        ])->get();
        $this->assertInstanceOf('ORM\Models\Comment', $comment);
        $this->assertEquals(100, $comment->target_id);
        $this->assertEquals('blogs', $comment->target_table);
    }

    public function testHasMany(): void
    {
        $blog = Blog::find(100);
        $comments = $blog->comments();
        $this->assertArrayHasObjectOfType('ORM\Models\Comment', $comments);
    }

    public function testBelongsTo(): void
    {
        $comment = Comment::find(1);
        $blog = $comment->blog();
        $this->assertInstanceOf('ORM\Models\Blog', $blog);
    }
}
