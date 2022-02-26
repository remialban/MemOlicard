require("./turbolinks");

document.addEventListener("turbolinks:load", function() {
    require("./edit_list").run();
    require("./learn_list").run();
    require("./flash_messages").run();
});
