import { createCookieSessionStorage } from "react-router";

export const { getSession, commitSession, destroySession } =
  createCookieSessionStorage({
    cookie: {
      name: "authToken",
      sameSite: "lax",
      httpOnly: true,
    },
  });
