import type {GetShiftsDTO} from "~/dtos/shifts/getShiftsDTOs";
import {deleteCookieAndRedirect, globalSessionId} from "~/utils/cookies";

export const getShifts = async (fromDate: Date, toDate: Date): Promise<GetShiftsDTO> => {

    const response = await fetch(`${process.env.SERVER_URL}/api/shifts/get-shifts?fromDate=${fromDate.toLocaleDateString()}&toDate=${toDate.toLocaleDateString()}`, {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            "Authorization": globalSessionId,
        }
    });


    if (!response.ok) {
        if (response.status === 401) {
            await deleteCookieAndRedirect();
        }
    }
    
    return await response.json();
}
    