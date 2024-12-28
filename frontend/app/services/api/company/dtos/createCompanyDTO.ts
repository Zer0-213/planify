type CreateCompanyDTO = {
    companyName: string;
    companyAddress: string;
    companyPhone: string;
    companyType: number;
}

type CreateCompanyResponse = {
    id: number
}