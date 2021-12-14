<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <?php

    use Fuel\Core\Asset;

    echo Asset::css('hitandblow/style.css');
    ?>
</head>


<body>
    <main>
        <section class="play">
            <div class="center">
                <h2 class="gussingNum">? ? ? ?</h2>
                <input class="inputNumber" maxlength="4" size="4" type="text" autofocus>
                <button class="btn guessingButton">Comfirm</button>
            </div>

        </section>
        <section class="play">
            <div class="history">
                <table class="history_table">
                    <tr>
                        <th>Row</th>
                        <th>Number</th>
                        <th>hit</th>
                        <th>blow</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </section>

        <button class="btn btn_newgame">New game</button>

    </main>
</body>
<?php echo Asset::js('hitandblow/hitandblow.js') ?>

</html>