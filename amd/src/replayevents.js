// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/*
 * @package    logstore_xapi
 * @copyright  2020 Learning Pool Ltd <http://learningpool.com>
 * @author     Záborski László <laszlo.zaborski@learningpool.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['core/str', 'core/config', 'core/notification', 'core/templates', 'jquery', 'jqueryui'],
    function(str, mdlcfg, notification, templates, $) {

        /**
         * Store a sum.
         */
        var countedEvents = 0;

        /**
         * Store load HTML snippet.
         */
        var loadHTML = '';

        /**
         * Store replay HTML snippet.
         */
        var replayHTML = '';

        /**
         * Store done HTML snippet.
         */
        var doneHTML = '';

        /**
         * Store failed HTML snippet.
         */
        var failedHTML = '';

        /**
         * Store jquery selectors.
         */
        var SELECTORS = {
            CHECKBOXES: '#xapierrorlog_form .form-check-input',
            CHECKBOX_DATEFROM: '#xapierrorlog_form #id_datefrom_enabled',
            CHECKBOX_DATETO: '#xapierrorlog_form #id_dateto_enabled',
            PAGE_LINKS: '.pagination .page-item .page-link',
            RESEND_BUTTON: '#xapierrorlog_form #id_resendselected',
            REPLAY_EVENTS: '#xapierrorlog_data .reply-event',
            SELECTS: '#xapierrorlog_form .custom-select',
            SELECT_ERRORTYPE: '#xapierrorlog_form #id_errortype',
            SELECT_EVENTNAME: '#xapierrorlog_form #id_eventname',
            SELECT_RESPONSE: '#xapierrorlog_form #id_response',
            SELECT_DATAFROM: '#xapierrorlog_form #id_datefrom .custom-select',
            SELECT_DATATO: '#xapierrorlog_form #id_dateto .custom-select',
            SUBMIT_FORM: '#xapierrorlog_form #id_submitbutton',
        };

        /**
         * Added prefix to the replay event id.
         */
        var REPLAY_EVENT_ID_PREFIX = 'reply-event-id-';

        var replayevents = {

            /**
             * Initialisation method called by php js_call_amd()
             */
            init: function(counts) {
                countedEvents = counts;

                this.disableResend();
                this.updateResend();

                this.addReplyEvents();
             },

            /**
             * Register reply an individual event listeners.
             */
            addReplyEvents: function() {
                if($(SELECTORS.REPLAY_EVENTS).length==0) {
                    return;
                }
                this.generateLoadHTML();
                this.generateDoneHTML();
                this.generateFailedHTML();
                this.generateReplayHTML();
                this.registerReplyEventListeners();
            },

            /**
             * Register reply an individual event listeners.
             */
            registerReplyEventListeners: function() {
                var self = this;

                $(SELECTORS.REPLAY_EVENTS).click(function(e) {
                    e.stopPropagation();
                    e.preventDefault();

                    self.disableFormControls();
                    self.disablePagination();

                    var element = $(this);

                    element.off('click');
                    element.addClass('disabled');

                    var id = element.attr('id');
                    var eventId = id.replace(REPLAY_EVENT_ID_PREFIX, '');

                    self.doReplayEvent(eventId);
                });
            },

            /**
             * Replay an individual event using ajax.
             */
            doReplayEvent: function(eventId) {
                var url = mdlcfg.wwwroot + '/admin/tool/log/store/xapi/ajax/moveback_to_log.php';
                var eventIds = [eventId];
                var self = this;
                var element = $('#' + REPLAY_EVENT_ID_PREFIX + eventId);

                element.empty();
                element.append(loadHTML);
                element.removeClass('reply-event');

                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        'events': eventIds,
                        'sesskey': M.cfg.sesskey
                    },
                    success: function(data) {
                        element.empty();

                        if (data.success) {
                            element.append(doneHTML);
                        } else {
                            element.append(failedHTML);
                        }
                        self.enableFormControls();
                        self.enablePagination();
                    },
                    fail: function(ex) {
                        notification.exception(ex);

                        element.empty();
                        element.append(failedHTML);
                        self.enableFormControls();
                        self.enablePagination();
                    }
                });
            },

            /**
             * Update Resend button label.
             */
            updateResend: function() {
                var element = $(SELECTORS.RESEND_BUTTON);
                var self = this;

                str.get_strings([
                    {
                        key: 'resendevents',
                        component: 'logstore_xapi',
                        param: {
                            count: countedEvents
                        }
                    }
                ]).done(function(resend) {
                    element.attr('Value', resend);

                    if (countedEvents != 0) {
                        self.enableResend();
                    }
                });
            },

            /**
             * Disable given elements.
             */
            disableElements: function(elements) {
                elements.addClass("disabled");
                elements.attr("disabled", "disabled");
                elements.prop('disabled', true);
            },

            /**
             * Enable given elements.
             */
            enableElements: function(elements) {
                elements.removeClass("disabled");
                elements.prop('disabled', false);
                elements.removeAttr("disabled");
            },

            /**
             * Disable page links.
             */
            disablePagination: function() {
                if ($(SELECTORS.PAGE_LINKS).length == 0) {
                    return;
                }

                var elements = $(SELECTORS.PAGE_LINKS);

                this.disableElements(elements);
                elements.bind('click', function(e){
                    e.preventDefault();
                });
            },

            /**
             * Enable page links.
             */
            enablePagination: function() {
                if ($(SELECTORS.PAGE_LINKS).length == 0) {
                    return;
                }
                var elements = $(SELECTORS.PAGE_LINKS);

                this.enableElements(elements);
                elements.unbind('click');
            },

            /**
             * Disable Resend button.
             */
            disableResend: function() {
                this.disableElements($(SELECTORS.RESEND_BUTTON));
            },

            /**
             * Enable Resend button.
             */
            enableResend: function() {
                this.enableElements($(SELECTORS.RESEND_BUTTON));
            },

            /**
             * Disable submit form control.
             */
            disableReplyEvents: function() {
                $(SELECTORS.REPLAY_EVENTS).off('click');
                this.disableElements($(SELECTORS.REPLAY_EVENTS));
            },

            /**
             * Enable submit form control.
             */
            enableReplyEvents: function() {
                this.enableElements($(SELECTORS.REPLAY_EVENTS));
                this.registerReplyEventListeners();
            },

            /**
             * Disable submit form control.
             */
            disableFormSubmit: function() {
                this.disableElements($(SELECTORS.SUBMIT_FORM));
            },

            /**
             * Enable submit form control.
             */
            enableFormSubmit: function() {
                this.enableElements($(SELECTORS.SUBMIT_FORM));
            },

            /**
             * Disable form selects.
             */
            disableFormSelects: function() {
                this.disableElements($(SELECTORS.SELECTS));
            },

            /**
             * Enable form selects.
             */
            enableFormSelects: function() {
                this.enableElements($(SELECTORS.SELECT_ERRORTYPE));
                this.enableElements($(SELECTORS.SELECT_EVENTNAME));
                this.enableElements($(SELECTORS.SELECT_RESPONSE));

                if ($(SELECTORS.CHECKBOX_DATEFROM).is(':checked')) {
                    this.enableElements($(SELECTORS.SELECT_DATAFROM));
                }

                if ($(SELECTORS.CHECKBOX_DATETO).is(':checked')) {
                    this.enableElements($(SELECTORS.SELECT_DATATO));
                }
            },

            /**
             * Disable form checkboxes.
             */
            disableFormCheckboxes: function() {
                this.disableElements($(SELECTORS.CHECKBOXES));
            },

            /**
             * Enable form checkboxes.
             */
            enableFormCheckboxes: function() {
                this.enableElements($(SELECTORS.CHECKBOXES));
            },

            /**
             * Disable form controls.
             */
            disableFormControls: function() {
                this.disableFormSubmit();
                this.disableFormCheckboxes();
                this.disableFormSelects();
                this.disableResend();
            },

            /**
             * Disable form controls.
             */
            enableFormControls: function() {
                this.enableFormCheckboxes();
                this.enableFormSelects();
                this.enableFormSubmit();
                this.enableResend();
            },

            /**
             * Generate load icon.
             */
            generateLoadHTML : function(){
                str.get_strings([
                    {
                        key: 'loadinghelp',
                        component: 'moodle'
                    }
                ]).done(function(loadStr) {
                    loadHTML = '<span aria-hidden="true"' +
                        ' class="fa fa-spinner fa-spin fa-pulse"' +
                        ' title="' + loadStr + '"></span>' +
                        '<span class="sr-only">' + loadStr + '</span>';
                });
            },

            /**
             * Generate done icon.
             */
            generateDoneHTML : function(){
                str.get_strings([
                    {
                        key: 'success',
                        component: 'moodle'
                    }
                ]).done(function(doneStr) {
                    doneHTML = '<span aria-hidden="true"' +
                        ' class="fa fa-check"' +
                        ' title="' + doneStr + '"></span>' +
                        '<span class="sr-only">' + doneStr + '</span>';
                });
            },

            /**
             * Generate failed icon.
             */
            generateFailedHTML : function(){
                str.get_strings([
                    {
                        key: 'failed',
                        component: 'logstore_xapi'
                    }
                ]).done(function(failedStr) {
                    failedHTML = '<span aria-hidden="true"' +
                        ' class="fa fa-remove"' +
                        ' title="' + failedStr + '"></span>' +
                        '<span class="sr-only">' + failedStr + '</span>';
                });
            },

            /**
             * Generate replay icon.
             */
            generateReplayHTML : function(){
                str.get_strings([
                    {
                        key: 'replayevent',
                        component: 'logstore_xapi'
                    }
                ]).done(function(replayStr) {
                    replayHTML = '<span aria-hidden="true"' +
                        ' class="fa fa-repeat"' +
                        ' title="' + replayStr + '"></span>' +
                        '<span class="sr-only">' + replayStr +' </span>';
                    $(SELECTORS.REPLAY_EVENTS).append(replayHTML);
                });
            },
        };

        return replayevents;
});

