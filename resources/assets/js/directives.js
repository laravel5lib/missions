Vue.directive('tour-guide', {
    inserted(el, binding, vnode) {

        function topScrollHandler (element){
            if (element) {
                let $element = window.jQuery(element);
                let topOfElement = $element.offset().top;
                let heightOfElement = $element.height();
                window.jQuery('html, body').animate({
                    scrollTop: topOfElement - heightOfElement
                }, {
                    duration: 1000
                });
            }
        }

        function storeTourRecord () {
            let completed = JSON.parse(localStorage.getItem('ToursCompleted')) || [];
            completed.push(location.pathname);
            localStorage.setItem('ToursCompleted', JSON.stringify(_.uniq(completed)));
        }

        let tour = window.tour = new Shepherd.Tour({
            defaults: {
                classes: 'shepherd-element shepherd-open shepherd-theme-arrows step-class',
                scrollTo: true,
                scrollToHandler: topScrollHandler,
                showCancelLink: true
            }
        });

        tour.addStep('intro', {
            title: 'Hello!',
            text: 'This guided tour will walk you through the features on this page. Take this tour anytime by clicking the <i class="fa fa-question-circle-o"></i> Tour link. Shall we begin?',
            showCancelLink: false,
            buttons: [
                {
                    text: 'Not Now',
                    action: tour.cancel,
                    classes: 'shepherd-button-secondary'
                },
                {
                    text: 'Continue',
                    action: tour.next
                }
            ]
        });

        // if pageSteps exists, add them to tour
        if (window.pageSteps && window.pageSteps.length) {
            _.each(window.pageSteps, (step) => {
                // if buttons are present
                if (step.buttons) {
                    _.each(step.buttons, (button) => {
                        // if action is present
                        if (button.action && _.isString(button.action))
                            button['action'] = tour[button.action];
                    });
                }
                tour.addStep(step);
            })
        }

        tour.on('cancel', storeTourRecord);

        tour.on('complete',storeTourRecord)
    },
    update() {
        // debugger
    },
    unbind() {
    }
});

/**
 * This directive handles client-side and server-side errors.
 * It expects an object expression with three values:
 * { value: fieldValue, client: 'clientSideField', server: 'serverSideField' }
 *
 * PROPERTIES
 * The `value` property expects the actual field value to stay reactive.
 * The `client` property expects the handle that the validator plugin uses
 * for validation.
 * The `server` property expects the handle that the server request rules use
 * for validation.
 * The `scope` property expects a string if provided, this is used to scope
 * field validation.
 * If the `handle` property is present, client and server properties will be
 * set to this.
 *
 * OPTIONAL PROPERTIES
 * The `class` property allows you to set the error class to be set during
 * validation.
 * The `messages` property allows you to customize the error message the user
 * sees based on validators i.e (`req` - required, `min` - minlength, `max` -
 * maxlength, `email` - custom email validator)
 *
 * When server-side validation errors are returned to the `this.errors`
 * object, this handle references the property for the field deep: true,
 */
Vue.directive('error-handler', {
    inserted(el, binding, vnode) {
        el.dataset.messages = JSON.stringify([]);

        vnode.context.$watch('errors', (val) => {
            let storedValue = el.dataset.storage !== undefined ? JSON.parse(el.dataset.storage) : binding.value;
            // The `attemptSubmit` variable delays validation until necessary, because this doesn't directly influence
            // the directive we want to watch it using the error-handler mixin
            if (storedValue) {
                vnode.context.handleValidationClass(el, storedValue);
                vnode.context.handleValidationMessages(el, storedValue);
            }
        }, { deep: true });


    },
    update(el, binding, vnode, oldVnode) {
        // If server value is identical to client, use 'handle' property for simplicity
        if(binding.value.handle) {
            binding.value.client = binding.value.handle;
            binding.value.server = binding.value.handle;
        }

        // Store the value within the directive to be used outside the update function
        el.dataset.storage = JSON.stringify(binding.value);

        // Handle error class and messages on element
        // vnode.context.handleValidationClass(el, vnode.context, binding.value);
        // vnode.context.handleValidationMessages(el, vnode.context, binding.value, vnode.context.errors);
    }
});
