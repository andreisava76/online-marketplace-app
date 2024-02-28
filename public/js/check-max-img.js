$(document).ready(() => {
    const max_file_number = 6,
        // Define your form id or class or just tag.
        $form = $('#listing-form'),
        // Define your upload field class or id or tag.
        $file_upload = $('#image_upload', $form),
        // Define your submit class or id or tag.
        $button = $('#submit', $form);

    // Disable submit button on page ready.
    $button.prop('disabled', 'disabled');

    $file_upload.on('change', function () {
        const number_of_images = $(this)[0].files.length;
        if (number_of_images > max_file_number) {
            alert(`Poti sa uploadezi maxim ${max_file_number} fisiere.`);
            $(this).val('');
            $button.prop('disabled', 'disabled');
        } else {
            $button.prop('disabled', false);
        }
    });
})
