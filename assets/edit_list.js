import React from "react";
import ReactDOM from "react-dom";
import App from "./edit_list/App.jsx";

var element = document.getElementById("list");

ReactDOM.render(
    <App id={element.getAttribute("data-id")} token={element.getAttribute("data-token")} />,
    element
);    
