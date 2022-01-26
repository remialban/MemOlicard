import React from "react";
import Card from "./Card";

export default function Cards({cardsList, setCardsList, updateCard, removeCard})
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
                                setCardsList={setCardsList}
                                updateCard={updateCard}
                                removeCard={removeCard} />
                })
            
            }
        </div>
    )
}
