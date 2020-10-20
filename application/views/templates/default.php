<?php
/**
 * @var string $title - Page title
 * @var string $content - Page content
 * @var array $arResult - Page Data
 */
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <title><?= $title; ?></title>
</head>
<body>
<div class="container">
    <div class="row mt-5">
        <div class="col-lg-12">
            <h1 class="text-center">The best task planner for your Team!</h1>

            <blockquote class="blockquote text-center">
                <p class="mb-0">Good tasks need to good plan!</p>
                <footer class="blockquote-footer">Someone famous</footer>
            </blockquote>
        </div>
    </div>
    <? if ($arResult['MENU']) { ?>
        <ul class="nav justify-content-end">
            <? foreach ($arResult['MENU'] as $item) { ?>
                <li class="nav-item">
                    <? if (!empty($item['URL'])) { ?>
                        <a class="nav-link" href="<?= $item['URL'] ?>"><?= $item['NAME'] ?></a>
                    <? } else { ?>
                        <a class="nav-link" href="<?= $item['URL'] ?>" data-toggle="modal"
                           data-target="#addTask"><?= $item['NAME'] ?></a>
                    <? } ?>
                </li>
            <? } ?>
        </ul>
    <? } ?>


    <?= $content; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
        crossorigin="anonymous"></script>
<script src="public/js/main.js"></script>

</body>
</html>