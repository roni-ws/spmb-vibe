(function() {
    var alertId = 'form-validation-alert';

    window.removeFormAlert = function() {
        var existing = document.getElementById(alertId);
        if (existing) existing.remove();
    };

    window.showFormAlert = function(form, errors) {
        if (!form || !errors || !errors.length) return;
        removeFormAlert();

        var items = '';
        for (var i = 0; i < errors.length; i++) {
            items += '<li>' + escapeHtml(errors[i]) + '</li>';
        }

        var alert = document.createElement('div');
        alert.id = alertId;
        alert.className = 'flex items-start gap-3 p-4 rounded-xl bg-secondary-container/15 border border-secondary/15 mb-md';
        alert.setAttribute('role', 'alert');
        alert.innerHTML =
            '<span class="material-symbols-outlined shrink-0 text-secondary mt-0.5" style="font-size:20px">warning</span>' +
            '<div class="flex-1 min-w-0">' +
            '<p class="font-semibold text-sm text-secondary">Terdapat kesalahan pada formulir</p>' +
            '<ul class="mt-1.5 text-sm text-on-secondary-container space-y-1 list-disc list-inside">' + items + '</ul>' +
            '</div>' +
            '<button type="button" class="shrink-0 text-on-secondary-container/70 hover:text-on-secondary-container transition-colors" onclick="this.closest(\'#' + alertId + '\').remove()">' +
            '<span class="material-symbols-outlined" style="font-size:16px">close</span>' +
            '</button>';

        form.parentNode.insertBefore(alert, form);
        alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
    };

    function escapeHtml(str) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }
})();
