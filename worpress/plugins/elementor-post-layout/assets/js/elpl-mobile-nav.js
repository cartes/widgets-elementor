/**
 * ELPL Mobile Nav – accordion behaviour
 * Handles the ⊕/⊖ toggle for menu items with submenus.
 */
(function () {
    'use strict';

    function initMobileNav(root) {
        root.querySelectorAll('.elpl-mnav-toggle').forEach(function (toggle) {
            function handleToggle(e) {
                // Allow clicks on the label <a> to navigate normally
                if (e.target && e.target.classList.contains('elpl-mnav-label')) {
                    return;
                }
                e.preventDefault();

                var expanded = toggle.getAttribute('aria-expanded') === 'true';
                var submenu  = toggle.nextElementSibling;

                toggle.setAttribute('aria-expanded', String(!expanded));
                toggle.classList.toggle('elpl-mnav-open', !expanded);

                if (submenu) {
                    if (expanded) {
                        submenu.setAttribute('hidden', '');
                    } else {
                        submenu.removeAttribute('hidden');
                    }
                }
            }

            toggle.addEventListener('click', handleToggle);
            toggle.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    handleToggle(e);
                }
            });
        });
    }

    // Init on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.elpl-mnav').forEach(function (nav) {
            initMobileNav(nav);
        });
    });

    // Re-init when Elementor frontend renders a widget (editor preview)
    if (window.elementorFrontend) {
        window.elementorFrontend.hooks.addAction('frontend/element_ready/elpl_mobile_nav.default', function ($scope) {
            initMobileNav($scope[0]);
        });
    }
})();
