(function () {
    const notices = document.querySelectorAll('.rsp-metricool-admin-notice');

    notices.forEach(function (notice) {
        const restUrl = notice.getAttribute('data-rest-url');
        const nonce = notice.getAttribute('data-nonce');
        const wpNonce = notice.getAttribute('data-wp-nonce');

        if (!restUrl || !nonce || !wpNonce) {
            return;
        }

        const buttons = notice.querySelectorAll('[data-notice-action]');

        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                const noticeAction = this.getAttribute('data-notice-action');
                if (!noticeAction) {
                    return;
                }

                fetch(restUrl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': wpNonce,
                    },
                    body: JSON.stringify({
                        nonce: nonce,
                        action: noticeAction,
                    }),
                }).then(function (response) {
                    if (response.ok) {
                        notice.style.display = 'none';
                    }
                });
            });
        });
    });
})();
