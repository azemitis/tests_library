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
        $this->withoutExceptionHandling();

        $response = $this->post('/books', [
            'title' => 'Cool book title',
            'author' => 'Victor',
        ]);

        $response->assertOk();
        $this->assertCount(1, Book::all());
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

    public function test_a_book_need_to_be_updated() 
    {
        $this->withoutExceptionHandling();
        
        $this->post('/books', [
            'title' => 'A title',
            'author' => 'Victor',
        ]);

        $book = Book::first();

        $response = $this->patch('/books/' . $book->id, [
            'title' => 'New Title',
            'author' => 'New Author',
        ]);

        $this->assertEquals('New Title', Book::first()->title);
        $this->assertEquals('New Author', Book::first()->author);
    }
}

