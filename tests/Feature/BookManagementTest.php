<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_book_can_be_added_to_library()
    {
        $response = $this->post('/books', [
            'title' => 'Cool book title',
            'author' => 'Victor',
        ]);

        $book = Book::first();

        $this->assertCount(1, Book::all());
        $response->assertRedirect($book->path());
    }

    public function test_a_title_is_required() 
    {
        $response = $this->post('/books', [
            'title' => '',
            'author' => 'Victor',
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_an_author_is_required() 
    {
        $response = $this->post('/books', [
            'title' => 'A title',
            'author' => '',
        ]);

        $response->assertSessionHasErrors('author');
    }

    public function test_a_book_can_be_updated() 
    {        
        $this->post('/books', [
            'title' => 'A title',
            'author' => 'Victor',
        ]);

        $book = Book::first();

        $response = $this->patch($book->path(), [
            'title' => 'New Title',
            'author' => 'New Author',
        ]);

        $this->assertEquals('New Title', Book::first()->title);
        $this->assertEquals('New Author', Book::first()->author);
        $response->assertRedirect($book->fresh()->path());
    }

    public function test_a_book_can_be_deleted() {

        $this->post('/books', [
            'title' => 'A title',
            'author' => 'Victor',
        ]);

        $book = Book::first();

        $this->assertCount(1, Book::all());

        $response = $this->delete($book->path());

        $this->assertCount(0, Book::all());

        $response->assertRedirect('/books');
    }
}

