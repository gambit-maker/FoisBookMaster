<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php

    use Fuel\Core\Asset;
    use Fuel\Core\Form;

    echo Asset::css('bootstrap.min.css');
    echo Asset::css('style.css');
    ?>
</head>

<body>

    <div class="container pt-5 ">
        <h4>
            Book maintence master
            <a href="" id="close_anchor">Đóng</a>
        </h4>
        <?php echo Form::open(['action' => 'book/checkButtonClicked', 'method' => 'post']); ?>

        <!-- Book ID + Tra cứu -->
        <div>
            <?php
            echo Form::label('Book ID :', 'id');
            echo Form::button('tracuu_btn', 'Tra cứu', ['class' => 'btn btn-success', 'id' => 'tracuu_btn']);
            // 'onclick' => 'checkValueBookId()'
            ?>
            <div class="book_input">
                <?php
                echo Form::input('id', $bookId, ['id' => 'bookId', 'class' => 'form-control', 'style' => 'width:80%']);

                ?>
            </div>
        </div>

        <!-- Book title -->

        <div class="pt-2">
            <?php
            echo Form::label('Book title :', 'title');
            echo Form::input('title', $bookTitle, ['class' => 'form-control', 'id' => 'book_title']);
            ?>
        </div>

        <!-- Tên tác giã -->
        <div class="pt-2">
            <?php
            echo Form::label('Tên tác giả :', 'author');
            echo Form::input('author', $author, ['class' => 'form-control', 'id' => 'author']);
            ?>
        </div>

        <!-- Nhà xuất bản -->
        <div class="pt-2">
            <?php
            echo Form::label('Nhà xuất bản :', 'publisher');
            echo Form::input('publisher', $publisher, ['class' => 'form-control', 'id' => 'publisher']);
            ?>
        </div>

        <!-- thời gian xuất bản -->
        <div class="pt-2">
            <?php
            echo Form::label('Ngày xuất bản: ');
            ?>

            <div class="pibliser_day d-flex flex-row">
                <div class="m-2">
                    <?php echo Form::label('Năm', 'year'); ?>
                </div>
                <div>
                    <?php
                    echo Form::input('year', $year, ['class' => 'form-control', 'id' => 'year']);
                    ?>
                </div>


                <div class="m-2">
                    <?php echo Form::label('Tháng', 'month'); ?>
                </div>
                <div>
                    <?php
                    echo Form::input('month', $month, ['class' => 'form-control', 'id' => 'month']);
                    ?>
                </div>


                <div class="m-2">
                    <?php echo Form::label('Ngày', 'date'); ?>
                </div>
                <div>
                    <?php
                    echo Form::input('date', $date, ['class' => 'form-control', 'id' => 'date']);
                    ?>
                </div>

            </div>

            <div class="crud_btn d-flex flex-row pt-3 float-right">
                <div class="pl-5">
                    <?php echo Form::button('them_btn', 'Thêm', ['class' => 'btn btn-primary', 'id' => 'them_btn']) ?>
                </div>
                <div class="pl-5">
                    <?php echo Form::button('update_btn', 'Update', ['class' => 'btn btn-warning', 'id' => 'update_btn']) ?>
                </div>
                <div class="pl-5">
                    <?php echo Form::button('xoa_btn', 'Xóa', ['class' => 'btn btn-danger', 'id' => 'xoa_btn']) ?>
                </div>
                <div class="pl-5">
                    <?php echo Form::button('clear_btn', 'Clear', ['class' => 'btn btn-secondary', 'id' => 'clear_btn']) ?>
                </div>
            </div>
        </div>
        <!-- button thêm xóa sửa -->

        <?php echo Form::close(); ?>
    </div>
</body>
<script>
    <?php if (isset($serverMessage)) : ?>
        alert("<?php echo $serverMessage; ?>");
    <?php endif; ?>
</script>



<?php echo Asset::js('checkValidation.js'); ?>

</html>