$(document).ready(function() {
    const $saveButton = $('form[name="category"] .card-footer .btn-primary');
    if ($saveButton.length) {
        const $previewButton = $(`<a target="_blank" href="${previewButtonUrl}" class="btn btn-secondary" id="category-preview">${previewButtonText}</a>`);
        $saveButton.before($previewButton);
    }
});