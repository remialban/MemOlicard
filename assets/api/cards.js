import useApi from "./api";

var DOMAIN = "cards";

/**
 * Update a card
 * @param {int} id 
 * @param {Object} content 
 * @param {string} token 
 * @returns {Object}
 */
export async function updateCard(id, content, token)
{
    return useApi(
        DOMAIN,
        token,
        "PATCH",
        content,
        id
    )
}

/**
 * Delete a card
 * @param {int} id 
 * @returns {Object}
 */
export async function deleteCard(id)
{
    return useApi(
        DOMAIN,
        token,
        "DELETE",
        null,
        id
    )
}

export async function createCard(id, content, token)
{
    return useApi(
        DOMAIN,
        token,
        "POST",
        content,
        null
    )
}
