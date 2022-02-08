import React from "react";
import useApi from "./api";

var DOMAIN = "cards_lists";

/**
 * Get a list
 * @param {int} id 
 * @returns {Object}
 */
export async function getList(id)
{
    return await useApi(
        DOMAIN,
        "GET",
        null,
        id
    );
}

/**
 * 
 * @param {int} id 
 * @param {Object} content 
 * @returns {Object}
 */
export async function updateList(id, content)
{
    return await useApi(
        DOMAIN,
        "PATCH",
        content,
        id
    );
}
