<?php


class Model_Book extends Orm\Model
{
    protected static $_connection = 'development';
    protected static $_table_name = 'book';
    protected static $_primary_key = ['book_id'];
}
