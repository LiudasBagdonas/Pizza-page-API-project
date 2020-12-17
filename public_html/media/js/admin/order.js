'use strict';

const endpoints = {
    get: 'api/pizza/get',
    create: 'api/pizza/create',
    edit: 'api/pizza/edit',
    update: 'api/pizza/update',
    delete: 'api/pizza/delete',
    order: '/api/order/create',
    order_edit: '/api/order/edit'
};

/**
 * This defines how JS code selects elements by ID
 */
const selectors = {
    forms: {
        status: 'status-edit-form'
    },
    modal: 'update-modal',
    grid: 'admin-orders-table-container'
}

/**
 * Executes API request
 * @param {type} url Endpoint URL
 * @param {type} formData instance of FormData
 * @param {type} success Success callback
 * @param {type} fail Fail callback
 * @returns {undefined}
 */
function api(url, formData, success, fail) {
    fetch(url, {
        method: 'POST',
        body: formData
    }).then(response => response.json())
        .then(obj => {
            if (obj.status === 'success') {
                success(obj.data);
            } else {
                fail(obj.errors);
            }
        })
        .catch(e => {
            if (e.toString() == 'SyntaxError: Unexpected token < in JSON at position 0') {
                fail(['Problem is with your API controller, did not return JSON! Check Chrome->Network->XHR->Response']);
            } else {
                fail([e.toString()]);
            }
        });
}

/**
 * Form array
 * Contains all form-related functionality
 *
 * Object forms
 */
const forms = {

    /**
     * Update Form
     */
    status: {
        init: function () {
            if (this.elements.form()) {
                this.elements.form().addEventListener('submit', this.onSubmitListener);

                const closeBtn = forms.status.elements.modal().querySelector('.close');
                closeBtn.addEventListener('click', forms.status.onCloseListener);
                return true;
            }

            return false;
        },
        elements: {
            form: function () {
                return document.getElementById(selectors.forms.status);
            },
            modal: function () {
                let modal = document.getElementById(selectors.modal);

                if (!modal) {
                    throw Error('Update modal was not found, check selector: ' + selectors.modal);
                }

                return modal;
            }
        },
        onSubmitListener: function (e) {
            e.preventDefault();
            let formData = new FormData(e.target);
            let id = forms.status.elements.form().getAttribute('data-id');
            formData.append('id', id);
            formData.append('action', 'update');

            api(endpoints.order_edit, formData, forms.status.success, forms.status.fail);
        },
        success: function (data) {
            grid.item.update(data);
            forms.status.hide();
        },
        fail: function (errors) {
            forms.ui.errors.show(forms.status.elements.form(), errors);
        },
        fill: function (data) {
            console.log(data)
            forms.ui.fill(forms.status.elements.form(), data);
        },
        onCloseListener: function (e) {
            console.log('onCloseListener')
            forms.status.hide();
        },
        show: function () {
            this.elements.modal().style.display = 'block';
        },
        hide: function () {
            this.elements.modal().style.display = 'none';
        }
    },
    /**
     * Common/Universal Form UI Functions
     */
    ui: {
        init: function () {
            // Function has to exist
            // since we're calling init() for
            // all elements withing forms object
            return true;
        },
        /**
         * Fills form fields with data
         * Each data index corelates with input name attribute
         *
         * @param {Element} form
         * @param {Object} data
         */
        fill: function (form, data) {
            console.log(form)
            console.log('Filling form fields with:', data);
            form.setAttribute('data-id', data.id);

            Object.keys(data).forEach(data_id => {
                if (form[data_id]) {
                    const input = form.querySelector('select[name="' + data_id + '"]');
                    if (input) {
                        console.log(data.status)
                        input.setAttribute('value', data.status);
                    } else {
                        console.log('Could not fill field ' + data_id + 'because it wasn`t found in form');
                    }
                }
            });
        },
        clear: function (form) {
            let fields = form.querySelectorAll('[name]')
            fields.forEach(field => {
                field.value = '';
            });
        },
        flash: {
            class: function (element, class_name) {
                const prev = element.className;

                element.className += class_name;
                setTimeout(function () {
                    element.className = prev;
                }, 1000);
            }
        },
        /**
         * Form-error related functionality
         */
        errors: {
            /**
             * Shows errors in form
             * Each error index correlates with input name attribute
             *
             * @param {Element} form
             * @param {Object} errors
             */
            show: function (form, errors) {
                this.hide(form);

                console.log('Form errors received', errors);

                Object.keys(errors).forEach(function (error_id) {
                    const field = form.querySelector('input[name="' + error_id + '"]');
                    if (field) {
                        const span = document.createElement("span");
                        span.className = 'field-error';
                        span.innerHTML = errors[error_id];
                        field.parentNode.append(span);

                        console.log('Form error in field: ' + error_id + ':' + errors[error_id]);
                    }
                });
            },
            /**
             * Hides (destroys) all errors in form
             * @param {type} form
             */
            hide: function (form) {
                const errors = form.querySelectorAll('.field-error');
                if (errors) {
                    errors.forEach(node => {
                        node.remove();
                    });
                }
            }
        }
    }
};

