import serverHelper from "~/utils/fetchHelper/severHelper";

export async function createCompany(createCompanyDTO: CreateCompanyDTO) {
    return await serverHelper.post<string>("/company/create-company", createCompanyDTO);
}