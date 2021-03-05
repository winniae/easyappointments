<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#35A768">

    <title><?= lang('page_title') . ' ' . $company_name ?></title>

    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/ext/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/ext/jquery-ui/jquery-ui.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/ext/cookieconsent/cookieconsent.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/css/frontend.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/css/general.css') ?>">

    <link rel="icon" type="image/x-icon" href="<?= asset_url('assets/img/favicon.ico') ?>">
    <link rel="icon" sizes="192x192" href="<?= asset_url('assets/img/logo.png') ?>">

    <script src="<?= asset_url('assets/ext/fontawesome/js/fontawesome.min.js') ?>"></script>
    <script src="<?= asset_url('assets/ext/fontawesome/js/solid.min.js') ?>"></script>
</head>

<body>
<div id="main" class="container">
    <div class="row wrapper">
        <div id="book-appointment-wizard" class="col-12 col-lg-10 col-xl-8">
            <style>
                #book-appointment-wizard {
                    border-radius: .0rem;
                    overflow: hidden;
                    box-shadow: rgba(45,62,80,.12) 0 1px 145px 0;
                }
                #book-appointment-wizard #header
                {background: rgb(12, 164, 213);!important;}
                body {
                    background-color: white;
                }
                #book-appointment-wizard .book-step {
                    background: #0a7292;
                }
                #book-appointment-wizard .book-step strong {
                    color: #ffffff;
                }
                #book-appointment-wizard .book-step {
                    background: #0a7292;
                }
                #book-appointment-wizard .active-step strong,body .ui-datepicker td a, body .ui-datepicker td span{color:#0a7292!important; }
                #book-appointment-wizard .active-step{background: #ffffff;}
                #book-appointment-wizard .footer-powered-by{display: none}
                #book-appointment-wizard .footer-options{width: 100%;text-align: center}
                body .ui-datepicker .ui-widget-header,body .ui-datepicker th,html body .ui-datepicker td a.ui-state-active {
                    background: #0a7292!important;
                }
                body .ui-datepicker td a.ui-state-highlight {
                    background: #5fb9d6 !important;}
                body .ui-widget.ui-widget-content {
                    border: 1px solid #0a7292;}
                #book-appointment-wizard #available-hours .selected-hour {
                    background-color: #0a7292;
                    border-color: #0a7292;
                }
                #service-description.sd2 strong,#service-description.sd2 span,#service-description.sd2 br {
                    display: none;
                }
                #service-description.sd2:after{
                    content: "";
                    display: block;
                    max-width: 100%;
                    background-image: url("/assets/img/chagall.jpg");
                    height: 300px;
                    background-position: center;
                    background-position-x: center;
                    background-position-y: center;
                    background-repeat: no-repeat;
                    background-size: contain;
                }
                #select-time .form-group{display: none}
                #book-appointment-wizard #service-description{max-height: 36vh;}
                #book-appointment-wizard #header {
                    background: rgb(255, 255, 255);
                }
                @media (max-width:768px){
                    #book-appointment-wizard #company-name{margin-left: -185px;position: relative;
                    }
                }
                .progress {
                    background-color: #e5481d!important;;
                    border-radius: 0;
                    margin-right: 0 !important;
                    margin-bottom: -6px;
                }
                .progress-bar {
                    background-color: #8abd24!important;
                }
                #book-appointment-wizard #available-hours div {
                    margin-right: 0;
                }
                a {
                    color: #00a1d4;
                }
                .badge-primary {
                    color: #fff;
                    background-color: #00a1d4;
                }
                a.badge-primary:focus, a.badge-primary:hover {
                    color: #fff;
                    background-color: #477f91;
                }
            </style>
            <!-- FRAME TOP BAR -->

            <div id="header">
                <span id="company-name"><img title="<?= $company_name ?>" src="/assets/img/logo.png"></span>

                <div id="steps">
                    <div id="step-1" class="book-step active-step"
                         data-tippy-content="<?= lang('service_and_provider') ?>">
                        <strong>1</strong>
                    </div>

                    <div id="step-2" class="book-step" data-toggle="tooltip"
                         data-tippy-content="<?= lang('appointment_date_and_time') ?>">
                        <strong>2</strong>
                    </div>
                    <div id="step-3" class="book-step" data-toggle="tooltip"
                         data-tippy-content="<?= lang('customer_information') ?>">
                        <strong>3</strong>
                    </div>
                    <div id="step-4" class="book-step" data-toggle="tooltip"
                         data-tippy-content="<?= lang('appointment_confirmation') ?>">
                        <strong>4</strong>
                    </div>
                </div>
            </div>

            <?php if ($manage_mode): ?>
                <div id="cancel-appointment-frame" class="row booking-header-bar">
                    <div class="col-12 col-md-8">
                        <small><?= lang('cancel_appointment_hint') ?></small>
                    </div>
                    <div class="col-12 col-md-4">
                        <form id="cancel-appointment-form" method="post"
                              action="<?= site_url('appointments/cancel/' . $appointment_data['hash']) ?>">

                            <input type="hidden" name="csrfToken" value="<?= $this->security->get_csrf_hash() ?>"/>

                            <textarea name="cancel_reason" style="display:none"></textarea>

                            <button id="cancel-appointment" class="btn btn-warning btn-sm">
                                <?= lang('cancel') ?>
                            </button>
                        </form>
                    </div>
                </div>
                <?php /* <div class="booking-header-bar row">
                    <div class="col-12 col-md-10">
                        <small><?= lang('delete_personal_information_hint') ?></small>
                    </div>
                    <div class="col-12 col-md-2">
                        <button id="delete-personal-information"
                                class="btn btn-danger btn-sm"><?= lang('delete') ?></button>
                    </div>
                </div>
            <?php */ endif; ?>

            <?php if (isset($exceptions)): ?>
                <div style="margin: 10px">
                    <h4><?= lang('unexpected_issues') ?></h4>

                    <?php foreach ($exceptions as $exception): ?>
                        <?= exceptionToHtml($exception) ?>
                    <?php endforeach ?>
                </div>
            <?php endif ?>


            <!-- SELECT SERVICE AND PROVIDER -->

            <div id="wizard-frame-1" class="wizard-frame">
                <div class="frame-container">
                    <h2 class="frame-title"><?= lang('service_and_provider') ?></h2>

                    <div class="row frame-content">
                        <div class="col">
                            <div class="form-group">
                                <label for="select-service">
                                    <strong><?= lang('service') ?></strong>
                                </label>

                                <select id="select-service" class="form-control">
                                    <?php
                                    // Group services by category, only if there is at least one service with a parent category.
                                    $has_category = FALSE;
                                    foreach ($available_services as $service)
                                    {
                                        if ($service['category_id'] != NULL)
                                        {
                                            $has_category = TRUE;
                                            break;
                                        }
                                    }

                                    if ($has_category)
                                    {
                                        $grouped_services = [];

                                        foreach ($available_services as $service)
                                        {
                                            if ($service['category_id'] != NULL)
                                            {
                                                if ( ! isset($grouped_services[$service['category_name']]))
                                                {
                                                    $grouped_services[$service['category_name']] = [];
                                                }

                                                $grouped_services[$service['category_name']][] = $service;
                                            }
                                        }

                                        // We need the uncategorized services at the end of the list so we will use
                                        // another iteration only for the uncategorized services.
                                        $grouped_services['uncategorized'] = [];
                                        foreach ($available_services as $service)
                                        {
                                            if ($service['category_id'] == NULL)
                                            {
                                                $grouped_services['uncategorized'][] = $service;
                                            }
                                        }

                                        foreach ($grouped_services as $key => $group)
                                        {
                                            $group_label = ($key != 'uncategorized')
                                                ? $group[0]['category_name'] : 'Uncategorized';

                                            if (count($group) > 0)
                                            {
                                                echo '<optgroup label="' . $group_label . '">';
                                                foreach ($group as $service)
                                                {
                                                    echo '<option value="' . $service['id'] . '">'
                                                        . $service['name'] . '</option>';
                                                }
                                                echo '</optgroup>';
                                            }
                                        }
                                    }
                                    else
                                    {
                                        foreach ($available_services as $service)
                                        {
                                            echo '<option value="' . $service['id'] . '">' . $service['name'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group" style="display: none">
                                <label for="select-provider">
                                    <strong><?= lang('provider') ?></strong>
                                </label>

                                <select id="select-provider" class="form-control"></select>
                            </div>

                            <div id="service-description"></div>
                        </div>
                    </div>
                </div>

                <div class="command-buttons">
                    <span>&nbsp;</span>

                    <button type="button" id="button-next-1" class="btn button-next btn-dark"
                            data-step_index="1">
                        <?= lang('next') ?>
                        <i class="fas fa-chevron-right ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- SELECT APPOINTMENT DATE -->

            <div id="wizard-frame-2" class="wizard-frame" style="display:none;">
                <div class="frame-container">

                    <h2 class="frame-title"><?= lang('appointment_date_and_time') ?></h2>

                    <div class="row frame-content">
                        <div class="col-12 col-md-6">
                            <div id="select-date" style="padding-top: 30px!important;"></div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div id="select-time">
                                <div class="form-group">
                                    <label for="select-timezone"><?= lang('timezone') ?></label>
                                    <?= render_timezone_dropdown('id="select-timezone" class="form-control" value="Europe/Berlin"'); ?>
                                </div>

                                <div id="available-hours"></div>
                            </div>
                        </div>
                    </div>
                    <div class="pl-5 pr-5">
                        <div class="form-group">
                            <label for="attendant-count" class="control-label">
                                <?= lang('attendant_count_frontend') ?>
                                <span class="text-danger">*</span>
                            </label>
                            <select id="attendant-count" class="required form-control"></select>
                        </div>
                    </div>

                    <div class="pl-5 pr-5">
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="audioguide">
                            <label class="form-check-label" for="audioguide">
                                <?= lang('audioguide_frontend') ?>
                            </label>
                        </div>
                    </div>

                </div>

                <div class="command-buttons">
                    <button type="button" id="button-back-2" class="btn button-back btn-outline-secondary"
                            data-step_index="2">
                        <i class="fas fa-chevron-left mr-2"></i>
                        <?= lang('back') ?>
                    </button>
                    <button type="button" id="button-next-2" class="btn button-next btn-dark"
                            data-step_index="2">
                        <?= lang('next') ?>
                        <i class="fas fa-chevron-right ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- ENTER CUSTOMER DATA -->

            <div id="wizard-frame-3" class="wizard-frame" style="display:none;">
                <div class="frame-container">

                    <h2 class="frame-title"><?= lang('customer_information') ?></h2>

                    <div class="row frame-content">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="first-name" class="control-label">
                                    <?= lang('first_name') ?>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="first-name" class="required form-control" maxlength="100"/>
                            </div>
                            <div class="form-group">
                                <label for="last-name" class="control-label">
                                    <?= lang('last_name') ?>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="last-name" class="required form-control" maxlength="120"/>
                            </div>
                            <div class="form-group">
                                <label for="email" class="control-label">
                                    <?= lang('email') ?>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="email" class="required form-control" maxlength="120"/>
                            </div>
                            <div class="form-group">
                                <label for="phone-number" class="control-label">
                                    <?= lang('phone_number') ?>
                                    <?= $require_phone_number === '1' ? '<span class="text-danger">*</span>' : '' ?>
                                </label>
                                <input type="text" id="phone-number" maxlength="60"
                                       class="<?= $require_phone_number === '1' ? 'required' : '' ?> form-control"/>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="address" class="control-label">
                                    <?= lang('address') ?>
                                </label>
                                <input type="text" id="address" class="form-control" maxlength="120"/>
                            </div>
                            <div class="form-group">
                                <label for="city" class="control-label">
                                    <?= lang('city') ?>
                                </label>
                                <input type="text" id="city" class="form-control" maxlength="120"/>
                            </div>
                            <div class="form-group">
                                <label for="zip-code" class="control-label">
                                    <?= lang('zip_code') ?>
                                </label>
                                <input type="text" id="zip-code" class="form-control" maxlength="120"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="notes" class="control-label">
                            <?= lang('notes_frontend') ?>
                        </label>
                        <textarea id="notes" maxlength="500" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="accept-newsletter">
                    <label class="form-check-label" for="accept-newsletter">
                        <?= lang('accept_newsletter_frontend') ?>
                    </label>
                </div>

                <?php if ($display_terms_and_conditions): ?>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="required form-check-input" id="accept-to-terms-and-conditions">
                        <label class="form-check-label" for="accept-to-terms-and-conditions">
                            <?= strtr(lang('read_and_agree_to_terms_and_conditions'),
                                [
                                    '{$link}' => '<a href="#" data-toggle="modal" data-target="#terms-and-conditions-modal">',
                                    '{/$link}' => '</a>'
                                ])
                            ?>
                            <span class="text-danger">*</span>
                        </label>
                    </div>
                <?php endif ?>

                <?php if ($display_privacy_policy): ?>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="required form-check-input" id="accept-to-privacy-policy">
                        <label class="form-check-label" for="accept-to-privacy-policy">
                            <?= strtr(lang('read_and_agree_to_privacy_policy'),
                                [
                                    '{$link}' => '<a href="#" data-toggle="modal" data-target="#privacy-policy-modal">',
                                    '{/$link}' => '</a>'
                                ])
                            ?>
                            <span class="text-danger">*</span>
                        </label>
                    </div>
                <?php endif ?>

                <div class="command-buttons">
                    <button type="button" id="button-back-3" class="btn button-back btn-outline-secondary"
                            data-step_index="3">
                        <i class="fas fa-chevron-left mr-2"></i>
                        <?= lang('back') ?>
                    </button>
                    <button type="button" id="button-next-3" class="btn button-next btn-dark"
                            data-step_index="3">
                        <?= lang('next') ?>
                        <i class="fas fa-chevron-right ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- APPOINTMENT DATA CONFIRMATION -->

            <div id="wizard-frame-4" class="wizard-frame" style="display:none;">
                <div class="frame-container">
                    <h2 class="frame-title"><?= lang('appointment_confirmation') ?></h2>
                    <div class="row frame-content">
                        <div id="appointment-details" class="col-12 col-md-6"></div>
                        <div id="customer-details" class="col-12 col-md-6"></div>
                        <div id="confirmation-notes" class="col-12 col-md-12"></div>
                    </div>
                    <?php if ($this->settings_model->get_setting('require_captcha') === '1'): ?>
                        <div class="row frame-content">
                            <div class="col-12 col-md-6">
                                <h4 class="captcha-title">
                                    CAPTCHA
                                    <button class="btn btn-link text-dark text-decoration-none py-0">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </h4>
                                <img class="captcha-image" src="<?= site_url('captcha') ?>">
                                <input class="captcha-text form-control" type="text" value=""/>
                                <span id="captcha-hint" class="help-block" style="opacity:0">&nbsp;</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="command-buttons">
                    <button type="button" id="button-back-4" class="btn button-back btn-outline-secondary"
                            data-step_index="4">
                        <i class="fas fa-chevron-left mr-2"></i>
                        <?= lang('back') ?>
                    </button>
                    <form id="book-appointment-form" style="display:inline-block" method="post">
                        <button id="book-appointment-submit" type="button" class="btn btn-success">
                            <i class="fas fa-check-square mr-2"></i>
                            <?= ! $manage_mode ? lang('confirm') : lang('update') ?>
                        </button>
                        <input type="hidden" name="csrfToken"/>
                        <input type="hidden" name="post_data"/>
                    </form>
                </div>
            </div>

            <!-- FRAME FOOTER -->

            <div id="frame-footer">
                <small>
                    <span class="footer-options">
                        <span id="select-language" class="badge badge-secondary">
                            <i class="fas fa-language mr-2"></i>
                            <?= ucfirst(config('language')) ?>
                        </span>

                        <a class="backend-link badge badge-primary" href="<?= site_url('backend'); ?>">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            <?= $this->session->user_id ? lang('backend_section') : lang('login') ?>
                        </a>

                        <a class="backend-link" target="_blank" href="https://www.kultur-lindau.de/impressum">
                            Impressum
                        </a>
                    </span>
                </small>
            </div>
        </div>
    </div>
</div>

<?php if ($display_cookie_notice === '1'): ?>
    <?php require 'cookie_notice_modal.php' ?>
<?php endif ?>

<?php if ($display_terms_and_conditions === '1'): ?>
    <?php require 'terms_and_conditions_modal.php' ?>
<?php endif ?>

<?php if ($display_privacy_policy === '1'): ?>
    <?php require 'privacy_policy_modal.php' ?>
<?php endif ?>

<script>
    var GlobalVariables = {
        availableServices: <?= json_encode($available_services) ?>,
        availableProviders: <?= json_encode($available_providers) ?>,
        baseUrl: <?= json_encode(config('base_url')) ?>,
        manageMode: <?= $manage_mode ? 'true' : 'false' ?>,
        customerToken: <?= json_encode($customer_token) ?>,
        dateFormat: <?= json_encode($date_format) ?>,
        timeFormat: <?= json_encode($time_format) ?>,
        firstWeekday: <?= json_encode($first_weekday) ?>,
        displayCookieNotice: <?= json_encode($display_cookie_notice === '1') ?>,
        appointmentData: <?= json_encode($appointment_data) ?>,
        providerData: <?= json_encode($provider_data) ?>,
        customerData: <?= json_encode($customer_data) ?>,
        displayAnyProvider: <?= json_encode($display_any_provider) ?>,
        csrfToken: <?= json_encode($this->security->get_csrf_hash()) ?>
    };

    var EALang = <?= json_encode($this->lang->language) ?>;
    var availableLanguages = <?= json_encode(config('available_languages')) ?>;
</script>

<script src="<?= asset_url('assets/js/general_functions.js') ?>"></script>
<script src="<?= asset_url('assets/ext/jquery/jquery.min.js') ?>"></script>
<script src="<?= asset_url('assets/ext/jquery-ui/jquery-ui.min.js') ?>"></script>
<script src="<?= asset_url('assets/ext/cookieconsent/cookieconsent.min.js') ?>"></script>
<script src="<?= asset_url('assets/ext/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= asset_url('assets/ext/popper/popper.min.js') ?>"></script>
<script src="<?= asset_url('assets/ext/tippy/tippy-bundle.umd.min.js') ?>"></script>
<script src="<?= asset_url('assets/ext/datejs/date.min.js') ?>"></script>
<script src="<?= asset_url('assets/ext/moment/moment.min.js') ?>"></script>
<script src="<?= asset_url('assets/ext/moment/moment-timezone-with-data.min.js') ?>"></script>
<script src="<?= asset_url('assets/js/frontend_book_api.js') ?>"></script>
<script src="<?= asset_url('assets/js/frontend_book.js') ?>"></script>

<script>
    $(function () {
        FrontendBook.initialize(true, GlobalVariables.manageMode);
        GeneralFunctions.enableLanguageSelection($('#select-language'));
    });
</script>

<?php google_analytics_script(); ?>
</body>
</html>
