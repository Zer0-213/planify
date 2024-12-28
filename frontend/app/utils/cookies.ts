import {createCookie} from "react-router";
import serverHelper from "~/utils/fetchHelper/severHelper";

export async function checkAuthCookie(
    request: Request
): Promise<boolean> {
    const cookie = request.headers.get("Cookie");
    const sessionId = await authToken.parse(cookie);

    if (!sessionId) {
        return false;
    }

    serverHelper.authToken = sessionId;
    return true;
}

export const authToken = createCookie("authToken", {
    sameSite: "lax",
    httpOnly: true,
    secure: true,
});
