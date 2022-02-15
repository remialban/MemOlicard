import React from "react";
import ReactDOM from "react-dom";
import App from "./learn_card/App";

document.addEventListener("turbolinks:load", function() {
    var element = document.getElementById("learn-list");
    if (element != null)
    {
        ReactDOM.render(
            <App id={element.getAttribute("data-id")} />,
            element
        );
    }
});
