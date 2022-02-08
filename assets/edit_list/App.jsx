import React, { useContext, useEffect, useReducer, useState } from "react";
import Input from "./Input";
import Cards from "./Cards";
import { getList, updateList } from "../api/lists";
import { createCard, deleteCard } from "../api/cards";

export default function App({id})
{
    var [isSaving, setSaving] = useState(false);

    var useApi = async () => {
        setCardsList({
            type: "set",
            value: await getList(id),
        });
    }

    var updateData = async () => {
        setSaving(true);
        await updateList(id, cardsList);
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
        await createCard({
            cardsList: cardsList['@id'],
            backValue: "",
            frontValue: ""
        });
        await useApi();
        setSaving(false);
    }

    var removeCard = async (id) => {
        setSaving(true);
        deleteCard(id);
        await useApi();
        setSaving(false);
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
            <Cards removeCard={removeCard} cardsList={cardsList} />
            <button className="btn btn-outline-primary btn-lg w-100 mt-3 mb-3" onClick={addCard}><i className="bi bi-plus-square"></i> Add card</button>

        </div>
    )
}
