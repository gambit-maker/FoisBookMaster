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

    ?>

</head>

<body>
    <div style="margin: auto; width: 500px;" class="pt-5">
        <h4>
            Book maintence master
            <a href="" style="float: right; text-decoration:none;">Đóng</a>
        </h4>
        <?php echo Form::open(['action' => 'book/checkButtonClicked', 'method' => 'post']); ?>

        <!-- Book ID + Tra cứu -->
        <div>
            <?php
            echo Form::label('Book ID :', 'id');
            echo Form::button('tracuu_btn', 'Tra cứu', ['class' => 'btn btn-primary', 'style' => 'float: right; margin-top:30px', 'id' => 'tracuu_btn']);
            // 'onclick' => 'checkValueBookId()'
            ?>
            <div style="overflow: hidden; padding-right: .5rem">
                <?php
                echo Form::input('id', $bookId, ['id' => 'bookId', 'class' => 'form-control', 'style' => 'width:80%']);

                ?>
            </div>
        </div>

        <!-- Book title -->

        <div style="padding-top: 10px;">
            <?php
            echo Form::label('Book title :', 'title');
            echo Form::input('title', $bookTitle, ['class' => 'form-control', 'id' => 'book_title']);
            ?>
        </div>

        <!-- Tên tác giã -->
        <div style="padding-top: 10px">
            <?php
            echo Form::label('Tên tác giả :', 'author');
            echo Form::input('author', $author, ['class' => 'form-control', 'id' => 'author']);
            ?>
        </div>

        <!-- Nhà xuất bản -->
        <div style="padding-top: 10px;">
            <?php
            echo Form::label('Nhà xuất bản :', 'publisher');
            echo Form::input('publisher', $publisher, ['class' => 'form-control', 'id' => 'publisher']);
            ?>
        </div>

        <!-- thời gian xuất bản -->
        <div style="padding-top: 10px">
            <?php
            echo Form::label('Ngày xuất bản: ');
            ?>

            <div class="d-flex flex-row">
                <div style="width: 25%;">
                    <?php
                    echo Form::input('year', $year, ['class' => 'form-control', 'style' => 'text-align:center;', 'id' => 'year']);
                    ?>
                </div>
                <div class="pt-2">
                    <?php echo Form::label('Năm', 'year'); ?>
                </div>

                <div style="width: 10%;">
                    <?php
                    echo Form::input('month', $month, ['class' => 'form-control', 'style' => 'text-align:center;', 'id' => 'month']);
                    ?>
                </div>
                <div class="pt-2">
                    <?php echo Form::label('Tháng', 'month'); ?>
                </div>

                <div style="width: 10%;">
                    <?php
                    echo Form::input('date', $date, ['class' => 'form-control', 'style' => 'text-align:center;', 'id' => 'date']);
                    ?>
                </div>
                <div class="pt-2">
                    <?php echo Form::label('Ngày', 'date'); ?>
                </div>
            </div>

            <div class="d-flex flex-row pt-3 float-right">
                <div class="pl-5">
                    <?php echo Form::button('them_btn', 'Thêm', ['class' => 'btn btn-primary', 'style' => 'width: 80px', 'id' => 'them_btn']) ?>
                </div>
                <div class="pl-5">
                    <?php echo Form::button('update_btn', 'Update', ['class' => 'btn btn-warning', 'style' => 'width: 80px', 'id' => 'update_btn']) ?>
                </div>
                <div class="pl-5">
                    <?php echo Form::button('xoa_btn', 'Xóa', ['class' => 'btn btn-danger', 'style' => 'width: 80px']) ?>
                </div>
                <div class="pl-5">
                    <?php echo Form::button('clear_btn', 'Clear', ['class' => 'btn btn-secondary', 'style' => 'width: 80px']) ?>
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