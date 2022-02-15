import React from "react";
import useApi from "./api";

var DOMAIN = "cards";

/**
 * Update a card
 * @param {int} id 
 * @param {Object} content 
 * @returns {Object}
 */
export async function updateCard(id, content)
{
    return useApi(
        DOMAIN,
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
        "DELETE",
        null,
        id
    )
}

/**
 * Create a card
 * @param {Object} content 
 * @returns 
 */
export async function createCard(content)
{
    return useApi(
        DOMAIN,
        "POST",
        content,
        null
    )
}

export async function getCards(id)
{
    return useApi(
        DOMAIN + "/?cardsList.id=" + id,
        "GET",
        null,
        null,
    )
}
