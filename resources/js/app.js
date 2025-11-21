import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;
const initialSidebarState = localStorage.getItem("sidebar_open") === "true";

Alpine.store("sidebar", {
    open: initialSidebarState,

    toggle() {
        this.open = !this.open;
        localStorage.setItem("sidebar_open", this.open);
    },

    setOpen(value) {
        this.open = value;
        localStorage.setItem("sidebar_open", value);
    },
});
Alpine.start();
