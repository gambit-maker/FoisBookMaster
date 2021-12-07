<?php

use Fuel\Core\Controller;
use Fuel\Core\Response;

class Controller_Book extends Controller
{
    public function action_index()
    {
        return Response::forge(View::forge('bookmaster/book'));
    }
}
