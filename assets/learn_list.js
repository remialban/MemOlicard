import React from "react";
import ReactDOM from "react-dom";
import App from "./learn_card/App";

export function run()
{
    var element = document.getElementById("learn-list");
    if (element != null)
    {
        ReactDOM.render(
            <App id={element.getAttribute("data-id")} />,
            element
        );
    }
}
