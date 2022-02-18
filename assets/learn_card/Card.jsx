import React, { useEffect, useState } from "react";
import { updateCard } from "../api/cards";

export default function Card({card, number, totalItem, nextCard})
{
    var [frontValue, setFrontValue] = useState("")
    var [backValue, setBackValue] = useState("")
    var [side, setSide] = useState("front");

    useState(() => {
        if (card['side'] == "front")
        {
            setFrontValue(card['frontValue']);
            setBackValue(card['backValue']);
        } else {
            setFrontValue(card['backValue']);
            setBackValue(card['frontValue']);
        }
    }, [number]);

    var onSubmit = async (isKnown) => {
        nextCard(isKnown);
    }

    return <div className="card text-center">
        <div className="card-header">
        {number}/{totalItem} studied in this cycle
        </div>
        <div className="card-body">
                <h5 className="card-title">{side == "front" ? frontValue : backValue}</h5>
                { side == "front" &&
                    <button className="btn btn-success btn-lg m-2" onClick={() => {setSide("back")}}>
                        Turn over
                    </button>
                }
                { side == "back" &&
                    <div>
                        <button className="btn btn-success btn-lg m-2" onClick={() => {onSubmit(true)}}>
                            <i className="bi bi-check-square-fill"></i>
                        </button>
                        <button className="btn btn-warning btn-lg m-2" onClick={() => {onSubmit(false)}}>
                            <i className="bi bi-x-square-fill"></i>
                        </button>
                    </div>
                    
                }
                
        </div>
    </div>
}
