import "./bootstrap";

document.addEventListener("turbo:load", function () {
    require("./disable_turbolinks_for_the_forms").run();
    require("./edit_list").run();
    require("./learn_list").run();
    require("./flash_messages").run();  
});
