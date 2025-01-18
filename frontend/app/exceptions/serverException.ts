class ServerException extends Error {
    constructor(message: string | undefined) {
        super(message);
        this.name = "ServerException";
    }
}