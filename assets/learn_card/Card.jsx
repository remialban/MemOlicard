import React, { useEffect, useState } from "react";
import { updateCard } from "../api/cards";

export default function Card({card, index, totalItem, nextCard})
{
    var [isTurnedOver, setIsTurnedOver] = useState(false);

    useEffect(() => {
        setIsTurnedOver(false);
    }, [card, index]);

    var onSubmit = async (isKnow) => {
        if (isKnow)
        {
            if (card['currentBoxNumber'] < 3)
            {
                card['currentBoxNumber'] = card['currentBoxNumber'] + 1;
            }
        } else {
            if (card['currentBoxNumber'] > 1)
            {
                card['currentBoxNumber'] = card['currentBoxNumber'] - 1;
            }
        }

        if (card['side'] == 'front')
        {
            card['side'] = 'back';
        } else {
            card['side'] = 'front'
        }

        var currentDate = new Date();
        card['movedAt'] = "" + currentDate.getFullYear() + "-" + currentDate.getMonth() + "-" + currentDate.getDate() + "T" + currentDate.getHours() + ":" + currentDate.getMinutes() + ":" + currentDate.getSeconds() + "+00:00";
        updateCard(card['id'], card);
        nextCard();
    }

    return <div className="card text-center">
        <div className="card-header">
        {index + 1}/{totalItem} studied in this cycle
        </div>
        {
            !isTurnedOver ? <div className="card-body">
                <h5 className="card-title">{card['side'] == 'front' ? card['frontValue'] : card['backValue']}</h5>
                <button className="btn btn-success btn-lg m-2" onClick={() => {setIsTurnedOver(true)}}>
                    Turn over
                </button>
            </div> : <div className="card-body">
                <h5 className="card-title">{card['side'] == 'back' ? card['frontValue'] : card['backValue']}</h5>
                <button className="btn btn-success btn-lg m-2" onClick={() => {onSubmit(true)}}>
                    <i className="bi bi-check-square-fill"></i>
                </button>
                <button className="btn btn-warning btn-lg m-2" onClick={() => {onSubmit(false)}}>
                    <i className="bi bi-x-square-fill"></i>
                </button>
            </div>
        }
    </div>
}
