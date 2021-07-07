<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="<?= $view->url()->getStatic('system/theme:favicon.ico') ?>" rel="shortcut icon" type="image/x-icon">
        <link href="<?= $view->url()->getStatic('system/theme:apple_touch_icon.png') ?>" rel="apple-touch-icon-precomposed">
        <?= $view->render('head') ?>
        <?php $view->style('theme', 'system/theme:assets/css/theme.css') ?>
        <?php $view->script('login', 'system/theme:app/bundle/login.js', ['uikit']) ?>
    </head>
    <body>

        <div class="uk-section-secondary">
            <div class="tm-background uk-flex uk-flex-middle uk-flex-center" uk-height-viewport>
                <div class="uk-panel uk-width-medium uk-text-center">

                    <img class="uk-margin-large-bottom" src="<?= $view->url()->getStatic('app/system/assets/images/pagekit-logo-text.svg') ?>" alt="Pagekit">

                    <?= $view->render('messages') ?>

                    <form class="js-login js-toggle" action="<?= $view->url('@user/authenticate') ?>" method="post">

                        <div class="uk-panel">

                            <div class="uk-margin">
                                <input class="uk-width-1-1 uk-input" type="text" name="credentials[username]" value="<?= $this->escape($last_username) ?>" placeholder="<?= __('Username') ?>" autofocus>
                            </div>

                            <div class="uk-margin">
                                <input class="uk-width-1-1 uk-input" type="password" name="credentials[password]" value="" placeholder="<?= __('Password') ?>" autocomplete="off">
                            </div>

                            <p class="uk-margin">
                                <button class="uk-button uk-button-default uk-button-large uk-width-1-1"><?= __('Login') ?></button>
                            </p>

                            <?php $view->token()->get() ?>
                            <input type="hidden" name="redirect" value="<?= $this->escape($redirect) ?>">

                        </div>

                        <ul class="uk-list uk-light">
                            <li><label class="uk-form"><input class="uk-checkbox" type="checkbox" name="remember_me"> <?= __('Remember Me') ?></label></li>
                            <li class="uk-margin-small-top"> <?= __('Forgot Password?') ?> <a class="uk-link" uk-toggle="target: .js-toggle"><?= __('Request Password') ?></a></li>
                        </ul>

                    </form>

                    <form class="js-toggle" action="<?= $view->url('@user/resetpassword/request') ?>" method="post" hidden>

                        <div class="uk-panel">

                            <div class="uk-margin">
                                <input class="uk-width-1-1 uk-input" type="text" name="email" value="" placeholder="<?= __('Email') ?>" required>
                            </div>

                            <p class="uk-margin">
                                <button class="uk-button uk-button-default uk-button-large uk-width-1-1"><?= __('Reset Password') ?></button>
                            </p>

                            <?php $view->token()->get() ?>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </body>
</html>
