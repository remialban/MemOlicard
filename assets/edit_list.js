import React from "react";
import ReactDOM from "react-dom";
import App from "./edit_list/App.jsx";

export function run() {
    var element = document.getElementById("list");

    if (element != null)
    {
        ReactDOM.render(
            <App id={element.getAttribute("data-id")} />,
            element
        ); 
    }
}
