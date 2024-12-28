import serverHelper from "~/utils/fetchHelper/severHelper";

export async function getUserData() {
  return await serverHelper.get<GetUserDTO>("/user/get-user");
}
