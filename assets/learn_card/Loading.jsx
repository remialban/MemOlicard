import React from "react";

export default function Loading()
{
    return (
        <div className="card text-center">
            <div className="card-header">
                Loading
            </div>
            <div className="card-body">
                <h5 className="card-title">The page is loading</h5>
                <div className="spinner-border" style={{"width": "50px", height: "50px"}} role="status">
                    <span className="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    )
}
