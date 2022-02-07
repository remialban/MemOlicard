/**
 * Use api platform
 * @param {string} domain 
 * @param {string} token 
 * @param {string} method 
 * @param {Object} data 
 * @returns {Object}
 */
export default async function useApi(domain, token, method, data = null, id = null)
{
    var url = "/api/" + domain;

    if (id)
    {
        url = url + "/" + id;    
    }
    console.log(url + method)
    var headers = {
        "Authorization": "Bearer " + token,
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

    var responseData = await response.json();
    return responseData;
}
