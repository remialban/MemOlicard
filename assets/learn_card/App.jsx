import React, { useEffect, useState } from "react";
import { getCards, updateCard } from "../api/cards";
import { getList, updateList } from "../api/lists";
import Card from "./Card";
import Summary from "./Summary";

export default function App({id})
{
    var [cards, setCards] = useState([]);
    var [currentCycle, setCurrentCycle] = useState(1);
    var [currentCardIndex, setCurrentCardIndex] = useState(0);
    var [mode, setMode] = useState("card");
    var [list, setList] = useState({});

    var update = async () => {
        var list = await getList(id);
        setCurrentCycle(list['currentCycle'])
        var cards_copy = [];
        while (cards_copy.length == 0)
        {
            ((await getCards(id))['hydra:member']).forEach(element => {
                console.log(currentCycle)
                if (parseInt(list['currentCycle']) % element['currentBoxNumber'] == 0)
                {
                    cards_copy.push(element);
                }
            });
            setCards(cards_copy);
            setList(list);
            if (cards_copy.length == 0)
            {
                setCurrentCardIndex(0);
                var list_copy = list;
                list_copy['currentCycle'] = (parseInt(list_copy['currentCycle']) + 1).toString();
                setCurrentCycle(list_copy['currentCycle'])
                setList(list_copy);
                list = list_copy;
                updateList(list_copy['id'], list_copy)
                console.log(list);
            }
        }
    };

    useEffect(async () => {
        update();
    }, [])

    var nextCard = async () => {
        if (currentCardIndex + 1 < cards.length)
        {
            setCurrentCardIndex(currentCardIndex + 1)
        } else {
            setMode("summary");
            setCurrentCardIndex(0);
            var list_copy = list;
            list_copy['currentCycle'] = (parseInt(list_copy['currentCycle']) + 1).toString();
            setList(list_copy);
            updateList(list_copy['id'], list_copy);
        }
    };
    console.log(list);
    var onContinue = async () => {
        setMode("card");
    };

    return <div>
        {
            mode == "summary" && <Summary listId={id} onContinue={onContinue} />
        }
        {cards.length > 0 & mode == "card" ? <Card
            card={cards[currentCardIndex]}
            totalItem={cards.length}
            index={currentCardIndex}
            nextCard={nextCard} /> : "Loading ..."}
    </div>
}
