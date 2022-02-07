import React from "react";
import Card from "./Card";

export default function Cards({cardsList, setCardsList, removeCard, token})
{
    var cards = cardsList['cards'] || [];
    return (
        <div>
            {
                cards.map((card, index) => {
                    return <Card
                                key={card['@id']}
                                card={card}
                                index={index} 
                                token={token}
                                setCardsList={setCardsList}
                                removeCard={removeCard} />
                })
            
            }
        </div>
    )
}
