import React from "react";
import { updateCard } from "../api/cards";

export default function Card({card, removeCard})
{
    var handleChange = (e) => {
        card[e.target.name] = e.target.value;
        updateCard(card['id'], card);
    }

    return (
        <form onBlur={handleChange} onSubmit={(e) => {e.preventDefault()}}>
            <div className="card mt-3">
                <div className="card-body">
                    <div className="row">
                        <div className="col-md-6">
                            <label>Front value:</label>
                            <textarea className="form-control" defaultValue={card['frontValue']} name="frontValue" />
                        </div>
                        <div className="col-md-6">
                            <label>Back value:</label>
                            <textarea className="form-control" defaultValue={card['backValue']} name="backValue" />
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
        </form>
    )
}
