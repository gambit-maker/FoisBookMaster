<?php

use Fuel\Core\DB;

class Model_Book extends Orm\Model

{
    protected static $_connection = 'development';
    protected static $_table_name = 'book';
    protected static $_primary_key = ['book_id'];
    protected static $_properties = [
        'book_id',
        'book_title',
        'author_name',
        'publisher',
        'publication_day',
        'insert_day',
        'update_day'
    ];

    public function getBookWithId($bookId)
    {
        $data = [];
        $query = DB::query('SELECT * FROM book WHERE book_id = :book_id');
        $query->param('book_id', $bookId);
        $result = $query->as_assoc()->execute($this::$_connection);
        foreach ($result as $item) {
            array_push($data, $item);
        }
        return $data;
    }

    public function insertBook($bookId, $bookTitle, $author, $publisher, $publicationDay, $insertDay)
    {
        $query = DB::insert('book');
        $query->set(
            [
                'book_id' => $bookId,
                'book_title' => $bookTitle,
                'author_name' => $author,
                'publisher' => $publisher,
                'publication_day' => $publicationDay,
                'insert_day' => $insertDay
            ]
        );
        $query->execute($this::$_connection);
    }

    public function updateBook($bookId, $bookTitle, $author, $publisher, $publicationDay, $updateDay)
    {
        $query = DB::update('book');
        $query->set(
            [
                'book_title' => $bookTitle,
                'author_name' => $author,
                'publisher' => $publisher,
                'publication_day' => $publicationDay,
                'update_day' => $updateDay
            ]
        );
        $query->where('book_id', '=', $bookId);
        $query->execute($this::$_connection);
    }

    public function deleteBook($bookId)
    {
        $query = DB::delete('book');
        $query->where('book_id', '=', $bookId);
        $query->execute($this::$_connection);
    }
}
