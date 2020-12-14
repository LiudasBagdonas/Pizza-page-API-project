'use strict';

const endpoints = {
    get: 'api/pizza/get',
    create: 'api/pizza/create',
    edit: 'api/pizza/edit',
    update: 'api/pizza/update',
    delete: 'api/pizza/delete'
};

/**
 * This defines how JS code selects elements by ID
 */
const selectors = {
    forms: {
        create: 'pizza-create-form',
        update: 'pizza-update-form'
    },
    modal: 'update-modal',
    grid: 'pizza-grid-container'
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
     * Create Form
     */
    create: {
        init: function () {
            if (this.getElement()) {
                this.getElement().addEventListener('submit', this.onSubmitListener);
            }
        },
        getElement: function () {
            let form =  document.getElementById(selectors.forms.create);
            if (!form) {
                console.log('Create form was not found');
            }

            return form;
        },
        onSubmitListener: function (e) {
            e.preventDefault();
            let formData = new FormData(e.target);
            formData.append('action', 'create');
            api(endpoints.create, formData, forms.create.success, forms.create.fail);
        },
        success: function (data) {
            const element = forms.create.getElement();

            grid.item.append(data);
            forms.ui.errors.hide(element);
            forms.ui.clear(element);
            forms.ui.flash.class(element, 'success');
        },
        fail: function (errors) {
            forms.ui.errors.show(forms.create.getElement(), errors);
        }
    },
    /**
     * Update Form
     */
    update: {
        init: function () {
            console.log('Initializing update form...');
            if (this.elements.form()) {
                this.elements.form().addEventListener('submit', this.onSubmitListener);

                const closeBtn = forms.update.elements.modal().querySelector('.close');
                closeBtn.addEventListener('click', forms.update.onCloseListener);
            }
        },
        elements: {
            form: function () {
                let form = document.getElementById(selectors.forms.update);

                if (!form) {
                    console.log('Update form was not found, check selector: ' + selectors.forms.update);
                }

                return form;
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
            let id = forms.update.elements.form().getAttribute('data-id');
            formData.append('id', id);
            formData.append('action', 'update');

            api(endpoints.update, formData, forms.update.success, forms.update.fail);
        },
        success: function (data) {
            grid.item.update(data);
            forms.update.hide();
        },
        fail: function (errors) {
            forms.ui.errors.show(forms.update.elements.form(), errors);
        },
        fill: function (data) {
            forms.ui.fill(forms.update.elements.form(), data);
        },
        onCloseListener: function (e) {
            forms.update.hide();
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
        },
        /**
         * Fills form fields with data
         * Each data index corelates with input name attribute
         *
         * @param {Element} form
         * @param {Object} data
         */
        fill: function (form, data) {
            console.log(data);
            form.setAttribute('data-id', data.id);

            Object.keys(data).forEach(data_id => {
                if (form[data_id]) {
                    const input = form.querySelector('input[name="' + data_id + '"]');
                    if (input) {
                        input.value = data[data_id];
                    }
                }
            });
        },
        clear: function (form) {
            var fields = form.querySelectorAll('[name]')
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
        let element = document.getElementById(selectors.grid);

        if (!element) {
            throw new Error('Error - Could not find grid in HTML. Check selector: ' + selectors.grid)
        }

        return element;
    },
    init: function () {
        this.data.load();

        Object.keys(this.buttons).forEach(buttonId => {
            grid.buttons[buttonId].init();
        });
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
            api(endpoints.get, null, this.success, this.fail);
        },
        success: function (data) {
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
            const item = document.createElement('div');

            if (data.id == null) {
                throw Error('There is no "ID" field in API response. Check controller!');
            }

            item.setAttribute('data-id', data.id);
            item.className = 'data-item';

            Object.keys(data).forEach(data_id => {
                switch (data_id) {
                    case 'image':
                        let img = document.createElement('img');
                        img.src = data[data_id];
                        item.append(img);
                        break;

                    case 'buttons':
                        let buttons = data[data_id];
                        Object.keys(buttons).forEach(button_id => {
                            let btn = document.createElement('button');
                            btn.innerHTML = buttons[button_id];
                            btn.className = button_id;
                            item.append(btn);
                        });
                        break;

                    default:
                        let span = document.createElement('span');
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
        },
        /**
         * Deletes existing item
         * @param {Integer} id
         */
        delete: function (id) {
            const item = grid.getElement().querySelector('.data-item[data-id="' + id + '"]');
            item.remove();
        }
    },
    buttons: {
        delete: {
            init: function () {
                if (grid.getElement()) {
                    grid.getElement().addEventListener('click', this.onClickListener);
                }
            },
            getElements: function () {
                return document.querySelectorAll('.delete-btn');
            },
            onClickListener: function (e) {
                // Listener is set on whole item, so we listen for which class button
                // has been pressed
                if (e.target.className === 'delete') {
                    let formData = new FormData();

                    let item = e.target.closest('.data-item');

                    formData.append('id', item.getAttribute('data-id'));
                    api(endpoints.delete, formData, grid.buttons.delete.success, grid.buttons.delete.fail);
                }
            },
            success: function (data) {
                grid.item.delete(data.id);
            },
            fail: function (errors) {
                alert(errors[0]);
            }
        },
        edit: {
            init: function () {
                console.log("Initializing edit button...");
                grid.getElement().addEventListener('click', this.onClickListener);
            },
            getElements: function () {
                return document.querySelectorAll('.edit-btn');
            },
            onClickListener: function (e) {
                if (e.target.className === 'edit') {
                    let formData = new FormData();

                    let item = e.target.closest('.data-item');

                    formData.append('id', item.getAttribute('data-id'));
                    api(endpoints.edit, formData, grid.buttons.edit.success, grid.buttons.edit.fail);
                }
            },
            success: function (api_data) {
                forms.update.show();
                forms.update.fill(api_data);
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
            forms[formId].init();
        });

        grid.init();
    }
};

// Launch App
app.init();