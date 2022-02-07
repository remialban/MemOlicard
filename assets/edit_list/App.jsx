import React, { useEffect, useReducer, useState } from "react";
import Input from "./Input";
import Cards from "./Cards";
import { getList, updateList } from "../api/lists";

export default function App({id, token})
{
    var [isSaving, setSaving] = useState(false);

    var useApi = async () => {
        setCardsList({
            type: "set",
            value: await getList(id, token),
        });
    }

    var updateData = async () => {
        setSaving(true);
        await updateList(id, token, cardsList);
        setSaving(false);
    }

    var [cardsList, setCardsList] = useReducer(function (state, action) {
        if (action.type == "set")
        {
            return action.value;
        }
        if (action.type == "edit_attribute")
        {
            var state_copy = state;
            state_copy[action.name] = action.value;
            return state_copy;
        }
        if (action.type == "edit_card")
        {
            var state_copy = state;
            var cards_copy = state_copy['cards'].slice();
            cards_copy[action.index][action.name] = action.value;
            state_copy['cards'] = cards_copy;
            return state_copy;
        }
    }, {})

    var onChangeValue = (e) => {
        setCardsList({
            type: "edit_attribute",
            name: "name",
            value: e.target.value,
        })
    }

    var addCard = async () => {
        setSaving(true);
        var url = "/api/cards";
        try {
            var response = await fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/ld+json",
                    "Authorization": "Bearer " + token,
                },
                body: JSON.stringify({
                    cardsList: cardsList['@id'],
                    backValue: "",
                    frontValue: ""
                }),
            });
            if (response.ok)
            {
                useApi();
            } else {
                alert("An error occured, your list has not been saved. Please try later.");
            }
        } catch (error) {
            alert("An error occured, your list has not been saved. Please try later.");
        }
    }

    var removeCard = async (id) => {
        setSaving(true);
        var url = "/api/cards/" + id;
        try {
            var response = await fetch(url, {
                method: "DELETE",
                headers: {
                    "Authorization": "Bearer " + token,
                },
            });
            if (response.ok)
            {
                useApi();
            } else {
                alert("An error occured, your list has not been saved. Please try later.");
            }
        } catch (error) {
            alert("An error occured, your list has not been saved. Please try later.");
        }
    }

    useEffect(() => {
        useApi()
    }, [])
    
    return (
        <div>
            {isSaving 
                ? (<div>
                <div className="spinner-border spinner-border-sm" role="status">
                    <span className="visually-hidden">Saving in progress ...</span>
                </div> Saving in progress ...
                </div>)
                : <div>All changes have been saved</div>
            }            
            <Input label="Name" defaultValue={cardsList['name']} arrayKey="name" onChange={onChangeValue} onBlur={updateData} />
            <h2>Cards:</h2>
            <Cards removeCard={removeCard} cardsList={cardsList} setCardsList={setCardsList} token={token} />
            <button className="btn btn-outline-primary btn-lg w-100 mt-3 mb-3" onClick={addCard}><i className="bi bi-plus-square"></i> Add card</button>

        </div>
    )
}
