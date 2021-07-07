<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= $view->render('head') ?>
        <?php $view->style('theme', 'system/theme:assets/css/theme.css') ?>
    </head>
    <body>

        <div class="uk-flex uk-flex-center uk-flex-middle uk-height-viewport">
            <div class="uk-container uk-text-center">
                <img class="uk-margin-large-bottom" src="<?= $view->url()->getStatic($logo) ?>" alt="Pagekit">
                <div class="uk-panel">
                    <h1><?= __('Maintenance') ?></h1>
                    <p><?= $message ?></p>
                </div>
            </div>
        </div>

    </body>
</html>
