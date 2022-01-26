import React from "react";

export default function Input({type="text", label, defaultValue, arrayKey, onChange, onBlur})
{
    return (
        <div className="mb-3">
            <label className="form-label">{label}:</label>
            {type == "text" && <input
                onBlur={onBlur}
                type="text"
                className="form-control"
                placeholder={label}
                defaultValue={defaultValue}
                onChange={onChange} />}
            {type == "textarea" && <textarea
                onBlur={onBlur}
                className="form-control"
                placeholder={label}
                defaultValue={defaultValue}
                onChange={onChange}></textarea>}
        </div>
    )
}
