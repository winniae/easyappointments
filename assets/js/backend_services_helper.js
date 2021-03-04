/* ----------------------------------------------------------------------------
 * Easy!Appointments - Open Source Web Scheduler
 *
 * @package     EasyAppointments
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) 2013 - 2020, Alex Tselegidis
 * @license     http://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        http://easyappointments.org
 * @since       v1.0.0
 * ---------------------------------------------------------------------------- */

(function () {

    'use strict';

    /**
     * ServicesHelper
     *
     * This class contains the methods that will be used by the "Services" tab of the page.
     *
     * @class ServicesHelper
     */
    function ServicesHelper() {
        this.filterResults = {};
        this.filterLimit = 20;
    }

    ServicesHelper.prototype.bindEventHandlers = function () {
        var instance = this;

        /**
         * Event: Filter Services Form "Submit"
         *
         * @param {jQuery.Event} event
         */
        $('#services').on('submit', '#filter-services form', function (event) {
            event.preventDefault();
            var key = $('#filter-services .key').val();
            $('#filter-services .selected').removeClass('selected');
            instance.resetForm();
            instance.filter(key);
        });

        /**
         * Event: Filter Service Cancel Button "Click"
         */
        $('#services').on('click', '#filter-services .clear', function () {
            $('#filter-services .key').val('');
            instance.filter('');
            instance.resetForm();
        });

        /**
         * Event: Filter Service Row "Click"
         *
         * Display the selected service data to the user.
         */
        $('#services').on('click', '.service-row', function () {
            if ($('#filter-services .filter').prop('disabled')) {
                $('#filter-services .results').css('color', '#AAA');
                return; // exit because we are on edit mode
            }

            var serviceId = $(this).attr('data-id');

            var service = instance.filterResults.find(function (filterResult) {
                return Number(filterResult.id) === Number(serviceId);
            });

            // Add dedicated provider link.
            var dedicatedUrl = GlobalVariables.baseUrl + '/index.php?service=' + encodeURIComponent(service.id);
            var $link = $('<a/>', {
                'href': dedicatedUrl,
                'html': [
                    $('<span/>', {
                        'class': 'fas fa-link'
                    })
                ]
            });

            $('#services .record-details h3')
                .find('a')
                .remove()
                .end()
                .append($link);

            instance.display(service);
            $('#filter-services .selected').removeClass('selected');
            $(this).addClass('selected');
            $('#edit-service, #delete-service').prop('disabled', false);
        });

        /**
         * Event: Add New Service Button "Click"
         */
        $('#services').on('click', '#add-service', function () {
            instance.resetForm();
            $('#services .add-edit-delete-group').hide();
            $('#services .save-cancel-group').show();
            $('#services .record-details')
                .find('input, select, textarea')
                .prop('disabled', false);
            $('#filter-services button').prop('disabled', true);
            $('#filter-services .results').css('color', '#AAA');

            // Default values
            $('#service-name').val('Service');
            $('#service-duration').val('30');
            $('#service-price').val('0');
            $('#service-currency').val('');
            $('#service-category').val('null');
            $('#service-availabilities-type').val('flexible');
            $('#service-attendants-number').val('1');
        });

        /**
         * Event: Cancel Service Button "Click"
         *
         * Cancel add or edit of a service record.
         */
        $('#services').on('click', '#cancel-service', function () {
            var id = $('#service-id').val();
            instance.resetForm();
            if (id !== '') {
                instance.select(id, true);
            }
        });

        /**
         * Event: Save Service Button "Click"
         */
        $('#services').on('click', '#save-service', function () {
            var start = $('#service-start-datetime').datepicker('getDate');
            var startDatetime = start ? start.toString('yyyy-MM-dd 00:00:00') : null;
            var end = $('#service-end-datetime').datepicker('getDate');
            var endDatetime = end? end.toString('yyyy-MM-dd 23:59:59') : null;

            var service = {
                name: $('#service-name').val(),
                duration: $('#service-duration').val(),
                price: $('#service-price').val(),
                currency: $('#service-currency').val(),
                description: $('#service-description').val(),
                location: $('#service-location').val(),
                availabilities_type: $('#service-availabilities-type').val(),
                attendants_number: $('#service-attendants-number').val(),
                attendants_per_booking: $('#service-attendants-per-booking').val(),
                start_datetime: startDatetime,
                end_datetime: endDatetime
            };

            if ($('#service-category').val() !== 'null') {
                service.id_service_categories = $('#service-category').val();
            } else {
                service.id_service_categories = null;
            }

            if ($('#service-id').val() !== '') {
                service.id = $('#service-id').val();
            }

            if (!instance.validate()) {
                return;
            }

            instance.save(service);
        });

        /**
         * Event: Edit Service Button "Click"
         */
        $('#services').on('click', '#edit-service', function () {
            $('#services .add-edit-delete-group').hide();
            $('#services .save-cancel-group').show();
            $('#services .record-details')
                .find('input, select, textarea')
                .prop('disabled', false);
            $('#filter-services button').prop('disabled', true);
            $('#filter-services .results').css('color', '#AAA');
        });

        /**
         * Event: Delete Service Button "Click"
         */
        $('#services').on('click', '#delete-service', function () {
            var serviceId = $('#service-id').val();
            var buttons = [
                {
                    text: EALang.cancel,
                    click: function () {
                        $('#message-box').dialog('close');
                    }
                },
                {
                    text: EALang.delete,
                    click: function () {
                        instance.delete(serviceId);
                        $('#message-box').dialog('close');
                    }
                }
            ];

            GeneralFunctions.displayMessageBox(EALang.delete_service,
                EALang.delete_record_prompt, buttons);
        });
    };

    /**
     * Remove the previously registered event handlers.
     */
    ServicesHelper.prototype.unbindEventHandlers = function () {
        $('#services')
            .off('submit', '#filter-services form')
            .off('click', '#filter-services .clear')
            .off('click', '.service-row')
            .off('click', '#add-service')
            .off('click', '#cancel-service')
            .off('click', '#save-service')
            .off('click', '#edit-service')
            .off('click', '#delete-service');
    };

    /**
     * Save service record to database.
     *
     * @param {Object} service Contains the service record data. If an 'id' value is provided
     * then the update operation is going to be executed.
     */
    ServicesHelper.prototype.save = function (service) {
        var url = GlobalVariables.baseUrl + '/index.php/backend_api/ajax_save_service';

        var data = {
            csrfToken: GlobalVariables.csrfToken,
            service: JSON.stringify(service)
        };

        $.post(url, data)
            .done(function (response) {
                Backend.displayNotification(EALang.service_saved);
                this.resetForm();
                $('#filter-services .key').val('');
                this.filter('', response.id, true);
            }.bind(this));
    };

    /**
     * Delete a service record from database.
     *
     * @param {Number} id Record ID to be deleted.
     */
    ServicesHelper.prototype.delete = function (id) {
        var url = GlobalVariables.baseUrl + '/index.php/backend_api/ajax_delete_service';

        var data = {
            csrfToken: GlobalVariables.csrfToken,
            service_id: id
        };

        $.post(url, data)
            .done(function () {
                Backend.displayNotification(EALang.service_deleted);

                this.resetForm();
                this.filter($('#filter-services .key').val());
            }.bind(this));
    };

    /**
     * Validates a service record.
     *
     * @return {Boolean} Returns the validation result.
     */
    ServicesHelper.prototype.validate = function () {
        $('#services .has-error').removeClass('has-error');
        $('#services .form-message')
            .removeClass('alert-danger')
            .hide();

        try {
            // validate required fields.
            var missingRequired = false;

            $('#services .required').each(function (index, requiredField) {
                if (!$(requiredField).val()) {
                    $(requiredField).closest('.form-group').addClass('has-error');
                    missingRequired = true;
                }
            });

            if (missingRequired) {
                throw new Error(EALang.fields_are_required);
            }

            return true;
        } catch (error) {
            $('#services .form-message')
                .addClass('alert-danger')
                .text(error.message)
                .show();
            return false;
        }
    };

    /**
     * Resets the service tab form back to its initial state.
     */
    ServicesHelper.prototype.resetForm = function () {
        $('#filter-services .selected').removeClass('selected');
        $('#filter-services button').prop('disabled', false);
        $('#filter-services .results').css('color', '');

        $('#services .record-details')
            .find('input, select, textarea')
            .val('')
            .prop('disabled', true);
        $('#services .record-details h3 a').remove();

        $('#services .add-edit-delete-group').show();
        $('#services .save-cancel-group').hide();
        $('#edit-service, #delete-service').prop('disabled', true);

        $('#services .record-details .has-error').removeClass('has-error');
        $('#services .record-details .form-message').hide();

        // reset and initialize datepicker
        var dateFormat;
        var startDatetime = null;
        var endDatetime = null;

        switch (GlobalVariables.dateFormat) {
            case 'DMY':
                dateFormat = 'dd/mm/yy';
                break;
            case 'DDMY':
                dateFormat = 'dd.mm.yy';
                break;
            case 'MDY':
                dateFormat = 'mm/dd/yy';
                break;
            case 'YMD':
                dateFormat = 'yy/mm/dd';
                break;
            default:
                throw new Error('Invalid GlobalVariables.dateFormat value.');
        }

        var firstWeekDay = GlobalVariables.firstWeekday;
        var firstWeekDayNumber = GeneralFunctions.getWeekDayId(firstWeekDay);

        $('#service-start-datetime').datepicker({
            dateFormat: dateFormat,

            // Translation
            dayNames: [EALang.sunday, EALang.monday, EALang.tuesday, EALang.wednesday,
                EALang.thursday, EALang.friday, EALang.saturday],
            dayNamesShort: [EALang.sunday.substr(0, 3), EALang.monday.substr(0, 3),
                EALang.tuesday.substr(0, 3), EALang.wednesday.substr(0, 3),
                EALang.thursday.substr(0, 3), EALang.friday.substr(0, 3),
                EALang.saturday.substr(0, 3)],
            dayNamesMin: [EALang.sunday.substr(0, 2), EALang.monday.substr(0, 2),
                EALang.tuesday.substr(0, 2), EALang.wednesday.substr(0, 2),
                EALang.thursday.substr(0, 2), EALang.friday.substr(0, 2),
                EALang.saturday.substr(0, 2)],
            monthNames: [EALang.january, EALang.february, EALang.march, EALang.april,
                EALang.may, EALang.june, EALang.july, EALang.august, EALang.september,
                EALang.october, EALang.november, EALang.december],
            prevText: EALang.previous,
            nextText: EALang.next,
            currentText: EALang.now,
            closeText: EALang.close,
            firstDay: firstWeekDayNumber
        });
        $('#service-start-datetime').datepicker('setDate', startDatetime);

        $('#service-end-datetime').datepicker({
            dateFormat: dateFormat,

            // Translation
            dayNames: [EALang.sunday, EALang.monday, EALang.tuesday, EALang.wednesday,
                EALang.thursday, EALang.friday, EALang.saturday],
            dayNamesShort: [EALang.sunday.substr(0, 3), EALang.monday.substr(0, 3),
                EALang.tuesday.substr(0, 3), EALang.wednesday.substr(0, 3),
                EALang.thursday.substr(0, 3), EALang.friday.substr(0, 3),
                EALang.saturday.substr(0, 3)],
            dayNamesMin: [EALang.sunday.substr(0, 2), EALang.monday.substr(0, 2),
                EALang.tuesday.substr(0, 2), EALang.wednesday.substr(0, 2),
                EALang.thursday.substr(0, 2), EALang.friday.substr(0, 2),
                EALang.saturday.substr(0, 2)],
            monthNames: [EALang.january, EALang.february, EALang.march, EALang.april,
                EALang.may, EALang.june, EALang.july, EALang.august, EALang.september,
                EALang.october, EALang.november, EALang.december],
            prevText: EALang.previous,
            nextText: EALang.next,
            currentText: EALang.now,
            closeText: EALang.close,
            firstDay: firstWeekDayNumber
        });
        $('#service-end-datetime').datepicker('setDate', endDatetime);
    };

    /**
     * Display a service record into the service form.
     *
     * @param {Object} service Contains the service record data.
     */
    ServicesHelper.prototype.display = function (service) {
        $('#service-id').val(service.id);
        $('#service-name').val(service.name);
        $('#service-duration').val(service.duration);
        $('#service-price').val(service.price);
        $('#service-currency').val(service.currency);
        $('#service-description').val(service.description);
        $('#service-location').val(service.location);
        $('#service-availabilities-type').val(service.availabilities_type);
        $('#service-attendants-number').val(service.attendants_number);
        $('#service-attendants-per-booking').val(service.attendants_per_booking);

        // Set the start and end datetime of the appointment.
        var startDatetime = Date.parseExact(service.start_datetime, 'yyyy-MM-dd HH:mm:ss');
        $('#service-start-datetime').datepicker('setDate', startDatetime);

        var endDatetime = Date.parseExact(service.end_datetime, 'yyyy-MM-dd HH:mm:ss');
        $('#service-end-datetime').datepicker('setDate', endDatetime);

        var categoryId = (service.id_service_categories !== null) ? service.id_service_categories : 'null';
        $('#service-category').val(categoryId);
    };

    /**
     * Filters service records depending a string key.
     *
     * @param {String} key This is used to filter the service records of the database.
     * @param {Number} selectId Optional, if set then after the filter operation the record with this
     * ID will be selected (but not displayed).
     * @param {Boolean} display Optional (false), if true then the selected record will be displayed on the form.
     */
    ServicesHelper.prototype.filter = function (key, selectId, display) {
        display = display || false;

        var url = GlobalVariables.baseUrl + '/index.php/backend_api/ajax_filter_services';

        var data = {
            csrfToken: GlobalVariables.csrfToken,
            key: key,
            limit: this.filterLimit
        };

        $.post(url, data)
            .done(function (response) {
                this.filterResults = response;

                $('#filter-services .results').empty();

                response.forEach(function (service, index) {
                    $('#filter-services .results')
                        .append(ServicesHelper.prototype.getFilterHtml(service))
                        .append($('<hr/>'))
                });

                if (response.length === 0) {
                    $('#filter-services .results').append(
                        $('<em/>', {
                            'text': EALang.no_records_found
                        })
                    );
                } else if (response.length === this.filterLimit) {
                    $('<button/>', {
                        'type': 'button',
                        'class': 'btn btn-block btn-outline-secondary load-more text-center',
                        'text': EALang.load_more,
                        'click': function () {
                            this.filterLimit += 20;
                            this.filter(key, selectId, display);
                        }.bind(this)
                    })
                        .appendTo('#filter-services .results');
                }

                if (selectId) {
                    this.select(selectId, display);
                }
            }.bind(this));
    };

    /**
     * Get Filter HTML
     *
     * Get a service row HTML code that is going to be displayed on the filter results list.
     *
     * @param {Object} service Contains the service record data.
     *
     * @return {String} The HTML code that represents the record on the filter results list.
     */
    ServicesHelper.prototype.getFilterHtml = function (service) {
        var name = service.name;

        var info = service.duration + ' min - ' + service.price + ' ' + service.currency;

        return $('<div/>', {
            'class': 'service-row entry',
            'data-id': service.id,
            'html': [
                $('<strong/>', {
                    'text': name
                }),
                $('<br/>'),
                $('<span/>', {
                    'text': info
                }),
                $('<br/>')
            ]
        });
    };

    /**
     * Select a specific record from the current filter results. If the service id does not exist
     * in the list then no record will be selected.
     *
     * @param {Number} id The record id to be selected from the filter results.
     * @param {Boolean} display Optional (false), if true then the method will display the record on the form.
     */
    ServicesHelper.prototype.select = function (id, display) {
        display = display || false;

        $('#filter-services .selected').removeClass('selected');

        $('#filter-services .service-row[data-id="' + id + '"]').addClass('selected');

        if (display) {
            var service = this.filterResults.find(function (filterResult) {
                return Number(filterResult.id) === Number(id);
            });

            this.display(service);

            $('#edit-service, #delete-service').prop('disabled', false);
        }
    };

    window.ServicesHelper = ServicesHelper;
})();
