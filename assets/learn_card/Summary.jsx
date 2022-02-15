import React, { useEffect, useState } from "react";
import { getCards } from "../api/cards";

export default function Summary({listId, onContinue})
{
    var messages = [
        "Well done ! You are making progress!",
        "Continue like that!",
        "Just a little more effort!",
        "Maybe you deserve a break!"
    ];

    var [progress, setProgress] = useState([0, 0, 0]);

    var [cards, setCards] = useState([]);

    useEffect(async () => {
        var cards = (await getCards(listId))['hydra:member'];
        setCards(cards);
    }, []);

    useEffect(() => {
        var progress = [0, 0, 0];
        for (var i = 1; i < 4; i++) {
            cards.forEach(card => {
                if (card['currentBoxNumber'] == i)
                {
                    progress[i-1] = progress[i-1] + 1;
                }
            });
        }

        setProgress(progress);
    }, [cards])
    return <div className="card text-center">
        <div className="card-header">
        Summary
        </div>
        <div className="card-body">
                <h5 className="card-title">{messages[Math.floor(Math.random()*messages.length)]}</h5>
                <div className="row fw-bold fs-4">
                    <div className="col-md-4 text-primary">
                        <span>Cards not learning</span>
                        <br />
                        {progress[0]}
                    </div>
                    <div className="col-md-4 text-warning">
                        <span>Learning cards</span>
                        <br />
                        {progress[1]}
                    </div>
                    <div className="col-md-4 text-success">
                        <span>Known cards</span>
                        <br />
                        {progress[2]}
                    </div>
                </div>
                <button className="btn btn-primary" onClick={() => {onContinue()}}>Continue to learn</button>
        </div>
    </div>
}
