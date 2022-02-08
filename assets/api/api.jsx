import React from "react";

function errorMessage()
{
    alert("An error occured, your list has not been saved. Please try later.");
}

/**
 * Use api platform
 * @param {string} domain 
 * @param {string} method 
 * @param {Object} data 
 * @returns {Object}
 */
export default async function useApi(domain, method, data = null, id = null)
{
    try {
        var url = "/api/" + domain;

        if (id)
        {
            url = url + "/" + id;    
        }

        var headers = {
            "Authorization": "Bearer " + document.getElementById("list").getAttribute("data-token"),
            "Accept": "application/ld+json",
        }

        if (method == "POST" || method == "DELETE")
        {
            headers["Content-Type"] = "application/json";
        }

        if (method == "PATCH")
        {
            headers["Content-Type"] = "application/merge-patch+json";
        }

        var requestInit = {
            method: method,
            headers: headers,
        }

        if (data)
        {
            requestInit['body'] = JSON.stringify(data);
        }

        var response = await fetch(url, requestInit)
        if (response.ok)
        {
            var responseData = await response.json();
            return responseData;    
        } else
        {
            errorMessage();
        }
    } catch (error)
    {
        errorMessage();
    }
}
