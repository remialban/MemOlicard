import React, { useEffect, useState } from  "react";
import { updateCard } from "../api/cards";
import { getList, updateList } from "../api/lists";
import Card from "./Card";
import Loading from "./Loading";

export default function Learn({listId, onFinishLearn})
{
    var [list, setList] = useState(undefined);
    var [cards, setCards] = useState(undefined);
    var [loading, setLoading] = useState(true);
    var [currentIndex, setCurrentIndex] = useState(0)

    useEffect(async () => {
        setLoading(true);
        var new_list = await getList(listId);
        setList(new_list);
    }, [])
    

    useEffect(async () => {
        if (list == undefined)
        {
            return;
        }
        var classifiedCards = [];

        while (true)
        {
            var copy_cards = list['cards'];
        
            for (var i = 1; i < 4; i++) {
                copy_cards.forEach(card => {
                    if (card['currentBoxNumber'] == i && parseInt(list['currentCycle']) % i == 0)
                    {
                        classifiedCards.push(card)
                    }
                });
            }
            if (classifiedCards.length == 0)
            {
                var list_copy = list;
                list_copy['currentCycle'] = (parseInt(list_copy['currentCycle']) + 1).toString();
                await updateList(list_copy['id'], list_copy);
            } else
            {
                break;
            }
        }
        
        setCards(classifiedCards);
        setLoading(false);
    }, [list])

    var nextCard = async (isKnown) => {
        var card = cards[currentIndex];

        if (card['side'] == "front")
        {
            card['side'] = "back";
        } else
        {
            card['back'] = "front";
        }

        if (isKnown)
        {
            if (card['currentBoxNumber'] < 3)
            {
                card['currentBoxNumber'] ++;
            }
        } else
        {
            if (card['currentBoxNumber'] > 1)
            {
                card['currentBoxNumber'] --;
            }
        }

        if (currentIndex + 1 < cards.length)
        {
            setLoading(true);
            await updateCard(card['id'], card);
            setCurrentIndex(currentIndex + 1);
            setLoading(false);
        } else
        {
            var list_copy = list;
            list_copy['currentCycle'] = (parseInt(list_copy['currentCycle']) + 1).toString();
            setLoading(true);
            await updateCard(card['id'], card);
            await updateList(list_copy['id'], list_copy);
            onFinishLearn();
        }
    }

    return <div>
        { loading && <Loading /> }
        { !loading && <Card card={cards[currentIndex]} number={currentIndex + 1} totalItem={cards.length} nextCard={nextCard} />}
    </div>
}
