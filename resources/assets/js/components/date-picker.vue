<template>
	<div class='input-group date' :class="{'input-group-sm': inputSm}">
		<span class='input-group-addon' v-if="addon" v-text="addon"></span>
		<input class="datepicker-input form-control" type="text" :placeholder="placeholder" :value="formattedValue" v-model="formattedValue" @keyup,change="emitValue">
		<span class='input-group-addon' v-if="!inline">
			<i class='fa fa-fw fa-calendar'></i>
		</span>
	</div>
</template>
<style>
	.bootstrap-datetimepicker-widget {
		z-index: 99;
        text-align:center;
        color: #2d2d2d !important;
	}
</style>
<script type="text/javascript">
	import $ from 'jquery';
	import moment from 'moment';
    /**
     * The array of names of the tooltip messages of the datetime picker.
     *
     * This is a constant and should not be modified.
     */
    const DATETIME_PICKER_TOOLTIPS = [
        "today", "clear", "close",
        "selectMonth", "prevMonth", "nextMonth",
        "selectYear", "prevYear", "nextYear",
        "selectDecade", "prevDecade", "nextDecade",
        "prevCentury", "nextCentury",
        "pickHour", "incrementHour", "decrementHour",
        "pickMinute", "incrementMinute", "decrementMinute",
        "pickSecond", "incrementSecond", "decrementSecond",
        "togglePeriod", "selectTime"
    ];

    /**
     * The default language used by this component.
     */
    const DEFAULT_LANGUAGE = "en-US";

    /**
     * A datetime picker control.
     *
     * @param value
     *    the value bind to the control, which must be a two way binding variable.
     * @param type
     *    the optional type of this datetime picker control. Available values are
     *    - "datetime": Indicating that this control is a datetime picker,
     *    - "date": Indicating that this control is a date picker,
     *    - "time": Indicating that this control is a time picker.
     *    Default value is "datetime".
     * @param language
     *    the optional language code used to localize the control, which must be
     *    a valid language code supported by the moment.js library. If it is not set,
     *    and the [vue-i18n](https://github.com/Haixing-Hu/vue-i18n) plugin is used,
     *    the component will use the language code `$language` provided by the
     *    [vue-i18n](https://github.com/Haixing-Hu/vue-i18n) plugin; otherwise, the
     *    component will use the default value "en-US".
     * @param datetimeFormat
     *    the optional format of the datetime this component should display, which
     *    must be a valid datetime format of the moment.js library. This property
     *    only works when the "type" property is "datetime". Default value is
     *    "YYYY-MM-DD HH:mm:ss".
     * @param dateFormat
     *    the optional format of the date this component should display, which
     *    must be a valid datetime format of the moment.js library. This property
     *    only works when the "type" property is "date". Default value is
     *    "YYYY-MM-DD".
     * @param timeFormat
     *    the optional format of the time this component should display, which
     *    must be a valid datetime format of the moment.js library. This property
     *    only works when the "type" property is "time". Default value is
     *    "HH:mm:ss".
     * @param name
     *    the optional name of the selection control.
     * @param onChange
     *    the optional event handler triggered when the value of the datetime picker
     *    was changed. If this parameter is presented and is not null, it must be a
     *    function which accept one argument: the new date time, which is a moment
     *    object.
     */

	export default {
		name: 'date-picker',
//        replace: true,
//        inherit: false,
        props: {
            value: {
                required: true,
            },
	        inline: {
                type: Boolean,
		        default: false
	        },
            inputSm: {
                type: Boolean,
		        default: false
	        },
            type: {
                type: String,
                required: false,
                default: "datetime"
            },
            placeholder: {
                type: String,
                required: false,
            },
            addon: {
                type: String,
                required: false,
                default: ""
            },
            language: {
                type: String,
                required: false,
                default: ""
            },
            datetimeFormat: {
                type: String,
//                required: false,
                default: "YYYY-MM-DD HH:mm:ss"
            },
            dateFormat: {
                type: String,
//                required: false,
                default: "YYYY-MM-DD"
            },
            timeFormat: {
                type: String,
//                required: false,
                default: "HH:mm:ss"
            },
	        viewFormat: {
                type: Array,
		        default() { return ['']}
	        },
            name: {
                type: String,
                required: false,
                default: ""
            },
	        minDate: {
                type: [String, Boolean],
                validator (value) {
                    return value !== true;
                },
                default: false,
	        },
	        maxDate: {
                type: [String, Boolean],
                validator (value) {
                    return value !== true;
                },
		        default: false,
	        },
        },
        data() {
            return {
                control: null,
                formattedValue: null,
            }
        },
	    computed: {},
        watch: {
		    value(val, oldVal){
		        if (val !== oldVal) {
                    this.formattedValue = this.momentFilter(val, this.viewFormat[0] || false, this.viewFormat[1] || false, this.viewFormat[2] || false)
                }
		    },
            minDate(val, oldVal) {
				if (this.control)
                    this.control.minDate(val)
            },
            maxDate(val, oldVal) {
                if (this.control)
                    this.control.maxDate(val)
            },
        },
        methods: {
            /**
             * Gets the language code from the "language-country" locale code.
             *
             * The function will strip the language code before the first "-" of a
             * locale code. For example, pass "en-US" will returns "en". But for some
             * special locales, the function reserves the locale code. For example,
             * the "zh-CN" for the simplified Chinese and the "zh-TW" for the
             * traditional Chinese.
             *
             * @param locale
             *    A locale code.
             * @return
             *    the language code of the locale.
             */
            getLanguageCode(locale) {
                if (locale === null || locale.length === 0) {
                    return "en";
                }
                if (locale.length <= 2) {
                    return locale;
                } else {
                    switch (locale) {
                        case "zh-CN":
                        case "zh-TW":
                        case "ar-MA":
                        case "ar-SA":
                        case "ar-TN":
                        case "de-AT":
                        case "en-AU":
                        case "en-CA":
                        case "en-GB":
                        case "fr-CA":
                        case "hy-AM":
                        case "ms-MY":
                        case "pt-BR":
                        case "sr-CYRL":
                        case "tl-PH":
                        case "tzm-LATN":
                        case "tzm":
                            return locale.toLowerCase();
                        default:
                            // reserve only the first two letters language code
                            return locale.substr(0, 2);
                    }
                }
            },
	        momentFilter(val, format, diff = false, noLocal = false) {
                if (!format) {
                    switch (this.type) {
                        case "date":
                            format = this.dateFormat;
                            break;
                        case "time":
                            format = this.timeFormat;
                            break;
                        case "datetime":
                        default:
                            format = this.datetimeFormat;
                            break;
                    }
                }
                let date;
		        if (!val) return val;

		        if (noLocal) {
		            return moment(val).startOf('minute').format(format); // do not convert to local
		        }

		        if (diff) {
		            date = moment.utc(val).startOf('minute').local().fromNow();
		        } else {
                    date = moment.utc(val, format).startOf('minute').local().format(format);
                }

		        return date;
		    },
            emitValue (event) {
                this.$emit('input', $(event.currentTarget).val());
            },
        },
        created() {
            this.control = null;
        },
        mounted() {
            // console.debug("datetime-picker.ready");
            let options = {
                useCurrent: false,
                showClear: true,
                showClose: false,
	            inline: this.inline,
	            minDate: this.minDate,
	            maxDate: this.maxDate,
                icons: {
                    time: 'fa fa-clock-o',
                    date: 'fa fa-calendar',
                    up: 'fa fa-chevron-up',
                    down: 'fa fa-chevron-down',
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-dot-circle-o',
                    clear: 'fa fa-trash',
                    close: 'fa fa-times'
                }
            };
            // set the locale
            let language = this.language;
            if (language === null || language === "") {
                if (this.$language) {
                    language = this.$language;
                } else {
                    language = DEFAULT_LANGUAGE;
                }
            }
            options.locale = this.getLanguageCode(language);
            // set the format
            switch (this.type) {
                case "date":
                    options.format = this.dateFormat;
                    break;
                case "time":
                    options.format = this.timeFormat;
                    break;
                case "datetime":
                default:
                    options.format = this.datetimeFormat;
                    options.extraFormats = ['YYYY-MM-DD HH:mm:ss', 'LLL'];
                    break;
            }
            // use the vue-i18n plugin for localize the tooltips
            if (this.$i18n && this.$i18n.datetime_picker) {
                let messages = this.$i18n.datetime_picker;
                let tooltips = $.fn.datetimepicker.defaults.tooltips;
                for (let i = 0; i < DATETIME_PICKER_TOOLTIPS.length; ++i) {
                    let name = DATETIME_PICKER_TOOLTIPS[i];
                    if (messages[name]) {
                        tooltips[name] = messages[name];    // localize
                    }
                }
                options.tooltips = tooltips;
            }
            // create the control
            $(this.$el).datetimepicker(options);
            this.control = $(this.$el).data("DateTimePicker");
            // set the date to the current value of the value
            this.control.date(this.value);
//            var me = this;
            $(this.$el).on("dp.change", () => {
                this.formattedValue = this.momentFilter(this.control.date(), this.viewFormat[0] || false, this.viewFormat[1] || false, this.viewFormat[2] || false);
                this.$emit('input', this.formattedValue);
            });
        },
	};
</script>