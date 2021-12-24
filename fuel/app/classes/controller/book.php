<?php

use Fuel\Core\Controller;
use Fuel\Core\Debug;
use Fuel\Core\Input;
use Fuel\Core\Model;
use Fuel\Core\Response;
use Fuel\Core\View;

class Controller_Book extends Controller
{
    public function action_checkButtonClicked()
    {
        //set time zone vietnam                    
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $message = [
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

        // xử lý thông tin khi nhấn tra cứu
        if (isset($_POST["tracuu_btn"])) {
            try {
                // lấy Input Id từ user
                $bookId = Input::post('id');
                // lấy data từ bookId     
                $data = $this->traCuu($bookId, $data);
                // gắn tin nhắn                
                if (empty($data['bookId'])) {
                    $data['serverMessage'] = $message['MSG0004'] . $bookId;
                    $data['bookId'] = $bookId;
                } else {
                    $data['serverMessage'] = $message['MSG0003'];
                }
            } catch (Exception $e) { // xử lý ngoại lệ server
                // Debug::dump($e);
                $data['bookId'] = $bookId;
                $data['serverMessage'] = $message['MSG0005'];
            }
            return Response::forge(View::forge('bookmaster/book', $data, false));
        }
        // xử lý thông tin khi nhấn thêm
        elseif (isset($_POST["them_btn"])) {
            try {
                // lấy thông tin Input
                $data = $this->getInput($data);
                // kiểm tra ngày có hợp lệ
                if (!$this->validDate($data['month'], $data['date'], $data['year'])) {
                    $data['serverMessage'] = $message['MSG0016'];
                    return Response::forge(View::forge('bookmaster/book', $data, false));
                }
                // xử lý BookId để thêm sách
                if (Model_Book::find($data['bookId'])) {
                    $data['serverMessage'] = str_replace('****', $data['bookId'], $message['MSG0011']);
                } else {
                    $publicationDay = $data['year'] . "-" . $data['month'] . "-" . $data['date'];
                    $insertDay = date("Y-m-d h:i:s");
                    // thêm sách
                    $this->themSach(
                        $data['bookId'],
                        $data['bookTitle'],
                        $data['author'],
                        $data['publisher'],
                        $publicationDay,
                        $insertDay,
                    );

                    $data['serverMessage'] = $message['MSG0012'];
                }
            } catch (Exception $e) { // xử lý ngoại lệ server
                // Debug::dump($e);
                $data['serverMessage'] = $message['MSG0005'];
            }
            return Response::forge(View::forge('bookmaster/book', $data, false));
        }
        // xử lý thông tin khi nhấn Update
        elseif (isset($_POST["update_btn"])) {
            try {
                // lấy thông tin Input
                $data = $this->getInput($data);
                // kiểm tra ngày có hợp lệ
                if (!$this->validDate($data['month'], $data['date'], $data['year'])) {
                    $data['serverMessage'] = $message['MSG0016'];
                    return Response::forge(View::forge('bookmaster/book', $data, false));
                }
                // xử lý BookId để Update                
                if (Model_Book::find($data['bookId'])) {
                    $publicationDay = $data['year'] . "-" . $data['month'] . "-" . $data['date'];
                    $updateDay = date("Y-m-d h:i:s");
                    //update Book
                    $this->updateSach(
                        $data['bookId'],
                        $data['bookTitle'],
                        $data['author'],
                        $data['publisher'],
                        $publicationDay,
                        $updateDay
                    );
                    $data['serverMessage'] = $message['MSG0013'];
                    return Response::forge(View::forge('bookmaster/book', $data, false));
                }

                $data['serverMessage'] = str_replace('****', $data['bookId'], $message['MSG0014']);
            } catch (Exception $e) { // xử lý ngoại lệ server
                // Debug::dump($e);
                $data['serverMessage'] = $message['MSG0005'];
            }
            return Response::forge(View::forge('bookmaster/book', $data, false));
        }
        // xử lý thông tin khi nhấn xóa dữ liệu
        elseif (isset($_POST["xoa_btn"])) {
            try {
                $bookId = Input::post('id');
                // kiểm tra BookId tồn tại
                if (Model_Book::find($bookId)) {
                    // xóa sách                    
                    $this->xoaSach($bookId);
                    $data['serverMessage'] = str_replace('****', $bookId, $message['MSG0015']);
                    return Response::forge(View::forge('bookmaster/book', $data, false));
                }

                $data['bookId'] = $bookId;
                $data['serverMessage'] = str_replace('****', $bookId, $message['MSG0014']);
                return Response::forge(View::forge('bookmaster/book', $data, false));
            } catch (Exception $e) {
                // Debug::dump($e);
                $data['bookId'] = $bookId;
                $data['serverMessage'] = $message['MSG0005'];
                return Response::forge(View::forge('bookmaster/book', $data, false));
            }
        } else {
            return Response::forge(View::forge('bookmaster/book', $data, false));
        }
    }

    // tra cứu sách và trả về dữ liệu của data view
    public function traCuu($bookId, $dataArr)
    {
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

    // thêm sách
    public function themSach($bookId, $bookTitle, $author, $publisher, $publicationDay, $insertDay)
    {
        $model = new Model_Book();
        $model->insertBook($bookId, $bookTitle, $author, $publisher, $publicationDay, $insertDay);
    }

    // update sách
    public function updateSach($bookId, $bookTitle, $author, $publisher, $publicationDay, $updateDay)
    {
        $model = new Model_Book();
        $model->updateBook($bookId, $bookTitle, $author, $publisher, $publicationDay, $updateDay);
    }

    // xóa sách
    public function xoaSach($bookId)
    {
        $model = new Model_Book();
        $model->deleteBook($bookId);
    }

    public function validDate($month, $day, $year)
    {
        if (is_numeric($month) && is_numeric($day) && is_numeric($year) && checkdate($month, $day, $year)) {
            return true;
        }
        return false;
    }

    public function getInput($dataArr)
    {
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
