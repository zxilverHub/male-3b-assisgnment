<?php
// setting menu items
include('items.php');

session_start();


$items_object = array(
    new Items('Sinigang', 1500.50, '1', 'sinigang.jpg'),
    new Items('Adobo', 2500.50, '2', 'adobo.jpg'),
    new Items('Bangus', 1400.70, '3', 'bangus.jpg'),
    new Items('Rice', 400.30, '4', 'rice.jpg')
);

$_SESSION['menuItems'] = [];

if (isset($_SESSION['menuItems'])) {
    $_SESSION['menuItems'] = $items_object;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Male | 3B</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php

    if (isset($_POST['order-Sinigang'])) {
        $_SESSION['orderItems'][] = $_SESSION['menuItems'][0];
    }

    if (isset($_POST['order-Adobo'])) {
        $_SESSION['orderItems'][] = $_SESSION['menuItems'][1];
    }

    if (isset($_POST['order-Bangus'])) {
        $_SESSION['orderItems'][] = $_SESSION['menuItems'][2];
    }

    if (isset($_POST['order-Rice'])) {
        $_SESSION['orderItems'][] = $_SESSION['menuItems'][3];
    }

    if (isset($_POST['reset'])) {
        unset($_SESSION['orderItems']);
        unset($_SESSION['change']);
        unset($_SESSION['total']);
    }

    if (isset($_POST['pay']) && isset($_POST['amount'])) {
        if (!$_POST['amount'] == '' && $_POST['amount'] >= $_SESSION['total']) {
            $_SESSION['change'] = $_POST['amount'] - $_SESSION['total'];
        }
    }

    ?>

    <form action="" method="post" class="reset-btn">
        <input name="reset" type="submit" value="Reset" />
    </form>

    <section class="menus">
        <h2>Menu</h2>
        <div class="menu">
            <?php

            $display_items = $_SESSION['menuItems'];
            foreach ($display_items as $ditem) {
                echo "<div class='card'>";
                echo "
                <img src='./images/$ditem->path' />
                <div class='name'> $ditem->name</div>
                <div class='price'>$ditem->price Php</div>
                <form action='' method='post'>
                    <input type='submit' value='Order' class='order-btn' name='order-$ditem->name' />
                </form>
                ";

                echo "</div>";
            }

            ?>
        </div>
        <div class="total">
            <?php
            if (isset($_SESSION['orderItems'])) {
                $total = 0;

                foreach ($_SESSION['orderItems'] as $i) {
                    $total += $i->price;
                }

                $_SESSION['total'] = $total;

                echo "
                    <h3>Total bill</h3>
                    <div class='total-price'>$total</div>
                    ";
            }
            ?>
        </div>

        <?php
        echo
        "
            <form class='total' action='' method='post'>
                <h3> Amount: </h3>

                <div class='option'>
                    <input type='number' name='amount' placeholder='Enter valid amount'>
                    <input type='submit' value='Pay order' name='pay'>
                </div>

            </form>
            ";
        ?>

        <?php
        function numberToWords($number)
        {
            $words = [
                '0' => 'Zero',
                '1' => 'One',
                '2' => 'Two',
                '3' => 'Three',
                '4' => 'Four',
                '5' => 'Five',
                '6' => 'Six',
                '7' => 'Seven',
                '8' => 'Eight',
                '9' => 'Nine',
                '10' => 'Ten',
                '11' => 'Eleven',
                '12' => 'Twelve',
                '13' => 'Thirteen',
                '14' => 'Fourteen',
                '15' => 'Fifteen',
                '16' => 'Sixteen',
                '17' => 'Seventeen',
                '18' => 'Eighteen',
                '19' => 'Nineteen',
                '20' => 'Twenty',
                '30' => 'Thirty',
                '40' => 'Forty',
                '50' => 'Fifty',
                '60' => 'Sixty',
                '70' => 'Seventy',
                '80' => 'Eighty',
                '90' => 'Ninety',
            ];

            if ($number < 21) {
                return $words[$number];
            } elseif ($number < 100) {
                return $words[10 * floor($number / 10)] . ($number % 10 ? ' ' . $words[$number % 10] : '');
            } elseif ($number < 1000) {
                return $words[floor($number / 100)] . ' Hundred' . ($number % 100 ? ' ' . numberToWords($number % 100) : '');
            } elseif ($number < 1000000) {
                return numberToWords(floor($number / 1000)) . ' Thousand' . ($number % 1000 ? ' ' . numberToWords($number % 1000) : '');
            }
            return '';
        }

        function toRomanNumerals($number)
        {
            $map = [
                1000 => 'M',
                900 => 'CM',
                500 => 'D',
                400 => 'CD',
                100 => 'C',
                90 => 'XC',
                50 => 'L',
                40 => 'XL',
                10 => 'X',
                9 => 'IX',
                5 => 'V',
                4 => 'IV',
                1 => 'I',
            ];

            $roman = '';
            foreach ($map as $value => $symbol) {
                while ($number >= $value) {
                    $roman .= $symbol;
                    $number -= $value;
                }
            }
            return $roman;
        }

        if (isset($_SESSION['change'])) {
            $change = $_SESSION['change'];
            $ntw = numberToWords($change);
            $rn = toRomanNumerals($change);

            $newW = '';
            $pattern = '/[aeiouAEIOU]/';

            for ($i = 0; $i < strlen($ntw); $i++) {
                $ni = $ntw[$i];
                if (preg_match($pattern, $ni)) {
                    $newW = $newW . "<span class='vw'>$ni</span>";
                } else {
                    $newW = $newW . $ni;
                }
            }

            echo
            "
                <div class='total'>
                    <h3>Change</h3>
                    <div class='total-price change'>$change</div>
                </div>
                
                <div class='total'>
                    <h3>In words: </h3>
                    <div class='total-price change'>$newW</div>
                </div>

                <div class='total'>
                    <h3>Roman Numeral: </h3>
                    <div class='total-price change'>$rn</div>
                </div>

            ";
        }
        ?>
    </section>

    <section class="receipt">

    </section>

</body>

</html>