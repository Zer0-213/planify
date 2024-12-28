import * as process from "node:process";

type ServerHelper = {
    url: string | undefined;
    authToken: string | undefined;
    request: <T>(
        fetchMethod: FetchMethod,
        url: string,
        body?: unknown
    ) => Promise<ResponseDTO<T>>;
    get: <T>(url: string) => Promise<ResponseDTO<T>>;
    post: <T>(url: string, body: unknown) => Promise<ResponseDTO<T>>;
    put: <T>(
        url: string,
        request: Request,
        body: unknown
    ) => Promise<ResponseDTO<T>>;
    delete: <T>(
        url: string,
        request: Request,
        body: unknown
    ) => Promise<ResponseDTO<T>>;
};

enum FetchMethod {
    GET = "GET",
    POST = "POST",
    PUT = "PUT",
    DELETE = "DELETE",
}

const serverHelper: ServerHelper = {
    url: process.env.SERVER_URL,
    authToken: undefined,
    async request<T>(
        fetchMethod: FetchMethod,
        url: string,
        body: unknown
    ): Promise<ResponseDTO<T>> {
        const response = await fetch(`${this.url}${url}`, {
            method: fetchMethod,
            headers: {
                "Content-Type": "application/json",
                Authorization: `${this.authToken}`,
            },
            body: JSON.stringify(body),
        });

        return await response.json();
    },

    async get<T>(url: string): Promise<ResponseDTO<T>> {
        return await this.request<T>(FetchMethod.GET, url);
    },

    async post<T>(url: string, body: unknown): Promise<ResponseDTO<T>> {
        console.log("POSTING", url, body);
        const response = await this.request<T>(FetchMethod.POST, url, body);
        console.log(response);
        return response;
    },

    async put<T>(url: string, body: unknown): Promise<ResponseDTO<T>> {
        return await this.request<T>(FetchMethod.PUT, url, body);
    },

    async delete<T>(
        url: string,
        request: Request,
        body: unknown
    ): Promise<ResponseDTO<T>> {
        return await this.request<T>(FetchMethod.DELETE, url, body);
    },
};
export default serverHelper;
