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
        'insert_day'
    ];
}
