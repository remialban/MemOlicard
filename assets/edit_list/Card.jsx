import React from "react";
import Input from "./Input";

export default function Card({card, index, setCardsList, updateCard, removeCard})
{
    var onChangeBackValue = (e) => {
        setCardsList({
            type: "edit_card",
            value: e.target.value,
            index: index,
            name: "backValue"
        });
    }
    var onChangeFrontValue = (e) => {
        setCardsList({
            type: "edit_card",
            value: e.target.value,
            index: index,
            name: "frontValue"
        });
    }

    return (
        <div className="card mt-3">
            <div className="card-body">
                <div className="row">
                    <div className="col-md-6">
                        <Input
                            type="textarea"
                            label="Front value"
                            defaultValue={card['frontValue']}
                            arrayKey={'frontValue'}
                            onChange={onChangeFrontValue}
                            onBlur={() => updateCard(card)}
                             />
                    </div>
                    <div className="col-md-6">
                        <Input
                            type="textarea"
                            label="Back value"
                            defaultValue={card['backValue']}
                            arrayKey={'backValue'} 
                            onChange={onChangeBackValue}
                            onBlur={() => updateCard(card)}
                            />
                    </div>
                    <div className="col-12">
                        <button
                            className="btn btn-sm btn-danger"
                            onClick={() => removeCard(card['id'])}>
                                <i className="bi bi-trash"></i> Delete card
                        </button>
                    </div>
                </div>
            </div>
        </div>
    )
}