/**
 * Table-related functionality
 */
const grid = {
    getElement: function () {
        return document.getElementById(selectors.grid);
    },
    init: function () {
        if (this.getElement()) {
            this.data.load();

            Object.keys(this.buttons).forEach(buttonId => {
                console.log(buttonId)
                let success = grid.buttons[buttonId].init();
                console.log('Setting up button listeners "' + buttonId + '": ' + (success ? 'PASS' : 'FAIL'));
            });

            return true;
        }

        return false;
    },
    /**
     * Data-Related functionality
     */
    data: {
        /**
         * Loads data and populates grid from API
         * @returns {undefined}
         */
        load: function () {
            console.log('Grid: Calling API to get data...');
            api(endpoints.order_edit, null, this.success, this.fail);
        },
        success: function (data) {
            console.log(data)
            Object.keys(data).forEach(i => {
                grid.item.append(data[i]);
            });
        },
        fail: function (errors) {
            console.log(errors);
        }
    },
    /**
     * Operations with items
     */
    item: {
        /**
         * Builds item element from data
         *
         * @param {Object} data
         * @returns {Element}
         */
        build: function (data) {
            console.log(data)
            const item = document.createElement('tr');

            if (data.id == null) {
                throw Error('JS can`t build the item, because API data doesn`t contain its ID. Check API controller!');
            }

            item.setAttribute('data-id', data.id);
            item.className = 'data-item';

            Object.keys(data).forEach(data_id => {
                switch (data_id) {
                    // case 'image':
                    //     let img = document.createElement('img');
                    //     img.src = data[data_id];
                    //     item.append(img);
                    //     break;
                    case 'headers':
                        let header = document.createElement('td');
                        header = data[data_id];
                        item.append(header);
                        break;
                    case 'buttons':
                        let buttons = data[data_id];
                        Object.keys(buttons).forEach(button_id => {
                            let btn = document.createElement('button');
                            console.log(buttons)
                            btn.innerHTML = buttons[button_id];
                            btn.className = button_id;
                            item.append(btn);
                        });
                        break;

                    default:
                        let span = document.createElement('td');
                        span.innerHTML = data[data_id];
                        span.className = data_id;
                        item.append(span);
                }
            });

            return item;
        },
        /**
         * Appends item to grid from data
         *
         * @param {Object} data
         */
        append: function (data) {
            console.log('Grid: Creating item in grid container from ', data);
            grid.getElement().append(this.build(data));
        },
        /**
         * Updates existing item in grid from data
         * Row is selected via "id" index in data
         *
         * @param {Object} data
         */
        update: function (data) {
            let item = grid.getElement().querySelector('.data-item[data-id="' + data.id + '"]');
            item.replaceWith(this.build(data));
            //row = this.build(data);
        }
    },
    // Buttons are declared on whole grid, not on each item individually, so
    // onClickListeners dont duplicate
    buttons: {
        edit: {
            init: function () {
                if (grid.getElement()) {
                    grid.getElement().addEventListener('click', this.onClickListener);
                    return true;
                }

                return false;
            },
            onClickListener: function (e) {
                if (e.target.className === 'edit') {
                    let formData = new FormData();
                    let item = e.target.closest('.data-item');
                    console.log('Edit button clicked on', item);

                    formData.append('id', item.getAttribute('data-id'));
                    api(endpoints.order_edit, formData, grid.buttons.edit.success, grid.buttons.edit.fail);
                }
            },
            success: function (api_data) {
                forms.status.show();
//fill() function has to send particular row
                forms.status.fill(api_data);
            },
            fail: function (errors) {
                alert(errors[0]);
            }
        }
    }
};

/**
 * Core page functionality
 */
const app = {
    init: function () {
        // Initialize all forms
        Object.keys(forms).forEach(formId => {
            let success = forms[formId].init();
            console.log(forms[formId])
            console.log('Initializing form "' + formId + '": ' + (success ? 'SUCCESS' : 'FAIL'));
        });

        console.log('Initializing grid...');
        let success = grid.init();
        console.log('Grid: Initialization: ' + (success ? 'PASS' : 'FAIL'));
    }
};

// Launch App
app.init();