import React, { useEffect, useState } from "react";
import Learn from "./Learn";
import Summary from "./Summary";

export default function App({id})
{
    var [mode, setMode] = useState("summary");

    var onContinue = () => {
        setMode("learn");
    }

    var onFinishLearn = () => {
        setMode("summary");
    }

    return (
        <div>
            {mode == "learn" && <Learn listId={id} onFinishLearn={onFinishLearn} />}
            {mode == "summary" && <Summary listId={id} onContinue={onContinue} />}
        </div>
    )
}
