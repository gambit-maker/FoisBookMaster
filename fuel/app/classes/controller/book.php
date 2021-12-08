<?php

use Fuel\Core\Controller;
use Fuel\Core\Debug;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\Validation;
use Fuel\Core\View;

class Controller_Book extends Controller
{
    public function action_checkButtonClicked()
    {
        $message = [
            'MSG0001' => 'Hãy nhập Book ID',
            'MSG0002' => 'Hãy nhập Book ID bằng chữ số anh 1 byte',
            'MSG0003' => 'Sách đã được tìm thấy',
            'MSG0004' => 'Không thể tìm thấy Book ID****',
            'MSG0005' => 'Đã phát sinh ngoại lệ bằng xử lý server',
            'MSG0006' => 'Hãy nhập Book title',
            'MSG0007' => 'Hãy nhập tên tác giả',
            'MSG0008' => 'Hãy nhập nhà xuất bản',
            'MSG0009' => 'Hãy nhập ngày xuất bản',
            'MSG0010' => 'Hãy nhập ngày xuất bản bằng chữ số 1 byte',
            'MSG0011' => 'Book ID****đã được đăng ký. Hãy nhập ID khác',
            'MSG0012' => 'Đã đăng ký sách',
            'MSG0013' => 'Đã update sách',
            'MSG0014' => 'Book ID****không được tìm thấy',
            'MSG0015' => 'Đã xóa Book ID****',
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

        $val = Validation::forge();
        $val->add('id', 'Book ID')->add_rule('required')->add_rule('max_length', 4);
        if ($val->run()) {
        } else {
            // echo "no";
        }

        if (isset($_POST["tracuu_btn"])) {
            try {
                $bookid = Input::post('id');
                // tìm sách với ID
                $data['searchBook'] = Model_Book::find($bookid);
                $data['bookId'] = $bookid;

                // lấy thông tin sách nếu tìm thấy
                if ($data['searchBook']) {
                    $data['bookTitle'] = $data['searchBook']['book_title'];
                    $data['author'] = $data['searchBook']['author_name'];
                    $data['publisher'] = $data['searchBook']['publisher'];

                    $publicationDay = $data['searchBook']['publication_day'];
                    $publicationDay = explode('-', $publicationDay);

                    $data['year'] = $publicationDay[0];
                    $data['month'] = $publicationDay[1];
                    $data['date'] = $publicationDay[2];
                }
                // gắn tin nhắn
                $data['serverMessage'] = $data['searchBook'] ? $message['MSG0003'] : $message['MSG0004'];
                return Response::forge(View::forge('bookmaster/book', $data, false));
            } catch (Exception $e) {
                Debug::dump($e);
                $data['bookId'] = $bookid;
                $data['serverMessage'] = $message['MSG0005'];
                return Response::forge(View::forge('bookmaster/book', $data, false));
            }
        } else {
            return Response::forge(View::forge('bookmaster/book', $data, false));
        }
    }
}
