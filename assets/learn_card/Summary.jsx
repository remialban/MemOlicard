import React, { useEffect, useState } from "react";
import { getCards } from "../api/cards";
import { getList } from "../api/lists";
import Loading from "./Loading";

export default function Summary({listId, setLoading, onContinue})
{
    var [list, setList] = useState(undefined);
    var [cardsResume, setCardsResume] = useState([0,0,0]);
    var [loading, setLoading] = useState(true);

    useEffect(async () => {
        setLoading(true);
        var new_list = await getList(listId);
        setList(new_list);
        setLoading(false);
    }, [])

    useEffect(async () => {
        if (list == undefined)
        {
            return;
        }
        var resume_cards = [0, 0, 0];

        var cards = list['cards'];
        cards.forEach(card => {
            resume_cards[card['currentBoxNumber'] - 1] ++;
        });
        setCardsResume(resume_cards);
    }, [list]);

    return <div>
        {loading && <Loading />}
        {!loading && (
            <div className="card text-center">
                <div className="card-header">
                Summary
                </div>
                <div className="card-body">
                    <h5 className="card-title"></h5>
                    <div className="row fw-bold fs-4">
                        <div className="col-md-4 text-primary">
                            <span>Cards not learning</span>
                            <br />
                            {cardsResume[0]}
                        </div>
                        <div className="col-md-4 text-warning">
                            <span>Learning cards</span>
                            <br />
                            {cardsResume[1]}
                        </div>
                        <div className="col-md-4 text-success">
                            <span>Known cards</span>
                            <br />
                            {cardsResume[2]}
                        </div>
                    </div>
                    <br />
                    <button className="btn btn-primary" onClick={onContinue}>Continue to learn</button>
            </div>
        </div>
        )}
    </div>
}
