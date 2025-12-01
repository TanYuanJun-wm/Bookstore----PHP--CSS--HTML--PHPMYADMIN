// ============================================================================
// General Functions
// ============================================================================



// ============================================================================
// Page Load (jQuery)
// ============================================================================

$(() => {
    
    // Autofocus
    $('form :input:not(button):first').focus();
    $('.err:first').prev().focus();
    $('.err:first').prev().find(':input:first').focus();
    
    // Confirmation message
    $('[data-confirm]').on('click', e => {
        const text = e.target.dataset.confirm || 'Are you sure?';
        if (!confirm(text)) {
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    });

    // Initiate GET request
    $('[data-get]').on('click', e => {
        e.preventDefault();
        const url = e.target.dataset.get;
        location = url || location;
    });

    // Initiate POST request
    $('[data-post]').on('click', e => {
        e.preventDefault();
        const url = e.target.dataset.post;
        const f = $('<form>').appendTo(document.body)[0];
        f.method = 'POST';
        f.action = url || location;
        f.submit();
    });

    // Reset form
    $('[type=reset]').on('click', e => {
        e.preventDefault();
        location = location;
    });

    // Auto uppercase
    $('[data-upper]').on('input', e => {
        const a = e.target.selectionStart;
        const b = e.target.selectionEnd;
        e.target.value = e.target.value.toUpperCase();
        e.target.setSelectionRange(a, b);
    });

    // Photo preview
    $('label.upload input[type=file]').on('change', e => {
        const f = e.target.files[0];
        const img = $(e.target).closest('label.upload').find('img')[0];

        if (!img) return;

        img.dataset.src ??= img.src;

        if (f?.type.startsWith('photos/')) {
            img.src = URL.createObjectURL(f);
        }
        else {
            img.src = img.dataset.src;
            e.target.value = '';
        }
    });

});

$(() => {
    $('#fake-payment-form').on('submit', function (e) {
        e.preventDefault(); // Prevent form submission until validation

        // Validate card name
        const cardName = $('#card_name').val();
        if (!cardName.trim()) {
            alert('Please enter the name on the card.');
            return;
        }

        // Validate card number (16 digits, no spaces, no letters)
        const cardNumber = $('#card_number').val();
        const cardRegex = /^[0-9]{16}$/; // Only 16 digits
        if (!cardRegex.test(cardNumber)) {
            alert('Invalid card number. Please enter exactly 16 digits.');
            return;
        }

        // Validate CVV (3 digits)
        const cvv = $('#cvv').val();
        const cvvRegex = /^[0-9]{3}$/; // 3 digits for CVV
        if (!cvvRegex.test(cvv)) {
            alert('Invalid CVV. Please enter a 3-digit CVV number.');
            return;
        }

        // Validate expiry date (must be a future date)
        const expiryDate = $('#expiry_date').val();
        const today = new Date();
        const [month, year] = expiryDate.split('-').map(num => parseInt(num));
        const expiryDateObj = new Date(year, month - 1);

        if (expiryDateObj < today) {
            alert('Card expiry date is in the past. Please enter a valid future date.');
            return;
        }

        // Proceed with fake payment if all validations pass
        if (confirm('Proceed with fake payment?')) {
            this.submit(); // Submit form to PHP
        } else {
            alert('Payment canceled.');
        }
    });

    // Move fade out info here, after form code
    setTimeout(function() {
        $('#info').fadeOut(500, function() {
            $(this).remove();
        });
    }, 3000);
});
