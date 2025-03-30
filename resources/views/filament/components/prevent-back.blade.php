<script>
(() => {
    function preventBack() {
        window.history.forward();
    }
    
    setTimeout(preventBack, 0);
    
    if (window.location.pathname.startsWith('/research-admin')) {
        // Clear history on research-admin panel
        window.history.pushState({}, '', window.location.href);
        window.onpopstate = function () {
            window.history.pushState({}, '', window.location.href);
            window.history.forward();
        };

        // Double-check prevention
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.history.forward();
            }
        });

        // Block programmatic history changes
        const originalPushState = window.history.pushState;
        window.history.pushState = function() {
            originalPushState.apply(this, arguments);
            window.history.forward();
        };
    }

    // Handle browser back button
    window.addEventListener('load', function() {
        window.history.pushState({}, '', window.location.href);
        window.history.pushState({}, '', window.location.href);
        window.addEventListener('popstate', function() {
            if (window.location.pathname.startsWith('/research-admin')) {
                window.history.forward();
            }
        });
    });

    // Handle beforeunload
    window.addEventListener('beforeunload', function() {
        if (window.location.pathname.startsWith('/research-admin')) {
            window.history.forward();
        }
    });
})();
</script>