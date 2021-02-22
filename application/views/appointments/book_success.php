<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#35A768">
    <title><?= lang('appointment_registered') . ' - ' . $company_name ?></title>

    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/ext/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/css/frontend.css') ?>">

    <link rel="icon" type="image/x-icon" href="<?= asset_url('assets/img/favicon.ico') ?>">
    <link rel="icon" sizes="192x192" href="<?= asset_url('assets/img/logo.png') ?>">

    <script src="<?= asset_url('assets/ext/fontawesome/js/fontawesome.min.js') ?>"></script>
    <script src="<?= asset_url('assets/ext/fontawesome/js/solid.min.js') ?>"></script>
    <style>
        #book-appointment-wizard,.frame-container {
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
        #select-time .form-group{display: none}
        #book-appointment-wizard #service-description{max-height: 36vh;}
        #book-appointment-wizard #header {
            background: rgb(255, 255, 255);
        }
        @media (max-width:768px){ {
            #book-appointment-wizard #company-name{margin-left: -185px;position: relative}
        }
    </style>
</head>
<body>
<div id="main" class="container">
    <div class="row wrapper">
        <div id="success-frame" class="col-12 my-auto frame-container">
            <div>
                <img id="success-icon" class="mt-0 mb-2" src="<?= base_url('assets/img/success.png') ?>"/>
            </div>

            <div>
                <h3><?= lang('appointment_registered') ?></h3>

                <p>
                    <?= lang('appointment_details_was_sent_to_you') ?>
                </p>

                <p>
                    <strong>
                        <?= lang('check_spam_folder') ?>
                    </strong>
                </p>

                <a href="<?= site_url() ?>" class="btn btn-success btn-large">
                    <i class="fas fa-calendar-alt"></i>
                    <?= lang('go_to_booking_page') ?>
                </a>

                <?php if (config('google_sync_feature')): ?>
                    <button id="add-to-google-calendar" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        <?= lang('add_to_google_calendar') ?>
                    </button>
                <?php endif ?>

                <?php if (isset($exceptions)): ?>
                    <div class="m-2">
                        <h4><?= lang('unexpected_issues') ?></h4>

                        <?php foreach ($exceptions as $exception): ?>
                            <?= exceptionToHtml($exception) ?>
                        <?php endforeach ?>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/ext/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/ext/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/ext/datejs/date.min.js') ?>"></script>
<script src="<?= asset_url('assets/ext/moment/moment.min.js') ?>"></script>
<script src="<?= asset_url('assets/ext/moment/moment-timezone-with-data.min.js') ?>"></script>
<script src="https://apis.google.com/js/client.js"></script>

<script>
    var GlobalVariables = {
        csrfToken: <?= json_encode($this->security->get_csrf_hash()) ?>,
        appointmentData: <?= json_encode($appointment_data) ?>,
        providerData: <?= json_encode($provider_data) ?>,
        customerData: <?= json_encode($customer_data) ?>,
        serviceData: <?= json_encode($service_data) ?>,
        companyName: <?= json_encode($company_name) ?>,
        googleApiKey: <?= json_encode(config('google_api_key')) ?>,
        googleClientId: <?= json_encode(config('google_client_id')) ?>,
        googleApiScope: 'https://www.googleapis.com/auth/calendar'
    };

    var EALang = <?= json_encode($this->lang->language) ?>;
</script>

<script src="<?= asset_url('assets/js/frontend_book_success.js') ?>"></script>
<script src="<?= asset_url('assets/js/polyfill.js') ?>"></script>
<script src="<?= asset_url('assets/js/general_functions.js') ?>"></script>

<?php google_analytics_script() ?>
</body>
</html>
