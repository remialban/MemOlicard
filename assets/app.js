require("./turbolinks");

document.addEventListener("turbolinks:load", function() {
    require("./edit_list");
    require("./learn_list");
    require("./flash_messages");
});
