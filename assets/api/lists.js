import useApi from "./api";

var DOMAIN = "cards_lists";

/**
 * Get a list
 * @param {int} id 
 * @returns {Object}
 */
export async function getList(id, token)
{
    return await useApi(
        DOMAIN,
        token,
        "GET",
        null,
        id
    );
}

/**
 * 
 * @param {int} id 
 * @param {string} token 
 * @param {Object} content 
 * @returns {Object}
 */
export async function updateList(id, token, content)
{
    return await useApi(
        DOMAIN,
        token,
        "PATCH",
        content,
        id
    );
}
