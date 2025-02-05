import {createCookie, redirect} from "react-router";

export let globalSessionId: string = "";

export const cookieStore = createCookie("SessionId", {
    httpOnly: true,
    secure: true,
    sameSite: "lax",
});

export async function checkCookie(request: Request): Promise<void> {
    const cookie = request.headers.get("Cookie");
    if (!cookie) throw redirect("/login");

    const sessionId = await cookieStore.parse(cookie);

    if (!sessionId) throw redirect("/login", {
        headers: {
            "Set-Cookie": await cookieStore.serialize("",)
        }
    });

    globalSessionId = sessionId;
}

export async function deleteCookieAndRedirect(): Promise<void> {
    throw redirect("/login", {
        headers: {
            "Set-Cookie": await cookieStore.serialize("",)
        }
    });
}