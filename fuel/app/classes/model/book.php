<?php

use Fuel\Core\DB;
use Fuel\Core\Debug;

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
        $query = DB::query('SELECT * FROM book WHERE book_id = :book_id');
        $query->param('book_id', $bookId);
        $result = $query->execute($this::$_connection);
        // Debug::dump($result->as_array());
        return  $result->as_array();
    }

    public function insertBook($data)
    {
        $query = DB::insert('book');
        $query->set(
            [
                'book_id' => $data['bookId'],
                'book_title' => $data['bookTitle'],
                'author_name' => $data['author'],
                'publisher' => $data['publisher'],
                'publication_day' => $data['publicationDay'],
                'insert_day' => $data['insertDay']
            ]
        );
        $query->execute($this::$_connection);
    }

    public function updateBook($data)
    {
        $query = DB::update('book');
        $query->set(
            [
                'book_title' => $data['bookTitle'],
                'author_name' => $data['author'],
                'publisher' => $data['publisher'],
                'publication_day' => $data['publicationDay'],
                'update_day' => $data['updateDay']
            ]
        );
        $query->where('book_id', '=', $data['bookId']);
        $query->execute($this::$_connection);
    }

    public function deleteBook($bookId)
    {
        $query = DB::delete('book');
        $query->where('book_id', '=', $bookId);
        $query->execute($this::$_connection);
    }
}
