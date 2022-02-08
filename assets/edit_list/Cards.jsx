import React from "react";
import Card from "./Card";

export default function Cards({cardsList, removeCard})
{
    var cards = cardsList['cards'] || [];
    return (
        <div>
            {
                cards.map((card) => {
                    return <Card
                                key={card['@id']}
                                card={card}
                                removeCard={removeCard} />
                })
            }
        </div>
    )
}
