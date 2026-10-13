export function setupBookingForm() {
    const form = document.querySelector('[data-booking-form]');
    if (!form) return;
    form.noValidate = true;

    const feedback = form.querySelector('[data-form-feedback]');
    const submit = form.querySelector('[type="submit"]');
    const date = form.querySelector('[name="preferred_date"]');
    date.min = new Date(Date.now() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 10);

    const showFeedback = (message, type) => {
        feedback.textContent = message;
        feedback.className = `form-feedback ${type}`;
        feedback.hidden = false;
    };
    const showFieldError = (field, message) => {
        field.setAttribute('aria-invalid', 'true');
        const error = document.getElementById(`${field.name}-error`);
        if (error) error.textContent = message;
    };
    const clearFieldErrors = () => {
        form.querySelectorAll('[aria-invalid]').forEach((field) => field.removeAttribute('aria-invalid'));
        form.querySelectorAll('.field-error').forEach((error) => { error.textContent = ''; });
    };
    const validate = () => {
        clearFieldErrors();
        let valid = true;
        form.querySelectorAll('[required]').forEach((field) => {
            if (!field.checkValidity()) {
                showFieldError(field, field.type === 'checkbox' ? 'Please confirm your consent.' : 'Please complete this field.');
                valid = false;
            }
        });
        const mobile = form.querySelector('[name="mobile"]');
        if (!/^(?:09\d{9}|\+639\d{9})$/.test(mobile.value.replace(/[\s\-()]/g, ''))) {
            showFieldError(mobile, 'Enter a Philippine mobile number starting with 09 or +63.');
            valid = false;
        }
        const email = form.querySelector('[name="email"]');
        if (email.value && !email.checkValidity()) {
            showFieldError(email, 'Enter a valid email address.');
            valid = false;
        }
        if (date.value && date.value < date.min) {
            showFieldError(date, 'Choose today or a future date.');
            valid = false;
        }
        if (!valid) form.querySelector('[aria-invalid="true"]')?.focus();
        return valid;
    };

    form.addEventListener('submit', async (event) => {
        if (!validate()) {
            event.preventDefault();
            showFeedback('Please check the highlighted fields.', 'error');
            return;
        }
        if (form.dataset.local !== 'true') return;

        event.preventDefault();
        submit.disabled = true;
        showFeedback('Sending your request…', '');
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: { Accept: 'application/json', 'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value },
                body: new FormData(form),
            });
            const result = await response.json();
            if (!response.ok) {
                if (result.errors) {
                    Object.entries(result.errors).forEach(([name, messages]) => {
                        const field = form.elements.namedItem(name);
                        if (field) showFieldError(field, messages[0]);
                    });
                }
                throw new Error(response.status === 429 ? 'Too many requests. Please try again shortly.' : result.message || 'We could not send your request. Please try again.');
            }
            form.reset();
            showFeedback(result.message, 'success');
        } catch (error) {
            showFeedback(error.message || 'We could not send your request. Please try again.', 'error');
        } finally {
            submit.disabled = false;
        }
    });
}
