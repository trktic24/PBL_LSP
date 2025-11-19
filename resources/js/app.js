import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;
Alpine.store("sidebar", {
    open: false,
});

Alpine.start();
