<?php

use Fuel\Core\Controller;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\View;

class Controller_Book extends Controller
{
    public function action_checkButtonClicked()
    {
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
                $bookId = Input::post('id');
                // tìm sách với ID Input của user
                $data['bookInfo'] = Model_Book::find($bookId);
                $data['bookId'] = $bookId;

                // lấy thông tin sách nếu tìm thấy
                if ($data['bookInfo']) {
                    $data['bookTitle'] = $data['bookInfo']['book_title'];
                    $data['author'] = $data['bookInfo']['author_name'];
                    $data['publisher'] = $data['bookInfo']['publisher'];
                    $publicationDay = $data['bookInfo']['publication_day'];
                    $publicationDay = explode('-', $publicationDay);
                    $data['year'] = $publicationDay[0];
                    $data['month'] = $publicationDay[1];
                    $data['date'] = $publicationDay[2];
                }

                // gắn tin nhắn
                $data['serverMessage'] = $data['bookInfo'] ? $message['MSG0003'] : $message['MSG0004'] . $bookId;

                return Response::forge(View::forge('bookmaster/book', $data, false));
            } catch (Exception $e) { // xử lý ngoại lệ server
                // Debug::dump($e);
                $data['bookId'] = $bookId;
                $data['serverMessage'] = $message['MSG0005'];
                return Response::forge(View::forge('bookmaster/book', $data, false));
            }
        }
        // xử lý thông tin khi nhấn thêm
        elseif (isset($_POST["them_btn"])) {
            try {
                // lấy thông tin Input
                $userInput = $this->getInput();
                foreach ($userInput as $key => $value) {
                    $data[$key] = $value;
                }

                // kiểm tra ngày có hợp lệ
                if (!$this->validDate($data['month'], $data['date'], $data['year'])) {
                    $data['serverMessage'] = $message['MSG0016'];
                    return Response::forge(View::forge('bookmaster/book', $data, false));
                }
                // xử lý BookId để thêm sách
                if (Model_Book::find($data['bookId'])) {
                    $data['serverMessage'] = str_replace('****', $data['bookId'], $message['MSG0011']);
                    return Response::forge(View::forge('bookmaster/book', $data, false));
                } else {
                    // đăng ký sách
                    $model = new Model_Book();
                    $model->book_id = $data['bookId'];
                    $model->book_title = $data['bookTitle'];
                    $model->author_name = $data['author'];
                    $model->publisher = $data['publisher'];
                    $model->publication_day = $data['year'] . "-" . $data['month'] . "-" . $data['date'];
                    //set time zone vietnam
                    date_default_timezone_set('Asia/Ho_Chi_Minh');
                    $model->insert_day = date("Y-m-d h:i:sa");
                    $model->save();

                    $data['serverMessage'] = $message['MSG0012'];
                    return Response::forge(View::forge('bookmaster/book', $data, false));
                }
            } catch (Exception $e) { // xử lý ngoại lệ server
                Debug::dump($e);
                $data['serverMessage'] = $message['MSG0005'];
                return Response::forge(View::forge('bookmaster/book', $data, false));
            }
        }
        // xử lý thông tin khi nhấn Update
        elseif (isset($_POST["update_btn"])) {
            try {
                // lấy thông tin Input
                $userInput = $this->getInput();
                foreach ($userInput as $key => $value) {
                    $data[$key] = $value;
                }
                // kiểm tra ngày có hợp lệ
                if (!$this->validDate($data['month'], $data['date'], $data['year'])) {
                    $data['serverMessage'] = $message['MSG0016'];
                    return Response::forge(View::forge('bookmaster/book', $data, false));
                }

                // xử lý BookId để Update
                $bookInfo = Model_Book::find($data['bookId']);
                if ($bookInfo) {
                    //update Book
                    $bookInfo->book_id = $data['bookId'];
                    $bookInfo->book_title = $data['bookTitle'];
                    $bookInfo->author_name = $data['author'];
                    $bookInfo->publisher = $data['publisher'];
                    $bookInfo->publication_day =
                        $data['year'] . "-" . $data['month'] . "-" . $data['date'];
                    $bookInfo->save();

                    $data['serverMessage'] = $message['MSG0013'];
                    return Response::forge(View::forge('bookmaster/book', $data, false));
                }

                $data['serverMessage'] = str_replace('****', $data['bookId'], $message['MSG0014']);
                return Response::forge(View::forge('bookmaster/book', $data, false));
            } catch (Exception $e) { // xử lý ngoại lệ server
                // Debug::dump($e);
                $data['serverMessage'] = $message['MSG0005'];
                return Response::forge(View::forge('bookmaster/book', $data, false));
            }
        }
        // xử lý thông tin khi nhấn xóa dữ liệu
        elseif (isset($_POST["xoa_btn"])) {
            try {
                $bookId = Input::post('id');
                // kiểm tra BookId tồn tại
                if (Model_Book::find($bookId)) {
                    // xóa sách
                    Model_Book::find($bookId)->delete();
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

    public function validDate($month, $day, $year)
    {
        if (is_numeric($month) && is_numeric($day) && is_numeric($year) && checkdate($month, $day, $year)) {
            return true;
        }
        return false;
    }

    public function getInput()
    {
        $input = [];
        $input['bookId'] = Input::post('id');
        $input['bookTitle'] = Input::post('title');
        $input['author'] = Input::post('author');
        $input['publisher'] = Input::post('publisher');
        $input['year'] = Input::post('year');
        $input['month'] = Input::post('month');
        $input['date'] = Input::post('date');

        return $input;
    }
}
