<?php

use Fuel\Core\Controller;
use Fuel\Core\Debug;
use Fuel\Core\Input;
use Fuel\Core\Model;
use Fuel\Core\Response;
use Fuel\Core\View;

//set time zone vietnam                    
date_default_timezone_set('Asia/Ho_Chi_Minh');

class Controller_Book extends Controller
{
    public $message = [
        'MSG0001' => 'Hãy nhập Book ID',
        'MSG0002' => 'Hãy nhập Book ID bằng chữ số anh 1 byte',
        'MSG0003' => 'Sách đã được tìm thấy',
        'MSG0004' => 'Không thể tìm thấy Book ID: ',
        'MSG0005' => 'Đã phát sinh ngoại lệ bằng xử lý server',
        'MSG0006' => 'Hãy nhập Book title',
        'MSG0007' => 'Hãy nhập tên tác giả',
        'MSG0008' => 'Hãy nhập nhà xuất bản',
        'MSG0009' => 'Hãy nhập ngày xuất bản',
        'MSG0010' => 'Hãy nhập ngày xuất bản bằng chữ số 1 byte',
        'MSG0011' => 'Book ID: **** đã được đăng ký. Hãy nhập ID khác',
        'MSG0012' => 'Đã đăng ký sách',
        'MSG0013' => 'Đã update sách',
        'MSG0014' => 'Book ID: **** không được tìm thấy',
        'MSG0015' => 'Đã xóa Book ID: ****',
        'MSG0016' => 'Ngày xuất bản không hợp lệ',
    ];

    public function action_index()
    {
        $data = $this->defaultViewData();
        return Response::forge(View::forge('bookmaster/book', $data, false));
    }

    // xử lý khi ấn tra cứu
    public function action_traCuu()
    {
        $data = $this->defaultViewData();
        // lấy Input Id từ user        
        $data['bookId'] = Input::post('id');
        try {
            if (Model_Book::find($data['bookId'])) {
                // lấy data từ bookId     
                $data = $this->traCuu($data['bookId']);
                // gắn tin nhắn                
                $data['serverMessage'] = $this->message['MSG0003'];
            } else {
                $data['serverMessage'] = $this->message['MSG0004'] . $data['bookId'];
            }
        } catch (Exception $e) {
            // Debug::dump($e);
            $data['serverMessage'] = $this->message['MSG0005'];
        }
        return Response::forge(View::forge('bookmaster/book', $data, false));
    }

    //xử lý khi nhấn thêm
    public function action_them()
    {
        // lấy thông tin Input        
        $data = $this->getInput();
        try {
            // kiểm tra ngày có hợp lệ
            if (!$this->validDate($data['month'], $data['date'], $data['year'])) {
                $data['serverMessage'] = $this->message['MSG0016'];
                return Response::forge(View::forge('bookmaster/book', $data, false));
            }
            // xử lý BookId để thêm sách
            if (Model_Book::find($data['bookId'])) {
                $data['serverMessage'] = str_replace('****', $data['bookId'], $this->message['MSG0011']);
            } else {
                $data['publicationDay'] = $data['year'] . "-" . $data['month'] . "-" . $data['date'];
                $data['insertDay'] = date("Y-m-d H:i:s");
                // thêm sách
                $model = new Model_Book();
                $model->insertBook($data);

                $data['serverMessage'] = $this->message['MSG0012'];
            }
        } catch (Exception $e) {
            $data['serverMessage'] = $this->message['MSG0005'];
        }
        return Response::forge(View::forge('bookmaster/book', $data, false));
    }

    // xử lý nhấn update
    public function action_update()
    {
        // lấy thông tin Input
        $data = $this->getInput();
        try {
            // kiểm tra ngày có hợp lệ
            if (!$this->validDate($data['month'], $data['date'], $data['year'])) {
                $data['serverMessage'] = $this->message['MSG0016'];
                return Response::forge(View::forge('bookmaster/book', $data, false));
            }
            // xử lý BookId để Update                
            if (Model_Book::find($data['bookId'])) {
                $data['publicationDay'] = $data['year'] . "-" . $data['month'] . "-" . $data['date'];
                $data['updateDay'] = date("Y-m-d H:i:s");
                //update Book
                $model = new Model_Book();
                $model->updateBook($data);

                $data['serverMessage'] = $this->message['MSG0013'];
            } else {
                $data['serverMessage'] = str_replace('****', $data['bookId'], $this->message['MSG0014']);
            }
        } catch (Exception $e) {
            // Debug::dump($e);
            $data['serverMessage'] = $this->message['MSG0005'];
        }
        return Response::forge(View::forge('bookmaster/book', $data, false));
    }

    // xử lý khi ấn delete
    public function action_delete()
    {
        $data = $this->defaultViewData();
        // lấy Input Id từ user
        $data['bookId'] = Input::post('id');
        try {
            // kiểm tra BookId tồn tại
            if (Model_Book::find($data['bookId'])) {
                // xóa sách                    
                $model = new Model_Book();
                $model->deleteBook($data['bookId']);
                $data['serverMessage'] = str_replace('****', $data['bookId'], $this->message['MSG0015']);
                $data['bookId'] = '';
            } else {
                $data['serverMessage'] = str_replace('****', $data['bookId'], $this->message['MSG0014']);
            }
        } catch (Exception $e) {
            $data['serverMessage'] = $this->message['MSG0005'];
        }
        return Response::forge(View::forge('bookmaster/book', $data, false));
    }


    // tra cứu sách và trả về dữ liệu của data view
    public function traCuu($bookId)
    {
        $dataArr = [];
        $model = new Model_Book();
        $book = $model->getBookWithId($bookId);
        if (count($book) > 0) {
            $book = $book[0];
            $dataArr['bookId'] = $book['book_id'];
            $dataArr['bookTitle'] = $book['book_title'];
            $dataArr['author'] = $book['author_name'];
            $dataArr['publisher'] = $book['publisher'];
            $publicationDay = $book['publication_day'];
            $publicationDay = explode('-', $publicationDay);
            $dataArr['year'] = $publicationDay[0];
            $dataArr['month'] = $publicationDay[1];
            $dataArr['date'] = $publicationDay[2];
            return $dataArr;
        }
        return $dataArr;
    }

    // khởi tạo giá trị ban đầu để hiển thị cho các trường rỗng
    public function defaultViewData()
    {
        $data = [];
        $data['bookId'] = '';
        $data['bookTitle'] = '';
        $data['author'] = '';
        $data['publisher'] = '';
        $data['year'] = '';
        $data['month'] = '';
        $data['date'] = '';
        $data['insertDay'] = '';
        $data['updateDay'] = '';
        return $data;
    }

    public function validDate($month, $day, $year)
    {
        if (is_numeric($month) && is_numeric($day) && is_numeric($year) && checkdate($month, $day, $year)) {
            return true;
        }
        return false;
    }

    // get input from user
    public function getInput()
    {
        $dataArr = [];
        $dataArr['bookId'] = Input::post('id');
        $dataArr['bookTitle'] = Input::post('title');
        $dataArr['author'] = Input::post('author');
        $dataArr['publisher'] = Input::post('publisher');
        $dataArr['year'] = Input::post('year');
        $dataArr['month'] = Input::post('month');
        $dataArr['date'] = Input::post('date');

        return $dataArr;
    }
}
