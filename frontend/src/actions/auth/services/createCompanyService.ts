import {cookies} from "next/headers";

export const createCompanyRequest = async (request: CreateCompanyDTO): Promise<Response> => {

    const cookieStore = await cookies();
    
    const sessionId = cookieStore.get('SessionId');

    const response = await fetch(`${process.env.SERVER_URL}/api/company/create-company`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            "Authorization": sessionId?.value || ''
        },
        body: JSON.stringify(request),
        credentials: 'include',
    })
    
    if (response.status === 401) {
        cookieStore.delete('SessionId');
    }
    
    return response;
}
    
    